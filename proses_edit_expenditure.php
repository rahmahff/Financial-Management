<?php
include "koneksi.php";
session_start(); 
$id_user = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : null;

$expense_name = $_POST['expense_name'];
$amount = $_POST['amount'];
$expense_date = $_POST['expense_date'];
$category = $_POST['category'];
$id_expenditure = $_POST['id_expenditure']; 

if (!empty($expense_name) && !empty($amount) && !empty($expense_date) && !empty($category) && !empty($id_expenditure) && !empty($id_user)) {

    $query = mysqli_query($koneksi, "UPDATE expenditures SET expense_name='$expense_name', amount='$amount', expense_date='$expense_date', category='$category' WHERE id_expenditure='$id_expenditure' AND id_user='$id_user'");

    if ($query) {
        echo "<script text='text/javascript'>
    alert('Data berhasil disimpan'); window.location.href='view_expenditure.php'
    </script>";
        // header("location: index.php");
    }
} else {
    echo "<script text='text/javascript'>
    alert('Data gagal disimpan'); window.location.href='edit_expenditure.php?id_expenditure=$id_expenditure'
    </script>";
}
?>
