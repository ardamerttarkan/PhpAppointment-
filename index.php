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
</head>
<body>

<?php if(isset($user)): ?>
    <p>Merhaba <?= htmlspecialchars($user["name"]) ?></p>
    <p><a href="logout.php">Çıkış Yap</a></p>
    <?php else: ?>

        <p><a href="login.php">Giriş Yap</a> veya <a href="signup.html">Kaydol</a></p>
    
    <?php endif; ?>
</body>
</html>