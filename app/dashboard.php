<?php
// ===================================================
// CMS PPID - DASHBOARD UTAMA - COMPATIBLE PHP 5.2
// ===================================================
if (!isset($_SESSION)) {
    session_start();
}

require_once "../conf/auth.php";
require_once "../conf/koneksi.php";

$title_halaman = "Dashboard";
$total_users   = 0;
$total_pesan   = 0;
$pesan_unread  = 0;

/** @var mysqli $koneksi */ 

// 1. HITUNG TOTAL USER
$query_count_users = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM users");
if ($query_count_users) {
    $data_users  = mysqli_fetch_assoc($query_count_users);
    $total_users = $data_users['total'];
}

// 2. HITUNG TOTAL PESAN MASUK (Kecuali Draft)
$query_count_pesan = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM mailbox_messages WHERE is_draft = 0");
if ($query_count_pesan) {
    $data_pesan  = mysqli_fetch_assoc($query_count_pesan);
    $total_pesan = $data_pesan['total'];
}

// 3. HITUNG PESAN YANG BELUM DIBACA
$query_count_unread = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM mailbox_messages WHERE is_draft = 0 AND status_baca = 'belum'");
if ($query_count_unread) {
    $data_unread  = mysqli_fetch_assoc($query_count_unread);
    $pesan_unread = $data_unread['total'];
}
?>
<!doctype html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title><?php echo isset($title_halaman) && $title_halaman != "" ? htmlspecialchars($title_halaman) . " | AdminPPID" : "Admin"; ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
  <meta name="color-scheme" content="light dark" />
  <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
  <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />

  <link rel="preload" href="./css/adminlte.css" as="style" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />
  <link rel="stylesheet" href="./css/adminlte.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css" crossorigin="anonymous" />
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  <div class="app-wrapper">

    <?php require_once "./includes/navbar.php"; ?>
    <?php require_once "./includes/sidebar.php"; ?>

    <main class="app-main">
      <div class="app-content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6">
              <h3 class="mb-0">Dashboard Overview</h3>
            </div>
          </div>
        </div>
      </div>

      <div class="app-content">
        <div class="container-fluid">

          <div class="row mb-2">
            <div class="col-lg-4 col-6">
              <div class="small-box text-bg-warning">
                <div class="inner">
                  <h3><?php echo (int)$total_users; ?></h3>
                  <p>Total Pengguna Sistem</p>
                </div>
                <i class="bi bi-people-fill small-box-icon" style="font-size: 4.5rem; top: 5px; right: 15px;"></i>
                <a href="#" class="small-box-footer link-dark link-underline-opacity-0">
                  Kelola Pengguna <i class="bi bi-arrow-right-circle ms-1"></i>
                </a>
              </div>
            </div>

            <div class="col-lg-4 col-6">
              <div class="small-box text-bg-success">
                <div class="inner">
                  <h3><?php echo (int)$total_pesan; ?></h3>
                  <p>Total Pesan Masuk</p>
                </div>
                <i class="bi bi-envelope-fill small-box-icon" style="font-size: 4.5rem; top: 5px; right: 15px;"></i>
                <a href="mailbox/inbox.php" class="small-box-footer link-light link-underline-opacity-0">
                  Buka Inbox <i class="bi bi-arrow-right-circle ms-1"></i>
                </a>
              </div>
            </div>

            <div class="col-lg-4 col-12">
              <div class="small-box text-bg-danger">
                <div class="inner">
                  <h3><?php echo (int)$pesan_unread; ?></h3>
                  <p>Belum Dibaca (Unread)</p>
                </div>
                <i class="bi bi-envelope-open-fill small-box-icon" style="font-size: 4.5rem; top: 5px; right: 15px;"></i>
                <a href="mailbox/inbox.php" class="small-box-footer link-light link-underline-opacity-0">
                  Tinjau Pesan Baru <i class="bi bi-arrow-right-circle ms-1"></i>
                </a>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-12 connectedSortable">

              <div class="card mb-4">
                <div class="card-header">
                  <h3 class="card-title"><i class="bi bi-bar-chart-line-fill me-2"></i>Grafik Data PPID</h3>
                </div>
                <div class="card-body">
                  <div id="revenue-chart"></div>
                </div>
              </div>

              <?php 
                if (file_exists("./calender/calender.php")) {
                    require_once "./calender/calender.php";
                }
              ?>

            </div>
          </div>

        </div>
      </div>
    </main>

    <?php require_once "./includes/footer.php"; ?>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
  <script src="./js/adminlte.js"></script>

  <script>
    (() => {
      'use strict';
      const STORAGE_KEY = 'lte-theme';
      const getStoredTheme = () => localStorage.getItem(STORAGE_KEY);
      const prefersDark = () => globalThis.matchMedia('(prefers-color-scheme: dark)').matches;
      const getPreferredTheme = () => {
        const stored = getStoredTheme();
        if (stored) return stored;
        return prefersDark() ? 'dark' : 'light';
      };
      const setTheme = (theme) => {
        const resolved = theme === 'auto' ? (prefersDark() ? 'dark' : 'light') : theme;
        document.documentElement.setAttribute('data-bs-theme', resolved);
      };
      setTheme(getPreferredTheme());
    })();
  </script>

  <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js" crossorigin="anonymous"></script>
  <script>
    new Sortable(document.querySelector('.connectedSortable'), {
      group: 'shared',
      handle: '.card-header'
    });
    document.querySelectorAll('.connectedSortable .card-header').forEach(h => h.style.cursor = 'move');
  </script>

  <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js" crossorigin="anonymous"></script>
  <script src="./js/chart.js"></script>
</body>

</html>