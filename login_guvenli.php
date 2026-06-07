<?php
session_start();
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$hata = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["usrnm"];
    $password = $_POST["psw"];

    $stmt = $conn->prepare("SELECT * FROM kullanicilar WHERE username=? AND password=?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $sonuc = $stmt->get_result();

    if ($sonuc && $sonuc->num_rows > 0) {
        $row = $sonuc->fetch_assoc();
        $_SESSION["user"] = $row["username"];
        header("Location: index.php");
        exit();
    } else {
        $hata = "Hatalı kullanıcı adı veya parola! (SQL Injection Engellendi)";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Sisteme Giriş (Güvenli)</title>
<link rel="stylesheet" href="style.css">
<style>
    body { background-color: #222; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; font-family: Arial, sans-serif; }
    .login-container { background: #e8f5e9; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.3); width: 350px; border: 2px solid #4CAF50; }
    .login-container h2 { text-align: center; color: #2e7d32; margin-top: 0; }
    .input-field { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
    .btn-login { width: 100%; padding: 10px; background: #2e7d32; color: white; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; margin-top: 10px; }
    .btn-login:hover { background: #1b5e20; }
    .error-msg { color: red; text-align: center; font-weight: bold; }
    .link { display: block; text-align: center; margin-top: 15px; color: #1976d2; text-decoration: none; font-size: 14px; }
    .nav-links { margin-top: 20px; text-align: center; font-size: 13px; font-weight: bold; }
    .nav-links a { color: #d32f2f; text-decoration: none; }
</style>
</head>
<body>
<div class="login-container">
    <h2>Sisteme Giriş (Güvenli)</h2>
    <?php if($hata) echo "<p class='error-msg'>$hata</p>"; ?>
    <form action="" method="POST">
        <input type="text" name="usrnm" class="input-field" placeholder="Kullanıcı Adı" required>
        <input type="password" name="psw" class="input-field" placeholder="Parola" required>
        <button type="submit" class="btn-login">Güvenli Giriş Yap</button>
    </form>
    <a href="register.php" class="link">Hesabın yok mu? Kayıt Ol</a>
    
    <div class="nav-links">
        <a href="login.php">Zafiyetli Giriş Ekranına Dön ⚠️</a>
    </div>
</div>
</body>
</html>