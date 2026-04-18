<?php
session_start();
session_unset();
session_destroy(); // menghapus semua sesi login

// Arahkan kembali ke halaman login
header('Location: login.php');
exit;
?>  