<?php
// Konfigurasi Database untuk PHP 5.2 (Menggunakan MySQLi Prosedural)
$host     = "localhost";     // Nama host server Anda
$user     = "root";          // Username database Anda (bawaan xampp adalah root)
$password = "";              // Password database Anda (bawaan xampp adalah kosong)
$database = "database_ppid"; // Nama database Anda

// Membuat koneksi ke database
$koneksi = mysqli_connect($host, $user, $password, $database);


// Periksa apakah koneksi berhasil
if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>