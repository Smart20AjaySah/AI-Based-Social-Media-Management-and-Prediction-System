<?php
session_start();
$_SESSION['from-index'] = true;

if (isset($_POST['button-login'])) {
    header("Location: /ajay/user/login.php");
    exit();
} elseif (isset($_POST['button-register'])) {
    header("Location: /ajay/user/register.php");
    exit();
} elseif (isset($_POST['button-admin'])) {
    header("Location: /ajay/owner/admin-login.php");
    exit();
}
elseif (isset($_POST['button-model'])) {
    header("Location: /ajay/python3/index.php");
    exit();
}
elseif (isset($_POST['button-chat'])) {
    header("Location: /ajay/python3/chatbot.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart 🌟 - Admin Dashboard</title>
    
    <link rel="stylesheet" href="/ajay/owner/owner.css">
    <link rel="icon" type="image/svg+xml" href="/ajay/owner/smart2.svg">

    <!-- ✅ SEO -->
    <meta name="robots" content="index, follow"> 
    <meta name="description" content="Smart 🌟 - Admin Dashboard for Managing Users and Posts.">
    <meta name="keywords" content="Admin Dashboard, Smart Social Media Admin, Manage Users, Manage Posts, Social Media Management">
    
    <!-- ✅ Open Graph -->
    <meta property="og:title" content="Smart 🌟 - Admin Dashboard">
    <meta property="og:description" content="Smart 🌟 Admin Panel: Users और Posts को Manage करें।">
    <meta property="og:url" content="/ajay/owner/index.php">
    <meta property="og:type" content="website">
    <meta property="og:image" content="/ajay/owner/smart.jpeg">

    <!-- ✅ Schema -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebPage",
      "name": "Smart 🌟 - Admin Dashboard",
      "url": "https://localhost:8080/ajay/owner/index.php",
      "description": "Smart 🌟 - Admin Dashboard for Managing Users and Posts.",
      "inLanguage": "hi",
      "isPartOf": {
        "@type": "WebSite",
        "name": "Smart 🌟",
        "url": "https://localhost:8080"
      }
    }
    </script>
</head>

<body>
    <div class="background-animation"></div>
    <div class="container" style="margin-top:-100px;">
        <div class="profile">
            <img src="/ajay/owner/ajay.jpeg" alt="Ajay Sah">
            <h1>Ajay Sah</h1>
            <p>Web Developer | AI Enthusiast</p>
        </div>

        <div class="details">
            <p><strong>📞 Phone:</strong> +91 8969594358</p>
            <p><strong>📧 Email:</strong> ajrockrock10@gmail.com</p>
            <p><strong>📍 Location:</strong> Jamshedpur, Jharkhand, India</p>
        </div>
        
        <div class="button">
            <form action="" method="POST">
                <input type="submit" class="visit-btn" name="button-login" value="SIGN IN" style="margin-right:20px; margin-top:40px;">
                <input type="submit" class="visit-btn" name="button-register" value="SIGN UP" style="margin-left:20px; margin-top:40px;">
                <br>
                <input type="submit" class="visit-btn" name="button-admin" value="ADMIN" style="margin-top:40px; background:linear-gradient(90deg,#1111ff,#00ccff); color:white;">
                <input type="submit" class="visit-btn" name="button-model" value="AI Model" style="margin-top:40px; margin-left:20px; background:linear-gradient(90deg,#1111ff,#00ccff); color:white;">
                <input type="submit" class="visit-btn" name="button-chat" value="AI ChatBot" style="margin-top:40px; margin-left:20px; background:linear-gradient(90deg,#1111ff,#00ccff); color:white;">
            </form>
        </div>

        <h2 style="text-align:center; color:blue;">Sign in: For Old Users<br>Sign up: For New Users<br>Admin: For Administrators</h2>
    </div>

    <!-- Firebase SDK -->
    <script src="https://www.gstatic.com/firebasejs/10.8.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.8.1/firebase-messaging.js"></script>

    <script>
        const firebaseConfig = {
            apiKey: "AIzaSyALBWyEnrQlyPJbySXuv_8LKh94Ih7Amw8",
            authDomain: "mywebsitenotifications-92744.firebaseapp.com",
            projectId: "mywebsitenotifications-92744",
            storageBucket: "mywebsitenotifications-92744.appspot.com",
            messagingSenderId: "1061928411368",
            appId: "1:1061928411368:web:f60dc90a07c60b9ddaa95d"
        };

        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();

        function requestPermission() {
            Notification.requestPermission().then(permission => {
                if (permission === "granted") {
                    return messaging.getToken();
                }
            }).then(token => {
                console.log("FCM Token:", token);
            }).catch(error => console.error("Error getting permission:", error));
        }

        messaging.onMessage(payload => {
            const notificationTitle = payload.notification.title;
            const notificationOptions = {
                body: payload.notification.body,
                icon: payload.notification.icon
            };
            new Notification(notificationTitle, notificationOptions);
        });

        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/owner/firebase-messaging-sw.js')
            .then(registration => console.log('Service Worker Registered:', registration))
            .catch(error => console.log('Service Worker Registration Failed:', error));
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const background = document.querySelector(".background-animation");

            function createShape(type) {
                let shape = document.createElement("div");
                shape.classList.add(type);
                shape.style.top = Math.random() * 100 + "vh";
                shape.style.left = Math.random() * 100 + "vw";
                shape.style.animationDuration = Math.random() * 6 + 6 + "s";
                background.appendChild(shape);
                setTimeout(() => shape.remove(), 6000);
            }

            setInterval(() => {
                createShape("star");
                createShape("circle");
            }, 500);
        });
    </script>
</body>
</html>
