// Import Firebase scripts for messaging
importScripts("https://www.gstatic.com/firebasejs/10.8.1/firebase-app.js");
importScripts("https://www.gstatic.com/firebasejs/10.8.1/firebase-messaging.js");

// ✅ 1. Firebase Configuration
const firebaseConfig = {
    apiKey: "AIzaSyALBWyEnrQlyPJbySXuv_8LKh94Ih7Amw8",
    authDomain: "mywebsitenotifications-92744.firebaseapp.com",
    projectId: "mywebsitenotifications-92744",
    storageBucket: "mywebsitenotifications-92744.appspot.com",
    messagingSenderId: "1061928411368",
    appId: "1:1061928411368:web:f60dc90a07c60b9ddaa95d"
};

// ✅ 2. Firebase Initialize
firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();

// ✅ 3. Background Notification Handler
messaging.onBackgroundMessage((payload) => {
    console.log("[firebase-messaging-sw.js] Received background message: ", payload);

    const notificationTitle = payload.notification.title;
    const notificationOptions = {
        body: payload.notification.body,
        icon: payload.notification.icon || "/ajay/owner/ajay2.png" // Default Icon
    };

    self.registration.showNotification(notificationTitle, notificationOptions);
});