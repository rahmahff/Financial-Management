<?php
include "koneksi.php";
session_start(); // Tambahkan ini untuk akses $_SESSION

// Ambil id_user dari session
$id_user = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : null;

// Ambil data dari POST
$income_name = $_POST['income_name'];
$income_amount = $_POST['income_amount'];
$income_date = $_POST['income_date'];
$id_income = $_POST['id_income']; // Ambil id_income dari POST

if (!empty($income_name) && !empty($income_amount) && !empty($income_date) && !empty($id_income) && !empty($id_user)) {
    // Jalankan query update
    $query = mysqli_query($koneksi, "UPDATE income SET income_name='$income_name', income_amount='$income_amount', income_date='$income_date' WHERE id_income='$id_income' AND id_user='$id_user'");

    if ($query) {
        echo "<script text='text/javascript'>
    alert('Data berhasil disimpan'); window.location.href='view_income.php'
    </script>";
        // header("location: index.php");
    }
} else {
    echo "<script text='text/javascript'>
    alert('Data gagal disimpan'); window.location.href='edit_income.php?id_income=$id_income'
    </script>";
}
?>
