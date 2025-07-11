<?php
include 'koneksi.php';  // Pastikan koneksi.php ada dan benar
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil input email dan password
    $email = $_POST['username'];  // Ganti 'username' menjadi 'email'
    $password = $_POST['password'];
    
    // Query untuk mencari pengguna berdasarkan email
    $query = "SELECT id_user, full_name, password FROM users WHERE email = ?";  // Ganti 'username' menjadi 'email'
    $stmt = $koneksi->prepare($query);  
    $stmt->bind_param('s', $email);  // Ganti 'username' menjadi 'email'
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $users = $result->fetch_assoc();
        
        // Verifikasi password
        if (password_verify($password, $users['password'])) {
            // Set session dan redirect ke halaman utama
            $_SESSION['id_user'] = $users['id_user'];  // Ganti 'id' menjadi 'id_user'
            $_SESSION['full_name'] = $users['full_name'];
            header("Location: index.php");
            exit();
        } else {
            echo "Incorrect Password!";
        }
    } else {
        echo "Email Not Found!";
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
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="app/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="app/dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="index.php">
        <img src="logo.png" alt="Logo" style="width: 65px; height: auto;">
      </a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Sign in to start your session</p>

      <form action="login.php" method="post">
        <div class="input-group mb-3">
            <input type="text" class="form-control" name="username" placeholder="Username" required>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user"></span>
                </div>
            </div>
        </div>
        <div class="input-group mb-3">
            <input type="password" class="form-control" name="password" placeholder="Password" required>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-8">
                <div class="icheck-primary">
                    <input type="checkbox" id="remember">
                    <label for="remember">Remember Me</label>
                </div>
            </div>
            <div class="col-4">
                <button type="submit" class="btn btn-primary btn-block">Sign In</button>
            </div>
        </div>
    </form>
      <p class="mb-0">
        <a href="register.php" class="text-center">Register a new membership</a>
      </p>
    </div>
  </div>
</div>

<!-- jQuery -->
<script src="app/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="app/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="app/dist/js/adminlte.min.js"></script>
</body>
</html>
