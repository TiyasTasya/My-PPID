<?php
// ===================================================
// CMS MAILBOX - READ MESSAGE - COMPATIBLE PHP 5.2
// ===================================================
if (!isset($_SESSION)) {
    session_start();
}

// FIX PATH: Naik 2 tingkat dari mailbox/ ke root (MY-PPID/) untuk mencari folder conf/
require_once "../../conf/auth.php";
require_once "../../conf/koneksi.php";

$title_halaman = "Detail Pesan";
$pesan = "";

/** @var mysqli $koneksi */

// 1. VALIDASI ID PESAN YANG AKAN DIBACA
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: inbox.php"); // Alihkan ke inbox jika ID tidak ditemukan
    exit;
}

$id_pesan = (int)$_GET['id'];

// 2. OTOMATIS UPDATE STATUS BACA MENJADI 'DIBACA' SAAT PESAN DIBUKA
mysqli_query($koneksi, "UPDATE mailbox_messages SET status_baca = 'dibaca' WHERE id = $id_pesan");

// 3. AMBIL DATA DETAIL SURAT/PESAN
$query_pesan = mysqli_query($koneksi, "SELECT * FROM mailbox_messages WHERE id = $id_pesan LIMIT 1");

if (!$query_pesan || mysqli_num_rows($query_pesan) == 0) {
    header("Location: inbox.php");
    exit;
}

$detail = mysqli_fetch_assoc($query_pesan);

// Mengambil inisial nama pengirim untuk avatar lingkaran (Contoh: Olivia Bennett -> OB)
$nama_pengirim = htmlspecialchars($detail['nama_pengirim']);
$words = explode(" ", $nama_pengirim);
$inisial = "";
if (count($words) >= 2) {
    $inisial = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
} else {
    $inisial = strtoupper(substr($nama_pengirim, 0, 2));
}

// LOGIKA JIKA ADMIN MENEKAN TOMBOL DELETE DI DETAIL PESAN
if (isset($_POST['action_hapus'])) {
    $id_del = (int)$_POST['id_pesan_del'];
    if (mysqli_query($koneksi, "DELETE FROM mailbox_messages WHERE id = $id_del")) {
        header("Location: inbox.php?status=deleted");
        exit;
    }
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="/my-ppid/app/css/adminlte.css" />
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">

    <div class="app-wrapper">

        <?php include "../includes/navbar.php"; ?>
        <?php include "../includes/sidebar.php"; ?>

        <main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">Read Message</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="inbox.php">Pesan Masuk</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Read</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="app-content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Subject: <?php echo htmlspecialchars($detail['subjek']); ?></h3>
                            <div class="btn-group btn-group-sm">
                                <a href="inbox.php" class="btn btn-outline-secondary" title="Back to inbox">
                                    <i class="bi bi-arrow-left" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex gap-3 align-items-start mb-4">
                                <div class="flex-shrink-0 rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center"
                                     style="width: 48px; height: 48px fw-bold" aria-hidden="true">
                                    <?php echo $inisial; ?>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <p class="mb-0 fw-semibold"><?php echo $nama_pengirim; ?></p>
                                            <small class="text-secondary"> 
                                                <?php echo htmlspecialchars($detail['email_pengirim']); ?> &mdash; to me 
                                            </small>
                                        </div>
                                        <small class="text-secondary">
                                            <?php echo date('d M Y, H:i', strtotime($detail['created_at'])); ?>
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4 border-top pt-3 text-dark" style="white-space: pre-line; line-height: 1.6;">
                                <?php echo htmlspecialchars($detail['isi_pesan']); ?>
                            </div>

                            <?php if ($detail['is_draft'] == 1): ?>
                                <span class="badge text-bg-warning"><i class="bi bi-file-earmark-code me-1"></i>Draft Mode</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="card-footer d-flex gap-2 bg-white">
                            <a href="kirim_pesan.php" class="btn btn-primary">
                                <i class="bi bi-reply me-1" aria-hidden="true"></i>Reply
                            </a>
                            
                            <form action="" method="POST" class="ms-auto" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesan ini?');">
                                <input type="hidden" name="id_pesan_del" value="<?php echo $detail['id']; ?>">
                                <button type="submit" name="action_hapus" class="btn btn-outline-danger">
                                    <i class="bi bi-trash me-1" aria-hidden="true"></i>Delete
                                </button>
                            </form>
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