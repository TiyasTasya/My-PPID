<?php
// ===================================================
// CMS MAILBOX - COMPOSE MESSAGE - COMPATIBLE PHP 5.2
// ===================================================
if (!isset($_SESSION)) {
    session_start();
}

// FIX PATH: Naik 2 tingkat dari mailbox/ ke root (MY-PPID/) untuk mencari folder conf/
require_once "../../conf/auth.php";
require_once "../../conf/koneksi.php";

$title_halaman = "Terima Pesan";
$pesan = "";

/** @var mysqli $koneksi */

// LOGIKA PROSES SIMPAN (KIRIM / DRAFT)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action_mailbox'])) {
    $email_penerima = isset($_POST['email_penerima']) ? trim($_POST['email_penerima']) : '';
    $subjek         = isset($_POST['subjek']) ? trim($_POST['subjek']) : '';
    $isi_pesan      = isset($_POST['isi_pesan']) ? trim($_POST['isi_pesan']) : '';
    $is_draft       = isset($_POST['is_draft']) ? (int)$_POST['is_draft'] : 0;

    if ($email_penerima != "" && $subjek != "") {
        // Mengamankan input (Aman dari PHP 5.2 sampai PHP 8.x)
        $email_aman = mysqli_real_escape_string($koneksi, $email_penerima);
        $subjek_aman = mysqli_real_escape_string($koneksi, $subjek);
        $isi_aman   = mysqli_real_escape_string($koneksi, $isi_pesan);
        
        // Catatan: Karena dikirim oleh Admin, kita set nama_pengirim secara default
        $nama_admin = "Admin PPID"; 

        $q_insert = "INSERT INTO mailbox_messages (nama_pengirim, email_pengirim, subjek, isi_pesan, status_baca, is_starred, is_draft) 
                     VALUES ('$nama_admin', '$email_aman', '$subjek_aman', '$isi_aman', 'dibaca', 0, $is_draft)";
        
        if (mysqli_query($koneksi, $q_insert)) {
            $status_redirect = ($is_draft == 1) ? "updated" : "success";
            header("Location: kirim_pesan.php?status=" . $status_redirect);
            exit;
        } else {
            $pesan = "<div class='alert alert-danger'><i class='bi bi-exclamation-triangle-fill me-2'></i>Gagal memproses pesan!</div>";
        }
    } else {
        $pesan = "<div class='alert alert-warning'><i class='bi bi-exclamation-circle-fill me-2'></i>Kolom 'To' dan 'Subject' wajib diisi!</div>";
    }
}

// KONDISI NOTIFIKASI
if (isset($_GET['status'])) {
    if ($_GET['status'] == 'success') $pesan = "<div class='alert alert-success'><i class='bi bi-check-circle-fill me-2'></i>Pesan berhasil terkirim!</div>";
    if ($_GET['status'] == 'updated') $pesan = "<div class='alert alert-info'><i class='bi bi-info-circle-fill me-2'></i>Pesan berhasil disimpan ke Draft!</div>";
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
                            <h3 class="mb-0">Compose Message</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="../dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Compose</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="app-content">
                <div class="container-fluid">
                    
                    <?php if ($pesan != "") echo $pesan; ?>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">New Message</h3>
                        </div>
                        
                        <form action="" method="POST" id="formMailbox">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label" for="mail-to">To</label>
                                        <input
                                            type="email"
                                            name="email_penerima"
                                            class="form-control"
                                            id="mail-to"
                                            placeholder="recipient@example.com" required />
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label" for="mail-subject">Subject</label>
                                        <input type="text" name="subjek" class="form-control" id="mail-subject" required />
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label" for="my-text-area">Message</label>
                                        <textarea id="my-text-area" name="isi_pesan" class="form-control" rows="5"></textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label" for="mail-attach">Attachments</label>
                                        <input type="file" class="form-control" id="mail-attach" multiple />
                                    </div>
                                </div>
                                
                                <input type="hidden" name="is_draft" id="is_draft" value="0" />
                                <input type="hidden" name="action_mailbox" value="1" />
                            </div>
                            
                            <div class="card-footer d-flex gap-2">
                                <button class="btn btn-primary" type="submit" onclick="setDraftStatus(0)">
                                    <i class="bi bi-send me-1" aria-hidden="true"></i>Send
                                </button>
                                <button class="btn btn-outline-secondary" type="submit" onclick="setDraftStatus(1)">
                                    <i class="bi bi-file-earmark me-1" aria-hidden="true"></i>Save draft
                                </button>
                                <a href="kirim_pesan.php" class="btn btn-outline-danger ms-auto">
                                    <i class="bi bi-x-lg me-1" aria-hidden="true"></i>Discard
                                </a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </main>
        <?php include "../includes/footer.php"; ?>

    </div>

    <script>
        // Mengatur nilai input is_draft sesaat sebelum disubmit formnya
        function setDraftStatus(val) {
            document.getElementById('is_draft').value = val;
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>
    <script src="/my-ppid/app/js/adminlte.js"></script>
    <script src="/my-ppid/app/js/mode.js"></script>
    <script src="/my-ppid/app/js/text.js"></script>
</body>

</html>