-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 01 Okt 2026 pada 12.46
-- Versi server: 8.0.30
-- Versi PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_tanaman`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admins`
--

CREATE TABLE `admins` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$mIUxNo6bkJXarUrgBW2UMO8iMHhEzs9iPN6tzYXN95hsardUC9yPm');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tanaman`
--

CREATE TABLE `tanaman` (
  `id` int NOT NULL,
  `nama` varchar(120) NOT NULL,
  `nama_latin` varchar(160) DEFAULT NULL,
  `ringkasan` varchar(255) DEFAULT NULL,
  `deskripsi` text,
  `perawatan` text,
  `foto` varchar(100) DEFAULT NULL,
  `video` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `karakteristik` text,
  `jenis` text,
  `manfaat` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `tanaman`
--

INSERT INTO `tanaman` (`id`, `nama`, `nama_latin`, `ringkasan`, `deskripsi`, `perawatan`, `foto`, `video`, `created_at`, `karakteristik`, `jenis`, `manfaat`) VALUES
(1, 'Monstera', 'Monstera deliciosa', 'Tanaman hias daun berlubang yang populer.', 'Monstera berasal dari hutan tropis Amerika Tengah. Daunnya besar, mengkilap, dan berlubang khas.', 'Cahaya terang tidak langsung. Siram saat 2-3 cm tanah atas kering. Pupuk sebulan sekali.', 'e89adc74bc1cc19a.jpg', NULL, '2026-09-29 12:40:07', NULL, NULL, NULL),
(2, 'Lidah Mertua', 'Sansevieria trifasciata', 'Tangguh, cocok untuk pemula.', 'Lidah mertua tahan kondisi kering dan minim cahaya, serta dikenal membantu menyaring udara dalam ruangan.', 'Siram 2 minggu sekali. Hindari genangan air. Tahan cahaya rendah.', '7465c525f611d6fe.jpeg', NULL, '2026-09-29 12:40:07', NULL, NULL, NULL),
(3, 'Aglaonema', 'Aglaonema commutatum', 'Daun berwarna cantik untuk dalam ruangan.', 'Aglaonema atau sri rezeki punya corak daun merah, hijau, dan perak yang menarik.', 'Tempatkan di cahaya sedang. Jaga media tanam tetap lembap, tidak becek.', '5b011276b0715ad7.jpg', NULL, '2026-09-29 12:40:07', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `tanaman`
--
ALTER TABLE `tanaman`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `tanaman`
--
ALTER TABLE `tanaman`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
