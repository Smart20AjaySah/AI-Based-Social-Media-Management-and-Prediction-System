<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require $_SERVER['DOCUMENT_ROOT'] . '/ajay/email/vendor/autoload.php';

function sendVerificationEmail($email, $fullname, $token) {
    $mail = new PHPMailer(true);
    try {
        // SMTP Debugging On करें (Error देखने के लिए)
        // $mail->SMTPDebug = 2; 
        $mail->Debugoutput = 'html'; 

        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        
        // Gmail Credentials
        include 'conn.php'; 
        $mail->Username   = 'ajrockrock10@gmail.com';
        $mail->Password   = 'zgvcwpnolggnmgky'; // App Password Check करें

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Sender और Receiver
        $mail->setFrom('ajrockrock10@gmail.com', 'Aventra');
        $mail->addAddress($email, $fullname);

        // Email Content
        $mail->isHTML(true);
        $mail->Subject = 'Verify Your Email - Aventra';
        $mail->Body    = "Click the link to verify your email: 
        <a href='https://ajaysah.in/ajay/user/verify.php?email=$email&token=$token'>Verify Email</a>";

        if ($mail->send()) {
            return true;
        } else {
            echo "Email Sending Failed: " . $mail->ErrorInfo;
            return false;
        }
    } catch (Exception $e) {
        echo "Mailer Error: " . $mail->ErrorInfo;
        return false;
    }
}
?>
