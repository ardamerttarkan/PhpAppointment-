<?php
ini_set("display_errors", "1");
ini_set("display_startup_errors", "1");
error_reporting(E_ALL);

session_start();

if (!isset($_SESSION['user_id'])) {
    echo 'Bu sayfayı görmek için <a href="login.php">giriş yapmalısınız</a>.';

    exit;
}

$mysqli = require __DIR__ . "/database.php";



function isDateAvailable($mysqli, $come_date, $leave_date) {
    $sql = "SELECT COUNT(*) FROM appointment WHERE (come_date >= ? AND leave_date <= ?) OR (come_date >= ? AND leave_date >= ?) OR (come_date <= ? AND leave_date >= ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ssssss", $come_date, $leave_date, $come_date, $leave_date, $come_date, $leave_date);

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $come_date = $_POST["comedate"];
        $leave_date = $_POST["leavedate"];
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        if($count > 0) {
            return false;
        } 
        else {
            return true;
        }
    }
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if(empty($_POST["name"])){
        echo "İsim Boş bırakılamaz.";
    }
    elseif(empty($_POST["phone"])){
        echo "Telefon Boş bırakılamaz.";
    }
    elseif(empty($_POST["reason"])){
        echo "Sebep Boş bırakılamaz.";
    }
    elseif(empty($_POST["comedate"])){
        echo "Tarih Boş bırakılamaz.";
    }
    elseif(empty($_POST["leavedate"])){
        echo "Tarih Boş bırakılamaz.";
    }
    elseif(empty($_POST["friend"])){
        echo "Arkadaş Boş bırakılamaz.";
    }
    elseif(empty($_POST["cat"])){
        echo "Kedi Boş bırakılamaz.";
    }
    else {
        $mysqli = require __DIR__ . "/database.php";
        if(isDateAvailable($mysqli, $_POST["comedate"], $_POST["leavedate"])) {
            $sql = "INSERT INTO appointment (name, telephone, guest_reason, come_date, leave_date, for_who, cat) VALUES (?, ?, ?, ?, ?, ?,?)";

            $stmt= $mysqli->stmt_init();
    
            if(! $stmt ->prepare($sql)){
                die("Bağlantı hatası: " . $mysqli->error);
            }
    
            $stmt-> bind_param("sssssss", $_POST["name"], $_POST["phone"], $_POST["reason"], $_POST["comedate"], $_POST["leavedate"], $_POST["friend"], $_POST["cat"]);
    
            if ($stmt->execute()) {
               $data = isDateAvailable($mysqli, $_POST["comedate"], $_POST["leavedate"]);
               echo "<script>alert('$data');</script>"; 
    
            }
        }else{
            echo "<script>alert('BU TARİHLER ARASINDA RANDEVU ALINAMAZ!');</script>"; 
        }
     
    }
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
    <div class="container">
        <style>
            .container {
                background-color: gray;
                margin-top: 100px;
            }
        </style>
    <h1>Evimize Hoşgeldiniz. Lütfen Randevu Oluşturun</h1>
    <form method="post">
    <div>
        <label for="name">Adınız:</label><br>
        <input type="text" id="name" name="name">
    </div>
    <div>
        <label for="phone">Telefon Numaranız:</label><br>
        <input type="text" id="phone" name="phone" pattern="[0-9]{3} [0-9]{3} [0-9]{4}" placeholder="(5--) --- -- --">
    </div> 
    <div>
        <label for="reason">Gelme Sebebiniz?</label><br>
        <input type="text" id="reason" name="reason">
    </div>
    <div>
    <label for="comedate">Geliş Tarihiniz:</label><br>
    <input type="date" id="comedate" name="comedate" min="<?php echo date('Y-m-d'); ?>">
</div>
    <div>
        <label for="leavedate">Çıkış Tarihiniz:</label><br>
        <input type="date" id="leavedate" name="leavedate" min="<?php echo date('Y-m-d'); ?>">
    </div>
    <div>
        <label for="friend">Kim için geleceksin?</label><br>
        <select id="friend" name="friend">
            <option value="">..</option>
            <option value="arda">Arda</option>
            <option value="ali">Ali</option>
            <option value="emre">Emre</option>
            <option value="hepsi">Hepsi</option>
        </select>
    </div>
    <div>
        <label for="cat" >Kedilerle Aran Nasıl? Evimizde Kedi var.</label><br>
        <select id="cat" name="cat">
            <option value="">..</option>
            <option value="iyi">İyi</option>
            <option value="kötü">Kötü</option>
        </select>
    </div>
    <button type="submit">Gönder</button><br>
    <a href="#">Hizmetler Ve Şartlar</a>
</form>
</div>

</body>
</html>
