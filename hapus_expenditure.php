<?php
include "koneksi.php";

$id_expenditure = $_GET["id_expenditure"];

$query = mysqli_query($koneksi, "delete from expenditures where id_expenditure=$id_expenditure");
// <script >alert, window.location.href</script>

// header("Location:  index.php");

if ($query) {
        echo "<script text='text/javascript'>
    alert('Data berhasil dihapus'); window.location.href='view_expenditure.php'
    </script>";
        // header("location: index.php");
    }
