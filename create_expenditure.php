<?php
session_start(); // Pastikan ini ada di baris pertama

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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $expense_name = $_POST["expense_name"];
    $amount = $_POST["amount"];
    $expense_date = $_POST["expense_date"];
    $category = $_POST["category"];
    
    // Query untuk menyimpan data ke database
    $query = mysqli_query($koneksi, "INSERT INTO expenditures (expense_name, amount, expense_date, category, id_user) 
      VALUES ('$expense_name', '$amount', '$expense_date', '$category', '$id_user')");
    
    // Eksekusi query dan cek hasilnya
    if ($query){
      echo"<script type='text/javascript'>alert ('Data Sudah Masuk'); window.location.href='create_expenditure.php'</script>";
    } else {
        echo "<script type='text/javascript'>alert('Error: " . mysqli_error($koneksi) . "');</script>";
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
  <!-- Font Awesome -->
  <link rel="stylesheet" href="app/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="app/dist/css/adminlte.min.css">
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
          <h3 class="card-title">Expenditure Form</h3>
        </div>
        <!-- Card Body with padding top -->
        <div class="card-body">
          <div class="form-container">
            <div class="form-box">
              <form action="create_expenditure.php" method="POST">
                <div class="form-group">
                  <label for="expense_name">Expense Name</label>
                  <input type="text" id="expense_name" name="expense_name" class="form-control" placeholder="Enter Expense Name" required>
                </div>
                <div class="form-group">
                  <label for="amount">Amount</label>
                  <input type="number" id="amount" name="amount" class="form-control" placeholder="Enter Expense Amount" required>
                </div>
                <div class="form-group">
                  <label for="expense_date">Date</label>
                  <input type="date" id="expense_date" name="expense_date" class="form-control" required>
                </div>
                <div class="form-group">
                  <label for="category">Category</label>
                  <select id="category" name="category" class="form-control" required>
                    <option value="">Select</option>
                    <option value="Food">Food</option>
                    <option value="Transport">Transportation</option>
                    <option value="Health">Health</option>
                    <option value="Entertainment">Recreation</option>
                    <option value="Others">Others</option>
                  </select>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="pengeluaran.php" class="btn btn-secondary">Back</a>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
              </form>
            </div>
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
<!-- AdminLTE for demo purposes -->
<script src="app/dist/js/demo.js"></script>
</body>
</html>