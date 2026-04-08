<?php
session_start();

// LOGIKA PHP: Mengecek HANYA JIKA form sudah di-submit (tombol ditekan)
if(isset($_POST["username"]) && isset($_POST["password"])){
    
    // Tangkap ketikan dari form
    $username_input = $_POST["username"];
    $password_input = $_POST["password"];

    // Cek apakah username = admin DAN password = password
    if ($username_input === "admin" && $password_input === "pw"){
        
        // Jika benar, buat sesi login
        $_SESSION["isLoggin"] = "login"; 
        
        // Arahkan ke halaman admin (pastikan nama file kamu admin.php)
        header("Location: admin.php");
        exit;
        
    } else {
        // Jika salah, munculkan pop-up
        echo "<script> 
                alert('Aduh! Username atau password salah, Brader!');
              </script>";
    }
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="icon" type="image/png" href="../../images/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="../style/login.css" />
  </head>
  <body>
    <div class="login-container">
      <div class="login-card">
        <!-- Logo & Nama TPQ -->
        <div class="brand-header">
          <img src="../../images/logo.png" alt="logo" width="50px">
          <h1>MSANTRI.</h1>
          <p>Panel Pengelola</p>
        </div>

        <!-- Kalimat Sambutan -->
        <div class="welcome-text">
          <h2>SELAMAT DATANG ADMIN</h2>
          <p>Silakan masukkan kredensial Anda untuk mengakses sistem TPQ.</p>
        </div>

        <!-- Form Login (Nantinya akan disambung ke PHP) -->
        <form method="POST">
          <!-- Input Username -->
          <div class="form-group">
            <label for="username">Username Admin</label>
            <div class="input-wrapper">
              <!-- Posisi ikon diletakkan setelah input agar CSS selector focus sibling berfungsi jika menggunakan flex order, 
                   namun cara klasik adalah menggunakan div pembungkus yang bisa kita style via JS, atau biarkan statis. 
                   Di sini kita biarkan ikon statis agar sederhana. -->
              <input
                type="text"
                id="username"
                name="username"
                class="form-control"
                placeholder="Ketik username Anda..."
                required
                autocomplete="off"
              />
              <i class="fa-regular fa-user"></i>
            </div>
          </div>

          <!-- Input Password -->
          <div class="form-group">
            <label for="password">Kata Sandi</label>
            <div class="input-wrapper">
              <input
                type="password"
                id="password"
                name="password"
                class="form-control"
                placeholder="••••••••"
                required
              />
              <i class="fa-solid fa-lock"></i>
            </div>
          </div>

          <!-- Tombol Masuk -->
          <button type="submit" class="btn-login">
            Masuk ke Sistem <i class="fa-solid fa-arrow-right-to-bracket"></i>
          </button>
        </form>
      </div>
    </div>
  </body>
</html>
