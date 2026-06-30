<?php
// ===================================================
// CMS MAILBOX / INBOX SYSTEM - COMPATIBLE PHP 5.2+
// ===================================================
if (!isset($_SESSION)) {
    session_start();
}

// FIX PATH: Naik 2 tingkat dari mailbox/ ke root (MY-PPID/) untuk mencari folder conf/
require_once "../../conf/auth.php";
require_once "../../conf/koneksi.php";

$title_halaman = "Inbox Mailbox"; 
$pesan = "";

/** @var mysqli $koneksi */

// 1. LOGIKA PROSES HAPUS PESAN (SINGLE ATAU MASSAL JIKA DIBUTUHKAN)
if (isset($_GET['hapus'])) {
    $id_hapus = (int)$_GET['hapus'];
    $q_delete = "DELETE FROM mailbox_messages WHERE id = $id_hapus";
    if (mysqli_query($koneksi, $q_delete)) {
        header("Location: kirim_pesan.php?status=deleted");
        exit;
    }
}

// TOGGLE STARRED (Ubah status bintang lewat parameter URL cepat)
if (isset($_GET['toggle_star'])) {
    $id_star = (int)$_GET['toggle_star'];
    mysqli_query($koneksi, "UPDATE mailbox_messages SET is_starred = NOT is_starred WHERE id = $id_star");
    header("Location: kirim_pesan.php");
    exit;
}

// 2. KONDISI NOTIFIKASI
if (isset($_GET['status'])) {
    if ($_GET['status'] == 'success') $pesan = "<div class='alert alert-success alert-dismissible fade show'><i class='bi bi-check-circle-fill me-2'></i>Pesan berhasil dikirim!<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    if ($_GET['status'] == 'deleted') $pesan = "<div class='alert alert-danger alert-dismissible fade show'><i class='bi bi-trash-fill me-2'></i>Pesan berhasil dihapus dari sistem.<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
}

// 3. QUERY MENGHITUNG KATEGORI (SIDEBAR)
$q_count_inbox   = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM mailbox_messages WHERE is_draft = 0");
$res_count_inbox = mysqli_fetch_assoc($q_count_inbox);
$total_inbox     = $res_count_inbox['total'];

$q_count_unread   = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM mailbox_messages WHERE is_draft = 0 AND status_baca = 'belum'");
$res_count_unread = mysqli_fetch_assoc($q_count_unread);
$total_unread     = $res_count_unread['total'];

$q_count_draft   = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM mailbox_messages WHERE is_draft = 1");
$res_count_draft = mysqli_fetch_assoc($q_count_draft);
$total_draft     = $res_count_draft['total'];

