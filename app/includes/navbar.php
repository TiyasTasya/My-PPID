<?php
if (!isset($_SESSION)) {
    session_start();
}

// Mengganti __DIR__ dengan dirname(__FILE__) agar kompatibel dengan PHP 5.2
require_once dirname(__FILE__) . "/../../conf/auth.php";
require_once dirname(__FILE__) . "/../../conf/koneksi.php";

/** @var mysqli $koneksi */ 

$session_username = mysqli_real_escape_string($koneksi, $_SESSION['admin_user']);
$query_user       = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$session_username' LIMIT 1");
$data_user        = mysqli_fetch_assoc($query_user);

$nama_asli = isset($data_user['nama'])     ? $data_user['nama']     : '';
$username  = isset($data_user['username']) ? $data_user['username'] : '';

// =========================================================
// 1. QUERY AMBIL PESAN MASUK (SESUAI TABEL: mailbox_messages)
// =========================================================
$query_pesan = mysqli_query($koneksi, "SELECT * FROM mailbox_messages ORDER BY created_at DESC LIMIT 3");
$total_pesan = mysqli_num_rows($query_pesan);

// =========================================================
// 2. QUERY EVENT KALENDER (3 JAM SEBELUM EVENT MULAI)
// =========================================================
$query_event_3jam = mysqli_query($koneksi, "
    SELECT *, TIMESTAMPDIFF(MINUTE, NOW(), start_date) as sisa_menit 
    FROM calendar_events 
    WHERE start_date >= NOW() 
      AND start_date <= DATE_ADD(NOW(), INTERVAL 3 HOUR)
    ORDER BY start_date ASC
");
$total_event_notif = mysqli_num_rows($query_event_3jam);

// Akumulasi total badge notifikasi di navbar
$total_notifikasi_badge = $total_event_notif + $total_pesan; 
?>

<nav class="app-header navbar navbar-expand bg-body sticky-top bg-white border-bottom shadow-sm">
  <div class="container-fluid">
    
    <ul class="navbar-nav align-items-center">
      <li class="nav-item">
        <a class="nav-link text-body" data-lte-toggle="sidebar" href="#" role="button" style="cursor: pointer;">
          <i class="bi bi-list fs-4 fw-bold"></i>
        </a>
      </li>
    </ul>
    <ul class="navbar-nav ms-auto align-items-center">

      <li class="nav-item dropdown">
        <a class="nav-link" data-bs-toggle="dropdown" href="#">
          <i class="bi bi-chat-text"></i>
          <?php if ($total_pesan > 0): ?>
            <span class="navbar-badge badge text-bg-danger"><?php echo $total_pesan; ?></span>
          <?php endif; ?>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
          <span class="dropdown-item dropdown-header text-body fw-bold text-center"><?php echo $total_pesan; ?> Pesan Masuk</span>
          <div class="dropdown-divider"></div>
          
          <?php 
          if ($total_pesan > 0) {
              while ($pesan = mysqli_fetch_assoc($query_pesan)) {
                  $sender = !empty($pesan['nama_pengirim']) ? htmlspecialchars($pesan['nama_pengirim']) : 'Anonim';
                  $isi_singkat = !empty($pesan['isi_pesan']) ? htmlspecialchars(substr($pesan['isi_pesan'], 0, 40)) . '...' : 'Tidak ada isi pesan.';
                  $waktu_pesan = date('H:i', strtotime($pesan['created_at']));
          ?>
                  <a href="mailbox.php?id=<?php echo $pesan['id']; ?>" class="dropdown-item text-wrap">
                    <div class="d-flex align-items-center">
                      <div class="flex-shrink-0">
                        <img src="/my-ppid/app/assets/img/user.png" alt="User Avatar" class="img-size-50 rounded-circle me-3" />
                      </div>
                      <div class="flex-grow-1">
                        <h3 class="dropdown-item-title fw-bold text-body fs-6 mb-1">
                          <?php echo $sender; ?>
                          <span class="float-end fs-7 text-danger"><i class="bi bi-star-fill"></i></span>
                        </h3>
                        <p class="fs-7 text-secondary mb-1"><?php echo $isi_singkat; ?></p>
                        <p class="fs-7 text-secondary mb-0"><i class="bi bi-clock-fill me-1"></i> Pukul <?php echo $waktu_pesan; ?></p>
                      </div>
                    </div>
                  </a>
                  <div class="dropdown-divider"></div>
          <?php 
              }
          } else {
              echo '<div class="text-center text-muted small py-3">Tidak ada pesan masuk baru</div>';
              echo '<div class="dropdown-divider"></div>';
          }
          ?>
          
          <a href="mailbox.php" class="dropdown-item dropdown-footer text-center small text-body fw-bold">Lihat Semua Pesan</a>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link" data-bs-toggle="dropdown" href="#">
          <i class="bi bi-bell-fill"></i>
          <?php if ($total_notifikasi_badge > 0): ?>
            <span class="navbar-badge badge text-bg-warning"><?php echo $total_notifikasi_badge; ?></span>
          <?php endif; ?>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
          <span class="dropdown-item dropdown-header text-body fw-bold text-center"><?php echo $total_notifikasi_badge; ?> Notifikasi</span>
          <div class="dropdown-divider"></div>
          
          <a href="mailbox.php" class="dropdown-item text-body d-flex justify-content-between align-items-center small">
            <span><i class="bi bi-envelope-fill me-2 text-primary"></i> Total <?php echo $total_pesan; ?> pesan di inbox</span>
            <span class="badge bg-secondary">Aktif</span>
          </a>
          
          <div class="dropdown-divider"></div>
          
          <?php 
          if ($total_event_notif > 0) {
              while ($event = mysqli_fetch_assoc($query_event_3jam)) {
                  $sisa_jam = ceil($event['sisa_menit'] / 60);
          ?>
                  <a href="calendar.php" class="dropdown-item text-wrap bg-light-warning text-dark border-bottom small">
                    <div class="py-1">
                      <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>
                      <strong>Agenda Mendatang:</strong> <?php echo htmlspecialchars($event['title']); ?> 
                      <div class="text-muted fs-7 mt-1 ml-4">
                        <i class="bi bi-clock"></i> Dimulai dalam waktu ± <?php echo $sisa_jam; ?> jam lagi!
                      </div>
                    </div>
                  </a>
          <?php 
              }
          } else {
          ?>
              <div class="dropdown-item text-body text-center small text-muted py-2">
                <i class="bi bi-calendar-check text-success me-1"></i> Belum ada agenda dalam 3 jam ke depan
              </div>
          <?php } ?>
          
          <div class="dropdown-divider"></div>
          <a href="/my-ppid/app/notifikasi/notifikasi.php" class="dropdown-item dropdown-footer text-center small text-body fw-bold">Lihat Semua Notifikasi</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#" data-lte-toggle="fullscreen">
          <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
          <i data-lte-icon="minimize" class="bi bi-fullscreen-exit d-none"></i>
        </a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link" href="#" id="bd-theme" aria-label="Toggle color scheme"
          data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-sun-fill" data-lte-theme-icon="light"></i>
          <i class="bi bi-moon-fill d-none" data-lte-theme-icon="dark"></i>
          <i class="bi bi-circle-half d-none" data-lte-theme-icon="auto"></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="bd-theme" style="--bs-dropdown-min-width: 8rem">
          <li>
            <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light" aria-pressed="false">
              <i class="bi bi-sun-fill me-2"></i> Light
            </button>
          </li>
          <li>
            <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
              <i class="bi bi-moon-fill me-2"></i> Dark
            </button>
          </li>
          <li>
            <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="auto" aria-pressed="true">
              <i class="bi bi-circle-half me-2"></i> Auto
            </button>
          </li>
        </ul>
      </li>
      <li class="nav-item dropdown user-menu">
        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
          <img src="/my-ppid/app/assets/img/user.png" class="user-image rounded-circle shadow" alt="User Image" />
          <span class="d-none d-md-inline text-body"><?php echo htmlspecialchars($nama_asli); ?></span>
        </a>
        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
          <li class="user-header text-bg-primary">
            <img src="/my-ppid/app/assets/img/user.png" class="rounded-circle shadow" alt="User Image" />
            <p>
              <?php echo htmlspecialchars($nama_asli); ?> - (@<?php echo htmlspecialchars($username); ?>)
              <small>Administrator PPID</small>
            </p>
          </li>
          <li class="user-footer d-flex justify-content-center">
            <a href="../logout.php" class="btn btn-outline-danger float-end">Sign out</a>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</nav>