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

    $current_month = date('m');
    $current_year = date('Y');

    // Menghitung total pemasukan
    $income_query = mysqli_query($koneksi, "SELECT SUM(income_amount) AS total_income FROM income WHERE id_user = $id_user AND MONTH(income_date) = $current_month AND YEAR(income_date) = $current_year");
    $income_data = mysqli_fetch_array($income_query);
    $total_income = $income_data['total_income'] ?? 0;

    // Menghitung total pengeluaran
    $expenditure_query = mysqli_query($koneksi, "SELECT SUM(amount) AS total_expenditure FROM expenditures WHERE id_user = $id_user AND MONTH(expense_date) = $current_month AND YEAR(expense_date) = $current_year");
    $expenditure_data = mysqli_fetch_array($expenditure_query);
    $total_expenditure = $expenditure_data['total_expenditure'] ?? 0;

    // Mengambil aktivitas terbaru
    $recent_activity_query = "
    SELECT 
        income_date AS transaction_date, 
        'income' AS type, 
        income_amount AS amount, 
        income_name AS name 
    FROM income
    WHERE id_user = $id_user
    UNION ALL
    SELECT 
        expense_date AS transaction_date, 
        'expenditure' AS type, 
        amount, 
        expense_name AS name 
    FROM expenditures
    WHERE id_user = $id_user
    ORDER BY transaction_date DESC
    LIMIT 4";
    $recent_activity_result = mysqli_query($koneksi, $recent_activity_query);
    $recent_activities = mysqli_fetch_all($recent_activity_result, MYSQLI_ASSOC);
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
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="app/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="app/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="app/plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="app/dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="app/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="app/plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="app/plugins/summernote/summernote-bs4.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
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
        <a href="#" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="about.php" class="nav-link">About</a>
      </li>
    </ul>
  
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

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-lg-6 col-6">
        <!-- small box for Income -->
        <div class="small-box bg-info">
          <div class="inner">
            <h3>Rp<?php echo number_format($total_income, 0, ',', '.'); ?></h3>
            <p>Income</p>
          </div>
          <div class="icon">
            <i class="nav-icon fas fa-dollar-sign"></i>
          </div>
          <a href="view_income.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <div class="col-lg-6 col-6">
        <!-- small box for Expenditure -->
        <div class="small-box bg-danger">
          <div class="inner">
            <h3>Rp<?php echo number_format($total_expenditure, 0, ',', '.'); ?></h3>
            <p>Expenditure</p>
          </div>
          <div class="icon">
            <i class="nav-icon fas fa-credit-card"></i>
          </div>
          <a href="view_expenditure.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
    </div>
    <!-- Recent Activity section -->
    <div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Recent Activity</h3>
      </div>
      <div class="card-body">
      <?php if (!empty($recent_activities)): ?>
    <ul class="list-group">
        <?php foreach ($recent_activities as $activity): ?>
            <li class="list-group-item">
                <?php echo date('d/m/Y', strtotime($activity['transaction_date'])); ?>:
                <?php if ($activity['type'] === 'income'): ?>
                    Income of Rp<?php echo number_format($activity['amount'], 0, ',', '.'); ?> 
                    from <?php echo htmlspecialchars($activity['name'], ENT_QUOTES, 'UTF-8'); ?>
                <?php elseif ($activity['type'] === 'expenditure'): ?>
                    Expenditure of Rp<?php echo number_format($activity['amount'], 0, ',', '.'); ?> 
                    for <?php echo htmlspecialchars($activity['name'], ENT_QUOTES, 'UTF-8'); ?>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No recent activities found.</p>
<?php endif; ?>
      </div>
    </div>
  </div>
</div>

  </div><!-- /.container-fluid -->
</section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2024 <a href="https://rafa.id">RaFa</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.1.0
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="app/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="app/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="app/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="app/plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="app/plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="app/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="app/plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="app/plugins/moment/moment.min.js"></script>
<script src="app/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="app/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="app/plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="app/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="app/dist/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="app/dist/js/demo.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="app/dist/js/pages/dashboard.js"></script>
</body>
</html>