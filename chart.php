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

$bulan = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
];

// Ambil data income dan expenditure per bulan untuk pengguna tertentu
$query = "
    SELECT 
        MONTH(i.income_date) AS month, 
        SUM(i.income_amount) AS total_income,
        (SELECT SUM(e.amount) FROM expenditures e WHERE MONTH(e.expense_date) = MONTH(i.income_date) AND e.id_user = i.id_user) AS total_expenditure
    FROM income i
    WHERE i.id_user = ?
    GROUP BY MONTH(i.income_date)
    ORDER BY MONTH(i.income_date)
";

$stmt = $koneksi->prepare($query);
$stmt->bind_param('i', $id_user);  
$stmt->execute();
$result = $stmt->get_result();

// Simpan data untuk grafik
$months = [];
$incomeData = [];
$expenditureData = [];

while ($row = $result->fetch_assoc()) {
  $month = $row['month'];
  $months[] = $bulan[$month];  // Ganti angka bulan dengan nama bulan
  $incomeData[] = $row['total_income'] ?? 0;  // Jika null, set 0
  $expenditureData[] = $row['total_expenditure'] ?? 0;  // Jika null, set 0
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

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <style>
    /* Menambahkan ruang di bawah grafik untuk keterangan */
    .chart-container {
      position: relative;
      width: 100%;
      height: 300px;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini">

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
            <a href="#" class="nav-link">
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
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Chart Income and Expenditure</h1>
          </div>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Income vs Expenditure</h3>
        </div>
        <div class="card-body">
          <!-- LINE CHART -->
          <div class="chart">
          <canvas id="lineChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
          </div>
        </div>        
      </div>
    </section>
  </div>

  <footer class="main-footer">
    <strong>Copyright &copy; 2024 <a href="https://rafa.id">RaFa</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.1.0
    </div>
  </footer>

  <script>
// Ambil data dari PHP dan format untuk Chart.js
var months = <?php echo json_encode($months); ?>;
var incomeData = <?php echo json_encode($incomeData); ?>;
var expenditureData = <?php echo json_encode($expenditureData); ?>;

// Membuat chart
var ctx = document.getElementById('lineChart').getContext('2d');
var chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: months,  // Label sumbu X: bulan dengan nama bulan
        datasets: [{
            label: 'Income',
            borderColor: 'rgba(60,141,188,0.9)',
            data: incomeData,  // Data income
            fill: false,
        }, {
            label: 'Expenditure',
            borderColor: 'rgba(255,99,132,1)',
            data: expenditureData,  // Data expenditure
            fill: false,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            x: {
                title: {
                    display: true,
                    text: 'Bulan'
                }
            },
            y: {
                title: {
                    display: true,
                    text: 'Jumlah (IDR)'
                }
            }
        }
    }
});
</script>

  <!-- REQUIRED SCRIPTS -->
  <script src="app/plugins/jquery/jquery.min.js"></script>
  <script src="app/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="app/dist/js/adminlte.min.js"></script>

</body>
</html>

