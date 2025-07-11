<?php
session_start();
include 'koneksi.php';  // Pastikan koneksi.php sudah benar

// Pastikan session id_user ada
if (isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];  // Ambil id_user dari session
    
    // Query untuk mengambil data pengguna berdasarkan id_user
    $query = "SELECT full_name, created_at FROM users WHERE id_user = ?";  // Menggunakan created_at
    $stmt = $koneksi->prepare($query);  
    $stmt->bind_param('i', $id_user);  // Tipe data integer (id_user)
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $full_name = $user['full_name'];  // Ambil nama pengguna
        $registration_date = date('d/m/Y', strtotime($user['created_at']));  // Gunakan created_at
    }
} else {
    // Jika session tidak ditemukan, alihkan ke login page
    header("Location: login.php");
    exit();
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
  <!-- Font Awesome -->
  <link rel="stylesheet" href="app/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="app/dist/css/adminlte.min.css">
  <!-- Custom CSS (optional) -->
  <style>
    .about-section {
    padding: 60px 0;
    background-color: #f4f6f9;
    }

    .about-content {
    text-align: center;
    max-width: 800px;
    margin: 0 auto;
    background: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    margin-top: 30px; /* Menambahkan jarak dari elemen sebelumnya */
    }
    .about-logo {
      width: 100px;
      margin-bottom: 20px;
    }
    .about-text {
      font-size: 18px;
      color: #333;
      line-height: 1.6;
    }
    .about-tagline {
      font-size: 22px;
      font-weight: 600;
      color: #007bff;
    }
    .about-footer {
      font-size: 14px;
      color: #777;
      margin-top: 20px;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini">
  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="logo.png" alt="LogoRaFa" height="60" width="60">
  </div>

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
        <a href="#" class="nav-link">About</a>
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

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <section class="content">
      <div class="container-fluid">
        <div class="row about-section">
          <div class="col-md-12">
            <div class="about-content">
              <!-- Logo Section -->
              <img src="logo.png" alt="RaFa Logo" class="about-logo">
              <h2 class="about-tagline"><strong>"Manage Your Wealth Effortlessly"</strong></h2>
              <!-- Description -->
              <p class="about-text">
                RaFa is an innovative platform designed to simplify personal finance management, 
                both for managing your income as well as your expenses. 
                With RaFa can help you become financially empowered and make the right decisions to 
                achieve their goals.
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- Footer -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2024 <a href="https://rafa.id">RaFa</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.1.0
    </div>
  </footer>

  <!-- jQuery -->
  <script src="app/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="app/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="app/dist/js/adminlte.min.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="app/dist/js/demo.js"></script>
</body>
</html>
