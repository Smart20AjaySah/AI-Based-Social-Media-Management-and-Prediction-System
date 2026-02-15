<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart 🌟 - Login</title>
    
    <link rel="stylesheet" href="/ajay/user/user4.css">
    <link rel="icon" type="image/svg+xml" href="/ajay/owner/smart2.svg"> <!-- Ensure Correct Path -->
    
    <!-- ✅ SEO & Search Engine Optimization -->
    <meta name="robots" content="index, follow">
    <meta name="description" content="Smart 🌟 - एक Social Media Platform जहाँ आप Chat, Post Upload, Like और Follow कर सकते हैं। अभी Login करें!">
    <meta name="keywords" content="Smart Social Media, Online Chat, Post Upload, Follow Users, Login to Smart, Social Media Platform">
    
    <!-- ✅ Open Graph (OG) Meta Tags (For Social Media Sharing) -->
    <meta property="og:title" content="Smart 🌟 - Login">
    <meta property="og:description" content="Smart 🌟 पर अपने दोस्तों से जुड़ें, Photos Share करें और बातचीत करें।">
    <meta property="og:url" content="/ajay/user/login.php">
    <meta property="og:type" content="website">
    <meta property="og:image" content="/ajay/owner/smart.jpeg"> <!-- Replace with actual logo image -->

    <!-- ✅ Schema Markup (For Better SEO) -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebPage",
      "name": "Smart 🌟 - Login & Register",
      "url": "https://localhost:8080/ajay/user/login.php",
      "description": "Smart 🌟 - एक Social Media Platform जहाँ आप Chat, Post Upload, Like और Follow कर सकते हैं। अभी Login करें!",
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
    <header>
        <h1>Welcome <?php session_start(); echo isset($_SESSION['fullname']) ? $_SESSION['fullname'] : "Guest"; ?></h1>

    </header>
