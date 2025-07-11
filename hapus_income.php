<?php
include "koneksi.php";

$id_income = $_GET["id_income"];

$query = mysqli_query($koneksi, "DELETE from income where id_income=$id_income");
// <script >alert, window.location.href</script>

// header("Location:  index.php");

if ($query) {
        echo "<script text='text/javascript'>
    alert('Data berhasil dihapus'); window.location.href='view_income.php'
    </script>";
        // header("location: index.php");
    }
