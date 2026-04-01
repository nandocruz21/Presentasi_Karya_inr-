<?php
include 'koneksi.php';
$id = $_POST['id'];
$status = $_POST['status'];

$sql = "UPDATE santri SET kehadiran='$status' WHERE id_santri='$id'";
if (mysqli_query($koneksi, $sql)) {
    echo "success";
} else {
    echo "error";
}
?>