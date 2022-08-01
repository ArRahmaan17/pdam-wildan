-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 30, 2022 at 07:38 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `air`
--

-- --------------------------------------------------------

--
-- Table structure for table `anggota`
--

CREATE TABLE `anggota` (
  `id_anggota` int(11) NOT NULL,
  `kode_rumah` varchar(5) NOT NULL,
  `nama_anggota` varchar(30) NOT NULL,
  `nomer_anggota` varchar(13) NOT NULL,
  `rt` varchar(3) NOT NULL,
  `jenis_kelamin` enum('lk','pr') NOT NULL,
  `meteran_bulanlalu` varchar(15) NOT NULL,
  `meteran_terakhir` varchar(15) NOT NULL,
  `bulan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `anggota`
--

INSERT INTO `anggota` (`id_anggota`, `kode_rumah`, `nama_anggota`, `nomer_anggota`, `rt`, `jenis_kelamin`, `meteran_bulanlalu`, `meteran_terakhir`, `bulan`) VALUES
(1, 'DT001', 'test', '089221', '01', 'lk', '20', '30', 7);

-- --------------------------------------------------------

--
-- Table structure for table `danakeluar`
--

CREATE TABLE `danakeluar` (
  `id_keluar` int(11) NOT NULL,
  `pengeluaran` varchar(50) NOT NULL,
  `nominal` int(20) NOT NULL,
  `tanggal` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `danakeluar`
--

INSERT INTO `danakeluar` (`id_keluar`, `pengeluaran`, `nominal`, `tanggal`) VALUES
(1, 'Taliasih Tanah', 10000000, '2022-07-29'),
(2, 'Pembelian Meteran & Perlengkapan', 10000000, '2022-07-29'),
(3, 'Pembayaran Notaris', 1000000, '2022-07-29'),
(4, 'Membeli Keran', 600000, '2022-07-29'),
(5, 'Membeli Peralon ¾ 18 pcs', 430000, '2022-07-29'),
(6, 'Konsumsi', 400000, '2022-07-29');

-- --------------------------------------------------------

--
-- Table structure for table `danamasuk`
--

CREATE TABLE `danamasuk` (
  `id_masuk` int(11) NOT NULL,
  `pemasukan` varchar(100) NOT NULL,
  `nominal` int(20) NOT NULL,
  `tanggal` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `danamasuk`
--

INSERT INTO `danamasuk` (`id_masuk`, `pemasukan`, `nominal`, `tanggal`) VALUES
(1, 'Iuran Warga', 23800000, '2022-07-29'),
(2, 'Pinjaman RT 02', 2500000, '2022-07-29'),
(3, 'Pinjaman RT 01', 7500000, '2022-07-29'),
(4, 'Subsidi CV. LIA', 600000, '2022-07-29');

-- --------------------------------------------------------

--
-- Table structure for table `informasi`
--

CREATE TABLE `informasi` (
  `id_informasi` int(11) NOT NULL,
  `isi_informasi` text NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `informasi`
--

INSERT INTO `informasi` (`id_informasi`, `isi_informasi`, `status`) VALUES
(1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus condimentum augue dolor, a suscipit est consequat et. Maecenas vitae urna erat. Fusce sodales pulvinar risus eu consectetur. Quisque sit amet quam pulvinar, faucibus lectus non, luctus mauris. In hac habitasse platea dictumst. Suspendisse dapibus augue vitae enim porttitor consequat at ac ipsum. Quisque ut malesuada odio, ultricies ullamcorper lorem. Maecenas suscipit et quam a iaculis. Fusce nec nunc lacinia, commodo est vitae, egestas nisl. Sed dignissim congue volutpat. Etiam in dictum dui. Integer vestibulum auctor eros ac malesuada.', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pegawai`
--

CREATE TABLE `pegawai` (
  `id_pegawai` int(11) NOT NULL,
  `nama_pegawai` varchar(20) NOT NULL,
  `nomer_pegawai` varchar(13) NOT NULL,
  `jenis_kelamin` enum('lk','pr') NOT NULL,
  `role` enum('petugas','admin') NOT NULL,
  `kode` varchar(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pegawai`
--

INSERT INTO `pegawai` (`id_pegawai`, `nama_pegawai`, `nomer_pegawai`, `jenis_kelamin`, `role`, `kode`) VALUES
(1, 'Sugeng Agus Widodo', '', 'lk', 'admin', NULL),
(2, 'Daya Negara', '', 'lk', 'admin', NULL),
(3, 'Wildan Abdul Rahman', '', 'lk', 'admin', NULL),
(4, 'H. Basuki', '', 'lk', 'admin', NULL),
(5, 'Muhammad Rasyid', '', 'lk', 'admin', NULL),
(6, ' Paijo', '', 'lk', 'admin', NULL),
(7, 'Hudiyanto', '', 'lk', 'admin', NULL),
(8, 'Sutrisno', '', 'lk', 'admin', NULL),
(9, 'H. Marsito', '', 'lk', 'admin', NULL),
(10, 'Sutrisno', '', 'lk', 'admin', NULL),
(11, 'Adi Sutrisno', '', 'lk', 'admin', NULL),
(12, 'Soleh Efendi', '', 'lk', 'petugas', NULL),
(13, 'Maryanto', '', 'lk', 'admin', NULL),
(14, 'Agus Widodo', '', 'lk', 'admin', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int(11) NOT NULL,
  `kode_rumah` varchar(10) NOT NULL,
  `meter_bulan_lalu` bigint(20) NOT NULL,
  `meter_bulan_ini` bigint(20) NOT NULL,
  `pemakaian_air` varchar(6) NOT NULL,
  `total_bayar` varchar(15) NOT NULL,
  `tanggal_bayar` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id_pembayaran`, `kode_rumah`, `meter_bulan_lalu`, `meter_bulan_ini`, `pemakaian_air`, `total_bayar`, `tanggal_bayar`) VALUES
(1, 'DT001', 0, 20, '20', '50000', '2022-07-30'),
(2, 'DT001', 20, 30, '10', '30000', '2022-07-30');

-- --------------------------------------------------------

--
-- Table structure for table `setting_pembayaran`
--

CREATE TABLE `setting_pembayaran` (
  `id_setting` int(11) NOT NULL,
  `biaya_beban` int(11) NOT NULL,
  `PPN` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `setting_pembayaran`
--

INSERT INTO `setting_pembayaran` (`id_setting`, `biaya_beban`, `PPN`) VALUES
(1, 2000, 10000);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `anggota`
--
ALTER TABLE `anggota`
  ADD PRIMARY KEY (`id_anggota`),
  ADD UNIQUE KEY `kode_rumah` (`kode_rumah`);

--
-- Indexes for table `danakeluar`
--
ALTER TABLE `danakeluar`
  ADD PRIMARY KEY (`id_keluar`);

--
-- Indexes for table `danamasuk`
--
ALTER TABLE `danamasuk`
  ADD PRIMARY KEY (`id_masuk`);

--
-- Indexes for table `informasi`
--
ALTER TABLE `informasi`
  ADD PRIMARY KEY (`id_informasi`);

--
-- Indexes for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD UNIQUE KEY `id_pegawai` (`id_pegawai`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `id_anggota` (`kode_rumah`);

--
-- Indexes for table `setting_pembayaran`
--
ALTER TABLE `setting_pembayaran`
  ADD PRIMARY KEY (`id_setting`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `anggota`
--
ALTER TABLE `anggota`
  MODIFY `id_anggota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `danakeluar`
--
ALTER TABLE `danakeluar`
  MODIFY `id_keluar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `danamasuk`
--
ALTER TABLE `danamasuk`
  MODIFY `id_masuk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `informasi`
--
ALTER TABLE `informasi`
  MODIFY `id_informasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pegawai`
--
ALTER TABLE `pegawai`
  MODIFY `id_pegawai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `setting_pembayaran`
--
ALTER TABLE `setting_pembayaran`
  MODIFY `id_setting` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
