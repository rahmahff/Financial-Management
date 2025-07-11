<?php
session_start();
include 'koneksi.php'; 

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['id_user'])) {
    echo "<script type='text/javascript'>
            alert('Anda harus login terlebih dahulu');
            window.location.href='login.php';
          </script>";
    exit();
} else {
    $id_user = $_SESSION['id_user']; 
    
    $query = mysqli_query($koneksi, "SELECT full_name, created_at FROM users WHERE id_user = $id_user");  
    $result = mysqli_fetch_array($query);
    
    if ($result) {
        $full_name = $result['full_name']; 
        $registration_date = date('d/m/Y', strtotime($result['created_at'])); 
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>My Finance</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.25/dist/sweetalert2.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="app/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="app/dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="index.php" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="about.php" class="nav-link">About</a>
      </li>
    </ul>
  
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- User Profile Dropdown -->
      <li class="nav-item dropdown">
        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
          <!-- Nama Pengguna -->
          <span><?php echo $full_name; ?></span> 
        </a>
        <div class="dropdown-menu dropdown-menu-right">
          <div class="dropdown-header text-center">
            <!-- Informasi Pengguna -->
            <strong class="user-name" style="font-size: 16px"><?php echo $full_name; ?></strong>
            <!-- Registration Date with 'Sejak:' -->
            <p class="mb-0">
              <small style="font-size: 13px">Sejak: <?php echo $registration_date; ?></small>
            </p>
          </div>
          <div class="dropdown-divider"></div>
          <a href="login.php" class="dropdown-item text-danger">
            <i class="fas fa-sign-out-alt"></i> Logout
          </a>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
      <img src="logo.png" alt="LogoRaFa" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">RaFa</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-header">FINANCE</li>
            <li class="nav-item">
              <a href="pemasukan.php" class="nav-link">
                <i class="nav-icon fas fa-hand-holding-usd"></i>
                <p>
                  Income
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="pengeluaran.php" class="nav-link">
                <i class="nav-icon fas fa-shopping-cart"></i>
                <p>
                  Expenditure
                </p>
              </a>
            </li>
            <li class="nav-item">
                <a href="chart.php" class="nav-link">
                  <i class="nav-icon fas fa-chart-line"></i>
                  <p>
                    Chart
                  </p>
                </a>
              </li>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

  <!-- Content Wrapper with margin top -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
        </div>
      </section>

    <!-- Main content -->
    <section class="content">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Expenditure List</h3>
        </div>
        <!-- Card Body dengan padding top -->
        <div class="card-body">
          <table id="expenditureTable" class="table table-bordered table-striped" style="text-align: center;">
            <thead>
              <tr>
                <th style="text-align: center;">Nomor</th>
                <th style="text-align: center;">Expenditure Name</th>
                <th style="text-align: center;">Amount</th>
                <th style="text-align: center;">Date</th>
                <th style="text-align: center;">Category</th>
                <th style="text-align: center;">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php 
                $query = mysqli_query($koneksi, "SELECT * FROM expenditures WHERE id_user = $id_user ORDER BY expense_date DESC");
                $nomor = 1;
                while ($data = mysqli_fetch_array($query)) {
                  echo "<tr>";
                  echo "<td>{$nomor}</td>";
                  echo "<td>{$data['expense_name']}</td>";
                  echo "<td>Rp" . number_format($data['amount'], 0, ',', '.') . "</td>";
                  echo "<td>{$data['expense_date']}</td>";
                  echo "<td>{$data['category']}</td>";
                  echo "<td>
                            <a href='edit_expenditure.php?id_expenditure={$data['id_expenditure']}' class='btn btn-warning btn-sm'>
                              <i class='fas fa-pencil-alt'></i> Edit
                            </a>
                            <a href='hapus_expenditure.php?id_expenditure={$data['id_expenditure']}' class='btn btn-danger btn-sm delete-button'>
                              <i class='fas fa-trash'></i> Delete
                            </a>
                        </td>";
                  echo "</tr>";
                  $nomor++;
                }
              ?>                
            </tbody>
          </table>
          <div class="d-flex justify-content-between mt-3">
            <a href="pengeluaran.php" class="btn btn-secondary">Back</a>
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
</div>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2024 <a href="https://rafa.id">RaFa</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.1.0
    </div>
  </footer>

<script src="navbar.js"></script>
<!-- jQuery -->
<script src="app/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="app/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="app/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.25/dist/sweetalert2.min.js"></script>
</body>
</html>
