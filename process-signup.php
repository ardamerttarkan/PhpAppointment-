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

$activation_token = bin2hex(random_bytes(16));
$acc_activation_hash = hash("sha256", $activation_token);

 $mysqli = require __DIR__ . "/database.php"; 

 $sql = "INSERT INTO users (name, email, password_hash, acc_activation_hash) VALUES (?, ?, ?,?)";

 $stmt= $mysqli->stmt_init();

 if(! $stmt ->prepare($sql)){
        die("Bağlantı hatası: " . $mysqli->error);
 }

 $stmt-> bind_param("ssss", $_POST["name"], $_POST["email"], $password_hash, $aacc_activation_hash);

 if ($stmt->execute()) {

    $mail = require __DIR__ . "/mailer.php";

    $mail->setFrom("noreply@example.com");
    $mail->addAddress($_POST["email"]);
    $mail->Subject = "Account Activation";
    $mail->Body = <<<END

    Click <a href="http://example.com/activate-account.php?token=$activation_token">here</a> 
    to activate your password.

    END;

    try {

        $mail->send();

    } catch (Exception $e) {

        echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
        exit;
    }





    header("Location: signup-success.html");
    exit;
    
} else {
    
    if ($mysqli->errno === 1062) {
        die("email already taken");
    } else {
        die($mysqli->error . " " . $mysqli->errno);
    }
}








