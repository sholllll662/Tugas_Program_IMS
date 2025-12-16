-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 16, 2025 at 03:03 PM
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
-- Database: `db_ims`
--

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_angsuran`
--

CREATE TABLE `jadwal_angsuran` (
  `id` int(11) NOT NULL,
  `no_kontrak` varchar(50) NOT NULL,
  `angsuran_ke` int(10) UNSIGNED NOT NULL,
  `angsuran_perbln` bigint(20) UNSIGNED NOT NULL,
  `tanggal_jatuh_tempo` date NOT NULL,
  `status_bayar` enum('BELUM BAYAR','LUNAS') NOT NULL DEFAULT 'BELUM BAYAR',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadwal_angsuran`
--

INSERT INTO `jadwal_angsuran` (`id`, `no_kontrak`, `angsuran_ke`, `angsuran_perbln`, `tanggal_jatuh_tempo`, `status_bayar`, `created_at`) VALUES
(1, 'AGR00001', 1, 12160000, '2026-01-16', 'BELUM BAYAR', '2025-12-16 21:02:42'),
(2, 'AGR00001', 2, 12160000, '2026-02-16', 'BELUM BAYAR', '2025-12-16 21:02:42'),
(3, 'AGR00001', 3, 12160000, '2026-03-16', 'BELUM BAYAR', '2025-12-16 21:02:42'),
(4, 'AGR00001', 4, 12160000, '2026-04-16', 'BELUM BAYAR', '2025-12-16 21:02:42'),
(5, 'AGR00001', 5, 12160000, '2026-05-16', 'BELUM BAYAR', '2025-12-16 21:02:42'),
(6, 'AGR00001', 6, 12160000, '2026-06-16', 'BELUM BAYAR', '2025-12-16 21:02:42'),
(7, 'AGR00001', 7, 12160000, '2026-07-16', 'BELUM BAYAR', '2025-12-16 21:02:42'),
(8, 'AGR00001', 8, 12160000, '2026-08-16', 'BELUM BAYAR', '2025-12-16 21:02:42'),
(9, 'AGR00001', 9, 12160000, '2026-09-16', 'BELUM BAYAR', '2025-12-16 21:02:42'),
(10, 'AGR00001', 10, 12160000, '2026-10-16', 'BELUM BAYAR', '2025-12-16 21:02:42'),
(11, 'AGR00001', 11, 12160000, '2026-11-16', 'BELUM BAYAR', '2025-12-16 21:02:42'),
(12, 'AGR00001', 12, 12160000, '2026-12-16', 'BELUM BAYAR', '2025-12-16 21:02:42'),
(13, 'AGR00001', 13, 12160000, '2027-01-16', 'BELUM BAYAR', '2025-12-16 21:02:42'),
(14, 'AGR00001', 14, 12160000, '2027-02-16', 'BELUM BAYAR', '2025-12-16 21:02:42'),
(15, 'AGR00001', 15, 12160000, '2027-03-16', 'BELUM BAYAR', '2025-12-16 21:02:42'),
(16, 'AGR00001', 16, 12160000, '2027-04-16', 'BELUM BAYAR', '2025-12-16 21:02:42'),
(17, 'AGR00001', 17, 12160000, '2027-05-16', 'BELUM BAYAR', '2025-12-16 21:02:42'),
(18, 'AGR00001', 18, 12160000, '2027-06-16', 'BELUM BAYAR', '2025-12-16 21:02:42');

-- --------------------------------------------------------

--
-- Table structure for table `kontrak`
--

CREATE TABLE `kontrak` (
  `id` int(11) NOT NULL,
  `no_kontrak` varchar(50) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `otr` bigint(20) UNSIGNED NOT NULL,
  `dp` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `jangka_waktu` int(10) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kontrak`
--

INSERT INTO `kontrak` (`id`, `no_kontrak`, `nama`, `otr`, `dp`, `jangka_waktu`, `created_at`) VALUES
(1, 'AGR00001', 'SUGUS', 240000000, 48000000, 18, '2025-12-16 21:02:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jadwal_angsuran`
--
ALTER TABLE `jadwal_angsuran`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_kontrak_angsuran` (`no_kontrak`,`angsuran_ke`);

--
-- Indexes for table `kontrak`
--
ALTER TABLE `kontrak`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `no_kontrak` (`no_kontrak`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jadwal_angsuran`
--
ALTER TABLE `jadwal_angsuran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `kontrak`
--
ALTER TABLE `kontrak`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jadwal_angsuran`
--
ALTER TABLE `jadwal_angsuran`
  ADD CONSTRAINT `fk_jadwal_kontrak` FOREIGN KEY (`no_kontrak`) REFERENCES `kontrak` (`no_kontrak`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
