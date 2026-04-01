<?php
$localhost = "localhost";
$username = "root";
$password = "";
$database = "db_msantri"; // <-- Pastikan ini db_msantri

try {
    $koneksi = mysqli_connect($localhost, $username, $password, $database);

    //echo "Koneksi Berhasil <br><br>"; 
} catch (\Throwable $th) {
    echo "Koneksi Gagal: " . $th->getMessage();
}
?>