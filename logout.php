<?php
// Memulai session agar sistem tahu session mana yang akan dihapus
session_start();

// 1. Hapus semua variabel session yang tersimpan
$_SESSION = array();

// 2. Hapus cookie session dari browser (jika ada) agar benar-benar bersih
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 42000, '/');
}

// 3. Hancurkan/destroy session di server
session_destroy();

// 4. Alihkan pengguna kembali ke halaman login utama
header("Location: login.php");
exit;
?>