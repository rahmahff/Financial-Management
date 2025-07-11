<?php
session_start();
include 'koneksi.php'; 

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
  <link rel="stylesheet" href="app/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="app/dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed">

<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
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

    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
          <span><?php echo $full_name; ?></span> 
        </a>
        <div class="dropdown-menu dropdown-menu-right">
          <div class="dropdown-header text-center">
            <strong class="user-name" style="font-size: 16px"><?php echo $full_name; ?></strong>
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
  <!-- Akhir Navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="index.php" class="brand-link">
      <img src="logo.png" alt="LogoRaFa" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">RaFa</span>
    </a>
    <div class="sidebar">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-header">FINANCE</li>
          <li class="nav-item">
            <a href="pemasukan.php" class="nav-link">
              <i class="nav-icon fas fa-hand-holding-usd"></i>
              <p>Income</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="pengeluaran.php" class="nav-link">
              <i class="nav-icon fas fa-shopping-cart"></i>
              <p>Expenditure</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="chart.php" class="nav-link">
              <i class="nav-icon fas fa-chart-line"></i>
              <p>Chart</p>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </aside>
  <!-- Akhir Main Sidebar Container -->

  <?php
  include "koneksi.php";
  $id_expenditure = $_GET["id_expenditure"];
    $query = mysqli_query($koneksi, "SELECT * FROM expenditures WHERE id_expenditure = '$id_expenditure' AND id_user = '$id_user'");
    while ($data = mysqli_fetch_array($query)) {
  ?>

  <!-- Content -->
  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
      </div>
    </section>
    <section class="content">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Update Expenditure</h3>
        </div>
        <div class="card-body">
          <div class="form-container">
            <div class="form-box">
              <form action="proses_edit_expenditure.php" method="post">
                <input type="hidden" name="id_expenditure" value="<?= $data['id_expenditure'] ?>">
                  <div class="form-group">
                    <label for="expense_name">Income Name</label>
                    <input type="text" class="form-control" id="expense_name" name="expense_name" value="<?= $data["expense_name"] ?>">
                  </div>
                  <div class="form-group">
                    <label for="income_amount">Amount</label>
                    <input type="number" class="form-control" id="amount" name="amount" value="<?= $data["amount"] ?>">
                  </div>
                  <div class="form-group">
                    <label for="expense_date">Date</label>
                    <input type="date" class="form-control" id="expense_date" name="expense_date" value="<?= $data["expense_date"] ?>">
                  </div>
                  <div class="form-group"> 
                    <label for="category">Category</label> 
                    <select id="category" name="category" class="form-control" required> 
                      <option value="">Select</option> 
                      <option value="Food" <?php echo ($data['category'] == 'Food') ? 'selected' : ''; ?>>Food</option> 
                      <option value="Transport" <?php echo ($data['category'] == 'Transport') ? 'selected' : ''; ?>>Transportation</option> 
                      <option value="Health" <?php echo ($data['category'] == 'Health') ? 'selected' : ''; ?>>Health</option> 
                      <option value="Entertainment" <?php echo ($data['category'] == 'Entertainment') ? 'selected' : ''; ?>>Recreation</option> 
                      <option value="Others" <?php echo ($data['category'] == 'Others') ? 'selected' : ''; ?>>Others</option> </select> 
                  </div>
                  <div class="d-flex justify-content-between">
                    <a href="view_expenditure.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update</button>
                  </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  <!-- Akhir Content -->

<?php
    } 
  ?>

  <!-- Footer -->
  <footer class="main-footer">
    <strong>&copy; 2024 <a href="https://rafa.id">RaFa</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.1.0
    </div>
  </footer>
  <!-- Akhir Footer -->

<script src="app/plugins/jquery/jquery.min.js"></script>
<script src="app/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="app/dist/js/adminlte.min.js"></script>
</body>
</html>
