<?php
session_start();

// Panggil file konfigurasi database dari folder conf
require_once "conf/koneksi.php";

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($username) || empty($password)) {
        $error_message = "Username dan Password tidak boleh kosong!";
    } 
    else if (!preg_match("/^[a-zA-Z0-9_]{3,20}$/", $username)) {
        $error_message = "Username hanya boleh huruf, angka, dan underscore!";
    } 
    else {
        // Amankan input dari SQL Injection
        $username_aman = mysqli_real_escape_string($koneksi, $username);
        $password_aman = mysqli_real_escape_string($koneksi, $password);

        $query  = "SELECT * FROM users WHERE username = '$username_aman' AND password = MD5('$password_aman') LIMIT 1";
        $result = mysqli_query($koneksi, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);
            $_SESSION['admin_user'] = $user_data['username'];
            
            header("Location: app/dashboard.php");
            exit;
        } else {
            $error_message = "Username atau Password salah!";
        }
    }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Admin | Login PPID</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light" />
    <meta name="theme-color" content="#007bff" />
    
    <link rel="preload" href="app/css/adminlte.css" as="style" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="app/css/adminlte.css" />
  </head>
  <body class="login-page bg-body-secondary d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="login-box">
      <div class="card card-outline card-primary shadow-sm">
        <div class="card-header text-center py-4">
          <a href="index.php" class="link-dark text-decoration-none">
            <h1 class="mb-0 fs-2"><b>Admin</b>PPID</h1>
          </a>
        </div>
        <div class="card-body login-card-body p-4">
          <p class="login-box-msg text-muted text-center mb-4">Masuk untuk memulai sesi pengelolaan data</p>

          <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger p-2 small border-0 mb-3" role="alert" style="border-left: 4px solid #dc3545 !important; border-radius: 4px;">
              <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo $error_message; ?>
            </div>
          <?php endif; ?>

          <form action="" method="post">
            <div class="input-group mb-3">
              <div class="form-floating flex-grow-1">
                <input id="loginUsername" name="username" type="text" class="form-control" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" placeholder="Username" required />
                <label for="loginUsername">Username</label>
              </div>
              <span class="input-group-text bg-white border-start-0 text-muted">
                <i class="bi bi-person-fill"></i>
              </span>
            </div>
            
            <div class="input-group mb-4">
              <div class="form-floating flex-grow-1">
                <input id="loginPassword" name="password" type="password" class="form-control" placeholder="Password" required />
                <label for="loginPassword">Password</label>
              </div>
              <span class="input-group-text bg-white border-start-0 text-muted">
                <i class="bi bi-lock-fill"></i>
              </span>
            </div>
            
            <div class="row">
              <div class="col-12">
                <div class="d-grid mb-3">
                  <button type="submit" class="btn btn-primary py-2 fw-semibold">Sign In</button>
                </div>
              </div>
            </div>
          </form>

        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="app/js/adminlte.js"></script>
    
    <script>
      document.documentElement.setAttribute('data-bs-theme', 'light');
    </script>
  </body>
</html>