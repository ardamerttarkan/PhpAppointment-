<?php
ini_set("display_errors", "1");
ini_set("display_startup_errors", "1");
error_reporting(E_ALL);

session_start(); 

if(isset($_SESSION["user_id"])){

    $mysqli = require __DIR__ . "/database.php";

    $sql = "SELECT * FROM users
             WHERE id =  {$_SESSION["user_id"]}";

    $result = $mysqli->query($sql);

    $user = $result->fetch_assoc();
}




?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="header">
        <a href="index.php" class="logo">Daire15</a>

        <nav class="navbar">
            <a href="logout.php">Çıkış Yap</a>
            </nav>
    </header>

<?php if(isset($user)): ?>

    <style>
        .text {
            text-align: center;
            margin-top: 500px;
            background-color: gray;
            color: black;
            
        }

        
    </style>
    <div class="text">
    <p>Hoşgeldin, <?= htmlspecialchars($user["name"]) ?></p>
    <p>Şimdi Randevu almak için lütfen şu soruları cevapla!<a href="appointment.php">Tıkla</a></p>
    <?php else: ?>
        </div>
        
        <header class="header">
        <a href="index.php" class="logo">Daire15</a>

        <nav class="navbar">
            <a href="login.php">Giriş Yap</a>
            </nav>
    </header>
        

        
        
    
    <?php endif; ?>

    
    
</body>
</html>