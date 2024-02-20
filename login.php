<?php
ini_set("display_errors", "1");
ini_set("display_startup_errors", "1");
error_reporting(E_ALL);


$is_invalid = false;
if($_SERVER["REQUEST_METHOD"] === "POST"){

    $mysqli = require __DIR__ . "/database.php";

    $sql  = sprintf("SELECT*FROM users
                     WHERE email = '%s'",
                     $mysqli->real_escape_string( $_POST["email"]));

    $result = $mysqli->query($sql);

    $users =$result->fetch_assoc();

   if($users && $users["acc_activation_hash"] === null){

   if( password_verify($_POST["password"], $users["password_hash"])){
         
    
    session_start();

    session_regenerate_id();


         $_SESSION["user_id"] = $users["id"];
         header("Location: index.php");
         exit;
   }
   }
   

    $is_invalid = true;

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

<div>

<?php if($is_invalid): ?>
    
        <em>Geçersiz email veya şifre</em>
    
    <?php endif; ?>

    <h1>Giriş Yap</h1>

<form method="post" >
            <label for="email">Email</label>
            <input type="email" id="email" name="email"
            value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">
        </div>
        
        <div>
            <label for="password">Şifre</label>
            <input type="password" id="password" name="password">
        </div>

        <button>Giriş Yap</button>

        <p> Hesabınız yok mu? <a href="signup.html">Kayıt Ol</a></p>
        </form>
    
</body>
</html>