#!/usr/bin/env python3
import argparse
import json
import sys
import numpy as np
import pandas as pd
from pathlib import Path

np.random.seed(42)

def chunk_generator(csv_path, chunksize=30):
    """Yield DataFrame chunks from CSV"""
    for chunk in pd.read_csv(csv_path, chunksize=chunksize):
        yield chunk

class LinearRegressionModel:
    def __init__(self, csv_path, x_cols, y_col):
        self.path = Path(csv_path)
        if not self.path.exists():
            raise FileNotFoundError(f"CSV file not found: {self.path}")

        self.X_col_name = list(x_cols)
        self.y_col_name = y_col

        # Validate columns exist in CSV
        df_preview = pd.read_csv(self.path, nrows=5)
        for c in self.X_col_name + [self.y_col_name]:
            if c not in df_preview.columns:
                raise ValueError(f"Column '{c}' not found in CSV")

        # Initialize first chunk to compute divisor
        self.chunk_iter = pd.read_csv(self.path, chunksize=5)
        self.c = next(self.chunk_iter)
        self.initializing()

    def initializing(self):
        temp_X, _ = self.xy_values(self.c)
        self.divisor = np.max(np.abs(temp_X[:, 1:]))
        if self.divisor == 0:
            self.divisor = 1.0

        self.theta = np.random.randn(temp_X.shape[1], 1)
        self.m = np.zeros((temp_X.shape[1], 1))
        self.v = np.zeros((temp_X.shape[1], 1))
        self.beta1 = 0.9
        self.beta2 = 0.999
        self.epsilon = 1e-8
        self.lr = 0.001

    def xy_values(self, df):
        X = np.ones((len(df), 1))
        for col in self.X_col_name:
            arr = df[col].to_numpy().reshape(len(df), 1)
            X = np.append(X, arr, axis=1)
        y = df[self.y_col_name].to_numpy().reshape(len(df), 1)
        return X, y

    def xy_with_divisor(self, df):
        X, y = self.xy_values(df)
        X = X / self.divisor
        y = y / self.divisor
        return X, y

    def prediction(self, X, theta):
        return X @ theta

    def compute_gradients(self, X, y_pred, y):
        return (2 / len(X)) * (X.T @ (y_pred - y))

    def train_adam(self, epochs=200):
        t = 1
        for epoch in range(epochs):
            for df in chunk_generator(self.path, chunksize=30):
                X, y = self.xy_with_divisor(df)
                for _ in range(500):
                    y_pred = self.prediction(X, self.theta)
                    g = self.compute_gradients(X, y_pred, y)
                    self.m = self.beta1 * self.m + (1 - self.beta1) * g
                    self.v = self.beta2 * self.v + (1 - self.beta2) * (g ** 2)
                    m_hat = self.m / (1 - (self.beta1 ** t))
                    v_hat = self.v / (1 - (self.beta2 ** t))
                    self.theta = self.theta - self.lr * (m_hat / (np.sqrt(v_hat) + self.epsilon))
                    t += 1
            # yield after each epoch (optional for PHP streaming)
            yield self.theta

def main():
    parser = argparse.ArgumentParser(description="Linear Regression (Adam) - Web Friendly")
    parser.add_argument('csv', help='path to csv file')
    parser.add_argument('--xcols', required=True, help='comma separated input columns')
    parser.add_argument('--ycol', required=True, help='output column')
    parser.add_argument('--epochs', type=int, default=200)
    args = parser.parse_args()

    xcols = [c.strip() for c in args.xcols.split(',') if c.strip()]
    ycol = args.ycol

    try:
        model = LinearRegressionModel(args.csv, xcols, ycol)
        for theta in model.train_adam(epochs=args.epochs):
            pass  # we just want final theta here

        # Final theta rounded for output
        theta_list = [[float(np.round(val, 6))] for val in model.theta.reshape(-1)]
        result = {
            'status': 'ok',
            'theta': theta_list,
            'divisor': float(model.divisor),
            'x_columns': model.X_col_name,
            'y_column': model.y_col_name
        }
        print(json.dumps(result))
    except Exception as e:
        print(json.dumps({'status': 'error', 'message': str(e)}))
        sys.exit(0)

if __name__ == '__main__':
    main()
