-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 25, 2025 at 11:15 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET FOREIGN_KEY_CHECKS = 0; 
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `suratunma`
--
CREATE DATABASE IF NOT EXISTS `suratunma` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `suratunma`;


-- --------------------------------------------------------

--
-- Table structure for table `sk`
--

DROP TABLE IF EXISTS `sk`;
CREATE TABLE IF NOT EXISTS `sk` (
  `id_sk` int NOT NULL AUTO_INCREMENT,
  `no_sk` varchar(100) NOT NULL,
  `tgl_sk` date NOT NULL,
  `perihal` varchar(255) NOT NULL,
  `sumber_sk` enum('Rektorat','Fakultas','Yayasan') NOT NULL,
  `file` varchar(255) NOT NULL,
  PRIMARY KEY (`id_sk`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sk`
--

INSERT INTO `sk` (`id_sk`, `no_sk`, `tgl_sk`, `perihal`, `sumber_sk`, `file`) VALUES
(5, '3', '2025-09-23', 'SK lulus', 'Yayasan', 'sk_68d29db4708e18.16246701.jpg'),
(10, '14/FT/3635', '2025-09-22', 'Penerimaan Beasiswa Hukum ', 'Rektorat', 'sk_68d29ba8013103.93919211.jpg'),
(13, '5', '2025-09-24', 'Penerimaan Beasiswa Hukum ', 'Fakultas', 'sk_68d31871006f36.64644881.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_disposisi`
--

DROP TABLE IF EXISTS `tbl_disposisi`;
CREATE TABLE IF NOT EXISTS `tbl_disposisi` (
  `id_disposisi` int(4) NOT NULL AUTO_INCREMENT,
  `tujuan` varchar(250) NOT NULL,
  `isi_disposisi` mediumtext NOT NULL,
  `sifat` varchar(100) NOT NULL,
  `batas_waktu` date NOT NULL,
  `catatan` varchar(250) NOT NULL,
  `id_surat` int NOT NULL,
  `id_user` tinyint(4) NOT NULL,
  PRIMARY KEY (`id_disposisi`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_disposisi`
--

INSERT INTO `tbl_disposisi` (`id_disposisi`, `tujuan`, `isi_disposisi`, `sifat`, `batas_waktu`, `catatan`, `id_surat`, `id_user`) VALUES
(3, 'TU ', 'tolong bantu mengerjakan website', 'biasa', '2025-07-09', 'segera', 7, 1),
(4, 'Akademik', 'pendataan mahasiswa', 'Penting', '2025-07-29', 'segera', 6, 1),
(9, 'majalengka', '118', 'penting', '2025-09-18', 'pentingg', 28, 10);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_instansi`
--

DROP TABLE IF EXISTS `tbl_instansi`;
CREATE TABLE IF NOT EXISTS `tbl_instansi` (
  `id_instansi` tinyint(1) NOT NULL,
  `institusi` varchar(150) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `status` varchar(150) NOT NULL,
  `alamat` varchar(150) NOT NULL,
  `kepsek` varchar(50) NOT NULL,
  `nidn` varchar(25) DEFAULT NULL,
  `website` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `logo` varchar(250) NOT NULL,
  `id_user` tinyint NOT NULL,
  PRIMARY KEY (`id_instansi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_instansi`
--

INSERT INTO `tbl_instansi` (`id_instansi`, `institusi`, `nama`, `status`, `alamat`, `kepsek`, `nidn`, `website`, `email`, `logo`, `id_user`) VALUES
(1, 'Fakultas Teknik', 'Universitas Majalengka', 'Terakreditasi B', 'Jl. Raya K H Abdul Halim No.103, Majalengka Kulon, Kec. Majalengka, Kabupaten Majalengka, Jawa Barat 45418', 'Doni Susandy', '-', 'https://ft.unma.ac.id/', 'universitasmajalengkat@gmail.com', 'logo_1756364306.png', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_klasifikasi`
--

DROP TABLE IF EXISTS `tbl_klasifikasi`;
CREATE TABLE IF NOT EXISTS `tbl_klasifikasi` (
  `id_klasifikasi` int NOT NULL AUTO_INCREMENT,
  `kode` varchar(30) NOT NULL,
  `nama` varchar(250) NOT NULL,
  `uraian` mediumtext NOT NULL,
  `id_user` tinyint NOT NULL,
  PRIMARY KEY (`id_klasifikasi`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sett`
--

DROP TABLE IF EXISTS `tbl_sett`;
CREATE TABLE IF NOT EXISTS `tbl_sett` (
  `id_sett` tinyint(1) NOT NULL,
  `surat_masuk` tinyint NOT NULL,
  `surat_keluar` tinyint NOT NULL,
  `referensi` tinyint NOT NULL,
  `id_user` tinyint NOT NULL,
  PRIMARY KEY (`id_sett`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_sett`
--

INSERT INTO `tbl_sett` (`id_sett`, `surat_masuk`, `surat_keluar`, `referensi`, `id_user`) VALUES
(1, 10, 10, 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_surat_keluar`
--

DROP TABLE IF EXISTS `tbl_surat_keluar`;
CREATE TABLE IF NOT EXISTS `tbl_surat_keluar` (
  `id_surat` int NOT NULL AUTO_INCREMENT,
  `no_agenda` int NOT NULL,
  `tujuan` varchar(250) NOT NULL,
  `no_surat` varchar(50) NOT NULL,
  `isi` mediumtext NOT NULL,
  `tgl_surat` date NOT NULL,
  `tgl_catat` date NOT NULL,
  `file` varchar(250) NOT NULL,
  `keterangan` varchar(250) NOT NULL,
  `id_user` tinyint NOT NULL,
  PRIMARY KEY (`id_surat`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_surat_keluar`
--

INSERT INTO `tbl_surat_keluar` (`id_surat`, `no_agenda`, `tujuan`, `no_surat`, `isi`, `tgl_surat`, `tgl_catat`, `file`, `keterangan`, `id_user`) VALUES
(1, 1, 'nurul', '22', 'pengajuan', '2024-11-02', '2025-02-06', 'logo_1756364258.png', 'Permintaan Donasi penting', 1),
(4, 4, 'heaven', '8', 'yuhuuu', '2025-08-28', '2025-08-28', 'tugas.docx', 'good', 1),
(16, 1, 'delia', '5', '', '2025-09-17', '2025-09-17', '', 'siap', 10);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_surat_masuk`
--

DROP TABLE IF EXISTS `tbl_surat_masuk`;
CREATE TABLE IF NOT EXISTS `tbl_surat_masuk` (
  `id_surat` int NOT NULL AUTO_INCREMENT,
  `no_agenda` int NOT NULL,
  `no_surat` varchar(50) NOT NULL,
  `asal_surat` varchar(250) NOT NULL,
  `isi` mediumtext NOT NULL,
  `tgl_surat` date NOT NULL,
  `tgl_diterima` date NOT NULL,
  `file` varchar(250) NOT NULL,
  `keterangan` varchar(250) NOT NULL,
  `id_user` tinyint NOT NULL,
  PRIMARY KEY (`id_surat`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_surat_masuk`
--

INSERT INTO `tbl_surat_masuk` (`id_surat`, `no_agenda`, `no_surat`, `asal_surat`, `isi`, `tgl_surat`, `tgl_diterima`, `file`, `keterangan`, `id_user`) VALUES
(6, 1, '34/FT/UNMA/2025', 'Majalengka', '', '2025-02-06', '2025-02-06', '8260-ANALISA APLIKASI BERBASIS WEB.pdf', 'Segera', 1),
(7, 2, '17', 'majalengka', 'mengerjakan website ini 1', '2025-07-07', '2025-07-06', 'c.jpg', 'siap', 1),
(26, 1, '01', 'Bank Indonesia', 'keuangan', '2025-09-10', '2025-09-10', '', '', 5),
(28, 5, '5', 'jakarta', 'apa aja', '2025-05-08', '2025-09-17', '', 'penting\r\n', 10);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

DROP TABLE IF EXISTS `tbl_user`;
CREATE TABLE IF NOT EXISTS `tbl_user` (
  `id_user` tinyint NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(225) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `nama` varchar(50) NOT NULL,
  `nip` varchar(25) NOT NULL,
  `admin` tinyint(1) NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id_user`, `username`, `email`, `password`, `nama`, `nip`, `admin`, `reset_token`) VALUES
(1, 'super admin', '', '827ccb0eea8a706c4c34a16891f84e7b', 'SUPER ADMIN', '102222373', 1, ''),
(5, 'petugas', '', 'b53fe7751b37e40ff34d012c7774d65f', 'petugas', '23052008', 3, ''),
(10, 'admin', '', 'e00cf25ad42683b3df678c61f42c6bda', 'admin', '2303498942', 2, '');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
