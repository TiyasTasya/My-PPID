<?php

require_once "../../conf/auth.php";
require_once "../../conf/koneksi.php";
/** @var mysqli $koneksi */

/**
 * 1. OTOMASI EMAIL MASUK KEDALAM TABEL NOTIFIKASI
 */
$cek_email_baru = mysqli_query($koneksi, "SELECT * FROM mailbox_messages");
if ($cek_email_baru && mysqli_num_rows($cek_email_baru) > 0) {
    while ($msg = mysqli_fetch_assoc($cek_email_baru)) {
        $msg_id = mysqli_real_escape_string($koneksi, $msg['id']);
        $pengirim = mysqli_real_escape_string($koneksi, $msg['nama_pengirim']);
        $subjek = mysqli_real_escape_string($koneksi, $msg['subjek']);
        $waktu_masuk = mysqli_real_escape_string($koneksi, $msg['created_at']);

        // Cek apakah email ID ini sudah tercatat di tabel notifikasi
        $sudah_ada_notif_email = mysqli_query($koneksi, "SELECT id FROM notifikasi WHERE tipe='email' AND referensi_id='$msg_id'");

        if ($sudah_ada_notif_email && mysqli_num_rows($sudah_ada_notif_email) == 0) {
            $judul_notif = "Email Masuk: " . $subjek;
            $pesan_notif = "Email baru diterima dari " . $pengirim;

            mysqli_query($koneksi, "INSERT INTO notifikasi (tipe, referensi_id, judul, pesan, icon, warna_icon, created_at) 
            VALUES ('email', '$msg_id', '$judul_notif', '$pesan_notif', 'bi-envelope-fill', 'text-primary', '$waktu_masuk')");
        }
    }
}

/**
 * 2. OTOMASI EVENT JATUH TEMPO (1 JAM SEBELUMNYA)
 */
$cek_event = mysqli_query($koneksi, "
    SELECT id, title, start_date 
    FROM calendar_events 
    WHERE start_date BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 1 HOUR)
");

if ($cek_event && mysqli_num_rows($cek_event) > 0) {
    while ($ev = mysqli_fetch_assoc($cek_event)) {
        $event_id = mysqli_real_escape_string($koneksi, $ev['id']);
        $nama_event = mysqli_real_escape_string($koneksi, $ev['title']);

        // Cek agar tidak duplikat generate
        $sudah_ada_notif_event = mysqli_query($koneksi, "SELECT id FROM notifikasi WHERE tipe='event' AND referensi_id='$event_id'");

        if ($sudah_ada_notif_event && mysqli_num_rows($sudah_ada_notif_event) == 0) {
            $judul_ev = "Pengingat Event Terdekat!";
            $pesan_ev = "Agenda '" . $nama_event . "' dijadwalkan akan dimulai dalam waktu 1 jam ke depan.";

            mysqli_query($koneksi, "INSERT INTO notifikasi (tipe, referensi_id, judul, pesan, icon, warna_icon, created_at) 
            VALUES ('event', '$event_id', '$judul_ev', '$pesan_ev', 'bi-clock-history', 'text-danger', NOW())");
        }
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo isset($title_halaman) ? htmlspecialchars($title_halaman) : 'Profile'; ?> | AdminPPID</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta color-scheme="light dark" />

    <link rel="preload" href="/my-ppid/app/css/adminlte.css" as="style" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="/my-ppid/app/css/adminlte.css" />
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">

    <div class="app-wrapper">

            <?php include "../includes/sidebar.php"; ?>

        <?php include "../includes/navbar.php"; ?>

        <main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">User Profile</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item"><a href="#">User</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Profile</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="app-content">
                <div class="container-fluid">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header p-0 border-bottom-0">
                                    <ul class="nav nav-tabs" id="profile-tabs" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button
                                                class="nav-link active"
                                                id="notifications-tab"
                                                data-bs-toggle="tab"
                                                data-bs-target="#notifications"
                                                type="button"
                                                role="tab"
                                                aria-controls="notifications"
                                                aria-selected="true">
                                                Notifications
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                                
                                <div class="tab-content card-body" id="profile-tabs-content">
                                    <div
                                        class="tab-pane fade show active"
                                        id="notifications"
                                        role="tabpanel"
                                        aria-labelledby="notifications-tab">
                                        <div class="list-group list-group-flush">
                                            <?php
                                            // Load semua data notifikasi gabungan (Sistem, Email, dan Event)
                                            $query_notif = mysqli_query($koneksi, "SELECT * FROM notifikasi ORDER BY created_at DESC");

                                            if ($query_notif && mysqli_num_rows($query_notif) > 0) {
                                                while ($notif = mysqli_fetch_assoc($query_notif)) {
                                                    $waktu = ($notif['created_at']) ? date('d M Y, H:i', strtotime($notif['created_at'])) : '-';
                                                    $bg_style = (isset($notif['is_read']) && $notif['is_read'] == 0) ? 'bg-light-subtle' : 'bg-transparent';
                                            ?>
                                                    <div class="list-group-item d-flex justify-content-between align-items-start <?php echo $bg_style; ?> px-0 py-3">
                                                        <div class="me-auto">
                                                            <div class="fw-semibold">
                                                                <i class="bi <?php echo htmlspecialchars($notif['icon']); ?> <?php echo htmlspecialchars($notif['warna_icon']); ?> me-2"></i>
                                                                <?php echo htmlspecialchars($notif['judul']); ?>
                                                            </div>
                                                            <small class="text-secondary"><?php echo htmlspecialchars($notif['pesan']); ?></small>
                                                        </div>
                                                        <small class="text-muted"><?php echo $waktu; ?></small>
                                                    </div>
                                            <?php
                                                }
                                            } else {
                                                echo '<div class="text-center text-secondary py-4">Tidak ada notifications saat ini.</div>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <?php include "../includes/footer.php"; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/my-ppid/app/js/adminlte.js"></script>
    <script src="/my-ppid/app/js/mode.js"></script>
</body>

</html>