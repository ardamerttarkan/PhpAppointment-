<?php
ini_set("display_errors", "1");
ini_set("display_startup_errors", "1");
error_reporting(E_ALL);



if(empty($_POST["name"])){
    echo "İsim Boş bırakılamaz.";

}

if( ! filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
    echo "Geçerli bir email adresi giriniz.";
}

if(strlen($_POST["password"]) < 6){
    echo "Parola en az 6 karakter olmalıdır.";
}

if(! preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/', $_POST["password"])){
    echo "Parola en az bir harf ve bir rakam içermelidir.";
}

if($_POST["password"] !== $_POST["password_conf"]){
    echo "Parolalar eşleşmiyor.";
}

$password_hash=  password_hash($_POST["password"], PASSWORD_DEFAULT);

 $mysqli = require __DIR__ . "/database.php"; 

 $sql = "INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)";

 $stmt= $mysqli->stmt_init();

 if(! $stmt ->prepare($sql)){
        die("Bağlantı hatası: " . $mysqli->error);
 }

 $stmt-> bind_param("sss", $_POST["name"], $_POST["email"], $password_hash);

 if ($stmt->execute()) {

    header("Location: signup-success.html");
    exit;
    
} else {
    
    if ($mysqli->errno === 1062) {
        die("email already taken");
    } else {
        die($mysqli->error . " " . $mysqli->errno);
    }
}








