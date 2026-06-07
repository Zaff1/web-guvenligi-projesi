<?php
session_start();
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$mesaj = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["usrnm"];
    $password = $_POST["psw"];

    $kontrol = "SELECT * FROM kullanicilar WHERE username='$username'";
    $kontrol_sonuc = $conn->query($kontrol);

    if ($kontrol_sonuc && $kontrol_sonuc->num_rows > 0) {
        $mesaj = "Bu kullanıcı adı zaten alınmış!";
    } else {
        $stmt = $conn->prepare("INSERT INTO kullanicilar (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $password);
        if ($stmt->execute()) {
            header("Location: login.php?kayit=basarili");
            exit();
        } else {
            $mesaj = "Kayıt sırasında bir hata oluştu.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Sisteme Kayıt Ol</title>
<link rel="stylesheet" href="style.css">
<style>
    body { background-color: #222; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; font-family: Arial, sans-serif; }
    .login-container { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.3); width: 350px; }
    .login-container h2 { text-align: center; color: #333; margin-top: 0; }
    .input-field { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
    .btn-login { width: 100%; padding: 10px; background: #2e7d32; color: white; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; margin-top: 10px; }
    .btn-login:hover { background: #1b5e20; }
    .error-msg { color: red; text-align: center; font-weight: bold; }
    .link { display: block; text-align: center; margin-top: 15px; color: #1976d2; text-decoration: none; font-size: 14px; }
</style>
</head>
<body>
<div class="login-container">
    <h2>Kayıt Ol</h2>
    <?php if($mesaj) echo "<p class='error-msg'>$mesaj</p>"; ?>
    <form action="" method="POST">
        <input type="text" name="usrnm" class="input-field" placeholder="Kullanıcı Adı Seçin" required>
        <input type="password" name="psw" class="input-field" placeholder="Parola Belirleyin" required>
        <button type="submit" class="btn-login">Kayıt Ol</button>
    </form>
    <a href="login.php" class="link">Zaten hesabın var mı? Giriş Yap</a>
</div>
</body>
</html>