// 4. QUERY UTAMA UNTUK MENAMPILKAN DAFTAR INBOX (Urutan pesan terbaru di atas)
$query_tampil = mysqli_query($koneksi, "SELECT * FROM mailbox_messages WHERE is_draft = 0 ORDER BY id DESC");
$total_data   = mysqli_num_rows($query_tampil);
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

        <main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">Mailbox</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Inbox</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="app-content">
                <div class="container-fluid">
                    
                    <?php if ($pesan != "") echo $pesan; ?>

                    <div class="row g-3">
                        <div class="col-lg-3">
                            <a href="../mailbox/kirim_pesan.php" class="btn btn-primary w-100 mb-3">
                                <i class="bi bi-pencil-square me-1" aria-hidden="true"></i> Compose
                            </a>
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Folders</h3>
                                </div>
                                <div class="card-body p-0">
                                    <ul class="nav nav-pills flex-column mb-0">
                                        <li class="nav-item">
                                            <a href="kirim_pesan.php" class="nav-link active rounded-0 d-flex justify-content-between">
                                                <span><i class="bi bi-inbox me-2" aria-hidden="true"></i>Inbox</span>
                                                <span class="badge text-bg-primary"><?php echo $total_unread; ?></span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#" class="nav-link rounded-0">
                                                <i class="bi bi-send me-2" aria-hidden="true"></i>Sent
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#" class="nav-link rounded-0 d-flex justify-content-between">
                                                <span><i class="bi bi-file-earmark me-2" aria-hidden="true"></i>Drafts</span>
                                                <span class="badge text-bg-secondary"><?php echo $total_draft; ?></span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#" class="nav-link rounded-0">
                                                <i class="bi bi-star me-2" aria-hidden="true"></i>Starred
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#" class="nav-link rounded-0">
                                                <i class="bi bi-trash me-2" aria-hidden="true"></i>Trash
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-9">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Inbox</h3>
                                    <div class="card-tools">
                                        <div class="input-group input-group-sm" style="width: 16rem">
                                            <span class="input-group-text"><i class="bi bi-search" aria-hidden="true"></i></span>
                                            <input type="search" class="form-control" placeholder="Search mail…" aria-label="Search mail" />
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="d-flex align-items-center px-3 py-2 border-bottom">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input" type="checkbox" id="select-all" />
                                            <label class="form-check-label visually-hidden" for="select-all">Select all</label>
                                        </div>
                                        <div class="btn-group btn-group-sm ms-3">
                                            <button class="btn btn-outline-secondary" type="button" title="Refresh" onclick="location.reload();">
                                                <i class="bi bi-arrow-clockwise" aria-hidden="true"></i>
                                            </button>
                                            <button class="btn btn-outline-secondary" type="button" title="Delete">
                                                <i class="bi bi-trash" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                        <span class="ms-auto text-secondary small">
                                            Total: <strong><?php echo $total_data; ?></strong> Pesan
                                        </span>
                                    </div>
                                    
                                    <ul class="list-group list-group-flush mb-0">
                                        <?php if ($total_data > 0): ?>
                                            <?php while($row = mysqli_fetch_assoc($query_tampil)): 
                                                // Logika pembeda style jika pesan belum dibaca (Font tebal & background kontras)
                                                $is_unread_class = ($row['status_baca'] == 'belum') ? 'fw-semibold bg-body-secondary' : '';
                                                $star_icon = ($row['is_starred'] == 1) ? 'bi-star-fill text-warning' : 'bi-star';
                                            ?>
                                                <li class="list-group-item d-flex align-items-center gap-2 py-3 <?php echo $is_unread_class; ?>">
                                                    <div class="form-check mb-0">
                                                        <input class="form-check-input" type="checkbox" value="<?php echo $row['id']; ?>" id="msg-<?php echo $row['id']; ?>" />
                                                    </div>
                                                    
                                                    <a href="kirim_pesan.php?toggle_star=<?php echo $row['id']; ?>" class="btn btn-link p-0 lh-1" title="Beri Bintang">
                                                        <i class="bi <?php echo $star_icon; ?>" aria-hidden="true"></i>
                                                    </a>
                                                    
                                                    <a href="../mailbox/read.php?id=<?php echo $row['id']; ?>" class="flex-grow-1 d-flex flex-column flex-md-row gap-md-3 text-decoration-none text-body">
                                                        <span class="text-truncate" style="min-width: 10rem">
                                                            <?php echo htmlspecialchars($row['nama_pengirim']); ?>
                                                        </span>
                                                        <span class="flex-grow-1 text-truncate">
                                                            <?php if($row['status_baca'] == 'belum'): ?>
                                                                <span class="badge text-bg-primary me-2">Baru</span>
                                                            <?php endif; ?>
                                                            <?php echo htmlspecialchars($row['subjek']); ?>
                                                            <span class="fw-normal text-secondary">
                                                                &nbsp;&mdash; <?php echo htmlspecialchars(substr(strip_tags($row['isi_pesan']), 0, 70)) . '...'; ?>
                                                            </span>
                                                        </span>
                                                        <span class="text-secondary small text-md-end" style="min-width: 8rem">
                                                            <?php echo date('d M Y, H:i', strtotime($row['created_at'])); ?>
                                                        </span>
                                                    </a>
                                                    
                                                    <div class="btn-group btn-group-sm ms-auto">
                                                        <a href="kirim_pesan.php?hapus=<?php echo $row['id']; ?>" onclick="return confirm('Hapus pesan ini?');" class="btn text-danger p-0 px-1" title="Hapus Permanen">
                                                            <i class="bi bi-trash"></i>
                                                        </a>
                                                    </div>
                                                </li>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <li class="list-group-item text-center text-muted p-4">
                                                <i class="bi bi-inbox display-6 mb-2 d-block text-secondary"></i> Kotak masuk Anda masih kosong.
                                            </li>
                                        <?php endif; ?>
                                    </ul>
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