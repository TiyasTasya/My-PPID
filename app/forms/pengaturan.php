<?php
if (!isset($_SESSION)) { session_start(); }
require_once dirname(__FILE__) . "/../../conf/koneksi.php";

$pesan_sukses = "";

// PROSES SIMPAN PERUBAHAN (PHP 5.2 COMPATIBLE)
if (isset($_POST['simpan_pengaturan'])) {
    $home_title  = mysqli_real_escape_string($koneksi, $_POST['home_title']);
    $home_desc   = mysqli_real_escape_string($koneksi, $_POST['home_desc']);
    $footer_text = mysqli_real_escape_string($koneksi, $_POST['footer_text']);
    
    // Update data satu-per-satu menggunakan query standar
    mysqli_query($koneksi, "UPDATE web_settings SET value_setting='$home_title' WHERE key_setting='home_title'");
    mysqli_query($koneksi, "UPDATE web_settings SET value_setting='$home_desc' WHERE key_setting='home_desc'");
    mysqli_query($koneksi, "UPDATE web_settings SET value_setting='$footer_text' WHERE key_setting='footer_text'");
    
    $pesan_sukses = "Pengaturan konten berhasil diperbarui!";
}

// Tarik data terbaru untuk ditampilkan ke Form
$settings = array();
$query_set = mysqli_query($koneksi, "SELECT * FROM web_settings");
while($row_set = mysqli_fetch_assoc($query_set)) {
    $settings[$row_set['key_setting']] = $row_set['value_setting'];
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $title_halaman; ?> | AdminPPID</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta color-scheme="light dark" />

    <link rel="preload" href="/my-ppid/app/css/adminlte.css" as="style" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="/my-ppid/app/css/adminlte.css" />
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">

    <div class="app-wrapper">

        <?php include "../includes/navbar.php"; ?>
        <?php include "../includes/sidebar.php"; ?>


        <!-- Konten -->
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6">
                <h3 class="mb-0">Settings</h3>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Settings</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <div class="app-content">
          <div class="container-fluid">
            <div class="row g-3">
              <!-- Left rail -->
              <div class="col-md-3">
                <div
                  class="list-group list-group-flush nav nav-pills flex-column"
                  id="settings-nav"
                  role="tablist"
                >
                  <a
                    href="#account"
                    class="list-group-item list-group-item-action active"
                    data-bs-toggle="pill"
                    role="tab"
                    aria-selected="true"
                  >
                    <i class="bi bi-person me-2" aria-hidden="true"></i>Account
                  </a>
                  <a
                    href="#notifications"
                    class="list-group-item list-group-item-action"
                    data-bs-toggle="pill"
                    role="tab"
                  >
                    <i class="bi bi-bell me-2" aria-hidden="true"></i>Notifications
                  </a>
                  <a
                    href="#security"
                    class="list-group-item list-group-item-action"
                    data-bs-toggle="pill"
                    role="tab"
                  >
                    <i class="bi bi-shield-lock me-2" aria-hidden="true"></i>Security
                  </a>
                  <a
                    href="#billing"
                    class="list-group-item list-group-item-action"
                    data-bs-toggle="pill"
                    role="tab"
                  >
                    <i class="bi bi-credit-card me-2" aria-hidden="true"></i>Billing
                  </a>
                  <a
                    href="#danger"
                    class="list-group-item list-group-item-action text-danger"
                    data-bs-toggle="pill"
                    role="tab"
                  >
                    <i class="bi bi-exclamation-triangle me-2" aria-hidden="true"></i>
                    Danger zone
                  </a>
                </div>
              </div>

              <!-- Tab content -->
              <div class="col-md-9">
                <div class="tab-content">
                  <!-- Account -->
                  <div class="tab-pane fade show active" id="account" role="tabpanel">
                    <div class="card">
                      <div class="card-header">
                        <h3 class="card-title">Account</h3>
                      </div>
                      <div class="card-body">
                        <form class="row g-3">
                          <div class="col-md-6">
                            <label class="form-label" for="settings-name"> Full name </label>
                            <input
                              type="text"
                              class="form-control"
                              id="settings-name"
                              value="Jane Doe"
                            />
                          </div>
                          <div class="col-md-6">
                            <label class="form-label" for="settings-email"> Email </label>
                            <input
                              type="email"
                              class="form-control"
                              id="settings-email"
                              value="jane@example.com"
                            />
                          </div>
                          <div class="col-md-6">
                            <label class="form-label" for="settings-tz"> Time zone </label>
                            <select class="form-select" id="settings-tz">
                              <option>UTC</option>
                              <option selected>America/Los_Angeles</option>
                              <option>Europe/London</option>
                              <option>Asia/Tokyo</option>
                            </select>
                          </div>
                          <div class="col-md-6">
                            <label class="form-label" for="settings-lang"> Language </label>
                            <select class="form-select" id="settings-lang">
                              <option selected>English</option>
                              <option>Español</option>
                              <option>Français</option>
                              <option>Deutsch</option>
                            </select>
                          </div>
                          <div class="col-12">
                            <button type="submit" class="btn btn-primary">Save changes</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>

                  <!-- Notifications -->
                  <div class="tab-pane fade" id="notifications" role="tabpanel">
                    <div class="card">
                      <div class="card-header">
                        <h3 class="card-title">Notifications</h3>
                      </div>
                      <div class="card-body">
                        <p class="text-secondary">Choose what to be notified about.</p>
                        <div
                          class="d-flex justify-content-between align-items-start py-2 border-bottom"
                        >
                          <div>
                            <p class="mb-0 fw-semibold">Product updates</p>
                            <small class="text-secondary">Major releases and changelogs</small>
                          </div>
                          <div class="form-check form-switch mb-0">
                            <input
                              class="form-check-input"
                              type="checkbox"
                              role="switch"
                              id="notif-0"
                              checked
                            />
                            <label class="visually-hidden" for="notif-0">
                              Toggle Product updates
                            </label>
                          </div>
                        </div>
                        <div
                          class="d-flex justify-content-between align-items-start py-2 border-bottom"
                        >
                          <div>
                            <p class="mb-0 fw-semibold">Security alerts</p>
                            <small class="text-secondary"
                              >Sign-in attempts and account changes</small
                            >
                          </div>
                          <div class="form-check form-switch mb-0">
                            <input
                              class="form-check-input"
                              type="checkbox"
                              role="switch"
                              id="notif-1"
                              checked
                            />
                            <label class="visually-hidden" for="notif-1">
                              Toggle Security alerts
                            </label>
                          </div>
                        </div>
                        <div
                          class="d-flex justify-content-between align-items-start py-2 border-bottom"
                        >
                          <div>
                            <p class="mb-0 fw-semibold">Weekly digest</p>
                            <small class="text-secondary"
                              >A summary of activity in your workspace</small
                            >
                          </div>
                          <div class="form-check form-switch mb-0">
                            <input
                              class="form-check-input"
                              type="checkbox"
                              role="switch"
                              id="notif-2"
                            />
                            <label class="visually-hidden" for="notif-2">
                              Toggle Weekly digest
                            </label>
                          </div>
                        </div>
                        <div
                          class="d-flex justify-content-between align-items-start py-2 border-bottom"
                        >
                          <div>
                            <p class="mb-0 fw-semibold">Mentions</p>
                            <small class="text-secondary">When a teammate @mentions you</small>
                          </div>
                          <div class="form-check form-switch mb-0">
                            <input
                              class="form-check-input"
                              type="checkbox"
                              role="switch"
                              id="notif-3"
                            />
                            <label class="visually-hidden" for="notif-3"> Toggle Mentions </label>
                          </div>
                        </div>
                        <button class="btn btn-primary mt-3">Save preferences</button>
                      </div>
                    </div>
                  </div>

                  <!-- Security -->
                  <div class="tab-pane fade" id="security" role="tabpanel">
                    <div class="card">
                      <div class="card-header">
                        <h3 class="card-title">Password</h3>
                      </div>
                      <div class="card-body">
                        <form class="row g-3">
                          <div class="col-md-12">
                            <label class="form-label" for="pwd-current"> Current password </label>
                            <input type="password" class="form-control" id="pwd-current" />
                          </div>
                          <div class="col-md-6">
                            <label class="form-label" for="pwd-new"> New password </label>
                            <input type="password" class="form-control" id="pwd-new" />
                          </div>
                          <div class="col-md-6">
                            <label class="form-label" for="pwd-confirm">
                              Confirm new password
                            </label>
                            <input type="password" class="form-control" id="pwd-confirm" />
                          </div>
                          <div class="col-12">
                            <button type="submit" class="btn btn-primary">Update password</button>
                          </div>
                        </form>
                      </div>
                    </div>
                    <div class="card mt-3">
                      <div class="card-header">
                        <h3 class="card-title">Two-factor authentication</h3>
                      </div>
                      <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                          <p class="mb-0 fw-semibold">Authenticator app</p>
                          <small class="text-secondary">
                            Use an authenticator app such as 1Password or Authy.
                          </small>
                        </div>
                        <button class="btn btn-outline-primary">Enable</button>
                      </div>
                    </div>
                  </div>

                  <!-- Billing -->
                  <div class="tab-pane fade" id="billing" role="tabpanel">
                    <div class="card">
                      <div class="card-header">
                        <h3 class="card-title">Current plan</h3>
                      </div>
                      <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                          <div>
                            <p class="mb-0 fw-semibold">Pro plan</p>
                            <small class="text-secondary">
                              $29 / month &middot; Renews June 18, 2026
                            </small>
                          </div>
                          <a href="#" class="btn btn-outline-primary btn-sm"> Change plan </a>
                        </div>
                        <hr />
                        <p class="fw-semibold mb-2">Payment method</p>
                        <div class="d-flex justify-content-between align-items-center">
                          <div>
                            <i class="bi bi-credit-card-2-front me-2" aria-hidden="true"></i>
                            Visa ending in 4242
                          </div>
                          <a href="#" class="btn btn-link btn-sm">Update</a>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Danger zone -->
                  <div class="tab-pane fade" id="danger" role="tabpanel">
                    <div class="card border-danger">
                      <div class="card-header bg-danger-subtle">
                        <h3 class="card-title text-danger">Danger zone</h3>
                      </div>
                      <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                          <div>
                            <p class="mb-0 fw-semibold">Export account data</p>
                            <small class="text-secondary">
                              Download a copy of all your data as a ZIP archive.
                            </small>
                          </div>
                          <button class="btn btn-outline-secondary">Export</button>
                        </div>
                        <hr />
                        <div class="d-flex justify-content-between align-items-start">
                          <div>
                            <p class="mb-0 fw-semibold text-danger">Delete account</p>
                            <small class="text-secondary">
                              This will permanently delete your account and all associated data.
                              This cannot be undone.
                            </small>
                          </div>
                          <button class="btn btn-danger">Delete account</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
        <!-- End Konten -->

        <?php include "../includes/footer.php"; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/my-ppid/app/js/adminlte.js"></script>
    <script src="/my-ppid/app/js/mode.js"></script>
</body>

</html>