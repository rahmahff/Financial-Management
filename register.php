<?php
    include "koneksi.php";
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
<body class="hold-transition register-page">
<div class="register-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="index.php" class="h1">
        <img src="logo.png" alt="Logo" style="width: 65px; height: auto;">
    </a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Register a new membership</p>

      <form action="register.php" method="post">
    <div class="input-group mb-3">
    <input type="text" class="form-control" name="full_name" placeholder="Full Name">
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-user"></span>
            </div>
        </div>
    </div>
    <div class="input-group mb-3">
        <input type="email" class="form-control" name="email" placeholder="Email">
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-envelope"></span>
            </div>
        </div>
    </div>
    <div class="input-group mb-3">
        <input type="password" class="form-control" name="password" placeholder="Password">
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-lock"></span>
            </div>
        </div>
    </div>
    <div class="input-group mb-3">
        <input type="password" class="form-control" name="confirm_password" placeholder="Retype password">
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-lock"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-8">
            <div class="icheck-primary">
                <input type="checkbox" id="agreeTerms" name="terms" value="agree">
                <label for="agreeTerms">
                    I agree to the <a href="#">terms</a>
                </label>
            </div>
        </div>
        <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Register</button>
        </div>
    </div>
</form>
      <a href="login.php" class="text-center">I already have a membership</a>
    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
</div>
<!-- /.register-box -->

<?php
// Memproses form ketika disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Mendapatkan data dari form
  $full_name = isset($_POST['full_name']) ? mysqli_real_escape_string($koneksi, $_POST['full_name']) : '';
  $email = isset($_POST['email']) ? mysqli_real_escape_string($koneksi, $_POST['email']) : '';
  $password = isset($_POST['password']) ? mysqli_real_escape_string($koneksi, $_POST['password']) : '';
  $confirm_password = isset($_POST['confirm_password']) ? mysqli_real_escape_string($koneksi, $_POST['confirm_password']) : '';

  // Validasi input
  if (empty($full_name) || empty($email) || empty($password) || empty($confirm_password)) {
      echo "Semua field harus diisi!";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      echo "Format email tidak valid!";
  } elseif ($password !== $confirm_password) {
      echo "Password dan konfirmasi password tidak cocok!";
  } else {
      // Mengecek apakah email sudah terdaftar
      $cek_email = mysqli_query($koneksi, "SELECT * FROM users WHERE email = '$email'");
      if (mysqli_num_rows($cek_email) > 0) {
          echo "Email sudah terdaftar!";
      } else {
          
        // Mengenkripsi password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Menyimpan ke database
        $query = "INSERT INTO users (full_name, email, password) VALUES ('$full_name', '$email', '$password_hash')";
        $result = mysqli_query($koneksi, $query);

        if ($result) {
            echo "Pendaftaran berhasil!";
        } else {
            echo "Gagal menyimpan ke database: " . mysqli_error($koneksi);
        }
      }
  }
}
?>

<!-- jQuery -->
<script src="app/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="app/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="app/dist/js/adminlte.min.js"></script>
</body>
</html>
