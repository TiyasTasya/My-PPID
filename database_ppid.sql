-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 30, 2026 at 06:15 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `database_ppid`
--

-- --------------------------------------------------------

--
-- Table structure for table `calendar_events`
--

CREATE TABLE `calendar_events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime DEFAULT NULL,
  `color` varchar(7) DEFAULT '#0d6efd',
  `is_all_day` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mailbox_messages`
--

CREATE TABLE `mailbox_messages` (
  `id` int(11) NOT NULL,
  `nama_pengirim` varchar(100) NOT NULL,
  `email_pengirim` varchar(100) NOT NULL,
  `subjek` varchar(255) NOT NULL,
  `isi_pesan` text NOT NULL,
  `status_baca` enum('belum','sudah') DEFAULT 'belum',
  `is_starred` tinyint(1) DEFAULT 0,
  `is_draft` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT 0,
  `nama_menu` varchar(100) NOT NULL,
  `url_menu` varchar(255) NOT NULL,
  `konten` text DEFAULT NULL,
  `urutan` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `parent_id`, `nama_menu`, `url_menu`, `konten`, `urutan`) VALUES
(1, 0, 'Profil PPID', '#', '', 1),
(2, 1, 'Visi, Misi & Tugas', 'index.php?page=2', '<h3>Visi, Misi & Tugas</h3><p>Isi konten visi misi...</p>', 1),
(3, 1, 'Struktur Organisasi', 'index.php?page=3', '<h3>Struktur Organisasi</h3><p>Isi struktur organisasi...</p>', 2),
(5, 0, 'Informasi Publik', '#', '', 2),
(6, 5, 'Informasi Berkala', 'index.php?page=6', '<h3>Informasi Berkala</h3>', 1),
(7, 5, 'Informasi Serta Merta', 'index.php?page=7', '<h3>Informasi Serta Merta</h3>', 2),
(8, 5, 'Informasi Setiap Saat', 'index.php?page=8', '<h3>Informasi Setiap Saat</h3>', 3),
(9, 1, 'Tentang PPID', 'index.php?page=9', 'Cek', 3),
(10, 0, 'Kontak', '#', '', 1),
(11, 10, 'Informasi Kontak', 'index.php?page=11', 'Direktorat Jenderal Kesehatan Lanjutan\r\n\r\nOur Office:\r\n\r\nJl. HR. Rasuna Said Blok X-5 Kav. 4 - 9, Kuningan, RT.1/RW.2, Kuningan, Kuningan Tim., Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12950\r\nEmail:\r\n\r\nhumas.keslan@kemkes.go.id\r\nhumas.keslan@gmail.com', 0);

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `judul` varchar(255) NOT NULL,
  `pesan` text NOT NULL,
  `icon` varchar(50) DEFAULT 'bi-info-circle',
  `warna_icon` varchar(30) DEFAULT 'text-info',
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(5) NOT NULL,
  `nama` varchar(35) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `username`, `password`) VALUES
(1, 'admin', 'superadmin', '5f4dcc3b5aa765d61d8327deb882cf99');

-- --------------------------------------------------------

--
-- Table structure for table `web_settings`
--

CREATE TABLE `web_settings` (
  `key_setting` varchar(50) NOT NULL,
  `value_setting` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `web_settings`
--

INSERT INTO `web_settings` (`key_setting`, `value_setting`) VALUES
('footer_text', '© 2026 PPID Resmi - Unit Pelayanan Kesehatan Kementerian Kesehatan RI. Hak Cipta Dilindungi.'),
('home_desc', 'Sesuai amanat Undang-Undang No. 14 Tahun 2008 tentang Keterbukaan Informasi Publik, kami berkomitmen menyediakan informasi yang akurat, relevan, dan mudah diakses oleh seluruh masyarakat.'),
('home_title', 'Portal Keterbukaan Informasi Publik');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `calendar_events`
--
ALTER TABLE `calendar_events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mailbox_messages`
--
ALTER TABLE `mailbox_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `web_settings`
--
ALTER TABLE `web_settings`
  ADD PRIMARY KEY (`key_setting`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `calendar_events`
--
ALTER TABLE `calendar_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `mailbox_messages`
--
ALTER TABLE `mailbox_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
