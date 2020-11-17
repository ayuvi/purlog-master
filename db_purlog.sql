-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 07 Apr 2020 pada 10.09
-- Versi Server: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `db_purlog`
--

DELIMITER $$
--
-- Prosedur
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `fill_date_dimension`(IN `startdate` DATE, IN `stopdate` DATE)
BEGIN
    DECLARE currentdate DATE;
    SET currentdate = startdate;
    WHILE currentdate < stopdate DO
        INSERT INTO time_dimension VALUES (
                        YEAR(currentdate)*10000+MONTH(currentdate)*100 + DAY(currentdate),
                        currentdate,
                        YEAR(currentdate),
                        MONTH(currentdate),
                        DAY(currentdate),
                        QUARTER(currentdate),
                        WEEKOFYEAR(currentdate),
                        DATE_FORMAT(currentdate,'%W'),
                        DATE_FORMAT(currentdate,'%M'),
                        'f',
                        CASE DAYOFWEEK(currentdate) WHEN 1 THEN 't' WHEN 7 then 't' ELSE 'f' END,
                        NULL);
        SET currentdate = ADDDATE(currentdate,INTERVAL 1 DAY);
    END WHILE;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `p_insert_inspeksi_detail`(IN `p_id_inspeksi` INT, IN `p_id_perusahaan` INT, IN `p_id_bu` INT)
BEGIN
 DECLARE done INT DEFAULT FALSE;
 DECLARE a INT;
 DECLARE b VARCHAR(255);

 DECLARE cur1 CURSOR FOR SELECT id_cek, nm_cek FROM ref_cek where id_perusahaan = p_id_perusahaan and active = 1 ;

 DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
 	
 OPEN cur1;
 read_loop: LOOP
 FETCH cur1 INTO a, b;
 		
 IF done THEN
 LEAVE read_loop;
 END IF;
 		
 INSERT INTO tr_inspeksi_detail (id_inspeksi, id_cek, nm_cek, id_perusahaan, id_bu, status) VALUES (p_id_inspeksi, a, b, p_id_perusahaan, p_id_bu, 0);
	
 END LOOP;
 CLOSE cur1;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `p_insert_jadwal`(IN `p_id_cabang` INT, IN `p_tanggal` VARCHAR(255))
BEGIN
 DECLARE done INT DEFAULT FALSE;
 DECLARE a INT;
 
 DECLARE cur1 CURSOR FOR SELECT id_jadwal FROM ms_jadwal where tanggal = p_tanggal and id_cabang = p_id_cabang;

 DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
 update tr_lmb set active = 2 WHERE id_bu = p_id_cabang;
 #delete from tr_lmb where  id_bu = p_id_cabang and DATE_FORMAT(jam_in,'%Y-%m-%d') = p_tanggal; 
 OPEN cur1;
 read_loop: LOOP
 FETCH cur1 INTO a;
 		
 IF done THEN
 LEAVE read_loop;
 END IF;
 		
 INSERT INTO tr_lmb (id_jadwal, rit, cuser, active, jam_in) VALUES (a, 0, 0, 1, now());
	
 END LOOP;
 CLOSE cur1;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `p_insert_jadwal_armada`(IN `p_id_cabang` INT, IN `p_tanggal` VARCHAR(255), IN `p_armada` VARCHAR(255))
BEGIN
 DECLARE done INT DEFAULT FALSE;
 DECLARE a INT;
 
 DECLARE cur1 CURSOR FOR SELECT id_jadwal FROM ms_jadwal where tanggal = p_tanggal and id_cabang = p_id_cabang and armada = p_armada;

 DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
 update tr_lmb set active = 2 WHERE id_bu = p_id_cabang and kd_armada = p_armada;
 #delete from tr_lmb where  id_bu = p_id_cabang and kd_armada = p_armada and DATE_FORMAT(jam_in,'%Y-%m-%d') = p_tanggal;
 OPEN cur1;
 read_loop: LOOP
 FETCH cur1 INTO a;
 		
 IF done THEN
 LEAVE read_loop;
 END IF;
 		
 INSERT INTO tr_lmb (id_jadwal, rit, cuser, active, jam_in, manual) VALUES (a, 0, 0, 1, now(), 1);
	
 END LOOP;
 CLOSE cur1;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `p_insert_jadwal_armada_not_in`(IN `p_id_cabang` INT, IN `p_tanggal` VARCHAR(255), IN `p_armada` VARCHAR(255))
BEGIN
 DECLARE done INT DEFAULT FALSE;
 DECLARE a INT;
 
 DECLARE cur1 CURSOR FOR SELECT id_jadwal FROM ms_jadwal where tanggal = p_tanggal and id_cabang = p_id_cabang and armada NOT IN (p_armada);

 DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
 update tr_lmb set active = 2 WHERE id_bu = p_id_cabang and kd_armada NOT IN (p_armada);
 OPEN cur1;
 read_loop: LOOP
 FETCH cur1 INTO a;
 		
 IF done THEN
 LEAVE read_loop;
 END IF;
 		
 INSERT INTO tr_lmb (id_jadwal, rit, cuser, active, jam_in) VALUES (a, 0, 0, 1, now());
	
 END LOOP;
 CLOSE cur1;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `p_insert_level`(IN `p_id_level` INT, IN `p_id_perusahaan` INT)
BEGIN
 DECLARE done INT DEFAULT FALSE;
 DECLARE a_id_menu_details INT;

 DECLARE cur1 CURSOR FOR SELECT id_menu_details FROM ref_menu_details ;

 DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
 	
 OPEN cur1;
 read_loop: LOOP
 FETCH cur1 INTO a_id_menu_details;
 		
 IF done THEN
 LEAVE read_loop;
 END IF;
 		
 INSERT INTO ref_menu_details_access (id_level, id_menu_details, active, id_perusahaan) VALUES (p_id_level, a_id_menu_details, 0, p_id_perusahaan);
	
 END LOOP;
 CLOSE cur1;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `p_insert_level_group`(IN `p_id_level` INT, IN `p_id_perusahaan` INT)
BEGIN
 DECLARE done INT DEFAULT FALSE;
 DECLARE a_id_menu_groups INT;

 DECLARE cur1 CURSOR FOR SELECT id_menu_groups FROM ref_menu_groups ;

 DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
 	
 OPEN cur1;
 read_loop: LOOP
 FETCH cur1 INTO a_id_menu_groups;
 		
 IF done THEN
 LEAVE read_loop;
 END IF;
 		
 INSERT INTO ref_menu_groups_access (id_level, id_menu_groups, active, id_perusahaan) VALUES (p_id_level, a_id_menu_groups, 0, p_id_perusahaan);
	
 END LOOP;
 CLOSE cur1;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `p_insert_survei`(IN `p_id_survei` INT, IN `p_id_perusahaan` INT)
BEGIN
 DECLARE done INT DEFAULT FALSE;
 DECLARE a INT;
 DECLARE b VARCHAR(255);

 DECLARE cur1 CURSOR FOR SELECT id_cek, nm_cek FROM ref_cek where id_perusahaan = p_id_perusahaan and active = 1 ;

 DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
 	
 OPEN cur1;
 read_loop: LOOP
 FETCH cur1 INTO a, b;
 		
 IF done THEN
 LEAVE read_loop;
 END IF;
 		
 INSERT INTO tr_survei_detail (id_survei, id_cek, nm_cek, id_perusahaan, status) VALUES (p_id_survei, a, b, p_id_perusahaan, 0);
	
 END LOOP;
 CLOSE cur1;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `p_insert_survei_responden`(IN `p_id_survei` INT, IN `p_id_perusahaan` INT, IN `p_id_session` VARCHAR(255))
BEGIN
 DECLARE done INT DEFAULT FALSE;
 DECLARE a INT;
 DECLARE b VARCHAR(255);

 DECLARE cur1 CURSOR FOR SELECT id_cek, nm_cek FROM ref_cek WHERE id_perusahaan = p_id_perusahaan AND active = 1 ;

 DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
 	
 OPEN cur1;
 read_loop: LOOP
 FETCH cur1 INTO a, b;
 		
 IF done THEN
 LEAVE read_loop;
 END IF;
 		
 INSERT INTO tr_survei_responden (id_session, id_survei, id_cek, nm_cek, id_perusahaan, STATUS) VALUES (p_id_session, p_id_survei, a, b, p_id_perusahaan, 1);
	
 END LOOP;
 CLOSE cur1;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `p_menudetails`(IN `p_id_menu_details` INT, IN `p_cuser` INT)
BEGIN
 DECLARE done INT DEFAULT FALSE;
 DECLARE a_id_level, a_id_perusahaan INT;
 

 DECLARE cur1 CURSOR FOR SELECT id_level, id_perusahaan FROM ref_level  ;

 DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
 	
 OPEN cur1;
 read_loop: LOOP
 FETCH cur1 INTO a_id_level, a_id_perusahaan;
 		
 IF done THEN
 LEAVE read_loop;
 END IF;
 		
 INSERT INTO ref_menu_details_access (id_level, id_menu_details, active, cdate, cuser, id_perusahaan) VALUES (a_id_level, p_id_menu_details, 0, CURRENT_TIMESTAMP(), p_cuser, a_id_perusahaan);
	
 END LOOP;
 CLOSE cur1;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `p_menugroup`(IN `p_id_menu_groups` INT, IN `p_cuser` INT)
BEGIN
 DECLARE done INT DEFAULT FALSE;
 DECLARE a_id_level, a_id_perusahaan INT;
 

 DECLARE cur1 CURSOR FOR SELECT id_level, id_perusahaan FROM ref_level   ;

 DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
 	
 OPEN cur1;
 read_loop: LOOP
 FETCH cur1 INTO a_id_level, a_id_perusahaan;
 		
 IF done THEN
 LEAVE read_loop;
 END IF;
 		
 INSERT INTO ref_menu_groups_access (id_level, id_menu_groups, active, cdate, cuser, id_perusahaan) VALUES (a_id_level, p_id_menu_groups , 0, CURRENT_TIMESTAMP(), p_cuser, a_id_perusahaan);
	
 END LOOP;
 CLOSE cur1;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `tes`(IN `p_id_cabang` INT, IN `p_tanggal` VARCHAR(255), IN `p_armada` VARCHAR(255))
BEGIN
DECLARE a varchar(255);
set a = p_armada;
 select * from ms_jadwal where tanggal = p_tanggal and id_cabang = p_id_cabang and armada NOT IN (a) ;

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `langs`
--

CREATE TABLE IF NOT EXISTS `langs` (
`lang_srl` int(10) unsigned NOT NULL,
  `inkomaro` varchar(200) NOT NULL,
  `english` varchar(500) DEFAULT NULL,
  `indonesian` varchar(500) DEFAULT NULL,
  `korean` varchar(500) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `langs`
--

INSERT INTO `langs` (`lang_srl`, `inkomaro`, `english`, `indonesian`, `korean`) VALUES
(1, 'Approval', 'Approval', 'Persetujuan', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_barang`
--

CREATE TABLE IF NOT EXISTS `ref_barang` (
`id_barang` int(11) NOT NULL,
  `kd_barang` varchar(100) DEFAULT NULL,
  `nm_barang` varchar(255) DEFAULT NULL,
  `id_kategori` int(11) DEFAULT NULL,
  `id_satuan` int(11) DEFAULT NULL,
  `harga` decimal(20,0) DEFAULT NULL,
  `min_stok` decimal(20,0) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `deskripsi` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `cuser` int(11) DEFAULT NULL,
  `id_perusahaan` int(11) DEFAULT NULL,
  `cdate` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `ref_barang`
--

INSERT INTO `ref_barang` (`id_barang`, `kd_barang`, `nm_barang`, `id_kategori`, `id_satuan`, `harga`, `min_stok`, `gambar`, `deskripsi`, `active`, `cuser`, `id_perusahaan`, `cdate`) VALUES
(1, '312D', 'FAUZI', 2, 2, '10', '11', 'd497ff97f3fae01a6c3571884afe00b2.jpg', '454', 1, 1, 77, '2020-04-07 10:28:31');

--
-- Trigger `ref_barang`
--
DELIMITER //
CREATE TRIGGER `before_insert_barang` BEFORE INSERT ON `ref_barang`
 FOR EACH ROW begin
set new.cdate = now();
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_bu`
--

CREATE TABLE IF NOT EXISTS `ref_bu` (
`id_bu` int(11) NOT NULL,
  `kd_bu` varchar(255) DEFAULT NULL,
  `id_divre` int(11) DEFAULT NULL,
  `nm_bu` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `id_perusahaan` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `ref_bu`
--

INSERT INTO `ref_bu` (`id_bu`, `kd_bu`, `id_divre`, `nm_bu`, `active`, `id_perusahaan`) VALUES
(1, 'D1.001', 1, 'Banda Aceh', 1, 77),
(2, 'D1.002', 1, 'Bandar Lampung', 1, 77),
(3, 'D1.003', 1, 'Bandara Soekarno Hatta', 1, 77),
(4, 'D1.004', 1, 'Bandung', 1, 77),
(5, 'D1.005', 1, 'Batam', 1, 77),
(6, 'D1.006', 1, 'Bengkulu', 1, 77),
(7, 'D1.007', 1, 'Bogor', 1, 77),
(8, 'D1.008', 1, 'Jakarta', 1, 77),
(9, 'D1.009', 1, 'Jambi', 1, 77),
(10, 'D1.010', 1, 'Medan', 1, 77),
(11, 'D1.011', 1, 'Padang', 1, 77),
(12, 'D1.012', 1, 'Palembang', 1, 77),
(13, 'D1.013', 1, 'Pangkal Pinang', 1, 77),
(14, 'D1.014', 1, 'Pekanbaru', 1, 77),
(15, 'D1.015', 1, 'Serang', 1, 77),
(16, 'D1.016', 1, 'Koridor 1 & 8', 1, 77),
(17, 'D1.017', 1, 'Logistik', 1, 77),
(18, 'D2.001', 2, 'Banjarmasin', 1, 77),
(19, 'D2.002', 2, 'Cilacap', 1, 77),
(20, 'D2.003', 2, 'Palangkaraya', 1, 77),
(21, 'D2.004', 2, 'Pontianak', 1, 77),
(22, 'D2.005', 2, 'Purwokerto', 1, 77),
(23, 'D2.006', 2, 'Purworejo', 1, 77),
(24, 'D2.007', 2, 'Samarinda', 1, 77),
(25, 'D2.008', 2, 'Semarang', 1, 77),
(26, 'D2.009', 2, 'Surakarta', 1, 77),
(27, 'D2.010', 2, 'Tanjung Selor', 1, 77),
(28, 'D2.011', 2, 'Yogyakarta', 1, 77),
(29, 'D3.001', 3, 'Banyuwangi', 1, 77),
(30, 'D3.002', 3, 'Denpasar', 1, 77),
(31, 'D3.003', 3, 'Ende', 1, 77),
(32, 'D3.004', 3, 'Jember', 1, 77),
(33, 'D3.005', 3, 'Kefamenanu', 1, 77),
(34, 'D3.006', 3, 'Kendari', 1, 77),
(35, 'D3.007', 3, 'Kupang', 1, 77),
(36, 'D3.008', 3, 'Makassar', 1, 77),
(37, 'D3.009', 3, 'Malang', 1, 77),
(38, 'D3.010', 3, 'Mamuju', 1, 77),
(39, 'D3.011', 3, 'Mataram', 1, 77),
(40, 'D3.012', 3, 'Palu', 1, 77),
(41, 'D3.013', 3, 'Pamekasan', 1, 77),
(42, 'D3.014', 3, 'Ponorogo', 1, 77),
(43, 'D3.015', 3, 'Surabaya', 1, 77),
(44, 'D3.016', 3, 'Waingapu', 1, 77),
(45, 'D4.001', 4, 'Ambon', 1, 77),
(46, 'D4.002', 4, 'Biak', 1, 77),
(47, 'D4.003', 4, 'Gorontalo', 1, 77),
(48, 'D4.004', 4, 'Halmahera', 1, 77),
(49, 'D4.005', 4, 'Jayapura', 1, 77),
(50, 'D4.006', 4, 'Manado', 1, 77),
(51, 'D4.007', 4, 'Manokwari', 1, 77),
(52, 'D4.008', 4, 'Merauke', 1, 77),
(53, 'D4.009', 4, 'Mimika', 1, 77),
(54, 'D4.010', 4, 'Nabire', 1, 77),
(55, 'D4.011', 4, 'Namlea', 1, 77),
(56, 'D4.012', 4, 'Sarmi', 1, 77),
(57, 'D4.013', 4, 'Serui', 1, 77),
(58, 'D4.014', 4, 'Sorong', 1, 77),
(59, 'D4.015', 4, 'Sorong Selatan', 1, 77),
(60, 'D0.001', 5, 'Kantor Pusat', 1, 77),
(61, 'D1', 1, 'Divre 1', 1, 77),
(62, 'D2', 2, 'Divre 2', 1, 77),
(63, 'D3', 3, 'Divre 3', 1, 77),
(64, 'D4', 4, 'Divre 4', 1, 77),
(65, 'D1.018', 1, 'Koridor 11', 1, 77);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_bu_access`
--

CREATE TABLE IF NOT EXISTS `ref_bu_access` (
`id_bu_access` int(11) NOT NULL,
  `id_bu` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `active` tinyint(11) DEFAULT NULL,
  `id_perusahaan` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=453 DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `ref_bu_access`
--

INSERT INTO `ref_bu_access` (`id_bu_access`, `id_bu`, `id_user`, `active`, `id_perusahaan`) VALUES
(8, 1, 1, 1, 77),
(9, 2, 1, 1, 77),
(10, 3, 1, 1, 77),
(11, 4, 1, 1, 77),
(12, 5, 1, 1, 77),
(13, 6, 1, 1, 77),
(14, 7, 1, 1, 77),
(15, 8, 1, 1, 77),
(16, 9, 1, 1, 77),
(17, 10, 1, 1, 77),
(18, 11, 1, 1, 77),
(19, 12, 1, 1, 77),
(20, 13, 1, 1, 77),
(21, 14, 1, 1, 77),
(22, 15, 1, 1, 77),
(23, 16, 1, 1, 77),
(24, 17, 1, 1, 77),
(25, 18, 1, 1, 77),
(26, 19, 1, 1, 77),
(27, 20, 1, 1, 77),
(28, 21, 1, 1, 77),
(29, 22, 1, 1, 77),
(30, 23, 1, 1, 77),
(31, 24, 1, 1, 77),
(32, 25, 1, 1, 77),
(33, 26, 1, 1, 77),
(34, 27, 1, 1, 77),
(35, 28, 1, 1, 77),
(36, 29, 1, 1, 77),
(37, 30, 1, 1, 77),
(38, 31, 1, 1, 77),
(39, 32, 1, 1, 77),
(40, 33, 1, 1, 77),
(41, 34, 1, 1, 77),
(42, 35, 1, 1, 77),
(43, 36, 1, 1, 77),
(44, 37, 1, 1, 77),
(45, 38, 1, 1, 77),
(46, 39, 1, 1, 77),
(47, 40, 1, 1, 77),
(48, 41, 1, 1, 77),
(49, 42, 1, 1, 77),
(50, 43, 1, 1, 77),
(51, 44, 1, 1, 77),
(52, 45, 1, 1, 77),
(53, 46, 1, 1, 77),
(54, 47, 1, 1, 77),
(55, 48, 1, 1, 77),
(56, 49, 1, 1, 77),
(57, 50, 1, 1, 77),
(58, 51, 1, 1, 77),
(59, 52, 1, 1, 77),
(60, 53, 1, 1, 77),
(61, 54, 1, 1, 77),
(62, 55, 1, 1, 77),
(63, 56, 1, 1, 77),
(64, 57, 1, 1, 77),
(65, 58, 1, 1, 77),
(66, 59, 1, 1, 77),
(67, 60, 1, 1, 77),
(68, 61, 1, 1, 77),
(69, 62, 1, 1, 77),
(70, 63, 1, 1, 77),
(71, 64, 1, 1, 77),
(72, 65, 1, 1, 77),
(191, 1, 6, 1, 77),
(192, 2, 7, 1, 77),
(193, 3, 8, 1, 77),
(194, 4, 9, 1, 77),
(195, 5, 10, 1, 77),
(196, 6, 11, 1, 77),
(197, 7, 12, 1, 77),
(198, 8, 13, 1, 77),
(199, 9, 14, 1, 77),
(200, 10, 15, 1, 77),
(201, 11, 16, 1, 77),
(202, 12, 17, 1, 77),
(203, 13, 18, 1, 77),
(204, 14, 19, 1, 77),
(205, 15, 20, 1, 77),
(206, 16, 21, 1, 77),
(207, 17, 22, 1, 77),
(208, 18, 23, 1, 77),
(209, 19, 24, 1, 77),
(210, 20, 25, 1, 77),
(211, 21, 26, 1, 77),
(212, 22, 27, 1, 77),
(213, 23, 28, 1, 77),
(214, 24, 29, 1, 77),
(215, 25, 30, 1, 77),
(216, 26, 31, 1, 77),
(217, 27, 32, 1, 77),
(218, 28, 33, 1, 77),
(219, 29, 34, 1, 77),
(220, 30, 35, 1, 77),
(221, 31, 36, 1, 77),
(222, 32, 37, 1, 77),
(223, 33, 38, 1, 77),
(224, 34, 39, 1, 77),
(225, 35, 40, 1, 77),
(226, 36, 41, 1, 77),
(227, 37, 42, 1, 77),
(228, 38, 43, 1, 77),
(229, 39, 44, 1, 77),
(230, 40, 45, 1, 77),
(231, 41, 46, 1, 77),
(232, 42, 47, 1, 77),
(233, 43, 48, 1, 77),
(234, 44, 49, 1, 77),
(235, 45, 50, 1, 77),
(236, 46, 51, 1, 77),
(237, 47, 52, 1, 77),
(238, 48, 53, 1, 77),
(239, 49, 54, 1, 77),
(240, 50, 55, 1, 77),
(241, 51, 56, 1, 77),
(242, 52, 57, 1, 77),
(243, 53, 58, 1, 77),
(244, 54, 59, 1, 77),
(245, 55, 60, 1, 77),
(246, 56, 61, 1, 77),
(247, 57, 62, 1, 77),
(248, 58, 63, 1, 77),
(249, 59, 64, 1, 77),
(250, 1, 2, 1, 77),
(251, 2, 2, 1, 77),
(252, 3, 2, 1, 77),
(253, 4, 2, 1, 77),
(254, 5, 2, 1, 77),
(255, 6, 2, 1, 77),
(256, 7, 2, 1, 77),
(257, 8, 2, 1, 77),
(258, 9, 2, 1, 77),
(259, 10, 2, 1, 77),
(260, 11, 2, 1, 77),
(261, 12, 2, 1, 77),
(262, 13, 2, 1, 77),
(263, 14, 2, 1, 77),
(264, 15, 2, 1, 77),
(265, 16, 2, 1, 77),
(266, 17, 2, 1, 77),
(267, 18, 2, 1, 77),
(268, 19, 2, 1, 77),
(269, 20, 2, 1, 77),
(270, 21, 2, 1, 77),
(271, 22, 2, 1, 77),
(272, 23, 2, 1, 77),
(273, 24, 2, 1, 77),
(274, 25, 2, 1, 77),
(275, 26, 2, 1, 77),
(276, 27, 2, 1, 77),
(277, 28, 2, 1, 77),
(278, 29, 2, 1, 77),
(279, 30, 2, 1, 77),
(280, 31, 2, 1, 77),
(281, 32, 2, 1, 77),
(282, 33, 2, 1, 77),
(283, 34, 2, 1, 77),
(284, 35, 2, 1, 77),
(285, 36, 2, 1, 77),
(286, 37, 2, 1, 77),
(287, 38, 2, 1, 77),
(288, 39, 2, 1, 77),
(289, 40, 2, 1, 77),
(290, 41, 2, 1, 77),
(291, 42, 2, 1, 77),
(292, 43, 2, 1, 77),
(293, 44, 2, 1, 77),
(294, 45, 2, 1, 77),
(295, 46, 2, 1, 77),
(296, 47, 2, 1, 77),
(297, 48, 2, 1, 77),
(298, 49, 2, 1, 77),
(299, 50, 2, 1, 77),
(300, 51, 2, 1, 77),
(301, 52, 2, 1, 77),
(302, 53, 2, 1, 77),
(303, 54, 2, 1, 77),
(304, 55, 2, 1, 77),
(305, 56, 2, 1, 77),
(306, 57, 2, 1, 77),
(307, 58, 2, 1, 77),
(308, 59, 2, 1, 77),
(309, 60, 2, 1, 77),
(310, 61, 2, 1, 77),
(311, 62, 2, 1, 77),
(312, 63, 2, 1, 77),
(313, 64, 2, 1, 77),
(314, 65, 2, 1, 77),
(316, 1, 88, 1, 77),
(317, 2, 88, 1, 77),
(318, 3, 88, 1, 77),
(319, 4, 88, 1, 77),
(320, 5, 88, 1, 77),
(321, 6, 88, 1, 77),
(322, 7, 88, 1, 77),
(323, 8, 88, 1, 77),
(324, 9, 88, 1, 77),
(325, 10, 88, 1, 77),
(326, 11, 88, 1, 77),
(327, 12, 88, 1, 77),
(328, 13, 88, 1, 77),
(329, 14, 88, 1, 77),
(330, 15, 88, 1, 77),
(331, 16, 88, 1, 77),
(332, 17, 88, 1, 77),
(333, 18, 89, 1, 77),
(334, 19, 89, 1, 77),
(335, 20, 89, 1, 77),
(336, 21, 89, 1, 77),
(337, 22, 89, 1, 77),
(338, 23, 89, 1, 77),
(339, 24, 89, 1, 77),
(340, 25, 89, 1, 77),
(341, 26, 89, 1, 77),
(342, 27, 89, 1, 77),
(343, 28, 89, 1, 77),
(344, 29, 90, 1, 77),
(345, 30, 90, 1, 77),
(346, 31, 90, 1, 77),
(347, 32, 90, 1, 77),
(348, 33, 90, 1, 77),
(349, 34, 90, 1, 77),
(350, 35, 90, 1, 77),
(351, 36, 90, 1, 77),
(352, 37, 90, 1, 77),
(353, 38, 90, 1, 77),
(354, 39, 90, 1, 77),
(355, 40, 90, 1, 77),
(356, 41, 90, 1, 77),
(357, 42, 90, 1, 77),
(358, 43, 90, 1, 77),
(359, 44, 90, 1, 77),
(360, 45, 91, 1, 77),
(361, 46, 91, 1, 77),
(362, 47, 91, 1, 77),
(363, 48, 91, 1, 77),
(364, 49, 91, 1, 77),
(365, 50, 91, 1, 77),
(366, 51, 91, 1, 77),
(367, 52, 91, 1, 77),
(368, 53, 91, 1, 77),
(369, 54, 91, 1, 77),
(370, 55, 91, 1, 77),
(371, 56, 91, 1, 77),
(372, 57, 91, 1, 77),
(373, 58, 91, 1, 77),
(374, 59, 91, 1, 77),
(375, 60, 91, 1, 77),
(376, 61, 91, 1, 77),
(377, 62, 91, 1, 77),
(378, 63, 91, 1, 77),
(379, 64, 91, 1, 77),
(380, 65, 91, 1, 77),
(381, 3, 65, 1, 77),
(382, 3, 77, 1, 77),
(383, 3, 80, 1, 77),
(384, 3, 81, 1, 77),
(385, 3, 102, 1, 77),
(387, 1, 82, 1, 77),
(388, 2, 82, 1, 77),
(389, 3, 82, 1, 77),
(390, 4, 82, 1, 77),
(391, 5, 82, 1, 77),
(392, 6, 82, 1, 77),
(393, 7, 82, 1, 77),
(394, 8, 82, 1, 77),
(395, 9, 82, 1, 77),
(396, 10, 82, 1, 77),
(397, 11, 82, 1, 77),
(398, 12, 82, 1, 77),
(399, 13, 82, 1, 77),
(400, 14, 82, 1, 77),
(401, 15, 82, 1, 77),
(402, 16, 82, 1, 77),
(403, 17, 82, 1, 77),
(404, 18, 82, 1, 77),
(405, 19, 82, 1, 77),
(406, 20, 82, 1, 77),
(407, 21, 82, 1, 77),
(408, 22, 82, 1, 77),
(409, 23, 82, 1, 77),
(410, 24, 82, 1, 77),
(411, 25, 82, 1, 77),
(412, 26, 82, 1, 77),
(413, 27, 82, 1, 77),
(414, 28, 82, 1, 77),
(415, 29, 82, 1, 77),
(416, 30, 82, 1, 77),
(417, 31, 82, 1, 77),
(418, 32, 82, 1, 77),
(419, 33, 82, 1, 77),
(420, 34, 82, 1, 77),
(421, 35, 82, 1, 77),
(422, 36, 82, 1, 77),
(423, 37, 82, 1, 77),
(424, 38, 82, 1, 77),
(425, 39, 82, 1, 77),
(426, 40, 82, 1, 77),
(427, 41, 82, 1, 77),
(428, 42, 82, 1, 77),
(429, 43, 82, 1, 77),
(430, 44, 82, 1, 77),
(431, 45, 82, 1, 77),
(432, 46, 82, 1, 77),
(433, 47, 82, 1, 77),
(434, 48, 82, 1, 77),
(435, 49, 82, 1, 77),
(436, 50, 82, 1, 77),
(437, 51, 82, 1, 77),
(438, 52, 82, 1, 77),
(439, 53, 82, 1, 77),
(440, 54, 82, 1, 77),
(441, 55, 82, 1, 77),
(442, 56, 82, 1, 77),
(443, 57, 82, 1, 77),
(444, 58, 82, 1, 77),
(445, 59, 82, 1, 77),
(446, 60, 82, 1, 77),
(447, 61, 82, 1, 77),
(448, 62, 82, 1, 77),
(449, 63, 82, 1, 77),
(450, 64, 82, 1, 77),
(452, 65, 82, 1, 77);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_divisi`
--

CREATE TABLE IF NOT EXISTS `ref_divisi` (
`id_divisi` int(11) NOT NULL,
  `kd_divisi` varchar(255) DEFAULT NULL,
  `nm_divisi` varchar(255) DEFAULT NULL,
  `jns_divisi` int(11) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `id_perusahaan` int(11) DEFAULT NULL,
  `cuser` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ref_divisi`
--

INSERT INTO `ref_divisi` (`id_divisi`, `kd_divisi`, `nm_divisi`, `jns_divisi`, `active`, `id_perusahaan`, `cuser`) VALUES
(1, '4JUI', 'Agama', 0, 1, 77, 1),
(2, '4D', 'Intelektual', 0, 1, 77, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_divisi_sub`
--

CREATE TABLE IF NOT EXISTS `ref_divisi_sub` (
`id_divisi_sub` int(11) NOT NULL,
  `nm_divisi_sub` varchar(255) DEFAULT NULL,
  `kd_divisi` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `id_perusahaan` int(11) DEFAULT NULL,
  `cuser` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `ref_divisi_sub`
--

INSERT INTO `ref_divisi_sub` (`id_divisi_sub`, `nm_divisi_sub`, `kd_divisi`, `active`, `id_perusahaan`, `cuser`) VALUES
(1, '133', '4', 1, 77, 1),
(2, '212', '4D', 0, 77, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_divre`
--

CREATE TABLE IF NOT EXISTS `ref_divre` (
`id_divre` int(11) NOT NULL,
  `kd_divre` varchar(255) DEFAULT NULL,
  `nm_divre` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `id_perusahaan` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `ref_divre`
--

INSERT INTO `ref_divre` (`id_divre`, `kd_divre`, `nm_divre`, `active`, `id_perusahaan`) VALUES
(1, NULL, 'Divre I', 1, 77),
(2, NULL, 'Divre II', 1, 77),
(3, NULL, 'Divre III', 1, 77),
(4, NULL, 'Divre IV', 1, 77),
(5, NULL, 'PUSAT', 1, 77);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_kategori`
--

CREATE TABLE IF NOT EXISTS `ref_kategori` (
`id_kategori` int(11) NOT NULL,
  `nm_kategori` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `cuser` int(11) DEFAULT NULL,
  `id_perusahaan` int(20) DEFAULT NULL,
  `cdate` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `ref_kategori`
--

INSERT INTO `ref_kategori` (`id_kategori`, `nm_kategori`, `active`, `cuser`, `id_perusahaan`, `cdate`) VALUES
(2, 'Barang', 1, 1, 77, NULL),
(3, 'Assets', 1, 1, 77, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_level`
--

CREATE TABLE IF NOT EXISTS `ref_level` (
`id_level` int(11) NOT NULL,
  `nm_level` varchar(45) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL COMMENT '1.active\n2.deactive',
  `cdate` datetime DEFAULT NULL,
  `cuser` int(11) DEFAULT NULL,
  `id_perusahaan` int(11) DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `ref_level`
--

INSERT INTO `ref_level` (`id_level`, `nm_level`, `active`, `cdate`, `cuser`, `id_perusahaan`) VALUES
(1, 'Super Admin', 1, '2018-09-17 13:29:04', 1, 77),
(2, 'User', 1, '2018-12-19 23:22:36', 2, 77),
(3, 'Admin Cabang', 1, '2019-03-08 09:12:52', 1, 77),
(4, 'PPA', 1, '2019-09-06 11:16:15', 1, 77),
(5, 'Admin Order', 1, '2019-10-03 15:40:08', 1, 77),
(6, 'Divre', 1, '2019-12-12 09:19:31', 1, 77),
(7, 'Teknik', 1, '2020-02-28 13:44:29', 1, 77);

--
-- Trigger `ref_level`
--
DELIMITER //
CREATE TRIGGER `ref_level_BEFORE_INSERT` BEFORE INSERT ON `ref_level`
 FOR EACH ROW BEGIN
set new.cdate = current_timestamp();
END
//
DELIMITER ;
DELIMITER //
CREATE TRIGGER `ref_level_after_inser` AFTER INSERT ON `ref_level`
 FOR EACH ROW begin
call p_insert_level(new.id_level, new.id_perusahaan);
call p_insert_level_group(new.id_level, new.id_perusahaan);
end
//
DELIMITER ;
DELIMITER //
CREATE TRIGGER `ref_level_before_delete` BEFORE DELETE ON `ref_level`
 FOR EACH ROW begin

delete from ref_menu_groups_access where id_level = old.id_level;
delete from ref_menu_details_access where id_level = old.id_level;
end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_lokasi`
--

CREATE TABLE IF NOT EXISTS `ref_lokasi` (
`id_lokasi` int(11) NOT NULL,
  `kd_lokasi` varchar(255) DEFAULT NULL,
  `nm_lokasi` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `cuser` int(11) DEFAULT NULL,
  `id_perusahaan` varchar(255) DEFAULT NULL,
  `cdate` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `ref_lokasi`
--

INSERT INTO `ref_lokasi` (`id_lokasi`, `kd_lokasi`, `nm_lokasi`, `active`, `cuser`, `id_perusahaan`, `cdate`) VALUES
(1, '14', '14', 1, 1, '77', NULL),
(2, '11', '12', 1, 1, '77', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_menu_details`
--

CREATE TABLE IF NOT EXISTS `ref_menu_details` (
`id_menu_details` int(11) NOT NULL,
  `kd_menu_details` varchar(10) DEFAULT NULL,
  `nm_menu_details` varchar(45) DEFAULT NULL,
  `url` varchar(45) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL COMMENT '1. active\n2. deactive\n',
  `position` tinyint(2) DEFAULT NULL,
  `id_menu_groups` int(11) DEFAULT NULL,
  `cdate` datetime DEFAULT NULL,
  `cuser` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `ref_menu_details`
--

INSERT INTO `ref_menu_details` (`id_menu_details`, `kd_menu_details`, `nm_menu_details`, `url`, `active`, `position`, `id_menu_groups`, `cdate`, `cuser`) VALUES
(1, 'S01', 'Level', 'level', 1, 1, 1, '2016-03-13 19:38:05', NULL),
(2, 'S02', 'User', 'user', 1, 2, 1, '2016-03-13 19:38:05', NULL),
(3, 'S03', 'Cabang', 'bu', 1, 3, 1, '2018-09-17 13:45:40', NULL),
(6, 'S04', 'Divre', 'divre', 1, 4, 1, '2018-09-17 16:54:50', NULL),
(37, 'M01', 'Menu Detail', 'menu_details', 1, 5, 1, '2019-08-26 16:35:56', NULL),
(38, 'M02', 'Menu Group', 'menu_groups', 1, 6, 1, '2019-08-26 16:36:11', NULL),
(47, 'S05', 'Barang', 'barang', 1, 5, 2, '2020-03-05 14:23:24', 77),
(48, 'S06', 'Lokasi', 'lokasi', 1, 6, 2, '2020-03-05 14:29:18', 77),
(49, 'S07', 'Satuan', 'satuan', 1, 7, 2, '2020-03-05 14:30:13', 77),
(50, 'S08', 'Stok', 'stok', 1, 8, 2, '2020-03-05 14:30:50', 77),
(51, 'S09', 'Kategori', 'kategori', 1, 9, 2, '2020-03-05 14:31:06', 77),
(52, 'S10', 'Supplier', 'supplier', 1, 10, 2, '2020-03-05 14:31:48', 77),
(53, 'S11', 'Divisi', 'divisi', 1, 11, 2, '2020-03-05 14:32:10', 77),
(54, 'S12', 'Sub Divisi', 'divisi_sub', 1, 12, 2, '2020-03-07 16:30:46', 77),
(55, 'S13', 'Kategori Supplier', 'supplier_kategori', 1, 13, 2, '2020-03-07 16:31:39', 77),
(56, 'U01', 'Permintaan Material', 'permintaan_material', 1, 1, 5, '2020-04-07 14:06:31', 77);

--
-- Trigger `ref_menu_details`
--
DELIMITER //
CREATE TRIGGER `before_delete_ref_menudetails_copy1` BEFORE DELETE ON `ref_menu_details`
 FOR EACH ROW begin
delete from ref_menu_details_access where id_menu_details = old.id_menu_details ;
end
//
DELIMITER ;
DELIMITER //
CREATE TRIGGER `ref_menu_details_BEFORE_INSERT_copy1` BEFORE INSERT ON `ref_menu_details`
 FOR EACH ROW BEGIN
set new.cdate = current_timestamp();
END
//
DELIMITER ;
DELIMITER //
CREATE TRIGGER `ref_menudetail_after_insert_copy1` AFTER INSERT ON `ref_menu_details`
 FOR EACH ROW begin

call p_menudetails(new.id_menu_details, new.cuser);

end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_menu_details_access`
--

CREATE TABLE IF NOT EXISTS `ref_menu_details_access` (
`id_menu_details_access` int(11) NOT NULL,
  `id_level` int(11) DEFAULT NULL,
  `id_menu_details` int(11) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL COMMENT '1. active\n2. deactive\n',
  `cdate` datetime DEFAULT NULL,
  `cuser` int(11) DEFAULT NULL,
  `id_perusahaan` int(11) DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=284 DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `ref_menu_details_access`
--

INSERT INTO `ref_menu_details_access` (`id_menu_details_access`, `id_level`, `id_menu_details`, `active`, `cdate`, `cuser`, `id_perusahaan`) VALUES
(1, 1, 1, 1, '2018-09-17 13:29:04', NULL, 77),
(2, 1, 2, 1, '2018-09-17 13:29:04', NULL, 77),
(3, 1, 3, 1, '2018-09-17 13:45:40', NULL, 77),
(6, 1, 6, 1, '2018-09-17 16:54:50', NULL, 77),
(13, 2, 1, 0, '2018-12-19 23:22:36', NULL, 77),
(14, 2, 2, 0, '2018-12-19 23:22:36', NULL, 77),
(15, 2, 3, 0, '2018-12-19 23:22:36', NULL, 77),
(18, 2, 6, 0, '2018-12-19 23:22:36', NULL, 77),
(65, 3, 1, 0, '2019-03-08 09:12:52', NULL, 77),
(66, 3, 2, 0, '2019-03-08 09:12:52', NULL, 77),
(67, 3, 3, 0, '2019-03-08 09:12:52', NULL, 77),
(68, 3, 6, 0, '2019-03-08 09:12:52', NULL, 77),
(90, 1, 37, 1, '2019-08-26 16:35:56', NULL, 77),
(91, 2, 37, 0, '2019-08-26 16:35:56', NULL, 77),
(92, 3, 37, 0, '2019-08-26 16:35:56', NULL, 77),
(93, 1, 38, 1, '2019-08-26 16:36:11', NULL, 77),
(94, 2, 38, 0, '2019-08-26 16:36:11', NULL, 77),
(95, 3, 38, 0, '2019-08-26 16:36:11', NULL, 77),
(102, 4, 1, 0, '2019-09-06 11:16:15', NULL, 77),
(103, 4, 2, 0, '2019-09-06 11:16:15', NULL, 77),
(104, 4, 3, 0, '2019-09-06 11:16:15', NULL, 77),
(105, 4, 6, 0, '2019-09-06 11:16:15', NULL, 77),
(116, 4, 37, 0, '2019-09-06 11:16:15', NULL, 77),
(117, 4, 38, 0, '2019-09-06 11:16:15', NULL, 77),
(120, 5, 1, 0, '2019-10-03 15:40:08', NULL, 77),
(121, 5, 2, 0, '2019-10-03 15:40:08', NULL, 77),
(122, 5, 3, 0, '2019-10-03 15:40:08', NULL, 77),
(123, 5, 6, 0, '2019-10-03 15:40:08', NULL, 77),
(134, 5, 37, 0, '2019-10-03 15:40:08', NULL, 77),
(135, 5, 38, 0, '2019-10-03 15:40:08', NULL, 77),
(148, 6, 1, 0, '2019-12-12 09:19:31', NULL, 77),
(149, 6, 2, 0, '2019-12-12 09:19:31', NULL, 77),
(150, 6, 3, 0, '2019-12-12 09:19:31', NULL, 77),
(151, 6, 6, 0, '2019-12-12 09:19:31', NULL, 77),
(162, 6, 37, 0, '2019-12-12 09:19:31', NULL, 77),
(163, 6, 38, 0, '2019-12-12 09:19:31', NULL, 77),
(186, 7, 1, 0, '2020-02-28 13:44:29', NULL, 77),
(187, 7, 2, 0, '2020-02-28 13:44:29', NULL, 77),
(188, 7, 3, 0, '2020-02-28 13:44:29', NULL, 77),
(189, 7, 6, 0, '2020-02-28 13:44:29', NULL, 77),
(200, 7, 37, 0, '2020-02-28 13:44:29', NULL, 77),
(201, 7, 38, 0, '2020-02-28 13:44:29', NULL, 77),
(214, 1, 47, 1, '2020-03-05 14:23:24', 77, 77),
(215, 2, 47, 0, '2020-03-05 14:23:24', 77, 77),
(216, 3, 47, 0, '2020-03-05 14:23:24', 77, 77),
(217, 4, 47, 0, '2020-03-05 14:23:24', 77, 77),
(218, 5, 47, 0, '2020-03-05 14:23:24', 77, 77),
(219, 6, 47, 0, '2020-03-05 14:23:24', 77, 77),
(220, 7, 47, 0, '2020-03-05 14:23:24', 77, 77),
(221, 1, 48, 1, '2020-03-05 14:29:18', 77, 77),
(222, 2, 48, 0, '2020-03-05 14:29:18', 77, 77),
(223, 3, 48, 0, '2020-03-05 14:29:18', 77, 77),
(224, 4, 48, 0, '2020-03-05 14:29:18', 77, 77),
(225, 5, 48, 0, '2020-03-05 14:29:18', 77, 77),
(226, 6, 48, 0, '2020-03-05 14:29:18', 77, 77),
(227, 7, 48, 0, '2020-03-05 14:29:18', 77, 77),
(228, 1, 49, 1, '2020-03-05 14:30:13', 77, 77),
(229, 2, 49, 0, '2020-03-05 14:30:13', 77, 77),
(230, 3, 49, 0, '2020-03-05 14:30:13', 77, 77),
(231, 4, 49, 0, '2020-03-05 14:30:13', 77, 77),
(232, 5, 49, 0, '2020-03-05 14:30:13', 77, 77),
(233, 6, 49, 0, '2020-03-05 14:30:13', 77, 77),
(234, 7, 49, 0, '2020-03-05 14:30:13', 77, 77),
(235, 1, 50, 1, '2020-03-05 14:30:50', 77, 77),
(236, 2, 50, 0, '2020-03-05 14:30:50', 77, 77),
(237, 3, 50, 0, '2020-03-05 14:30:50', 77, 77),
(238, 4, 50, 0, '2020-03-05 14:30:50', 77, 77),
(239, 5, 50, 0, '2020-03-05 14:30:50', 77, 77),
(240, 6, 50, 0, '2020-03-05 14:30:50', 77, 77),
(241, 7, 50, 0, '2020-03-05 14:30:50', 77, 77),
(242, 1, 51, 1, '2020-03-05 14:31:06', 77, 77),
(243, 2, 51, 0, '2020-03-05 14:31:06', 77, 77),
(244, 3, 51, 0, '2020-03-05 14:31:06', 77, 77),
(245, 4, 51, 0, '2020-03-05 14:31:06', 77, 77),
(246, 5, 51, 0, '2020-03-05 14:31:06', 77, 77),
(247, 6, 51, 0, '2020-03-05 14:31:06', 77, 77),
(248, 7, 51, 0, '2020-03-05 14:31:06', 77, 77),
(249, 1, 52, 1, '2020-03-05 14:31:48', 77, 77),
(250, 2, 52, 0, '2020-03-05 14:31:48', 77, 77),
(251, 3, 52, 0, '2020-03-05 14:31:48', 77, 77),
(252, 4, 52, 0, '2020-03-05 14:31:48', 77, 77),
(253, 5, 52, 0, '2020-03-05 14:31:48', 77, 77),
(254, 6, 52, 0, '2020-03-05 14:31:48', 77, 77),
(255, 7, 52, 0, '2020-03-05 14:31:48', 77, 77),
(256, 1, 53, 1, '2020-03-05 14:32:10', 77, 77),
(257, 2, 53, 0, '2020-03-05 14:32:10', 77, 77),
(258, 3, 53, 0, '2020-03-05 14:32:10', 77, 77),
(259, 4, 53, 0, '2020-03-05 14:32:10', 77, 77),
(260, 5, 53, 0, '2020-03-05 14:32:10', 77, 77),
(261, 6, 53, 0, '2020-03-05 14:32:10', 77, 77),
(262, 7, 53, 0, '2020-03-05 14:32:10', 77, 77),
(263, 1, 54, 1, '2020-03-07 16:30:46', 77, 77),
(264, 2, 54, 0, '2020-03-07 16:30:46', 77, 77),
(265, 3, 54, 0, '2020-03-07 16:30:46', 77, 77),
(266, 4, 54, 0, '2020-03-07 16:30:46', 77, 77),
(267, 5, 54, 0, '2020-03-07 16:30:46', 77, 77),
(268, 6, 54, 0, '2020-03-07 16:30:46', 77, 77),
(269, 7, 54, 0, '2020-03-07 16:30:46', 77, 77),
(270, 1, 55, 1, '2020-03-07 16:31:39', 77, 77),
(271, 2, 55, 0, '2020-03-07 16:31:39', 77, 77),
(272, 3, 55, 0, '2020-03-07 16:31:39', 77, 77),
(273, 4, 55, 0, '2020-03-07 16:31:39', 77, 77),
(274, 5, 55, 0, '2020-03-07 16:31:39', 77, 77),
(275, 6, 55, 0, '2020-03-07 16:31:39', 77, 77),
(276, 7, 55, 0, '2020-03-07 16:31:39', 77, 77),
(277, 1, 56, 1, '2020-04-07 14:06:31', 77, 77),
(278, 2, 56, 0, '2020-04-07 14:06:31', 77, 77),
(279, 3, 56, 0, '2020-04-07 14:06:31', 77, 77),
(280, 4, 56, 0, '2020-04-07 14:06:31', 77, 77),
(281, 5, 56, 0, '2020-04-07 14:06:31', 77, 77),
(282, 6, 56, 0, '2020-04-07 14:06:31', 77, 77),
(283, 7, 56, 0, '2020-04-07 14:06:31', 77, 77);

--
-- Trigger `ref_menu_details_access`
--
DELIMITER //
CREATE TRIGGER `ref_menu_details_access_BEFORE_INSERT_copy1` BEFORE INSERT ON `ref_menu_details_access`
 FOR EACH ROW BEGIN
set new.cdate = current_timestamp();
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_menu_groups`
--

CREATE TABLE IF NOT EXISTS `ref_menu_groups` (
`id_menu_groups` int(11) NOT NULL,
  `nm_menu_groups` varchar(45) DEFAULT NULL,
  `icon` varchar(45) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL COMMENT '1.active\n2. deactive',
  `position` tinyint(2) DEFAULT NULL,
  `cdate` datetime DEFAULT NULL,
  `cuser` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `ref_menu_groups`
--

INSERT INTO `ref_menu_groups` (`id_menu_groups`, `nm_menu_groups`, `icon`, `active`, `position`, `cdate`, `cuser`) VALUES
(1, 'Settings', 'fa fa-wrench', 1, 4, '2016-03-13 19:38:05', 1),
(2, 'Master', 'fa fa-database', 1, 2, '2016-03-13 19:38:05', 1),
(3, 'Administrasi', 'fa fa-calendar-check-o', 0, 5, '2018-01-25 09:55:34', 1),
(4, 'Report', 'fa fa-steam', 1, 3, '2018-03-08 14:27:40', 1),
(5, 'Pengadaan', 'fa fa-bus', 1, 6, '2020-04-07 14:05:02', NULL);

--
-- Trigger `ref_menu_groups`
--
DELIMITER //
CREATE TRIGGER `ref_menu_groups_BEFORE_INSERT` BEFORE INSERT ON `ref_menu_groups`
 FOR EACH ROW BEGIN
set new.cdate = current_timestamp();
END
//
DELIMITER ;
DELIMITER //
CREATE TRIGGER `ref_menugroup_after_insert` AFTER INSERT ON `ref_menu_groups`
 FOR EACH ROW begin

call p_menugroup(new.id_menu_groups, new.cuser);

end
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_menu_groups_access`
--

CREATE TABLE IF NOT EXISTS `ref_menu_groups_access` (
`id_menu_groups_access` int(11) NOT NULL,
  `id_menu_groups` int(11) DEFAULT NULL,
  `id_level` int(11) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL COMMENT '1. Active\n2. Deactive',
  `cdate` datetime DEFAULT NULL,
  `cuser` int(11) DEFAULT NULL,
  `id_perusahaan` int(11) DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `ref_menu_groups_access`
--

INSERT INTO `ref_menu_groups_access` (`id_menu_groups_access`, `id_menu_groups`, `id_level`, `active`, `cdate`, `cuser`, `id_perusahaan`) VALUES
(1, 1, 1, 1, '2018-09-17 13:29:04', 1, 77),
(2, 2, 1, 1, '2018-09-17 13:29:04', 1, 77),
(3, 3, 1, 1, '2018-09-17 13:29:04', 1, 77),
(4, 4, 1, 1, '2018-09-17 13:29:04', 1, 77),
(5, 5, 1, 1, '2018-12-17 14:09:01', 1, 77),
(6, 1, 2, 0, '2018-12-19 23:22:36', NULL, 77),
(7, 2, 2, 0, '2018-12-19 23:22:36', NULL, 77),
(8, 3, 2, 0, '2018-12-19 23:22:36', NULL, 77),
(9, 4, 2, 0, '2018-12-19 23:22:36', NULL, 77),
(10, 5, 2, 1, '2018-12-19 23:22:36', NULL, 77),
(11, 6, 1, 1, '2019-02-06 08:34:55', 1, 77),
(12, 6, 2, 0, '2019-02-06 08:34:55', 1, 77),
(13, 7, 1, 1, '2019-02-15 06:43:30', 1, 77),
(14, 7, 2, 0, '2019-02-15 06:43:30', NULL, 77),
(15, 8, 1, 1, '2019-02-15 06:43:33', 1, 77),
(16, 8, 2, 0, '2019-02-15 06:43:33', NULL, 77),
(17, 1, 3, 0, '2019-03-08 09:12:52', NULL, 77),
(18, 2, 3, 1, '2019-03-08 09:12:52', NULL, 77),
(19, 3, 3, 1, '2019-03-08 09:12:52', NULL, 77),
(20, 4, 3, 0, '2019-03-08 09:12:52', NULL, 77),
(21, 1, 4, 0, '2019-09-06 11:16:15', NULL, 77),
(22, 2, 4, 0, '2019-09-06 11:16:15', NULL, 77),
(23, 3, 4, 0, '2019-09-06 11:16:15', NULL, 77),
(24, 4, 4, 0, '2019-09-06 11:16:15', NULL, 77),
(25, 1, 5, 0, '2019-10-03 15:40:08', NULL, 77),
(26, 2, 5, 1, '2019-10-03 15:40:08', NULL, 77),
(27, 3, 5, 1, '2019-10-03 15:40:08', NULL, 77),
(28, 4, 5, 0, '2019-10-03 15:40:08', NULL, 77),
(29, 1, 6, 0, '2019-12-12 09:19:31', NULL, 77),
(30, 2, 6, 1, '2019-12-12 09:19:31', NULL, 77),
(31, 3, 6, 0, '2019-12-12 09:19:31', NULL, 77),
(32, 4, 6, 0, '2019-12-12 09:19:31', NULL, 77),
(33, 1, 7, 0, '2020-02-28 13:44:29', NULL, 77),
(34, 2, 7, 1, '2020-02-28 13:44:29', NULL, 77),
(35, 3, 7, 0, '2020-02-28 13:44:29', NULL, 77),
(36, 4, 7, 0, '2020-02-28 13:44:29', NULL, 77),
(37, 5, 1, 0, '2020-04-07 14:05:02', NULL, 77),
(38, 5, 2, 0, '2020-04-07 14:05:02', NULL, 77),
(39, 5, 3, 0, '2020-04-07 14:05:02', NULL, 77),
(40, 5, 4, 0, '2020-04-07 14:05:02', NULL, 77),
(41, 5, 5, 0, '2020-04-07 14:05:02', NULL, 77),
(42, 5, 6, 0, '2020-04-07 14:05:02', NULL, 77),
(43, 5, 7, 0, '2020-04-07 14:05:02', NULL, 77);

--
-- Trigger `ref_menu_groups_access`
--
DELIMITER //
CREATE TRIGGER `ref_menu_groups_access_BEFORE_INSERT` BEFORE INSERT ON `ref_menu_groups_access`
 FOR EACH ROW BEGIN
set new.cdate = current_timestamp();
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_pegawai`
--

CREATE TABLE IF NOT EXISTS `ref_pegawai` (
`id_driver` int(11) NOT NULL,
  `id_pegawai` int(11) DEFAULT NULL,
  `nm_pegawai` varchar(255) DEFAULT NULL,
  `nik_pegawai` varchar(255) DEFAULT NULL,
  `status_pegawai` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `id_bu` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ref_pegawai`
--

INSERT INTO `ref_pegawai` (`id_driver`, `id_pegawai`, `nm_pegawai`, `nik_pegawai`, `status_pegawai`, `active`, `id_bu`) VALUES
(1, 1, 'Adi', '68612', 'PKWTT', 1, 3),
(2, 2, 'Nanda', '900', 'PKWTT', 1, 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_perusahaan`
--

CREATE TABLE IF NOT EXISTS `ref_perusahaan` (
`id_perusahaan` int(11) NOT NULL,
  `nm_perusahaan` varchar(45) DEFAULT NULL,
  `alamat` text,
  `telp` varchar(15) DEFAULT NULL,
  `jenis` tinyint(1) DEFAULT NULL COMMENT '1. Pusat 2. Subcon',
  `active` tinyint(1) DEFAULT NULL COMMENT '1. Active\n2. Deactive',
  `cdate` datetime DEFAULT NULL,
  `cuser` int(11) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `fifo` tinyint(1) DEFAULT '0' COMMENT '0. deactive 1.active',
  `fefo` tinyint(1) DEFAULT '0' COMMENT '0. deactive 1.active',
  `best` tinyint(1) DEFAULT '0' COMMENT '0. deactive 1.active',
  `alloc` varchar(1) DEFAULT 'F' COMMENT 'F FIFO E FEFO B Best Fit',
  `language` varchar(50) DEFAULT 'english' COMMENT 'english / indonesian'
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `ref_perusahaan`
--

INSERT INTO `ref_perusahaan` (`id_perusahaan`, `nm_perusahaan`, `alamat`, `telp`, `jenis`, `active`, `cdate`, `cuser`, `logo`, `fifo`, `fefo`, `best`, `alloc`, `language`) VALUES
(77, 'DAMRI', 'DKI Jakarta', '', 1, 1, '2017-07-13 06:51:28', 2, '32eaa902bd56712f93c0ee514b47b59c.png', 1, 1, 0, 'F', 'indonesian');

--
-- Trigger `ref_perusahaan`
--
DELIMITER //
CREATE TRIGGER `ref_perusahaan_BEFORE_INSERT` BEFORE INSERT ON `ref_perusahaan`
 FOR EACH ROW BEGIN
set new.cdate = current_timestamp();
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_satuan`
--

CREATE TABLE IF NOT EXISTS `ref_satuan` (
`id_satuan` int(11) NOT NULL,
  `nm_satuan` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `cuser` int(11) DEFAULT NULL,
  `id_perusahaan` int(11) DEFAULT NULL,
  `cdate` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `ref_satuan`
--

INSERT INTO `ref_satuan` (`id_satuan`, `nm_satuan`, `active`, `cuser`, `id_perusahaan`, `cdate`) VALUES
(2, 'Unit', 1, 1, 77, NULL),
(3, 'Assets', 1, 1, 77, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_stok`
--

CREATE TABLE IF NOT EXISTS `ref_stok` (
`id_stok` int(11) NOT NULL,
  `kd_barang` varchar(50) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `detail` text,
  `id_supplier` int(11) DEFAULT NULL,
  `qty` decimal(20,0) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `cuser` int(11) DEFAULT NULL,
  `id_perusahaan` int(11) DEFAULT NULL,
  `cdate` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `ref_stok`
--

INSERT INTO `ref_stok` (`id_stok`, `kd_barang`, `type`, `detail`, `id_supplier`, `qty`, `date`, `cuser`, `id_perusahaan`, `cdate`) VALUES
(1, '312D', 1, '212', 1, '212', NULL, 1, 77, NULL),
(2, '312D', 0, '33', 2, '44', NULL, 1, 77, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_supplier`
--

CREATE TABLE IF NOT EXISTS `ref_supplier` (
`id_supplier` int(11) NOT NULL,
  `nm_supplier` varchar(255) DEFAULT NULL,
  `alamat_supplier` text,
  `id_supplier_kategori` int(20) DEFAULT NULL,
  `pic_supplier` varchar(255) DEFAULT NULL,
  `tlp_supplier` varchar(20) DEFAULT NULL,
  `email_supplier` varchar(255) DEFAULT NULL,
  `kota_supplier` varchar(255) DEFAULT NULL,
  `kodepos_supplier` decimal(20,0) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `cuser` int(11) DEFAULT NULL,
  `id_perusahaan` tinyint(1) DEFAULT NULL,
  `cdate` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `ref_supplier`
--

INSERT INTO `ref_supplier` (`id_supplier`, `nm_supplier`, `alamat_supplier`, `id_supplier_kategori`, `pic_supplier`, `tlp_supplier`, `email_supplier`, `kota_supplier`, `kodepos_supplier`, `active`, `cuser`, `id_perusahaan`, `cdate`) VALUES
(1, 'fauzan', 'malang', 1, '123', '123', '123', 'malang', '577667', 1, 1, 77, NULL),
(2, '1', '1', 1, '1', '1', '1', '1', '14', 1, NULL, 77, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_supplier_kategori`
--

CREATE TABLE IF NOT EXISTS `ref_supplier_kategori` (
`id_supplier_kategori` int(20) NOT NULL,
  `nm_supplier_kategori` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `cuser` int(11) DEFAULT NULL,
  `id_perusahaan` int(11) DEFAULT NULL,
  `cdate` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `ref_supplier_kategori`
--

INSERT INTO `ref_supplier_kategori` (`id_supplier_kategori`, `nm_supplier_kategori`, `active`, `cuser`, `id_perusahaan`, `cdate`) VALUES
(1, 'Asset', 1, 1, 77, NULL),
(2, 'Barang', 1, 1, 77, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ref_user`
--

CREATE TABLE IF NOT EXISTS `ref_user` (
`id_user` int(11) NOT NULL,
  `nm_user` varchar(45) DEFAULT NULL,
  `username` varchar(45) DEFAULT NULL,
  `password` varchar(45) DEFAULT NULL,
  `id_perusahaan` int(11) DEFAULT '0',
  `id_level` int(11) DEFAULT NULL,
  `id_atasan` int(11) DEFAULT NULL,
  `id_bu` int(11) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL COMMENT '1. Active\n2. Deactive\n',
  `cdate` datetime DEFAULT NULL,
  `cuser` int(11) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `vendor` tinyint(1) DEFAULT '0',
  `admin` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `ref_user`
--

INSERT INTO `ref_user` (`id_user`, `nm_user`, `username`, `password`, `id_perusahaan`, `id_level`, `id_atasan`, `id_bu`, `active`, `cdate`, `cuser`, `email`, `vendor`, `admin`) VALUES
(1, 'admin', 'admin1', 'e00cf25ad42683b3df678c61f42c6bda', 77, 1, 1, 3, 1, '2016-05-31 10:14:01', 1, 'noe.adipratama@gmail.com', 1, 1),
(2, 'Rektek', 'admin.teknik@damri.co.id', '8ffdd8a1e644c75ce53d5d9dcf6912ba', 77, 7, NULL, 60, 1, '2018-12-18 15:00:33', 1, 'admin.teknik@damri.co.id', 0, NULL),
(3, 'Admin SDM', 'damrisdm', 'ef8e9a90ec034cb42ce2495377b6b6b2', 77, 1, NULL, 8, 2, '2018-12-20 06:51:06', 2, 'damrisdm', 0, NULL),
(4, 'User Survei SDM', 'damri', '23110f0ac5101b068530196ec4089809', 77, 2, NULL, 60, 2, '2018-12-27 08:58:36', 1, 'damri', 0, NULL),
(5, 'Teguh Aditya', 'teguh', '261a794363c16c2a9969c2ee093673d6', 77, 1, NULL, 60, 2, '2019-01-11 18:25:08', 1, 'teguh', 0, NULL),
(6, 'Banda Aceh', 'd1.aceh', '21232f297a57a5a743894a0e4a801fc3', 77, 3, 1, 1, 1, '2019-03-08 14:21:34', NULL, 'd1.aceh', 0, NULL),
(7, 'Bandar Lampung', 'd1.lampung', '35bda01372ca0cd183bd5d6fdde19e4e', 77, 3, 1, 2, 1, '2019-03-08 14:21:34', NULL, 'd1.lampung', 0, NULL),
(8, 'Bandara Soekarno Hatta', 'd1.basoeta', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 3, 1, '2019-03-08 14:21:34', NULL, 'd1.basoeta', 0, NULL),
(9, 'Bandung', 'd1.bandung', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 4, 1, '2019-03-08 14:21:34', NULL, 'd1.bandung', 0, NULL),
(10, 'Batam', 'd1.batam', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 5, 1, '2019-03-08 14:21:34', NULL, 'd1.batam', 0, NULL),
(11, 'Bengkulu', 'd1.bengkulu', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 6, 1, '2019-03-08 14:21:34', NULL, 'd1.bengkulu', 0, NULL),
(12, 'Bogor', 'd1.bogor', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 7, 1, '2019-03-08 14:21:34', NULL, 'd1.bogor', 0, NULL),
(13, 'Jakarta', 'd1.jakarta', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 8, 1, '2019-03-08 14:21:34', NULL, 'd1.jakarta', 0, NULL),
(14, 'Jambi', 'd1.jambi', '1d995f997d758de8f2851c7faf9941b8', 77, 3, 1, 9, 1, '2019-03-08 14:21:34', NULL, 'd1.jambi', 0, NULL),
(15, 'Medan', 'd1.medan', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 10, 1, '2019-03-08 14:21:34', NULL, 'd1.medan', 0, NULL),
(16, 'Padang', 'd1.padang', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 11, 1, '2019-03-08 14:21:34', NULL, 'd1.padang', 0, NULL),
(17, 'Palembang', 'd1.palembang', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 12, 1, '2019-03-08 14:21:34', NULL, 'd1.palembang', 0, NULL),
(18, 'Pangkal Pinang', 'd1.pkpinang', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 13, 1, '2019-03-08 14:21:34', NULL, 'd1.pkpinang', 0, NULL),
(19, 'Pekanbaru', 'd1.pkbaru', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 14, 1, '2019-03-08 14:21:34', 1, 'd1.pkbaru', 0, NULL),
(20, 'Serang', 'd1.serang', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 15, 1, '2019-03-08 14:21:34', NULL, 'd1.serang', 0, NULL),
(21, 'Koridor 1 & 8', 'd1.kor18', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 16, 1, '2019-03-08 14:21:34', NULL, 'd1.kor18', 0, NULL),
(22, 'Logistik', 'd1.paket', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 17, 1, '2019-03-08 14:21:34', NULL, 'd1.paket', 0, NULL),
(23, 'Banjarmasin', 'd2.banjar', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 18, 1, '2019-03-08 14:21:34', 1, 'd2.banjar', 0, NULL),
(24, 'Cilacap', 'd2.cilacap', '9d22eb2c4dcf894cd0fd7ca0297b5055', 77, 3, 1, 19, 1, '2019-03-08 14:21:34', NULL, 'd2.cilacap', 0, NULL),
(25, 'Palangkaraya', 'd2.palangka', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 20, 1, '2019-03-08 14:21:34', NULL, 'd2.palangka', 0, NULL),
(26, 'Pontianak', 'd2.pontianak', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 21, 1, '2019-03-08 14:21:34', NULL, 'd2.pontianak', 0, NULL),
(27, 'Purwokerto', 'd2.purwokerto', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 22, 1, '2019-03-08 14:21:34', NULL, 'd2.purwokerto', 0, NULL),
(28, 'Purworejo', 'd2.purworejo', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 23, 1, '2019-03-08 14:21:34', NULL, 'd2.purworejo', 0, NULL),
(29, 'Samarinda', 'd2.samarinda', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 24, 1, '2019-03-08 14:21:34', NULL, 'd2.samarinda', 0, NULL),
(30, 'Semarang', 'd2.semarang', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 25, 1, '2019-03-08 14:21:34', NULL, 'd2.semarang', 0, NULL),
(31, 'Surakarta', 'd2.solo', '54c88b9d94949670bbdfb76858a28355', 77, 3, 1, 26, 1, '2019-03-08 14:21:34', NULL, 'd2.solo', 0, NULL),
(32, 'Tanjung Selor', 'd2.tjselor', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 27, 1, '2019-03-08 14:21:34', NULL, 'd2.tjselor', 0, NULL),
(33, 'Yogyakarta', 'd2.yogya', 'a36c88f811975a7d39832f4d1f40d896', 77, 3, 1, 28, 1, '2019-03-08 14:21:34', NULL, 'd2.yogya', 0, NULL),
(34, 'Banyuwangi', 'd3.banyuwangi', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 29, 1, '2019-03-08 14:21:34', NULL, 'd3.banyuwangi', 0, NULL),
(35, 'Denpasar', 'd3.denpasar', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 30, 1, '2019-03-08 14:21:34', NULL, 'd3.denpasar', 0, NULL),
(36, 'Ende', 'd3.ende', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 31, 1, '2019-03-08 14:21:34', NULL, 'd3.ende', 0, NULL),
(37, 'Jember', 'd3.jember', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 32, 1, '2019-03-08 14:21:34', NULL, 'd3.jember', 0, NULL),
(38, 'Kefamenanu', 'd3.kefamenanu', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 33, 1, '2019-03-08 14:21:34', NULL, 'd3.kefamenanu', 0, NULL),
(39, 'Kendari', 'd3.kendari', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 34, 1, '2019-03-08 14:21:34', NULL, 'd3.kendari', 0, NULL),
(40, 'Kupang', 'd3.kupang', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 35, 1, '2019-03-08 14:21:34', NULL, 'd3.kupang', 0, NULL),
(41, 'Makassar', 'd3.makassar', '09d3cf5b3c0d93f2ef2a350c71052cac', 77, 3, 1, 36, 1, '2019-03-08 14:21:34', NULL, 'd3.makassar', 0, NULL),
(42, 'Malang', 'd3.malang', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 37, 1, '2019-03-08 14:21:34', NULL, 'd3.malang', 0, NULL),
(43, 'Mamuju', 'd3.mamuju', 'e34e7ace0a23cf04bd965000f9193e8b', 77, 3, 1, 38, 1, '2019-03-08 14:21:34', NULL, 'd3.mamuju', 0, NULL),
(44, 'Mataram', 'd3.mataram', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 39, 1, '2019-03-08 14:21:34', NULL, 'd3.mataram', 0, NULL),
(45, 'Palu', 'd3.palu', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 40, 1, '2019-03-08 14:21:34', NULL, 'd3.palu', 0, NULL),
(46, 'Pamekasan', 'd3.pamekasan', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 41, 1, '2019-03-08 14:21:34', NULL, 'd3.pamekasan', 0, NULL),
(47, 'Ponorogo', 'd3.ponorogo', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 42, 1, '2019-03-08 14:21:34', NULL, 'd3.ponorogo', 0, NULL),
(48, 'Surabaya', 'd3.surabaya', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 43, 1, '2019-03-08 14:21:34', NULL, 'd3.surabaya', 0, NULL),
(49, 'Waingapu', 'd3.waingapu', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 44, 1, '2019-03-08 14:21:34', NULL, 'd3.waingapu', 0, NULL),
(50, 'Ambon', 'd4.ambon', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 45, 1, '2019-03-08 14:21:34', NULL, 'd4.ambon', 0, NULL),
(51, 'Biak', 'd4.biak', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 46, 1, '2019-03-08 14:21:34', NULL, 'd4.biak', 0, NULL),
(52, 'Gorontalo', 'd4.gorontalo', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 47, 1, '2019-03-08 14:21:34', NULL, 'd4.gorontalo', 0, NULL),
(53, 'Halmahera', 'd4.halmahera', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 48, 1, '2019-03-08 14:21:34', NULL, 'd4.halmahera', 0, NULL),
(54, 'Jayapura', 'd4.jayapura', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 49, 1, '2019-03-08 14:21:34', NULL, 'd4.jayapura', 0, NULL),
(55, 'Manado', 'd4.manado', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 50, 1, '2019-03-08 14:21:34', NULL, 'd4.manado', 0, NULL),
(56, 'Manokwari', 'd4.manokwari', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 51, 1, '2019-03-08 14:21:34', NULL, 'd4.manokwari', 0, NULL),
(57, 'Merauke', 'd4.merauke', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 52, 1, '2019-03-08 14:21:34', 1, 'd4.merauke', 0, NULL),
(58, 'Mimika', 'd4.mimika', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 53, 1, '2019-03-08 14:21:34', NULL, 'd4.mimika', 0, NULL),
(59, 'Nabire', 'd4.nabire', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 54, 1, '2019-03-08 14:21:34', NULL, 'd4.nabire', 0, NULL),
(60, 'Namlea', 'd4.namlea', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 55, 1, '2019-03-08 14:21:34', NULL, 'd4.namlea', 0, NULL),
(61, 'Sarmi', 'd4.sarmi', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 56, 1, '2019-03-08 14:21:34', NULL, 'd4.sarmi', 0, NULL),
(62, 'Serui', 'd4.serui', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 57, 1, '2019-03-08 14:21:34', NULL, 'd4.serui', 0, NULL),
(63, 'Sorong', 'd4.sorong', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 58, 1, '2019-03-08 14:21:34', NULL, 'd4.sorong', 0, NULL),
(64, 'Sorong Selatan', 'd4.sorongsel', '23110f0ac5101b068530196ec4089809', 77, 3, 1, 59, 1, '2019-03-08 14:21:34', NULL, 'd4.sorongsel', 0, NULL),
(65, 'it', 'it', '0d149b90e7394297301c90191ae775f0', 77, 1, NULL, 60, 1, '2019-08-23 09:01:18', 1, 'it', 0, NULL),
(66, 'pool cakung', 'cakung', '202cb962ac59075b964b07152d234b70', 77, 3, NULL, 3, 1, '2019-09-05 17:20:25', 1, 'cakung', 0, NULL),
(67, 'WAGINO', '68958970', '202cb962ac59075b964b07152d234b70', 77, 4, NULL, 3, 1, '2019-09-06 11:16:59', 1, '68958970', 0, NULL),
(68, 'ABDULAH', '69938091', '202cb962ac59075b964b07152d234b70', 77, 4, NULL, 3, 1, '2019-09-06 11:20:15', 1, '69938091', 0, NULL),
(69, 'SYARIFUDDIN SP', '679178818', '202cb962ac59075b964b07152d234b70', 77, 4, NULL, 3, 1, '2019-09-06 11:22:39', 1, '679178818', 0, NULL),
(70, 'FAUZAN', '64855826', '202cb962ac59075b964b07152d234b70', 77, 4, NULL, 3, 1, '2019-09-06 13:10:29', 1, '64855826', 0, NULL),
(71, 'ARIP RAHMAN', '11120219', '202cb962ac59075b964b07152d234b70', 77, 4, NULL, 3, 1, '2019-09-06 13:11:41', 1, '11120219', 0, NULL),
(72, 'SYAFARUDDIN .NT', '68896637', '202cb962ac59075b964b07152d234b70', 77, 4, NULL, 3, 1, '2019-09-06 13:28:07', 1, '68896637', 0, NULL),
(73, 'NURYANTO', '759810380', '202cb962ac59075b964b07152d234b70', 77, 4, NULL, 3, 1, '2019-09-06 13:53:50', 1, '759810380', 0, NULL),
(75, 'MARIMIN', '74959037', '202cb962ac59075b964b07152d234b70', 77, 4, NULL, 3, 1, '2019-09-06 13:58:18', 1, '74959037', 0, NULL),
(76, 'ACEP ABDUL HAPID', '9314120576', '202cb962ac59075b964b07152d234b70', 77, 4, NULL, 3, 1, '2019-09-06 14:34:46', 1, '9314120576', 0, NULL),
(77, 'SARKIM', '71927695', '202cb962ac59075b964b07152d234b70', 77, 5, NULL, 3, 1, '2019-10-03 15:42:24', 1, '71927695', 0, NULL),
(78, 'PPA', 'ppa', '8a2a05a31e5a5c8aba63195cb7132fca', 77, 4, NULL, 3, 1, '2019-10-08 17:21:39', 1, 'ppa', 0, NULL),
(79, 'admin', 'admin', '21232f297a57a5a743894a0e4a801fc3', 77, 5, NULL, 3, 2, '2019-10-10 12:00:18', 1, 'admin', 0, NULL),
(80, 'ISMOYO EDI WIBOWO', '68969696', '202cb962ac59075b964b07152d234b70', 77, 5, NULL, 3, 1, '2019-10-16 08:13:18', 1, '68969696', 0, NULL),
(81, 'FERI AFRIANTO PUTRO.S.Pd', '831111696', '202cb962ac59075b964b07152d234b70', 77, 5, NULL, 3, 1, '2019-10-16 08:24:24', 1, '831111696', 0, NULL),
(82, 'Harwat', 'harwat', '827ccb0eea8a706c4c34a16891f84e7b', 77, 7, NULL, 3, 1, '2019-10-28 16:32:01', 1, 'harwat', 0, NULL),
(83, 'SUDIYANA', '750310460', '202cb962ac59075b964b07152d234b70', 77, 4, NULL, 3, 1, '2019-11-04 14:16:57', 1, '750310460', 0, NULL),
(84, 'MANNU TABASSAMA AULIA', '9314120573', '202cb962ac59075b964b07152d234b70', 77, 4, NULL, 3, 1, '2019-11-04 14:17:46', 1, '9314120573', 0, NULL),
(87, 'ARIS WINARTO	', '71948469', '202cb962ac59075b964b07152d234b70', 77, 4, NULL, 3, 1, '2019-11-04 14:57:36', 1, '71948469', 0, NULL),
(88, 'Divre I', 'd1.admin', '23110f0ac5101b068530196ec4089809', 77, 6, NULL, 61, 1, '2019-12-12 09:20:36', 1, 'd1.admin', 0, NULL),
(89, 'Divre II', 'd2.admin', '23110f0ac5101b068530196ec4089809', 77, 6, NULL, 62, 1, '2019-12-12 09:40:31', 1, 'd2.admin', 0, NULL),
(90, 'Divre III', 'd3.admin', '23110f0ac5101b068530196ec4089809', 77, 6, NULL, 63, 1, '2019-12-12 09:41:01', 1, 'd3.admin', 0, NULL),
(91, 'Divre IV', 'd4.admin', '38071ad91a044f01bf051c6c90745d5c', 77, 6, NULL, 64, 1, '2019-12-12 09:41:41', 1, 'd4.admin', 0, NULL),
(92, 'Agus Suryanto', '70926758', '202cb962ac59075b964b07152d234b70', 77, 4, NULL, 3, 1, '2019-12-28 12:52:14', 1, '70926758', 0, NULL),
(93, 'Ignatius Winarno', '73938400', '202cb962ac59075b964b07152d234b70', 77, 4, NULL, 3, 1, '2019-12-28 12:53:41', 1, '73938400', 0, NULL),
(94, 'Roni Fajar', '11120450', '202cb962ac59075b964b07152d234b70', 77, 4, NULL, 3, 1, '2019-12-29 14:19:17', 1, '11120450', 0, NULL),
(96, 'IWAN SETIAWAN , Spd', '8217120684', '202cb962ac59075b964b07152d234b70', 77, 4, NULL, 3, 1, '2020-01-04 18:34:01', 1, '8217120684', 0, NULL),
(97, 'Admin Pontianak', 'usaha.pontianak@damri.co.id', 'd41d8cd98f00b204e9800998ecf8427e', 77, 5, NULL, 21, 0, '2020-01-09 08:45:16', 1, 'usaha.pontianak@damri.co.id', 0, NULL),
(98, 'Driver Pontianak', 'driver.pontianak@damri.co.id', 'd41d8cd98f00b204e9800998ecf8427e', 77, 4, NULL, 21, 0, '2020-01-09 08:45:45', 1, 'driver.pontianak@damri.co.id', 0, NULL),
(99, 'PPA Pontianak', 'ppa.pontianak@damri.co.id', 'd41d8cd98f00b204e9800998ecf8427e', 77, 4, NULL, 21, 0, '2020-01-09 08:46:05', 1, 'ppa.pontianak@damri.co.id', 0, NULL),
(100, 'Order Pontianak', 'order.pontianak@damri.co.id', 'd41d8cd98f00b204e9800998ecf8427e', 77, 5, NULL, 21, 0, '2020-01-09 08:46:42', 1, 'order.pontianak@damri.co.id', 0, NULL),
(101, 'Irfan', '789810361', '202cb962ac59075b964b07152d234b70', 77, 4, NULL, 3, 1, '2020-01-26 14:25:51', 1, '789810361', 0, NULL),
(102, 'SUHARIADI', '780611142', '202cb962ac59075b964b07152d234b70', 77, 5, NULL, 3, 1, '2020-02-18 21:09:15', 1, '780611142', 0, NULL);

--
-- Trigger `ref_user`
--
DELIMITER //
CREATE TRIGGER `ref_user_BEFORE_INSERT` BEFORE INSERT ON `ref_user`
 FOR EACH ROW BEGIN
set new.cdate = current_timestamp();
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `time_dimension`
--

CREATE TABLE IF NOT EXISTS `time_dimension` (
  `id` int(11) NOT NULL,
  `db_date` date NOT NULL,
  `year` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `day` int(11) NOT NULL,
  `quarter` int(11) NOT NULL,
  `week` int(11) NOT NULL,
  `day_name` varchar(9) NOT NULL,
  `month_name` varchar(9) NOT NULL,
  `holiday_flag` char(1) DEFAULT 'f',
  `weekend_flag` char(1) DEFAULT 'f',
  `event` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `time_dimension`
--

INSERT INTO `time_dimension` (`id`, `db_date`, `year`, `month`, `day`, `quarter`, `week`, `day_name`, `month_name`, `holiday_flag`, `weekend_flag`, `event`) VALUES
(20180101, '2018-01-01', 2018, 1, 1, 1, 1, 'Monday', 'January', 'f', 'f', NULL),
(20180102, '2018-01-02', 2018, 1, 2, 1, 1, 'Tuesday', 'January', 'f', 'f', NULL),
(20180103, '2018-01-03', 2018, 1, 3, 1, 1, 'Wednesday', 'January', 'f', 'f', NULL),
(20180104, '2018-01-04', 2018, 1, 4, 1, 1, 'Thursday', 'January', 'f', 'f', NULL),
(20180105, '2018-01-05', 2018, 1, 5, 1, 1, 'Friday', 'January', 'f', 'f', NULL),
(20180106, '2018-01-06', 2018, 1, 6, 1, 1, 'Saturday', 'January', 'f', 't', NULL),
(20180107, '2018-01-07', 2018, 1, 7, 1, 1, 'Sunday', 'January', 'f', 't', NULL),
(20180108, '2018-01-08', 2018, 1, 8, 1, 2, 'Monday', 'January', 'f', 'f', NULL),
(20180109, '2018-01-09', 2018, 1, 9, 1, 2, 'Tuesday', 'January', 'f', 'f', NULL),
(20180110, '2018-01-10', 2018, 1, 10, 1, 2, 'Wednesday', 'January', 'f', 'f', NULL),
(20180111, '2018-01-11', 2018, 1, 11, 1, 2, 'Thursday', 'January', 'f', 'f', NULL),
(20180112, '2018-01-12', 2018, 1, 12, 1, 2, 'Friday', 'January', 'f', 'f', NULL),
(20180113, '2018-01-13', 2018, 1, 13, 1, 2, 'Saturday', 'January', 'f', 't', NULL),
(20180114, '2018-01-14', 2018, 1, 14, 1, 2, 'Sunday', 'January', 'f', 't', NULL),
(20180115, '2018-01-15', 2018, 1, 15, 1, 3, 'Monday', 'January', 'f', 'f', NULL),
(20180116, '2018-01-16', 2018, 1, 16, 1, 3, 'Tuesday', 'January', 'f', 'f', NULL),
(20180117, '2018-01-17', 2018, 1, 17, 1, 3, 'Wednesday', 'January', 'f', 'f', NULL),
(20180118, '2018-01-18', 2018, 1, 18, 1, 3, 'Thursday', 'January', 'f', 'f', NULL),
(20180119, '2018-01-19', 2018, 1, 19, 1, 3, 'Friday', 'January', 'f', 'f', NULL),
(20180120, '2018-01-20', 2018, 1, 20, 1, 3, 'Saturday', 'January', 'f', 't', NULL),
(20180121, '2018-01-21', 2018, 1, 21, 1, 3, 'Sunday', 'January', 'f', 't', NULL),
(20180122, '2018-01-22', 2018, 1, 22, 1, 4, 'Monday', 'January', 'f', 'f', NULL),
(20180123, '2018-01-23', 2018, 1, 23, 1, 4, 'Tuesday', 'January', 'f', 'f', NULL),
(20180124, '2018-01-24', 2018, 1, 24, 1, 4, 'Wednesday', 'January', 'f', 'f', NULL),
(20180125, '2018-01-25', 2018, 1, 25, 1, 4, 'Thursday', 'January', 'f', 'f', NULL),
(20180126, '2018-01-26', 2018, 1, 26, 1, 4, 'Friday', 'January', 'f', 'f', NULL),
(20180127, '2018-01-27', 2018, 1, 27, 1, 4, 'Saturday', 'January', 'f', 't', NULL),
(20180128, '2018-01-28', 2018, 1, 28, 1, 4, 'Sunday', 'January', 'f', 't', NULL),
(20180129, '2018-01-29', 2018, 1, 29, 1, 5, 'Monday', 'January', 'f', 'f', NULL),
(20180130, '2018-01-30', 2018, 1, 30, 1, 5, 'Tuesday', 'January', 'f', 'f', NULL),
(20180131, '2018-01-31', 2018, 1, 31, 1, 5, 'Wednesday', 'January', 'f', 'f', NULL),
(20180201, '2018-02-01', 2018, 2, 1, 1, 5, 'Thursday', 'February', 'f', 'f', NULL),
(20180202, '2018-02-02', 2018, 2, 2, 1, 5, 'Friday', 'February', 'f', 'f', NULL),
(20180203, '2018-02-03', 2018, 2, 3, 1, 5, 'Saturday', 'February', 'f', 't', NULL),
(20180204, '2018-02-04', 2018, 2, 4, 1, 5, 'Sunday', 'February', 'f', 't', NULL),
(20180205, '2018-02-05', 2018, 2, 5, 1, 6, 'Monday', 'February', 'f', 'f', NULL),
(20180206, '2018-02-06', 2018, 2, 6, 1, 6, 'Tuesday', 'February', 'f', 'f', NULL),
(20180207, '2018-02-07', 2018, 2, 7, 1, 6, 'Wednesday', 'February', 'f', 'f', NULL),
(20180208, '2018-02-08', 2018, 2, 8, 1, 6, 'Thursday', 'February', 'f', 'f', NULL),
(20180209, '2018-02-09', 2018, 2, 9, 1, 6, 'Friday', 'February', 'f', 'f', NULL),
(20180210, '2018-02-10', 2018, 2, 10, 1, 6, 'Saturday', 'February', 'f', 't', NULL),
(20180211, '2018-02-11', 2018, 2, 11, 1, 6, 'Sunday', 'February', 'f', 't', NULL),
(20180212, '2018-02-12', 2018, 2, 12, 1, 7, 'Monday', 'February', 'f', 'f', NULL),
(20180213, '2018-02-13', 2018, 2, 13, 1, 7, 'Tuesday', 'February', 'f', 'f', NULL),
(20180214, '2018-02-14', 2018, 2, 14, 1, 7, 'Wednesday', 'February', 'f', 'f', NULL),
(20180215, '2018-02-15', 2018, 2, 15, 1, 7, 'Thursday', 'February', 'f', 'f', NULL),
(20180216, '2018-02-16', 2018, 2, 16, 1, 7, 'Friday', 'February', 'f', 'f', NULL),
(20180217, '2018-02-17', 2018, 2, 17, 1, 7, 'Saturday', 'February', 'f', 't', NULL),
(20180218, '2018-02-18', 2018, 2, 18, 1, 7, 'Sunday', 'February', 'f', 't', NULL),
(20180219, '2018-02-19', 2018, 2, 19, 1, 8, 'Monday', 'February', 'f', 'f', NULL),
(20180220, '2018-02-20', 2018, 2, 20, 1, 8, 'Tuesday', 'February', 'f', 'f', NULL),
(20180221, '2018-02-21', 2018, 2, 21, 1, 8, 'Wednesday', 'February', 'f', 'f', NULL),
(20180222, '2018-02-22', 2018, 2, 22, 1, 8, 'Thursday', 'February', 'f', 'f', NULL),
(20180223, '2018-02-23', 2018, 2, 23, 1, 8, 'Friday', 'February', 'f', 'f', NULL),
(20180224, '2018-02-24', 2018, 2, 24, 1, 8, 'Saturday', 'February', 'f', 't', NULL),
(20180225, '2018-02-25', 2018, 2, 25, 1, 8, 'Sunday', 'February', 'f', 't', NULL),
(20180226, '2018-02-26', 2018, 2, 26, 1, 9, 'Monday', 'February', 'f', 'f', NULL),
(20180227, '2018-02-27', 2018, 2, 27, 1, 9, 'Tuesday', 'February', 'f', 'f', NULL),
(20180228, '2018-02-28', 2018, 2, 28, 1, 9, 'Wednesday', 'February', 'f', 'f', NULL),
(20180301, '2018-03-01', 2018, 3, 1, 1, 9, 'Thursday', 'March', 'f', 'f', NULL),
(20180302, '2018-03-02', 2018, 3, 2, 1, 9, 'Friday', 'March', 'f', 'f', NULL),
(20180303, '2018-03-03', 2018, 3, 3, 1, 9, 'Saturday', 'March', 'f', 't', NULL),
(20180304, '2018-03-04', 2018, 3, 4, 1, 9, 'Sunday', 'March', 'f', 't', NULL),
(20180305, '2018-03-05', 2018, 3, 5, 1, 10, 'Monday', 'March', 'f', 'f', NULL),
(20180306, '2018-03-06', 2018, 3, 6, 1, 10, 'Tuesday', 'March', 'f', 'f', NULL),
(20180307, '2018-03-07', 2018, 3, 7, 1, 10, 'Wednesday', 'March', 'f', 'f', NULL),
(20180308, '2018-03-08', 2018, 3, 8, 1, 10, 'Thursday', 'March', 'f', 'f', NULL),
(20180309, '2018-03-09', 2018, 3, 9, 1, 10, 'Friday', 'March', 'f', 'f', NULL),
(20180310, '2018-03-10', 2018, 3, 10, 1, 10, 'Saturday', 'March', 'f', 't', NULL),
(20180311, '2018-03-11', 2018, 3, 11, 1, 10, 'Sunday', 'March', 'f', 't', NULL),
(20180312, '2018-03-12', 2018, 3, 12, 1, 11, 'Monday', 'March', 'f', 'f', NULL),
(20180313, '2018-03-13', 2018, 3, 13, 1, 11, 'Tuesday', 'March', 'f', 'f', NULL),
(20180314, '2018-03-14', 2018, 3, 14, 1, 11, 'Wednesday', 'March', 'f', 'f', NULL),
(20180315, '2018-03-15', 2018, 3, 15, 1, 11, 'Thursday', 'March', 'f', 'f', NULL),
(20180316, '2018-03-16', 2018, 3, 16, 1, 11, 'Friday', 'March', 'f', 'f', NULL),
(20180317, '2018-03-17', 2018, 3, 17, 1, 11, 'Saturday', 'March', 'f', 't', NULL),
(20180318, '2018-03-18', 2018, 3, 18, 1, 11, 'Sunday', 'March', 'f', 't', NULL),
(20180319, '2018-03-19', 2018, 3, 19, 1, 12, 'Monday', 'March', 'f', 'f', NULL),
(20180320, '2018-03-20', 2018, 3, 20, 1, 12, 'Tuesday', 'March', 'f', 'f', NULL),
(20180321, '2018-03-21', 2018, 3, 21, 1, 12, 'Wednesday', 'March', 'f', 'f', NULL),
(20180322, '2018-03-22', 2018, 3, 22, 1, 12, 'Thursday', 'March', 'f', 'f', NULL),
(20180323, '2018-03-23', 2018, 3, 23, 1, 12, 'Friday', 'March', 'f', 'f', NULL),
(20180324, '2018-03-24', 2018, 3, 24, 1, 12, 'Saturday', 'March', 'f', 't', NULL),
(20180325, '2018-03-25', 2018, 3, 25, 1, 12, 'Sunday', 'March', 'f', 't', NULL),
(20180326, '2018-03-26', 2018, 3, 26, 1, 13, 'Monday', 'March', 'f', 'f', NULL),
(20180327, '2018-03-27', 2018, 3, 27, 1, 13, 'Tuesday', 'March', 'f', 'f', NULL),
(20180328, '2018-03-28', 2018, 3, 28, 1, 13, 'Wednesday', 'March', 'f', 'f', NULL),
(20180329, '2018-03-29', 2018, 3, 29, 1, 13, 'Thursday', 'March', 'f', 'f', NULL),
(20180330, '2018-03-30', 2018, 3, 30, 1, 13, 'Friday', 'March', 'f', 'f', NULL),
(20180331, '2018-03-31', 2018, 3, 31, 1, 13, 'Saturday', 'March', 'f', 't', NULL),
(20180401, '2018-04-01', 2018, 4, 1, 2, 13, 'Sunday', 'April', 'f', 't', NULL),
(20180402, '2018-04-02', 2018, 4, 2, 2, 14, 'Monday', 'April', 'f', 'f', NULL),
(20180403, '2018-04-03', 2018, 4, 3, 2, 14, 'Tuesday', 'April', 'f', 'f', NULL),
(20180404, '2018-04-04', 2018, 4, 4, 2, 14, 'Wednesday', 'April', 'f', 'f', NULL),
(20180405, '2018-04-05', 2018, 4, 5, 2, 14, 'Thursday', 'April', 'f', 'f', NULL),
(20180406, '2018-04-06', 2018, 4, 6, 2, 14, 'Friday', 'April', 'f', 'f', NULL),
(20180407, '2018-04-07', 2018, 4, 7, 2, 14, 'Saturday', 'April', 'f', 't', NULL),
(20180408, '2018-04-08', 2018, 4, 8, 2, 14, 'Sunday', 'April', 'f', 't', NULL),
(20180409, '2018-04-09', 2018, 4, 9, 2, 15, 'Monday', 'April', 'f', 'f', NULL),
(20180410, '2018-04-10', 2018, 4, 10, 2, 15, 'Tuesday', 'April', 'f', 'f', NULL),
(20180411, '2018-04-11', 2018, 4, 11, 2, 15, 'Wednesday', 'April', 'f', 'f', NULL),
(20180412, '2018-04-12', 2018, 4, 12, 2, 15, 'Thursday', 'April', 'f', 'f', NULL),
(20180413, '2018-04-13', 2018, 4, 13, 2, 15, 'Friday', 'April', 'f', 'f', NULL),
(20180414, '2018-04-14', 2018, 4, 14, 2, 15, 'Saturday', 'April', 'f', 't', NULL),
(20180415, '2018-04-15', 2018, 4, 15, 2, 15, 'Sunday', 'April', 'f', 't', NULL),
(20180416, '2018-04-16', 2018, 4, 16, 2, 16, 'Monday', 'April', 'f', 'f', NULL),
(20180417, '2018-04-17', 2018, 4, 17, 2, 16, 'Tuesday', 'April', 'f', 'f', NULL),
(20180418, '2018-04-18', 2018, 4, 18, 2, 16, 'Wednesday', 'April', 'f', 'f', NULL),
(20180419, '2018-04-19', 2018, 4, 19, 2, 16, 'Thursday', 'April', 'f', 'f', NULL),
(20180420, '2018-04-20', 2018, 4, 20, 2, 16, 'Friday', 'April', 'f', 'f', NULL),
(20180421, '2018-04-21', 2018, 4, 21, 2, 16, 'Saturday', 'April', 'f', 't', NULL),
(20180422, '2018-04-22', 2018, 4, 22, 2, 16, 'Sunday', 'April', 'f', 't', NULL),
(20180423, '2018-04-23', 2018, 4, 23, 2, 17, 'Monday', 'April', 'f', 'f', NULL),
(20180424, '2018-04-24', 2018, 4, 24, 2, 17, 'Tuesday', 'April', 'f', 'f', NULL),
(20180425, '2018-04-25', 2018, 4, 25, 2, 17, 'Wednesday', 'April', 'f', 'f', NULL),
(20180426, '2018-04-26', 2018, 4, 26, 2, 17, 'Thursday', 'April', 'f', 'f', NULL),
(20180427, '2018-04-27', 2018, 4, 27, 2, 17, 'Friday', 'April', 'f', 'f', NULL),
(20180428, '2018-04-28', 2018, 4, 28, 2, 17, 'Saturday', 'April', 'f', 't', NULL),
(20180429, '2018-04-29', 2018, 4, 29, 2, 17, 'Sunday', 'April', 'f', 't', NULL),
(20180430, '2018-04-30', 2018, 4, 30, 2, 18, 'Monday', 'April', 'f', 'f', NULL),
(20180501, '2018-05-01', 2018, 5, 1, 2, 18, 'Tuesday', 'May', 'f', 'f', NULL),
(20180502, '2018-05-02', 2018, 5, 2, 2, 18, 'Wednesday', 'May', 'f', 'f', NULL),
(20180503, '2018-05-03', 2018, 5, 3, 2, 18, 'Thursday', 'May', 'f', 'f', NULL),
(20180504, '2018-05-04', 2018, 5, 4, 2, 18, 'Friday', 'May', 'f', 'f', NULL),
(20180505, '2018-05-05', 2018, 5, 5, 2, 18, 'Saturday', 'May', 'f', 't', NULL),
(20180506, '2018-05-06', 2018, 5, 6, 2, 18, 'Sunday', 'May', 'f', 't', NULL),
(20180507, '2018-05-07', 2018, 5, 7, 2, 19, 'Monday', 'May', 'f', 'f', NULL),
(20180508, '2018-05-08', 2018, 5, 8, 2, 19, 'Tuesday', 'May', 'f', 'f', NULL),
(20180509, '2018-05-09', 2018, 5, 9, 2, 19, 'Wednesday', 'May', 'f', 'f', NULL),
(20180510, '2018-05-10', 2018, 5, 10, 2, 19, 'Thursday', 'May', 'f', 'f', NULL),
(20180511, '2018-05-11', 2018, 5, 11, 2, 19, 'Friday', 'May', 'f', 'f', NULL),
(20180512, '2018-05-12', 2018, 5, 12, 2, 19, 'Saturday', 'May', 'f', 't', NULL),
(20180513, '2018-05-13', 2018, 5, 13, 2, 19, 'Sunday', 'May', 'f', 't', NULL),
(20180514, '2018-05-14', 2018, 5, 14, 2, 20, 'Monday', 'May', 'f', 'f', NULL),
(20180515, '2018-05-15', 2018, 5, 15, 2, 20, 'Tuesday', 'May', 'f', 'f', NULL),
(20180516, '2018-05-16', 2018, 5, 16, 2, 20, 'Wednesday', 'May', 'f', 'f', NULL),
(20180517, '2018-05-17', 2018, 5, 17, 2, 20, 'Thursday', 'May', 'f', 'f', NULL),
(20180518, '2018-05-18', 2018, 5, 18, 2, 20, 'Friday', 'May', 'f', 'f', NULL),
(20180519, '2018-05-19', 2018, 5, 19, 2, 20, 'Saturday', 'May', 'f', 't', NULL),
(20180520, '2018-05-20', 2018, 5, 20, 2, 20, 'Sunday', 'May', 'f', 't', NULL),
(20180521, '2018-05-21', 2018, 5, 21, 2, 21, 'Monday', 'May', 'f', 'f', NULL),
(20180522, '2018-05-22', 2018, 5, 22, 2, 21, 'Tuesday', 'May', 'f', 'f', NULL),
(20180523, '2018-05-23', 2018, 5, 23, 2, 21, 'Wednesday', 'May', 'f', 'f', NULL),
(20180524, '2018-05-24', 2018, 5, 24, 2, 21, 'Thursday', 'May', 'f', 'f', NULL),
(20180525, '2018-05-25', 2018, 5, 25, 2, 21, 'Friday', 'May', 'f', 'f', NULL),
(20180526, '2018-05-26', 2018, 5, 26, 2, 21, 'Saturday', 'May', 'f', 't', NULL),
(20180527, '2018-05-27', 2018, 5, 27, 2, 21, 'Sunday', 'May', 'f', 't', NULL),
(20180528, '2018-05-28', 2018, 5, 28, 2, 22, 'Monday', 'May', 'f', 'f', NULL),
(20180529, '2018-05-29', 2018, 5, 29, 2, 22, 'Tuesday', 'May', 'f', 'f', NULL),
(20180530, '2018-05-30', 2018, 5, 30, 2, 22, 'Wednesday', 'May', 'f', 'f', NULL),
(20180531, '2018-05-31', 2018, 5, 31, 2, 22, 'Thursday', 'May', 'f', 'f', NULL),
(20180601, '2018-06-01', 2018, 6, 1, 2, 22, 'Friday', 'June', 'f', 'f', NULL),
(20180602, '2018-06-02', 2018, 6, 2, 2, 22, 'Saturday', 'June', 'f', 't', NULL),
(20180603, '2018-06-03', 2018, 6, 3, 2, 22, 'Sunday', 'June', 'f', 't', NULL),
(20180604, '2018-06-04', 2018, 6, 4, 2, 23, 'Monday', 'June', 'f', 'f', NULL),
(20180605, '2018-06-05', 2018, 6, 5, 2, 23, 'Tuesday', 'June', 'f', 'f', NULL),
(20180606, '2018-06-06', 2018, 6, 6, 2, 23, 'Wednesday', 'June', 'f', 'f', NULL),
(20180607, '2018-06-07', 2018, 6, 7, 2, 23, 'Thursday', 'June', 'f', 'f', NULL),
(20180608, '2018-06-08', 2018, 6, 8, 2, 23, 'Friday', 'June', 'f', 'f', NULL),
(20180609, '2018-06-09', 2018, 6, 9, 2, 23, 'Saturday', 'June', 'f', 't', NULL),
(20180610, '2018-06-10', 2018, 6, 10, 2, 23, 'Sunday', 'June', 'f', 't', NULL),
(20180611, '2018-06-11', 2018, 6, 11, 2, 24, 'Monday', 'June', 'f', 'f', NULL),
(20180612, '2018-06-12', 2018, 6, 12, 2, 24, 'Tuesday', 'June', 'f', 'f', NULL),
(20180613, '2018-06-13', 2018, 6, 13, 2, 24, 'Wednesday', 'June', 'f', 'f', NULL),
(20180614, '2018-06-14', 2018, 6, 14, 2, 24, 'Thursday', 'June', 'f', 'f', NULL),
(20180615, '2018-06-15', 2018, 6, 15, 2, 24, 'Friday', 'June', 'f', 'f', NULL),
(20180616, '2018-06-16', 2018, 6, 16, 2, 24, 'Saturday', 'June', 'f', 't', NULL),
(20180617, '2018-06-17', 2018, 6, 17, 2, 24, 'Sunday', 'June', 'f', 't', NULL),
(20180618, '2018-06-18', 2018, 6, 18, 2, 25, 'Monday', 'June', 'f', 'f', NULL),
(20180619, '2018-06-19', 2018, 6, 19, 2, 25, 'Tuesday', 'June', 'f', 'f', NULL),
(20180620, '2018-06-20', 2018, 6, 20, 2, 25, 'Wednesday', 'June', 'f', 'f', NULL),
(20180621, '2018-06-21', 2018, 6, 21, 2, 25, 'Thursday', 'June', 'f', 'f', NULL),
(20180622, '2018-06-22', 2018, 6, 22, 2, 25, 'Friday', 'June', 'f', 'f', NULL),
(20180623, '2018-06-23', 2018, 6, 23, 2, 25, 'Saturday', 'June', 'f', 't', NULL),
(20180624, '2018-06-24', 2018, 6, 24, 2, 25, 'Sunday', 'June', 'f', 't', NULL),
(20180625, '2018-06-25', 2018, 6, 25, 2, 26, 'Monday', 'June', 'f', 'f', NULL),
(20180626, '2018-06-26', 2018, 6, 26, 2, 26, 'Tuesday', 'June', 'f', 'f', NULL),
(20180627, '2018-06-27', 2018, 6, 27, 2, 26, 'Wednesday', 'June', 'f', 'f', NULL),
(20180628, '2018-06-28', 2018, 6, 28, 2, 26, 'Thursday', 'June', 'f', 'f', NULL),
(20180629, '2018-06-29', 2018, 6, 29, 2, 26, 'Friday', 'June', 'f', 'f', NULL),
(20180630, '2018-06-30', 2018, 6, 30, 2, 26, 'Saturday', 'June', 'f', 't', NULL),
(20180701, '2018-07-01', 2018, 7, 1, 3, 26, 'Sunday', 'July', 'f', 't', NULL),
(20180702, '2018-07-02', 2018, 7, 2, 3, 27, 'Monday', 'July', 'f', 'f', NULL),
(20180703, '2018-07-03', 2018, 7, 3, 3, 27, 'Tuesday', 'July', 'f', 'f', NULL),
(20180704, '2018-07-04', 2018, 7, 4, 3, 27, 'Wednesday', 'July', 'f', 'f', NULL),
(20180705, '2018-07-05', 2018, 7, 5, 3, 27, 'Thursday', 'July', 'f', 'f', NULL),
(20180706, '2018-07-06', 2018, 7, 6, 3, 27, 'Friday', 'July', 'f', 'f', NULL),
(20180707, '2018-07-07', 2018, 7, 7, 3, 27, 'Saturday', 'July', 'f', 't', NULL),
(20180708, '2018-07-08', 2018, 7, 8, 3, 27, 'Sunday', 'July', 'f', 't', NULL),
(20180709, '2018-07-09', 2018, 7, 9, 3, 28, 'Monday', 'July', 'f', 'f', NULL),
(20180710, '2018-07-10', 2018, 7, 10, 3, 28, 'Tuesday', 'July', 'f', 'f', NULL),
(20180711, '2018-07-11', 2018, 7, 11, 3, 28, 'Wednesday', 'July', 'f', 'f', NULL),
(20180712, '2018-07-12', 2018, 7, 12, 3, 28, 'Thursday', 'July', 'f', 'f', NULL),
(20180713, '2018-07-13', 2018, 7, 13, 3, 28, 'Friday', 'July', 'f', 'f', NULL),
(20180714, '2018-07-14', 2018, 7, 14, 3, 28, 'Saturday', 'July', 'f', 't', NULL),
(20180715, '2018-07-15', 2018, 7, 15, 3, 28, 'Sunday', 'July', 'f', 't', NULL),
(20180716, '2018-07-16', 2018, 7, 16, 3, 29, 'Monday', 'July', 'f', 'f', NULL),
(20180717, '2018-07-17', 2018, 7, 17, 3, 29, 'Tuesday', 'July', 'f', 'f', NULL),
(20180718, '2018-07-18', 2018, 7, 18, 3, 29, 'Wednesday', 'July', 'f', 'f', NULL),
(20180719, '2018-07-19', 2018, 7, 19, 3, 29, 'Thursday', 'July', 'f', 'f', NULL),
(20180720, '2018-07-20', 2018, 7, 20, 3, 29, 'Friday', 'July', 'f', 'f', NULL),
(20180721, '2018-07-21', 2018, 7, 21, 3, 29, 'Saturday', 'July', 'f', 't', NULL),
(20180722, '2018-07-22', 2018, 7, 22, 3, 29, 'Sunday', 'July', 'f', 't', NULL),
(20180723, '2018-07-23', 2018, 7, 23, 3, 30, 'Monday', 'July', 'f', 'f', NULL),
(20180724, '2018-07-24', 2018, 7, 24, 3, 30, 'Tuesday', 'July', 'f', 'f', NULL),
(20180725, '2018-07-25', 2018, 7, 25, 3, 30, 'Wednesday', 'July', 'f', 'f', NULL),
(20180726, '2018-07-26', 2018, 7, 26, 3, 30, 'Thursday', 'July', 'f', 'f', NULL),
(20180727, '2018-07-27', 2018, 7, 27, 3, 30, 'Friday', 'July', 'f', 'f', NULL),
(20180728, '2018-07-28', 2018, 7, 28, 3, 30, 'Saturday', 'July', 'f', 't', NULL),
(20180729, '2018-07-29', 2018, 7, 29, 3, 30, 'Sunday', 'July', 'f', 't', NULL),
(20180730, '2018-07-30', 2018, 7, 30, 3, 31, 'Monday', 'July', 'f', 'f', NULL),
(20180731, '2018-07-31', 2018, 7, 31, 3, 31, 'Tuesday', 'July', 'f', 'f', NULL),
(20180801, '2018-08-01', 2018, 8, 1, 3, 31, 'Wednesday', 'August', 'f', 'f', NULL),
(20180802, '2018-08-02', 2018, 8, 2, 3, 31, 'Thursday', 'August', 'f', 'f', NULL),
(20180803, '2018-08-03', 2018, 8, 3, 3, 31, 'Friday', 'August', 'f', 'f', NULL),
(20180804, '2018-08-04', 2018, 8, 4, 3, 31, 'Saturday', 'August', 'f', 't', NULL),
(20180805, '2018-08-05', 2018, 8, 5, 3, 31, 'Sunday', 'August', 'f', 't', NULL),
(20180806, '2018-08-06', 2018, 8, 6, 3, 32, 'Monday', 'August', 'f', 'f', NULL),
(20180807, '2018-08-07', 2018, 8, 7, 3, 32, 'Tuesday', 'August', 'f', 'f', NULL),
(20180808, '2018-08-08', 2018, 8, 8, 3, 32, 'Wednesday', 'August', 'f', 'f', NULL),
(20180809, '2018-08-09', 2018, 8, 9, 3, 32, 'Thursday', 'August', 'f', 'f', NULL),
(20180810, '2018-08-10', 2018, 8, 10, 3, 32, 'Friday', 'August', 'f', 'f', NULL),
(20180811, '2018-08-11', 2018, 8, 11, 3, 32, 'Saturday', 'August', 'f', 't', NULL),
(20180812, '2018-08-12', 2018, 8, 12, 3, 32, 'Sunday', 'August', 'f', 't', NULL),
(20180813, '2018-08-13', 2018, 8, 13, 3, 33, 'Monday', 'August', 'f', 'f', NULL),
(20180814, '2018-08-14', 2018, 8, 14, 3, 33, 'Tuesday', 'August', 'f', 'f', NULL),
(20180815, '2018-08-15', 2018, 8, 15, 3, 33, 'Wednesday', 'August', 'f', 'f', NULL),
(20180816, '2018-08-16', 2018, 8, 16, 3, 33, 'Thursday', 'August', 'f', 'f', NULL),
(20180817, '2018-08-17', 2018, 8, 17, 3, 33, 'Friday', 'August', 'f', 'f', NULL),
(20180818, '2018-08-18', 2018, 8, 18, 3, 33, 'Saturday', 'August', 'f', 't', NULL),
(20180819, '2018-08-19', 2018, 8, 19, 3, 33, 'Sunday', 'August', 'f', 't', NULL),
(20180820, '2018-08-20', 2018, 8, 20, 3, 34, 'Monday', 'August', 'f', 'f', NULL),
(20180821, '2018-08-21', 2018, 8, 21, 3, 34, 'Tuesday', 'August', 'f', 'f', NULL),
(20180822, '2018-08-22', 2018, 8, 22, 3, 34, 'Wednesday', 'August', 'f', 'f', NULL),
(20180823, '2018-08-23', 2018, 8, 23, 3, 34, 'Thursday', 'August', 'f', 'f', NULL),
(20180824, '2018-08-24', 2018, 8, 24, 3, 34, 'Friday', 'August', 'f', 'f', NULL),
(20180825, '2018-08-25', 2018, 8, 25, 3, 34, 'Saturday', 'August', 'f', 't', NULL),
(20180826, '2018-08-26', 2018, 8, 26, 3, 34, 'Sunday', 'August', 'f', 't', NULL),
(20180827, '2018-08-27', 2018, 8, 27, 3, 35, 'Monday', 'August', 'f', 'f', NULL),
(20180828, '2018-08-28', 2018, 8, 28, 3, 35, 'Tuesday', 'August', 'f', 'f', NULL),
(20180829, '2018-08-29', 2018, 8, 29, 3, 35, 'Wednesday', 'August', 'f', 'f', NULL),
(20180830, '2018-08-30', 2018, 8, 30, 3, 35, 'Thursday', 'August', 'f', 'f', NULL),
(20180831, '2018-08-31', 2018, 8, 31, 3, 35, 'Friday', 'August', 'f', 'f', NULL),
(20180901, '2018-09-01', 2018, 9, 1, 3, 35, 'Saturday', 'September', 'f', 't', NULL),
(20180902, '2018-09-02', 2018, 9, 2, 3, 35, 'Sunday', 'September', 'f', 't', NULL),
(20180903, '2018-09-03', 2018, 9, 3, 3, 36, 'Monday', 'September', 'f', 'f', NULL),
(20180904, '2018-09-04', 2018, 9, 4, 3, 36, 'Tuesday', 'September', 'f', 'f', NULL),
(20180905, '2018-09-05', 2018, 9, 5, 3, 36, 'Wednesday', 'September', 'f', 'f', NULL),
(20180906, '2018-09-06', 2018, 9, 6, 3, 36, 'Thursday', 'September', 'f', 'f', NULL),
(20180907, '2018-09-07', 2018, 9, 7, 3, 36, 'Friday', 'September', 'f', 'f', NULL),
(20180908, '2018-09-08', 2018, 9, 8, 3, 36, 'Saturday', 'September', 'f', 't', NULL),
(20180909, '2018-09-09', 2018, 9, 9, 3, 36, 'Sunday', 'September', 'f', 't', NULL),
(20180910, '2018-09-10', 2018, 9, 10, 3, 37, 'Monday', 'September', 'f', 'f', NULL),
(20180911, '2018-09-11', 2018, 9, 11, 3, 37, 'Tuesday', 'September', 'f', 'f', NULL),
(20180912, '2018-09-12', 2018, 9, 12, 3, 37, 'Wednesday', 'September', 'f', 'f', NULL),
(20180913, '2018-09-13', 2018, 9, 13, 3, 37, 'Thursday', 'September', 'f', 'f', NULL),
(20180914, '2018-09-14', 2018, 9, 14, 3, 37, 'Friday', 'September', 'f', 'f', NULL),
(20180915, '2018-09-15', 2018, 9, 15, 3, 37, 'Saturday', 'September', 'f', 't', NULL),
(20180916, '2018-09-16', 2018, 9, 16, 3, 37, 'Sunday', 'September', 'f', 't', NULL),
(20180917, '2018-09-17', 2018, 9, 17, 3, 38, 'Monday', 'September', 'f', 'f', NULL),
(20180918, '2018-09-18', 2018, 9, 18, 3, 38, 'Tuesday', 'September', 'f', 'f', NULL),
(20180919, '2018-09-19', 2018, 9, 19, 3, 38, 'Wednesday', 'September', 'f', 'f', NULL),
(20180920, '2018-09-20', 2018, 9, 20, 3, 38, 'Thursday', 'September', 'f', 'f', NULL),
(20180921, '2018-09-21', 2018, 9, 21, 3, 38, 'Friday', 'September', 'f', 'f', NULL),
(20180922, '2018-09-22', 2018, 9, 22, 3, 38, 'Saturday', 'September', 'f', 't', NULL),
(20180923, '2018-09-23', 2018, 9, 23, 3, 38, 'Sunday', 'September', 'f', 't', NULL),
(20180924, '2018-09-24', 2018, 9, 24, 3, 39, 'Monday', 'September', 'f', 'f', NULL),
(20180925, '2018-09-25', 2018, 9, 25, 3, 39, 'Tuesday', 'September', 'f', 'f', NULL),
(20180926, '2018-09-26', 2018, 9, 26, 3, 39, 'Wednesday', 'September', 'f', 'f', NULL),
(20180927, '2018-09-27', 2018, 9, 27, 3, 39, 'Thursday', 'September', 'f', 'f', NULL),
(20180928, '2018-09-28', 2018, 9, 28, 3, 39, 'Friday', 'September', 'f', 'f', NULL),
(20180929, '2018-09-29', 2018, 9, 29, 3, 39, 'Saturday', 'September', 'f', 't', NULL),
(20180930, '2018-09-30', 2018, 9, 30, 3, 39, 'Sunday', 'September', 'f', 't', NULL),
(20181001, '2018-10-01', 2018, 10, 1, 4, 40, 'Monday', 'October', 'f', 'f', NULL),
(20181002, '2018-10-02', 2018, 10, 2, 4, 40, 'Tuesday', 'October', 'f', 'f', NULL),
(20181003, '2018-10-03', 2018, 10, 3, 4, 40, 'Wednesday', 'October', 'f', 'f', NULL),
(20181004, '2018-10-04', 2018, 10, 4, 4, 40, 'Thursday', 'October', 'f', 'f', NULL),
(20181005, '2018-10-05', 2018, 10, 5, 4, 40, 'Friday', 'October', 'f', 'f', NULL),
(20181006, '2018-10-06', 2018, 10, 6, 4, 40, 'Saturday', 'October', 'f', 't', NULL),
(20181007, '2018-10-07', 2018, 10, 7, 4, 40, 'Sunday', 'October', 'f', 't', NULL),
(20181008, '2018-10-08', 2018, 10, 8, 4, 41, 'Monday', 'October', 'f', 'f', NULL),
(20181009, '2018-10-09', 2018, 10, 9, 4, 41, 'Tuesday', 'October', 'f', 'f', NULL),
(20181010, '2018-10-10', 2018, 10, 10, 4, 41, 'Wednesday', 'October', 'f', 'f', NULL),
(20181011, '2018-10-11', 2018, 10, 11, 4, 41, 'Thursday', 'October', 'f', 'f', NULL),
(20181012, '2018-10-12', 2018, 10, 12, 4, 41, 'Friday', 'October', 'f', 'f', NULL),
(20181013, '2018-10-13', 2018, 10, 13, 4, 41, 'Saturday', 'October', 'f', 't', NULL),
(20181014, '2018-10-14', 2018, 10, 14, 4, 41, 'Sunday', 'October', 'f', 't', NULL),
(20181015, '2018-10-15', 2018, 10, 15, 4, 42, 'Monday', 'October', 'f', 'f', NULL),
(20181016, '2018-10-16', 2018, 10, 16, 4, 42, 'Tuesday', 'October', 'f', 'f', NULL),
(20181017, '2018-10-17', 2018, 10, 17, 4, 42, 'Wednesday', 'October', 'f', 'f', NULL),
(20181018, '2018-10-18', 2018, 10, 18, 4, 42, 'Thursday', 'October', 'f', 'f', NULL),
(20181019, '2018-10-19', 2018, 10, 19, 4, 42, 'Friday', 'October', 'f', 'f', NULL),
(20181020, '2018-10-20', 2018, 10, 20, 4, 42, 'Saturday', 'October', 'f', 't', NULL),
(20181021, '2018-10-21', 2018, 10, 21, 4, 42, 'Sunday', 'October', 'f', 't', NULL),
(20181022, '2018-10-22', 2018, 10, 22, 4, 43, 'Monday', 'October', 'f', 'f', NULL),
(20181023, '2018-10-23', 2018, 10, 23, 4, 43, 'Tuesday', 'October', 'f', 'f', NULL),
(20181024, '2018-10-24', 2018, 10, 24, 4, 43, 'Wednesday', 'October', 'f', 'f', NULL),
(20181025, '2018-10-25', 2018, 10, 25, 4, 43, 'Thursday', 'October', 'f', 'f', NULL),
(20181026, '2018-10-26', 2018, 10, 26, 4, 43, 'Friday', 'October', 'f', 'f', NULL),
(20181027, '2018-10-27', 2018, 10, 27, 4, 43, 'Saturday', 'October', 'f', 't', NULL),
(20181028, '2018-10-28', 2018, 10, 28, 4, 43, 'Sunday', 'October', 'f', 't', NULL),
(20181029, '2018-10-29', 2018, 10, 29, 4, 44, 'Monday', 'October', 'f', 'f', NULL),
(20181030, '2018-10-30', 2018, 10, 30, 4, 44, 'Tuesday', 'October', 'f', 'f', NULL),
(20181031, '2018-10-31', 2018, 10, 31, 4, 44, 'Wednesday', 'October', 'f', 'f', NULL),
(20181101, '2018-11-01', 2018, 11, 1, 4, 44, 'Thursday', 'November', 'f', 'f', NULL),
(20181102, '2018-11-02', 2018, 11, 2, 4, 44, 'Friday', 'November', 'f', 'f', NULL),
(20181103, '2018-11-03', 2018, 11, 3, 4, 44, 'Saturday', 'November', 'f', 't', NULL),
(20181104, '2018-11-04', 2018, 11, 4, 4, 44, 'Sunday', 'November', 'f', 't', NULL),
(20181105, '2018-11-05', 2018, 11, 5, 4, 45, 'Monday', 'November', 'f', 'f', NULL),
(20181106, '2018-11-06', 2018, 11, 6, 4, 45, 'Tuesday', 'November', 'f', 'f', NULL),
(20181107, '2018-11-07', 2018, 11, 7, 4, 45, 'Wednesday', 'November', 'f', 'f', NULL),
(20181108, '2018-11-08', 2018, 11, 8, 4, 45, 'Thursday', 'November', 'f', 'f', NULL),
(20181109, '2018-11-09', 2018, 11, 9, 4, 45, 'Friday', 'November', 'f', 'f', NULL),
(20181110, '2018-11-10', 2018, 11, 10, 4, 45, 'Saturday', 'November', 'f', 't', NULL),
(20181111, '2018-11-11', 2018, 11, 11, 4, 45, 'Sunday', 'November', 'f', 't', NULL),
(20181112, '2018-11-12', 2018, 11, 12, 4, 46, 'Monday', 'November', 'f', 'f', NULL),
(20181113, '2018-11-13', 2018, 11, 13, 4, 46, 'Tuesday', 'November', 'f', 'f', NULL),
(20181114, '2018-11-14', 2018, 11, 14, 4, 46, 'Wednesday', 'November', 'f', 'f', NULL),
(20181115, '2018-11-15', 2018, 11, 15, 4, 46, 'Thursday', 'November', 'f', 'f', NULL),
(20181116, '2018-11-16', 2018, 11, 16, 4, 46, 'Friday', 'November', 'f', 'f', NULL),
(20181117, '2018-11-17', 2018, 11, 17, 4, 46, 'Saturday', 'November', 'f', 't', NULL),
(20181118, '2018-11-18', 2018, 11, 18, 4, 46, 'Sunday', 'November', 'f', 't', NULL),
(20181119, '2018-11-19', 2018, 11, 19, 4, 47, 'Monday', 'November', 'f', 'f', NULL),
(20181120, '2018-11-20', 2018, 11, 20, 4, 47, 'Tuesday', 'November', 'f', 'f', NULL),
(20181121, '2018-11-21', 2018, 11, 21, 4, 47, 'Wednesday', 'November', 'f', 'f', NULL),
(20181122, '2018-11-22', 2018, 11, 22, 4, 47, 'Thursday', 'November', 'f', 'f', NULL),
(20181123, '2018-11-23', 2018, 11, 23, 4, 47, 'Friday', 'November', 'f', 'f', NULL),
(20181124, '2018-11-24', 2018, 11, 24, 4, 47, 'Saturday', 'November', 'f', 't', NULL),
(20181125, '2018-11-25', 2018, 11, 25, 4, 47, 'Sunday', 'November', 'f', 't', NULL),
(20181126, '2018-11-26', 2018, 11, 26, 4, 48, 'Monday', 'November', 'f', 'f', NULL),
(20181127, '2018-11-27', 2018, 11, 27, 4, 48, 'Tuesday', 'November', 'f', 'f', NULL),
(20181128, '2018-11-28', 2018, 11, 28, 4, 48, 'Wednesday', 'November', 'f', 'f', NULL),
(20181129, '2018-11-29', 2018, 11, 29, 4, 48, 'Thursday', 'November', 'f', 'f', NULL),
(20181130, '2018-11-30', 2018, 11, 30, 4, 48, 'Friday', 'November', 'f', 'f', NULL),
(20181201, '2018-12-01', 2018, 12, 1, 4, 48, 'Saturday', 'December', 'f', 't', NULL),
(20181202, '2018-12-02', 2018, 12, 2, 4, 48, 'Sunday', 'December', 'f', 't', NULL),
(20181203, '2018-12-03', 2018, 12, 3, 4, 49, 'Monday', 'December', 'f', 'f', NULL),
(20181204, '2018-12-04', 2018, 12, 4, 4, 49, 'Tuesday', 'December', 'f', 'f', NULL),
(20181205, '2018-12-05', 2018, 12, 5, 4, 49, 'Wednesday', 'December', 'f', 'f', NULL),
(20181206, '2018-12-06', 2018, 12, 6, 4, 49, 'Thursday', 'December', 'f', 'f', NULL),
(20181207, '2018-12-07', 2018, 12, 7, 4, 49, 'Friday', 'December', 'f', 'f', NULL),
(20181208, '2018-12-08', 2018, 12, 8, 4, 49, 'Saturday', 'December', 'f', 't', NULL),
(20181209, '2018-12-09', 2018, 12, 9, 4, 49, 'Sunday', 'December', 'f', 't', NULL),
(20181210, '2018-12-10', 2018, 12, 10, 4, 50, 'Monday', 'December', 'f', 'f', NULL),
(20181211, '2018-12-11', 2018, 12, 11, 4, 50, 'Tuesday', 'December', 'f', 'f', NULL),
(20181212, '2018-12-12', 2018, 12, 12, 4, 50, 'Wednesday', 'December', 'f', 'f', NULL),
(20181213, '2018-12-13', 2018, 12, 13, 4, 50, 'Thursday', 'December', 'f', 'f', NULL),
(20181214, '2018-12-14', 2018, 12, 14, 4, 50, 'Friday', 'December', 'f', 'f', NULL),
(20181215, '2018-12-15', 2018, 12, 15, 4, 50, 'Saturday', 'December', 'f', 't', NULL),
(20181216, '2018-12-16', 2018, 12, 16, 4, 50, 'Sunday', 'December', 'f', 't', NULL),
(20181217, '2018-12-17', 2018, 12, 17, 4, 51, 'Monday', 'December', 'f', 'f', NULL),
(20181218, '2018-12-18', 2018, 12, 18, 4, 51, 'Tuesday', 'December', 'f', 'f', NULL),
(20181219, '2018-12-19', 2018, 12, 19, 4, 51, 'Wednesday', 'December', 'f', 'f', NULL),
(20181220, '2018-12-20', 2018, 12, 20, 4, 51, 'Thursday', 'December', 'f', 'f', NULL),
(20181221, '2018-12-21', 2018, 12, 21, 4, 51, 'Friday', 'December', 'f', 'f', NULL),
(20181222, '2018-12-22', 2018, 12, 22, 4, 51, 'Saturday', 'December', 'f', 't', NULL),
(20181223, '2018-12-23', 2018, 12, 23, 4, 51, 'Sunday', 'December', 'f', 't', NULL),
(20181224, '2018-12-24', 2018, 12, 24, 4, 52, 'Monday', 'December', 'f', 'f', NULL),
(20181225, '2018-12-25', 2018, 12, 25, 4, 52, 'Tuesday', 'December', 'f', 'f', NULL),
(20181226, '2018-12-26', 2018, 12, 26, 4, 52, 'Wednesday', 'December', 'f', 'f', NULL),
(20181227, '2018-12-27', 2018, 12, 27, 4, 52, 'Thursday', 'December', 'f', 'f', NULL),
(20181228, '2018-12-28', 2018, 12, 28, 4, 52, 'Friday', 'December', 'f', 'f', NULL),
(20181229, '2018-12-29', 2018, 12, 29, 4, 52, 'Saturday', 'December', 'f', 't', NULL),
(20181230, '2018-12-30', 2018, 12, 30, 4, 52, 'Sunday', 'December', 'f', 't', NULL),
(20181231, '2018-12-31', 2018, 12, 31, 4, 1, 'Monday', 'December', 'f', 'f', NULL),
(20190101, '2019-01-01', 2019, 1, 1, 1, 1, 'Tuesday', 'January', 'f', 'f', NULL),
(20190102, '2019-01-02', 2019, 1, 2, 1, 1, 'Wednesday', 'January', 'f', 'f', NULL),
(20190103, '2019-01-03', 2019, 1, 3, 1, 1, 'Thursday', 'January', 'f', 'f', NULL),
(20190104, '2019-01-04', 2019, 1, 4, 1, 1, 'Friday', 'January', 'f', 'f', NULL),
(20190105, '2019-01-05', 2019, 1, 5, 1, 1, 'Saturday', 'January', 'f', 't', NULL),
(20190106, '2019-01-06', 2019, 1, 6, 1, 1, 'Sunday', 'January', 'f', 't', NULL),
(20190107, '2019-01-07', 2019, 1, 7, 1, 2, 'Monday', 'January', 'f', 'f', NULL),
(20190108, '2019-01-08', 2019, 1, 8, 1, 2, 'Tuesday', 'January', 'f', 'f', NULL),
(20190109, '2019-01-09', 2019, 1, 9, 1, 2, 'Wednesday', 'January', 'f', 'f', NULL),
(20190110, '2019-01-10', 2019, 1, 10, 1, 2, 'Thursday', 'January', 'f', 'f', NULL),
(20190111, '2019-01-11', 2019, 1, 11, 1, 2, 'Friday', 'January', 'f', 'f', NULL),
(20190112, '2019-01-12', 2019, 1, 12, 1, 2, 'Saturday', 'January', 'f', 't', NULL),
(20190113, '2019-01-13', 2019, 1, 13, 1, 2, 'Sunday', 'January', 'f', 't', NULL),
(20190114, '2019-01-14', 2019, 1, 14, 1, 3, 'Monday', 'January', 'f', 'f', NULL),
(20190115, '2019-01-15', 2019, 1, 15, 1, 3, 'Tuesday', 'January', 'f', 'f', NULL),
(20190116, '2019-01-16', 2019, 1, 16, 1, 3, 'Wednesday', 'January', 'f', 'f', NULL),
(20190117, '2019-01-17', 2019, 1, 17, 1, 3, 'Thursday', 'January', 'f', 'f', NULL),
(20190118, '2019-01-18', 2019, 1, 18, 1, 3, 'Friday', 'January', 'f', 'f', NULL),
(20190119, '2019-01-19', 2019, 1, 19, 1, 3, 'Saturday', 'January', 'f', 't', NULL),
(20190120, '2019-01-20', 2019, 1, 20, 1, 3, 'Sunday', 'January', 'f', 't', NULL),
(20190121, '2019-01-21', 2019, 1, 21, 1, 4, 'Monday', 'January', 'f', 'f', NULL),
(20190122, '2019-01-22', 2019, 1, 22, 1, 4, 'Tuesday', 'January', 'f', 'f', NULL),
(20190123, '2019-01-23', 2019, 1, 23, 1, 4, 'Wednesday', 'January', 'f', 'f', NULL),
(20190124, '2019-01-24', 2019, 1, 24, 1, 4, 'Thursday', 'January', 'f', 'f', NULL),
(20190125, '2019-01-25', 2019, 1, 25, 1, 4, 'Friday', 'January', 'f', 'f', NULL),
(20190126, '2019-01-26', 2019, 1, 26, 1, 4, 'Saturday', 'January', 'f', 't', NULL),
(20190127, '2019-01-27', 2019, 1, 27, 1, 4, 'Sunday', 'January', 'f', 't', NULL),
(20190128, '2019-01-28', 2019, 1, 28, 1, 5, 'Monday', 'January', 'f', 'f', NULL),
(20190129, '2019-01-29', 2019, 1, 29, 1, 5, 'Tuesday', 'January', 'f', 'f', NULL),
(20190130, '2019-01-30', 2019, 1, 30, 1, 5, 'Wednesday', 'January', 'f', 'f', NULL),
(20190131, '2019-01-31', 2019, 1, 31, 1, 5, 'Thursday', 'January', 'f', 'f', NULL),
(20190201, '2019-02-01', 2019, 2, 1, 1, 5, 'Friday', 'February', 'f', 'f', NULL),
(20190202, '2019-02-02', 2019, 2, 2, 1, 5, 'Saturday', 'February', 'f', 't', NULL),
(20190203, '2019-02-03', 2019, 2, 3, 1, 5, 'Sunday', 'February', 'f', 't', NULL),
(20190204, '2019-02-04', 2019, 2, 4, 1, 6, 'Monday', 'February', 'f', 'f', NULL),
(20190205, '2019-02-05', 2019, 2, 5, 1, 6, 'Tuesday', 'February', 'f', 'f', NULL),
(20190206, '2019-02-06', 2019, 2, 6, 1, 6, 'Wednesday', 'February', 'f', 'f', NULL),
(20190207, '2019-02-07', 2019, 2, 7, 1, 6, 'Thursday', 'February', 'f', 'f', NULL),
(20190208, '2019-02-08', 2019, 2, 8, 1, 6, 'Friday', 'February', 'f', 'f', NULL),
(20190209, '2019-02-09', 2019, 2, 9, 1, 6, 'Saturday', 'February', 'f', 't', NULL),
(20190210, '2019-02-10', 2019, 2, 10, 1, 6, 'Sunday', 'February', 'f', 't', NULL),
(20190211, '2019-02-11', 2019, 2, 11, 1, 7, 'Monday', 'February', 'f', 'f', NULL),
(20190212, '2019-02-12', 2019, 2, 12, 1, 7, 'Tuesday', 'February', 'f', 'f', NULL),
(20190213, '2019-02-13', 2019, 2, 13, 1, 7, 'Wednesday', 'February', 'f', 'f', NULL),
(20190214, '2019-02-14', 2019, 2, 14, 1, 7, 'Thursday', 'February', 'f', 'f', NULL),
(20190215, '2019-02-15', 2019, 2, 15, 1, 7, 'Friday', 'February', 'f', 'f', NULL),
(20190216, '2019-02-16', 2019, 2, 16, 1, 7, 'Saturday', 'February', 'f', 't', NULL),
(20190217, '2019-02-17', 2019, 2, 17, 1, 7, 'Sunday', 'February', 'f', 't', NULL),
(20190218, '2019-02-18', 2019, 2, 18, 1, 8, 'Monday', 'February', 'f', 'f', NULL),
(20190219, '2019-02-19', 2019, 2, 19, 1, 8, 'Tuesday', 'February', 'f', 'f', NULL),
(20190220, '2019-02-20', 2019, 2, 20, 1, 8, 'Wednesday', 'February', 'f', 'f', NULL),
(20190221, '2019-02-21', 2019, 2, 21, 1, 8, 'Thursday', 'February', 'f', 'f', NULL),
(20190222, '2019-02-22', 2019, 2, 22, 1, 8, 'Friday', 'February', 'f', 'f', NULL),
(20190223, '2019-02-23', 2019, 2, 23, 1, 8, 'Saturday', 'February', 'f', 't', NULL),
(20190224, '2019-02-24', 2019, 2, 24, 1, 8, 'Sunday', 'February', 'f', 't', NULL),
(20190225, '2019-02-25', 2019, 2, 25, 1, 9, 'Monday', 'February', 'f', 'f', NULL),
(20190226, '2019-02-26', 2019, 2, 26, 1, 9, 'Tuesday', 'February', 'f', 'f', NULL),
(20190227, '2019-02-27', 2019, 2, 27, 1, 9, 'Wednesday', 'February', 'f', 'f', NULL),
(20190228, '2019-02-28', 2019, 2, 28, 1, 9, 'Thursday', 'February', 'f', 'f', NULL),
(20190301, '2019-03-01', 2019, 3, 1, 1, 9, 'Friday', 'March', 'f', 'f', NULL),
(20190302, '2019-03-02', 2019, 3, 2, 1, 9, 'Saturday', 'March', 'f', 't', NULL),
(20190303, '2019-03-03', 2019, 3, 3, 1, 9, 'Sunday', 'March', 'f', 't', NULL),
(20190304, '2019-03-04', 2019, 3, 4, 1, 10, 'Monday', 'March', 'f', 'f', NULL),
(20190305, '2019-03-05', 2019, 3, 5, 1, 10, 'Tuesday', 'March', 'f', 'f', NULL),
(20190306, '2019-03-06', 2019, 3, 6, 1, 10, 'Wednesday', 'March', 'f', 'f', NULL),
(20190307, '2019-03-07', 2019, 3, 7, 1, 10, 'Thursday', 'March', 'f', 'f', NULL),
(20190308, '2019-03-08', 2019, 3, 8, 1, 10, 'Friday', 'March', 'f', 'f', NULL),
(20190309, '2019-03-09', 2019, 3, 9, 1, 10, 'Saturday', 'March', 'f', 't', NULL),
(20190310, '2019-03-10', 2019, 3, 10, 1, 10, 'Sunday', 'March', 'f', 't', NULL),
(20190311, '2019-03-11', 2019, 3, 11, 1, 11, 'Monday', 'March', 'f', 'f', NULL),
(20190312, '2019-03-12', 2019, 3, 12, 1, 11, 'Tuesday', 'March', 'f', 'f', NULL),
(20190313, '2019-03-13', 2019, 3, 13, 1, 11, 'Wednesday', 'March', 'f', 'f', NULL),
(20190314, '2019-03-14', 2019, 3, 14, 1, 11, 'Thursday', 'March', 'f', 'f', NULL),
(20190315, '2019-03-15', 2019, 3, 15, 1, 11, 'Friday', 'March', 'f', 'f', NULL),
(20190316, '2019-03-16', 2019, 3, 16, 1, 11, 'Saturday', 'March', 'f', 't', NULL),
(20190317, '2019-03-17', 2019, 3, 17, 1, 11, 'Sunday', 'March', 'f', 't', NULL),
(20190318, '2019-03-18', 2019, 3, 18, 1, 12, 'Monday', 'March', 'f', 'f', NULL),
(20190319, '2019-03-19', 2019, 3, 19, 1, 12, 'Tuesday', 'March', 'f', 'f', NULL),
(20190320, '2019-03-20', 2019, 3, 20, 1, 12, 'Wednesday', 'March', 'f', 'f', NULL),
(20190321, '2019-03-21', 2019, 3, 21, 1, 12, 'Thursday', 'March', 'f', 'f', NULL),
(20190322, '2019-03-22', 2019, 3, 22, 1, 12, 'Friday', 'March', 'f', 'f', NULL),
(20190323, '2019-03-23', 2019, 3, 23, 1, 12, 'Saturday', 'March', 'f', 't', NULL),
(20190324, '2019-03-24', 2019, 3, 24, 1, 12, 'Sunday', 'March', 'f', 't', NULL),
(20190325, '2019-03-25', 2019, 3, 25, 1, 13, 'Monday', 'March', 'f', 'f', NULL),
(20190326, '2019-03-26', 2019, 3, 26, 1, 13, 'Tuesday', 'March', 'f', 'f', NULL),
(20190327, '2019-03-27', 2019, 3, 27, 1, 13, 'Wednesday', 'March', 'f', 'f', NULL),
(20190328, '2019-03-28', 2019, 3, 28, 1, 13, 'Thursday', 'March', 'f', 'f', NULL),
(20190329, '2019-03-29', 2019, 3, 29, 1, 13, 'Friday', 'March', 'f', 'f', NULL),
(20190330, '2019-03-30', 2019, 3, 30, 1, 13, 'Saturday', 'March', 'f', 't', NULL),
(20190331, '2019-03-31', 2019, 3, 31, 1, 13, 'Sunday', 'March', 'f', 't', NULL),
(20190401, '2019-04-01', 2019, 4, 1, 2, 14, 'Monday', 'April', 'f', 'f', NULL),
(20190402, '2019-04-02', 2019, 4, 2, 2, 14, 'Tuesday', 'April', 'f', 'f', NULL),
(20190403, '2019-04-03', 2019, 4, 3, 2, 14, 'Wednesday', 'April', 'f', 'f', NULL),
(20190404, '2019-04-04', 2019, 4, 4, 2, 14, 'Thursday', 'April', 'f', 'f', NULL),
(20190405, '2019-04-05', 2019, 4, 5, 2, 14, 'Friday', 'April', 'f', 'f', NULL),
(20190406, '2019-04-06', 2019, 4, 6, 2, 14, 'Saturday', 'April', 'f', 't', NULL),
(20190407, '2019-04-07', 2019, 4, 7, 2, 14, 'Sunday', 'April', 'f', 't', NULL),
(20190408, '2019-04-08', 2019, 4, 8, 2, 15, 'Monday', 'April', 'f', 'f', NULL),
(20190409, '2019-04-09', 2019, 4, 9, 2, 15, 'Tuesday', 'April', 'f', 'f', NULL),
(20190410, '2019-04-10', 2019, 4, 10, 2, 15, 'Wednesday', 'April', 'f', 'f', NULL),
(20190411, '2019-04-11', 2019, 4, 11, 2, 15, 'Thursday', 'April', 'f', 'f', NULL),
(20190412, '2019-04-12', 2019, 4, 12, 2, 15, 'Friday', 'April', 'f', 'f', NULL),
(20190413, '2019-04-13', 2019, 4, 13, 2, 15, 'Saturday', 'April', 'f', 't', NULL),
(20190414, '2019-04-14', 2019, 4, 14, 2, 15, 'Sunday', 'April', 'f', 't', NULL),
(20190415, '2019-04-15', 2019, 4, 15, 2, 16, 'Monday', 'April', 'f', 'f', NULL),
(20190416, '2019-04-16', 2019, 4, 16, 2, 16, 'Tuesday', 'April', 'f', 'f', NULL),
(20190417, '2019-04-17', 2019, 4, 17, 2, 16, 'Wednesday', 'April', 'f', 'f', NULL),
(20190418, '2019-04-18', 2019, 4, 18, 2, 16, 'Thursday', 'April', 'f', 'f', NULL),
(20190419, '2019-04-19', 2019, 4, 19, 2, 16, 'Friday', 'April', 'f', 'f', NULL),
(20190420, '2019-04-20', 2019, 4, 20, 2, 16, 'Saturday', 'April', 'f', 't', NULL),
(20190421, '2019-04-21', 2019, 4, 21, 2, 16, 'Sunday', 'April', 'f', 't', NULL),
(20190422, '2019-04-22', 2019, 4, 22, 2, 17, 'Monday', 'April', 'f', 'f', NULL),
(20190423, '2019-04-23', 2019, 4, 23, 2, 17, 'Tuesday', 'April', 'f', 'f', NULL),
(20190424, '2019-04-24', 2019, 4, 24, 2, 17, 'Wednesday', 'April', 'f', 'f', NULL),
(20190425, '2019-04-25', 2019, 4, 25, 2, 17, 'Thursday', 'April', 'f', 'f', NULL),
(20190426, '2019-04-26', 2019, 4, 26, 2, 17, 'Friday', 'April', 'f', 'f', NULL),
(20190427, '2019-04-27', 2019, 4, 27, 2, 17, 'Saturday', 'April', 'f', 't', NULL),
(20190428, '2019-04-28', 2019, 4, 28, 2, 17, 'Sunday', 'April', 'f', 't', NULL),
(20190429, '2019-04-29', 2019, 4, 29, 2, 18, 'Monday', 'April', 'f', 'f', NULL),
(20190430, '2019-04-30', 2019, 4, 30, 2, 18, 'Tuesday', 'April', 'f', 'f', NULL),
(20190501, '2019-05-01', 2019, 5, 1, 2, 18, 'Wednesday', 'May', 'f', 'f', NULL),
(20190502, '2019-05-02', 2019, 5, 2, 2, 18, 'Thursday', 'May', 'f', 'f', NULL),
(20190503, '2019-05-03', 2019, 5, 3, 2, 18, 'Friday', 'May', 'f', 'f', NULL),
(20190504, '2019-05-04', 2019, 5, 4, 2, 18, 'Saturday', 'May', 'f', 't', NULL),
(20190505, '2019-05-05', 2019, 5, 5, 2, 18, 'Sunday', 'May', 'f', 't', NULL),
(20190506, '2019-05-06', 2019, 5, 6, 2, 19, 'Monday', 'May', 'f', 'f', NULL),
(20190507, '2019-05-07', 2019, 5, 7, 2, 19, 'Tuesday', 'May', 'f', 'f', NULL),
(20190508, '2019-05-08', 2019, 5, 8, 2, 19, 'Wednesday', 'May', 'f', 'f', NULL),
(20190509, '2019-05-09', 2019, 5, 9, 2, 19, 'Thursday', 'May', 'f', 'f', NULL),
(20190510, '2019-05-10', 2019, 5, 10, 2, 19, 'Friday', 'May', 'f', 'f', NULL),
(20190511, '2019-05-11', 2019, 5, 11, 2, 19, 'Saturday', 'May', 'f', 't', NULL),
(20190512, '2019-05-12', 2019, 5, 12, 2, 19, 'Sunday', 'May', 'f', 't', NULL),
(20190513, '2019-05-13', 2019, 5, 13, 2, 20, 'Monday', 'May', 'f', 'f', NULL),
(20190514, '2019-05-14', 2019, 5, 14, 2, 20, 'Tuesday', 'May', 'f', 'f', NULL),
(20190515, '2019-05-15', 2019, 5, 15, 2, 20, 'Wednesday', 'May', 'f', 'f', NULL),
(20190516, '2019-05-16', 2019, 5, 16, 2, 20, 'Thursday', 'May', 'f', 'f', NULL),
(20190517, '2019-05-17', 2019, 5, 17, 2, 20, 'Friday', 'May', 'f', 'f', NULL),
(20190518, '2019-05-18', 2019, 5, 18, 2, 20, 'Saturday', 'May', 'f', 't', NULL),
(20190519, '2019-05-19', 2019, 5, 19, 2, 20, 'Sunday', 'May', 'f', 't', NULL),
(20190520, '2019-05-20', 2019, 5, 20, 2, 21, 'Monday', 'May', 'f', 'f', NULL),
(20190521, '2019-05-21', 2019, 5, 21, 2, 21, 'Tuesday', 'May', 'f', 'f', NULL),
(20190522, '2019-05-22', 2019, 5, 22, 2, 21, 'Wednesday', 'May', 'f', 'f', NULL),
(20190523, '2019-05-23', 2019, 5, 23, 2, 21, 'Thursday', 'May', 'f', 'f', NULL),
(20190524, '2019-05-24', 2019, 5, 24, 2, 21, 'Friday', 'May', 'f', 'f', NULL),
(20190525, '2019-05-25', 2019, 5, 25, 2, 21, 'Saturday', 'May', 'f', 't', NULL),
(20190526, '2019-05-26', 2019, 5, 26, 2, 21, 'Sunday', 'May', 'f', 't', NULL),
(20190527, '2019-05-27', 2019, 5, 27, 2, 22, 'Monday', 'May', 'f', 'f', NULL),
(20190528, '2019-05-28', 2019, 5, 28, 2, 22, 'Tuesday', 'May', 'f', 'f', NULL),
(20190529, '2019-05-29', 2019, 5, 29, 2, 22, 'Wednesday', 'May', 'f', 'f', NULL),
(20190530, '2019-05-30', 2019, 5, 30, 2, 22, 'Thursday', 'May', 'f', 'f', NULL),
(20190531, '2019-05-31', 2019, 5, 31, 2, 22, 'Friday', 'May', 'f', 'f', NULL),
(20190601, '2019-06-01', 2019, 6, 1, 2, 22, 'Saturday', 'June', 'f', 't', NULL),
(20190602, '2019-06-02', 2019, 6, 2, 2, 22, 'Sunday', 'June', 'f', 't', NULL),
(20190603, '2019-06-03', 2019, 6, 3, 2, 23, 'Monday', 'June', 'f', 'f', NULL),
(20190604, '2019-06-04', 2019, 6, 4, 2, 23, 'Tuesday', 'June', 'f', 'f', NULL),
(20190605, '2019-06-05', 2019, 6, 5, 2, 23, 'Wednesday', 'June', 'f', 'f', NULL),
(20190606, '2019-06-06', 2019, 6, 6, 2, 23, 'Thursday', 'June', 'f', 'f', NULL),
(20190607, '2019-06-07', 2019, 6, 7, 2, 23, 'Friday', 'June', 'f', 'f', NULL),
(20190608, '2019-06-08', 2019, 6, 8, 2, 23, 'Saturday', 'June', 'f', 't', NULL),
(20190609, '2019-06-09', 2019, 6, 9, 2, 23, 'Sunday', 'June', 'f', 't', NULL),
(20190610, '2019-06-10', 2019, 6, 10, 2, 24, 'Monday', 'June', 'f', 'f', NULL),
(20190611, '2019-06-11', 2019, 6, 11, 2, 24, 'Tuesday', 'June', 'f', 'f', NULL),
(20190612, '2019-06-12', 2019, 6, 12, 2, 24, 'Wednesday', 'June', 'f', 'f', NULL),
(20190613, '2019-06-13', 2019, 6, 13, 2, 24, 'Thursday', 'June', 'f', 'f', NULL),
(20190614, '2019-06-14', 2019, 6, 14, 2, 24, 'Friday', 'June', 'f', 'f', NULL),
(20190615, '2019-06-15', 2019, 6, 15, 2, 24, 'Saturday', 'June', 'f', 't', NULL),
(20190616, '2019-06-16', 2019, 6, 16, 2, 24, 'Sunday', 'June', 'f', 't', NULL),
(20190617, '2019-06-17', 2019, 6, 17, 2, 25, 'Monday', 'June', 'f', 'f', NULL),
(20190618, '2019-06-18', 2019, 6, 18, 2, 25, 'Tuesday', 'June', 'f', 'f', NULL),
(20190619, '2019-06-19', 2019, 6, 19, 2, 25, 'Wednesday', 'June', 'f', 'f', NULL),
(20190620, '2019-06-20', 2019, 6, 20, 2, 25, 'Thursday', 'June', 'f', 'f', NULL),
(20190621, '2019-06-21', 2019, 6, 21, 2, 25, 'Friday', 'June', 'f', 'f', NULL),
(20190622, '2019-06-22', 2019, 6, 22, 2, 25, 'Saturday', 'June', 'f', 't', NULL),
(20190623, '2019-06-23', 2019, 6, 23, 2, 25, 'Sunday', 'June', 'f', 't', NULL),
(20190624, '2019-06-24', 2019, 6, 24, 2, 26, 'Monday', 'June', 'f', 'f', NULL),
(20190625, '2019-06-25', 2019, 6, 25, 2, 26, 'Tuesday', 'June', 'f', 'f', NULL),
(20190626, '2019-06-26', 2019, 6, 26, 2, 26, 'Wednesday', 'June', 'f', 'f', NULL),
(20190627, '2019-06-27', 2019, 6, 27, 2, 26, 'Thursday', 'June', 'f', 'f', NULL),
(20190628, '2019-06-28', 2019, 6, 28, 2, 26, 'Friday', 'June', 'f', 'f', NULL),
(20190629, '2019-06-29', 2019, 6, 29, 2, 26, 'Saturday', 'June', 'f', 't', NULL),
(20190630, '2019-06-30', 2019, 6, 30, 2, 26, 'Sunday', 'June', 'f', 't', NULL),
(20190701, '2019-07-01', 2019, 7, 1, 3, 27, 'Monday', 'July', 'f', 'f', NULL),
(20190702, '2019-07-02', 2019, 7, 2, 3, 27, 'Tuesday', 'July', 'f', 'f', NULL),
(20190703, '2019-07-03', 2019, 7, 3, 3, 27, 'Wednesday', 'July', 'f', 'f', NULL),
(20190704, '2019-07-04', 2019, 7, 4, 3, 27, 'Thursday', 'July', 'f', 'f', NULL),
(20190705, '2019-07-05', 2019, 7, 5, 3, 27, 'Friday', 'July', 'f', 'f', NULL),
(20190706, '2019-07-06', 2019, 7, 6, 3, 27, 'Saturday', 'July', 'f', 't', NULL),
(20190707, '2019-07-07', 2019, 7, 7, 3, 27, 'Sunday', 'July', 'f', 't', NULL),
(20190708, '2019-07-08', 2019, 7, 8, 3, 28, 'Monday', 'July', 'f', 'f', NULL),
(20190709, '2019-07-09', 2019, 7, 9, 3, 28, 'Tuesday', 'July', 'f', 'f', NULL),
(20190710, '2019-07-10', 2019, 7, 10, 3, 28, 'Wednesday', 'July', 'f', 'f', NULL),
(20190711, '2019-07-11', 2019, 7, 11, 3, 28, 'Thursday', 'July', 'f', 'f', NULL),
(20190712, '2019-07-12', 2019, 7, 12, 3, 28, 'Friday', 'July', 'f', 'f', NULL),
(20190713, '2019-07-13', 2019, 7, 13, 3, 28, 'Saturday', 'July', 'f', 't', NULL),
(20190714, '2019-07-14', 2019, 7, 14, 3, 28, 'Sunday', 'July', 'f', 't', NULL),
(20190715, '2019-07-15', 2019, 7, 15, 3, 29, 'Monday', 'July', 'f', 'f', NULL),
(20190716, '2019-07-16', 2019, 7, 16, 3, 29, 'Tuesday', 'July', 'f', 'f', NULL),
(20190717, '2019-07-17', 2019, 7, 17, 3, 29, 'Wednesday', 'July', 'f', 'f', NULL),
(20190718, '2019-07-18', 2019, 7, 18, 3, 29, 'Thursday', 'July', 'f', 'f', NULL),
(20190719, '2019-07-19', 2019, 7, 19, 3, 29, 'Friday', 'July', 'f', 'f', NULL),
(20190720, '2019-07-20', 2019, 7, 20, 3, 29, 'Saturday', 'July', 'f', 't', NULL),
(20190721, '2019-07-21', 2019, 7, 21, 3, 29, 'Sunday', 'July', 'f', 't', NULL),
(20190722, '2019-07-22', 2019, 7, 22, 3, 30, 'Monday', 'July', 'f', 'f', NULL),
(20190723, '2019-07-23', 2019, 7, 23, 3, 30, 'Tuesday', 'July', 'f', 'f', NULL),
(20190724, '2019-07-24', 2019, 7, 24, 3, 30, 'Wednesday', 'July', 'f', 'f', NULL),
(20190725, '2019-07-25', 2019, 7, 25, 3, 30, 'Thursday', 'July', 'f', 'f', NULL),
(20190726, '2019-07-26', 2019, 7, 26, 3, 30, 'Friday', 'July', 'f', 'f', NULL),
(20190727, '2019-07-27', 2019, 7, 27, 3, 30, 'Saturday', 'July', 'f', 't', NULL),
(20190728, '2019-07-28', 2019, 7, 28, 3, 30, 'Sunday', 'July', 'f', 't', NULL),
(20190729, '2019-07-29', 2019, 7, 29, 3, 31, 'Monday', 'July', 'f', 'f', NULL),
(20190730, '2019-07-30', 2019, 7, 30, 3, 31, 'Tuesday', 'July', 'f', 'f', NULL),
(20190731, '2019-07-31', 2019, 7, 31, 3, 31, 'Wednesday', 'July', 'f', 'f', NULL),
(20190801, '2019-08-01', 2019, 8, 1, 3, 31, 'Thursday', 'August', 'f', 'f', NULL),
(20190802, '2019-08-02', 2019, 8, 2, 3, 31, 'Friday', 'August', 'f', 'f', NULL),
(20190803, '2019-08-03', 2019, 8, 3, 3, 31, 'Saturday', 'August', 'f', 't', NULL),
(20190804, '2019-08-04', 2019, 8, 4, 3, 31, 'Sunday', 'August', 'f', 't', NULL),
(20190805, '2019-08-05', 2019, 8, 5, 3, 32, 'Monday', 'August', 'f', 'f', NULL),
(20190806, '2019-08-06', 2019, 8, 6, 3, 32, 'Tuesday', 'August', 'f', 'f', NULL),
(20190807, '2019-08-07', 2019, 8, 7, 3, 32, 'Wednesday', 'August', 'f', 'f', NULL),
(20190808, '2019-08-08', 2019, 8, 8, 3, 32, 'Thursday', 'August', 'f', 'f', NULL),
(20190809, '2019-08-09', 2019, 8, 9, 3, 32, 'Friday', 'August', 'f', 'f', NULL),
(20190810, '2019-08-10', 2019, 8, 10, 3, 32, 'Saturday', 'August', 'f', 't', NULL),
(20190811, '2019-08-11', 2019, 8, 11, 3, 32, 'Sunday', 'August', 'f', 't', NULL),
(20190812, '2019-08-12', 2019, 8, 12, 3, 33, 'Monday', 'August', 'f', 'f', NULL),
(20190813, '2019-08-13', 2019, 8, 13, 3, 33, 'Tuesday', 'August', 'f', 'f', NULL),
(20190814, '2019-08-14', 2019, 8, 14, 3, 33, 'Wednesday', 'August', 'f', 'f', NULL),
(20190815, '2019-08-15', 2019, 8, 15, 3, 33, 'Thursday', 'August', 'f', 'f', NULL),
(20190816, '2019-08-16', 2019, 8, 16, 3, 33, 'Friday', 'August', 'f', 'f', NULL),
(20190817, '2019-08-17', 2019, 8, 17, 3, 33, 'Saturday', 'August', 'f', 't', NULL),
(20190818, '2019-08-18', 2019, 8, 18, 3, 33, 'Sunday', 'August', 'f', 't', NULL),
(20190819, '2019-08-19', 2019, 8, 19, 3, 34, 'Monday', 'August', 'f', 'f', NULL),
(20190820, '2019-08-20', 2019, 8, 20, 3, 34, 'Tuesday', 'August', 'f', 'f', NULL),
(20190821, '2019-08-21', 2019, 8, 21, 3, 34, 'Wednesday', 'August', 'f', 'f', NULL),
(20190822, '2019-08-22', 2019, 8, 22, 3, 34, 'Thursday', 'August', 'f', 'f', NULL),
(20190823, '2019-08-23', 2019, 8, 23, 3, 34, 'Friday', 'August', 'f', 'f', NULL),
(20190824, '2019-08-24', 2019, 8, 24, 3, 34, 'Saturday', 'August', 'f', 't', NULL),
(20190825, '2019-08-25', 2019, 8, 25, 3, 34, 'Sunday', 'August', 'f', 't', NULL),
(20190826, '2019-08-26', 2019, 8, 26, 3, 35, 'Monday', 'August', 'f', 'f', NULL),
(20190827, '2019-08-27', 2019, 8, 27, 3, 35, 'Tuesday', 'August', 'f', 'f', NULL),
(20190828, '2019-08-28', 2019, 8, 28, 3, 35, 'Wednesday', 'August', 'f', 'f', NULL),
(20190829, '2019-08-29', 2019, 8, 29, 3, 35, 'Thursday', 'August', 'f', 'f', NULL),
(20190830, '2019-08-30', 2019, 8, 30, 3, 35, 'Friday', 'August', 'f', 'f', NULL),
(20190831, '2019-08-31', 2019, 8, 31, 3, 35, 'Saturday', 'August', 'f', 't', NULL),
(20190901, '2019-09-01', 2019, 9, 1, 3, 35, 'Sunday', 'September', 'f', 't', NULL),
(20190902, '2019-09-02', 2019, 9, 2, 3, 36, 'Monday', 'September', 'f', 'f', NULL),
(20190903, '2019-09-03', 2019, 9, 3, 3, 36, 'Tuesday', 'September', 'f', 'f', NULL),
(20190904, '2019-09-04', 2019, 9, 4, 3, 36, 'Wednesday', 'September', 'f', 'f', NULL),
(20190905, '2019-09-05', 2019, 9, 5, 3, 36, 'Thursday', 'September', 'f', 'f', NULL),
(20190906, '2019-09-06', 2019, 9, 6, 3, 36, 'Friday', 'September', 'f', 'f', NULL),
(20190907, '2019-09-07', 2019, 9, 7, 3, 36, 'Saturday', 'September', 'f', 't', NULL),
(20190908, '2019-09-08', 2019, 9, 8, 3, 36, 'Sunday', 'September', 'f', 't', NULL),
(20190909, '2019-09-09', 2019, 9, 9, 3, 37, 'Monday', 'September', 'f', 'f', NULL),
(20190910, '2019-09-10', 2019, 9, 10, 3, 37, 'Tuesday', 'September', 'f', 'f', NULL);
INSERT INTO `time_dimension` (`id`, `db_date`, `year`, `month`, `day`, `quarter`, `week`, `day_name`, `month_name`, `holiday_flag`, `weekend_flag`, `event`) VALUES
(20190911, '2019-09-11', 2019, 9, 11, 3, 37, 'Wednesday', 'September', 'f', 'f', NULL),
(20190912, '2019-09-12', 2019, 9, 12, 3, 37, 'Thursday', 'September', 'f', 'f', NULL),
(20190913, '2019-09-13', 2019, 9, 13, 3, 37, 'Friday', 'September', 'f', 'f', NULL),
(20190914, '2019-09-14', 2019, 9, 14, 3, 37, 'Saturday', 'September', 'f', 't', NULL),
(20190915, '2019-09-15', 2019, 9, 15, 3, 37, 'Sunday', 'September', 'f', 't', NULL),
(20190916, '2019-09-16', 2019, 9, 16, 3, 38, 'Monday', 'September', 'f', 'f', NULL),
(20190917, '2019-09-17', 2019, 9, 17, 3, 38, 'Tuesday', 'September', 'f', 'f', NULL),
(20190918, '2019-09-18', 2019, 9, 18, 3, 38, 'Wednesday', 'September', 'f', 'f', NULL),
(20190919, '2019-09-19', 2019, 9, 19, 3, 38, 'Thursday', 'September', 'f', 'f', NULL),
(20190920, '2019-09-20', 2019, 9, 20, 3, 38, 'Friday', 'September', 'f', 'f', NULL),
(20190921, '2019-09-21', 2019, 9, 21, 3, 38, 'Saturday', 'September', 'f', 't', NULL),
(20190922, '2019-09-22', 2019, 9, 22, 3, 38, 'Sunday', 'September', 'f', 't', NULL),
(20190923, '2019-09-23', 2019, 9, 23, 3, 39, 'Monday', 'September', 'f', 'f', NULL),
(20190924, '2019-09-24', 2019, 9, 24, 3, 39, 'Tuesday', 'September', 'f', 'f', NULL),
(20190925, '2019-09-25', 2019, 9, 25, 3, 39, 'Wednesday', 'September', 'f', 'f', NULL),
(20190926, '2019-09-26', 2019, 9, 26, 3, 39, 'Thursday', 'September', 'f', 'f', NULL),
(20190927, '2019-09-27', 2019, 9, 27, 3, 39, 'Friday', 'September', 'f', 'f', NULL),
(20190928, '2019-09-28', 2019, 9, 28, 3, 39, 'Saturday', 'September', 'f', 't', NULL),
(20190929, '2019-09-29', 2019, 9, 29, 3, 39, 'Sunday', 'September', 'f', 't', NULL),
(20190930, '2019-09-30', 2019, 9, 30, 3, 40, 'Monday', 'September', 'f', 'f', NULL),
(20191001, '2019-10-01', 2019, 10, 1, 4, 40, 'Tuesday', 'October', 'f', 'f', NULL),
(20191002, '2019-10-02', 2019, 10, 2, 4, 40, 'Wednesday', 'October', 'f', 'f', NULL),
(20191003, '2019-10-03', 2019, 10, 3, 4, 40, 'Thursday', 'October', 'f', 'f', NULL),
(20191004, '2019-10-04', 2019, 10, 4, 4, 40, 'Friday', 'October', 'f', 'f', NULL),
(20191005, '2019-10-05', 2019, 10, 5, 4, 40, 'Saturday', 'October', 'f', 't', NULL),
(20191006, '2019-10-06', 2019, 10, 6, 4, 40, 'Sunday', 'October', 'f', 't', NULL),
(20191007, '2019-10-07', 2019, 10, 7, 4, 41, 'Monday', 'October', 'f', 'f', NULL),
(20191008, '2019-10-08', 2019, 10, 8, 4, 41, 'Tuesday', 'October', 'f', 'f', NULL),
(20191009, '2019-10-09', 2019, 10, 9, 4, 41, 'Wednesday', 'October', 'f', 'f', NULL),
(20191010, '2019-10-10', 2019, 10, 10, 4, 41, 'Thursday', 'October', 'f', 'f', NULL),
(20191011, '2019-10-11', 2019, 10, 11, 4, 41, 'Friday', 'October', 'f', 'f', NULL),
(20191012, '2019-10-12', 2019, 10, 12, 4, 41, 'Saturday', 'October', 'f', 't', NULL),
(20191013, '2019-10-13', 2019, 10, 13, 4, 41, 'Sunday', 'October', 'f', 't', NULL),
(20191014, '2019-10-14', 2019, 10, 14, 4, 42, 'Monday', 'October', 'f', 'f', NULL),
(20191015, '2019-10-15', 2019, 10, 15, 4, 42, 'Tuesday', 'October', 'f', 'f', NULL),
(20191016, '2019-10-16', 2019, 10, 16, 4, 42, 'Wednesday', 'October', 'f', 'f', NULL),
(20191017, '2019-10-17', 2019, 10, 17, 4, 42, 'Thursday', 'October', 'f', 'f', NULL),
(20191018, '2019-10-18', 2019, 10, 18, 4, 42, 'Friday', 'October', 'f', 'f', NULL),
(20191019, '2019-10-19', 2019, 10, 19, 4, 42, 'Saturday', 'October', 'f', 't', NULL),
(20191020, '2019-10-20', 2019, 10, 20, 4, 42, 'Sunday', 'October', 'f', 't', NULL),
(20191021, '2019-10-21', 2019, 10, 21, 4, 43, 'Monday', 'October', 'f', 'f', NULL),
(20191022, '2019-10-22', 2019, 10, 22, 4, 43, 'Tuesday', 'October', 'f', 'f', NULL),
(20191023, '2019-10-23', 2019, 10, 23, 4, 43, 'Wednesday', 'October', 'f', 'f', NULL),
(20191024, '2019-10-24', 2019, 10, 24, 4, 43, 'Thursday', 'October', 'f', 'f', NULL),
(20191025, '2019-10-25', 2019, 10, 25, 4, 43, 'Friday', 'October', 'f', 'f', NULL),
(20191026, '2019-10-26', 2019, 10, 26, 4, 43, 'Saturday', 'October', 'f', 't', NULL),
(20191027, '2019-10-27', 2019, 10, 27, 4, 43, 'Sunday', 'October', 'f', 't', NULL),
(20191028, '2019-10-28', 2019, 10, 28, 4, 44, 'Monday', 'October', 'f', 'f', NULL),
(20191029, '2019-10-29', 2019, 10, 29, 4, 44, 'Tuesday', 'October', 'f', 'f', NULL),
(20191030, '2019-10-30', 2019, 10, 30, 4, 44, 'Wednesday', 'October', 'f', 'f', NULL),
(20191031, '2019-10-31', 2019, 10, 31, 4, 44, 'Thursday', 'October', 'f', 'f', NULL),
(20191101, '2019-11-01', 2019, 11, 1, 4, 44, 'Friday', 'November', 'f', 'f', NULL),
(20191102, '2019-11-02', 2019, 11, 2, 4, 44, 'Saturday', 'November', 'f', 't', NULL),
(20191103, '2019-11-03', 2019, 11, 3, 4, 44, 'Sunday', 'November', 'f', 't', NULL),
(20191104, '2019-11-04', 2019, 11, 4, 4, 45, 'Monday', 'November', 'f', 'f', NULL),
(20191105, '2019-11-05', 2019, 11, 5, 4, 45, 'Tuesday', 'November', 'f', 'f', NULL),
(20191106, '2019-11-06', 2019, 11, 6, 4, 45, 'Wednesday', 'November', 'f', 'f', NULL),
(20191107, '2019-11-07', 2019, 11, 7, 4, 45, 'Thursday', 'November', 'f', 'f', NULL),
(20191108, '2019-11-08', 2019, 11, 8, 4, 45, 'Friday', 'November', 'f', 'f', NULL),
(20191109, '2019-11-09', 2019, 11, 9, 4, 45, 'Saturday', 'November', 'f', 't', NULL),
(20191110, '2019-11-10', 2019, 11, 10, 4, 45, 'Sunday', 'November', 'f', 't', NULL),
(20191111, '2019-11-11', 2019, 11, 11, 4, 46, 'Monday', 'November', 'f', 'f', NULL),
(20191112, '2019-11-12', 2019, 11, 12, 4, 46, 'Tuesday', 'November', 'f', 'f', NULL),
(20191113, '2019-11-13', 2019, 11, 13, 4, 46, 'Wednesday', 'November', 'f', 'f', NULL),
(20191114, '2019-11-14', 2019, 11, 14, 4, 46, 'Thursday', 'November', 'f', 'f', NULL),
(20191115, '2019-11-15', 2019, 11, 15, 4, 46, 'Friday', 'November', 'f', 'f', NULL),
(20191116, '2019-11-16', 2019, 11, 16, 4, 46, 'Saturday', 'November', 'f', 't', NULL),
(20191117, '2019-11-17', 2019, 11, 17, 4, 46, 'Sunday', 'November', 'f', 't', NULL),
(20191118, '2019-11-18', 2019, 11, 18, 4, 47, 'Monday', 'November', 'f', 'f', NULL),
(20191119, '2019-11-19', 2019, 11, 19, 4, 47, 'Tuesday', 'November', 'f', 'f', NULL),
(20191120, '2019-11-20', 2019, 11, 20, 4, 47, 'Wednesday', 'November', 'f', 'f', NULL),
(20191121, '2019-11-21', 2019, 11, 21, 4, 47, 'Thursday', 'November', 'f', 'f', NULL),
(20191122, '2019-11-22', 2019, 11, 22, 4, 47, 'Friday', 'November', 'f', 'f', NULL),
(20191123, '2019-11-23', 2019, 11, 23, 4, 47, 'Saturday', 'November', 'f', 't', NULL),
(20191124, '2019-11-24', 2019, 11, 24, 4, 47, 'Sunday', 'November', 'f', 't', NULL),
(20191125, '2019-11-25', 2019, 11, 25, 4, 48, 'Monday', 'November', 'f', 'f', NULL),
(20191126, '2019-11-26', 2019, 11, 26, 4, 48, 'Tuesday', 'November', 'f', 'f', NULL),
(20191127, '2019-11-27', 2019, 11, 27, 4, 48, 'Wednesday', 'November', 'f', 'f', NULL),
(20191128, '2019-11-28', 2019, 11, 28, 4, 48, 'Thursday', 'November', 'f', 'f', NULL),
(20191129, '2019-11-29', 2019, 11, 29, 4, 48, 'Friday', 'November', 'f', 'f', NULL),
(20191130, '2019-11-30', 2019, 11, 30, 4, 48, 'Saturday', 'November', 'f', 't', NULL),
(20191201, '2019-12-01', 2019, 12, 1, 4, 48, 'Sunday', 'December', 'f', 't', NULL),
(20191202, '2019-12-02', 2019, 12, 2, 4, 49, 'Monday', 'December', 'f', 'f', NULL),
(20191203, '2019-12-03', 2019, 12, 3, 4, 49, 'Tuesday', 'December', 'f', 'f', NULL),
(20191204, '2019-12-04', 2019, 12, 4, 4, 49, 'Wednesday', 'December', 'f', 'f', NULL),
(20191205, '2019-12-05', 2019, 12, 5, 4, 49, 'Thursday', 'December', 'f', 'f', NULL),
(20191206, '2019-12-06', 2019, 12, 6, 4, 49, 'Friday', 'December', 'f', 'f', NULL),
(20191207, '2019-12-07', 2019, 12, 7, 4, 49, 'Saturday', 'December', 'f', 't', NULL),
(20191208, '2019-12-08', 2019, 12, 8, 4, 49, 'Sunday', 'December', 'f', 't', NULL),
(20191209, '2019-12-09', 2019, 12, 9, 4, 50, 'Monday', 'December', 'f', 'f', NULL),
(20191210, '2019-12-10', 2019, 12, 10, 4, 50, 'Tuesday', 'December', 'f', 'f', NULL),
(20191211, '2019-12-11', 2019, 12, 11, 4, 50, 'Wednesday', 'December', 'f', 'f', NULL),
(20191212, '2019-12-12', 2019, 12, 12, 4, 50, 'Thursday', 'December', 'f', 'f', NULL),
(20191213, '2019-12-13', 2019, 12, 13, 4, 50, 'Friday', 'December', 'f', 'f', NULL),
(20191214, '2019-12-14', 2019, 12, 14, 4, 50, 'Saturday', 'December', 'f', 't', NULL),
(20191215, '2019-12-15', 2019, 12, 15, 4, 50, 'Sunday', 'December', 'f', 't', NULL),
(20191216, '2019-12-16', 2019, 12, 16, 4, 51, 'Monday', 'December', 'f', 'f', NULL),
(20191217, '2019-12-17', 2019, 12, 17, 4, 51, 'Tuesday', 'December', 'f', 'f', NULL),
(20191218, '2019-12-18', 2019, 12, 18, 4, 51, 'Wednesday', 'December', 'f', 'f', NULL),
(20191219, '2019-12-19', 2019, 12, 19, 4, 51, 'Thursday', 'December', 'f', 'f', NULL),
(20191220, '2019-12-20', 2019, 12, 20, 4, 51, 'Friday', 'December', 'f', 'f', NULL),
(20191221, '2019-12-21', 2019, 12, 21, 4, 51, 'Saturday', 'December', 'f', 't', NULL),
(20191222, '2019-12-22', 2019, 12, 22, 4, 51, 'Sunday', 'December', 'f', 't', NULL),
(20191223, '2019-12-23', 2019, 12, 23, 4, 52, 'Monday', 'December', 'f', 'f', NULL),
(20191224, '2019-12-24', 2019, 12, 24, 4, 52, 'Tuesday', 'December', 'f', 'f', NULL),
(20191225, '2019-12-25', 2019, 12, 25, 4, 52, 'Wednesday', 'December', 'f', 'f', NULL),
(20191226, '2019-12-26', 2019, 12, 26, 4, 52, 'Thursday', 'December', 'f', 'f', NULL),
(20191227, '2019-12-27', 2019, 12, 27, 4, 52, 'Friday', 'December', 'f', 'f', NULL),
(20191228, '2019-12-28', 2019, 12, 28, 4, 52, 'Saturday', 'December', 'f', 't', NULL),
(20191229, '2019-12-29', 2019, 12, 29, 4, 52, 'Sunday', 'December', 'f', 't', NULL),
(20191230, '2019-12-30', 2019, 12, 30, 4, 1, 'Monday', 'December', 'f', 'f', NULL),
(20200101, '2020-01-01', 2020, 1, 1, 1, 1, 'Wednesday', 'January', 'f', 'f', NULL),
(20200102, '2020-01-02', 2020, 1, 2, 1, 1, 'Thursday', 'January', 'f', 'f', NULL),
(20200103, '2020-01-03', 2020, 1, 3, 1, 1, 'Friday', 'January', 'f', 'f', NULL),
(20200104, '2020-01-04', 2020, 1, 4, 1, 1, 'Saturday', 'January', 'f', 't', NULL),
(20200105, '2020-01-05', 2020, 1, 5, 1, 1, 'Sunday', 'January', 'f', 't', NULL),
(20200106, '2020-01-06', 2020, 1, 6, 1, 2, 'Monday', 'January', 'f', 'f', NULL),
(20200107, '2020-01-07', 2020, 1, 7, 1, 2, 'Tuesday', 'January', 'f', 'f', NULL),
(20200108, '2020-01-08', 2020, 1, 8, 1, 2, 'Wednesday', 'January', 'f', 'f', NULL),
(20200109, '2020-01-09', 2020, 1, 9, 1, 2, 'Thursday', 'January', 'f', 'f', NULL),
(20200110, '2020-01-10', 2020, 1, 10, 1, 2, 'Friday', 'January', 'f', 'f', NULL),
(20200111, '2020-01-11', 2020, 1, 11, 1, 2, 'Saturday', 'January', 'f', 't', NULL),
(20200112, '2020-01-12', 2020, 1, 12, 1, 2, 'Sunday', 'January', 'f', 't', NULL),
(20200113, '2020-01-13', 2020, 1, 13, 1, 3, 'Monday', 'January', 'f', 'f', NULL),
(20200114, '2020-01-14', 2020, 1, 14, 1, 3, 'Tuesday', 'January', 'f', 'f', NULL),
(20200115, '2020-01-15', 2020, 1, 15, 1, 3, 'Wednesday', 'January', 'f', 'f', NULL),
(20200116, '2020-01-16', 2020, 1, 16, 1, 3, 'Thursday', 'January', 'f', 'f', NULL),
(20200117, '2020-01-17', 2020, 1, 17, 1, 3, 'Friday', 'January', 'f', 'f', NULL),
(20200118, '2020-01-18', 2020, 1, 18, 1, 3, 'Saturday', 'January', 'f', 't', NULL),
(20200119, '2020-01-19', 2020, 1, 19, 1, 3, 'Sunday', 'January', 'f', 't', NULL),
(20200120, '2020-01-20', 2020, 1, 20, 1, 4, 'Monday', 'January', 'f', 'f', NULL),
(20200121, '2020-01-21', 2020, 1, 21, 1, 4, 'Tuesday', 'January', 'f', 'f', NULL),
(20200122, '2020-01-22', 2020, 1, 22, 1, 4, 'Wednesday', 'January', 'f', 'f', NULL),
(20200123, '2020-01-23', 2020, 1, 23, 1, 4, 'Thursday', 'January', 'f', 'f', NULL),
(20200124, '2020-01-24', 2020, 1, 24, 1, 4, 'Friday', 'January', 'f', 'f', NULL),
(20200125, '2020-01-25', 2020, 1, 25, 1, 4, 'Saturday', 'January', 'f', 't', NULL),
(20200126, '2020-01-26', 2020, 1, 26, 1, 4, 'Sunday', 'January', 'f', 't', NULL),
(20200127, '2020-01-27', 2020, 1, 27, 1, 5, 'Monday', 'January', 'f', 'f', NULL),
(20200128, '2020-01-28', 2020, 1, 28, 1, 5, 'Tuesday', 'January', 'f', 'f', NULL),
(20200129, '2020-01-29', 2020, 1, 29, 1, 5, 'Wednesday', 'January', 'f', 'f', NULL),
(20200130, '2020-01-30', 2020, 1, 30, 1, 5, 'Thursday', 'January', 'f', 'f', NULL),
(20200131, '2020-01-31', 2020, 1, 31, 1, 5, 'Friday', 'January', 'f', 'f', NULL),
(20200201, '2020-02-01', 2020, 2, 1, 1, 5, 'Saturday', 'February', 'f', 't', NULL),
(20200202, '2020-02-02', 2020, 2, 2, 1, 5, 'Sunday', 'February', 'f', 't', NULL),
(20200203, '2020-02-03', 2020, 2, 3, 1, 6, 'Monday', 'February', 'f', 'f', NULL),
(20200204, '2020-02-04', 2020, 2, 4, 1, 6, 'Tuesday', 'February', 'f', 'f', NULL),
(20200205, '2020-02-05', 2020, 2, 5, 1, 6, 'Wednesday', 'February', 'f', 'f', NULL),
(20200206, '2020-02-06', 2020, 2, 6, 1, 6, 'Thursday', 'February', 'f', 'f', NULL),
(20200207, '2020-02-07', 2020, 2, 7, 1, 6, 'Friday', 'February', 'f', 'f', NULL),
(20200208, '2020-02-08', 2020, 2, 8, 1, 6, 'Saturday', 'February', 'f', 't', NULL),
(20200209, '2020-02-09', 2020, 2, 9, 1, 6, 'Sunday', 'February', 'f', 't', NULL),
(20200210, '2020-02-10', 2020, 2, 10, 1, 7, 'Monday', 'February', 'f', 'f', NULL),
(20200211, '2020-02-11', 2020, 2, 11, 1, 7, 'Tuesday', 'February', 'f', 'f', NULL),
(20200212, '2020-02-12', 2020, 2, 12, 1, 7, 'Wednesday', 'February', 'f', 'f', NULL),
(20200213, '2020-02-13', 2020, 2, 13, 1, 7, 'Thursday', 'February', 'f', 'f', NULL),
(20200214, '2020-02-14', 2020, 2, 14, 1, 7, 'Friday', 'February', 'f', 'f', NULL),
(20200215, '2020-02-15', 2020, 2, 15, 1, 7, 'Saturday', 'February', 'f', 't', NULL),
(20200216, '2020-02-16', 2020, 2, 16, 1, 7, 'Sunday', 'February', 'f', 't', NULL),
(20200217, '2020-02-17', 2020, 2, 17, 1, 8, 'Monday', 'February', 'f', 'f', NULL),
(20200218, '2020-02-18', 2020, 2, 18, 1, 8, 'Tuesday', 'February', 'f', 'f', NULL),
(20200219, '2020-02-19', 2020, 2, 19, 1, 8, 'Wednesday', 'February', 'f', 'f', NULL),
(20200220, '2020-02-20', 2020, 2, 20, 1, 8, 'Thursday', 'February', 'f', 'f', NULL),
(20200221, '2020-02-21', 2020, 2, 21, 1, 8, 'Friday', 'February', 'f', 'f', NULL),
(20200222, '2020-02-22', 2020, 2, 22, 1, 8, 'Saturday', 'February', 'f', 't', NULL),
(20200223, '2020-02-23', 2020, 2, 23, 1, 8, 'Sunday', 'February', 'f', 't', NULL),
(20200224, '2020-02-24', 2020, 2, 24, 1, 9, 'Monday', 'February', 'f', 'f', NULL),
(20200225, '2020-02-25', 2020, 2, 25, 1, 9, 'Tuesday', 'February', 'f', 'f', NULL),
(20200226, '2020-02-26', 2020, 2, 26, 1, 9, 'Wednesday', 'February', 'f', 'f', NULL),
(20200227, '2020-02-27', 2020, 2, 27, 1, 9, 'Thursday', 'February', 'f', 'f', NULL),
(20200228, '2020-02-28', 2020, 2, 28, 1, 9, 'Friday', 'February', 'f', 'f', NULL),
(20200229, '2020-02-29', 2020, 2, 29, 1, 9, 'Saturday', 'February', 'f', 't', NULL),
(20200301, '2020-03-01', 2020, 3, 1, 1, 9, 'Sunday', 'March', 'f', 't', NULL),
(20200302, '2020-03-02', 2020, 3, 2, 1, 10, 'Monday', 'March', 'f', 'f', NULL),
(20200303, '2020-03-03', 2020, 3, 3, 1, 10, 'Tuesday', 'March', 'f', 'f', NULL),
(20200304, '2020-03-04', 2020, 3, 4, 1, 10, 'Wednesday', 'March', 'f', 'f', NULL),
(20200305, '2020-03-05', 2020, 3, 5, 1, 10, 'Thursday', 'March', 'f', 'f', NULL),
(20200306, '2020-03-06', 2020, 3, 6, 1, 10, 'Friday', 'March', 'f', 'f', NULL),
(20200307, '2020-03-07', 2020, 3, 7, 1, 10, 'Saturday', 'March', 'f', 't', NULL),
(20200308, '2020-03-08', 2020, 3, 8, 1, 10, 'Sunday', 'March', 'f', 't', NULL),
(20200309, '2020-03-09', 2020, 3, 9, 1, 11, 'Monday', 'March', 'f', 'f', NULL),
(20200310, '2020-03-10', 2020, 3, 10, 1, 11, 'Tuesday', 'March', 'f', 'f', NULL),
(20200311, '2020-03-11', 2020, 3, 11, 1, 11, 'Wednesday', 'March', 'f', 'f', NULL),
(20200312, '2020-03-12', 2020, 3, 12, 1, 11, 'Thursday', 'March', 'f', 'f', NULL),
(20200313, '2020-03-13', 2020, 3, 13, 1, 11, 'Friday', 'March', 'f', 'f', NULL),
(20200314, '2020-03-14', 2020, 3, 14, 1, 11, 'Saturday', 'March', 'f', 't', NULL),
(20200315, '2020-03-15', 2020, 3, 15, 1, 11, 'Sunday', 'March', 'f', 't', NULL),
(20200316, '2020-03-16', 2020, 3, 16, 1, 12, 'Monday', 'March', 'f', 'f', NULL),
(20200317, '2020-03-17', 2020, 3, 17, 1, 12, 'Tuesday', 'March', 'f', 'f', NULL),
(20200318, '2020-03-18', 2020, 3, 18, 1, 12, 'Wednesday', 'March', 'f', 'f', NULL),
(20200319, '2020-03-19', 2020, 3, 19, 1, 12, 'Thursday', 'March', 'f', 'f', NULL),
(20200320, '2020-03-20', 2020, 3, 20, 1, 12, 'Friday', 'March', 'f', 'f', NULL),
(20200321, '2020-03-21', 2020, 3, 21, 1, 12, 'Saturday', 'March', 'f', 't', NULL),
(20200322, '2020-03-22', 2020, 3, 22, 1, 12, 'Sunday', 'March', 'f', 't', NULL),
(20200323, '2020-03-23', 2020, 3, 23, 1, 13, 'Monday', 'March', 'f', 'f', NULL),
(20200324, '2020-03-24', 2020, 3, 24, 1, 13, 'Tuesday', 'March', 'f', 'f', NULL),
(20200325, '2020-03-25', 2020, 3, 25, 1, 13, 'Wednesday', 'March', 'f', 'f', NULL),
(20200326, '2020-03-26', 2020, 3, 26, 1, 13, 'Thursday', 'March', 'f', 'f', NULL),
(20200327, '2020-03-27', 2020, 3, 27, 1, 13, 'Friday', 'March', 'f', 'f', NULL),
(20200328, '2020-03-28', 2020, 3, 28, 1, 13, 'Saturday', 'March', 'f', 't', NULL),
(20200329, '2020-03-29', 2020, 3, 29, 1, 13, 'Sunday', 'March', 'f', 't', NULL),
(20200330, '2020-03-30', 2020, 3, 30, 1, 14, 'Monday', 'March', 'f', 'f', NULL),
(20200331, '2020-03-31', 2020, 3, 31, 1, 14, 'Tuesday', 'March', 'f', 'f', NULL),
(20200401, '2020-04-01', 2020, 4, 1, 2, 14, 'Wednesday', 'April', 'f', 'f', NULL),
(20200402, '2020-04-02', 2020, 4, 2, 2, 14, 'Thursday', 'April', 'f', 'f', NULL),
(20200403, '2020-04-03', 2020, 4, 3, 2, 14, 'Friday', 'April', 'f', 'f', NULL),
(20200404, '2020-04-04', 2020, 4, 4, 2, 14, 'Saturday', 'April', 'f', 't', NULL),
(20200405, '2020-04-05', 2020, 4, 5, 2, 14, 'Sunday', 'April', 'f', 't', NULL),
(20200406, '2020-04-06', 2020, 4, 6, 2, 15, 'Monday', 'April', 'f', 'f', NULL),
(20200407, '2020-04-07', 2020, 4, 7, 2, 15, 'Tuesday', 'April', 'f', 'f', NULL),
(20200408, '2020-04-08', 2020, 4, 8, 2, 15, 'Wednesday', 'April', 'f', 'f', NULL),
(20200409, '2020-04-09', 2020, 4, 9, 2, 15, 'Thursday', 'April', 'f', 'f', NULL),
(20200410, '2020-04-10', 2020, 4, 10, 2, 15, 'Friday', 'April', 'f', 'f', NULL),
(20200411, '2020-04-11', 2020, 4, 11, 2, 15, 'Saturday', 'April', 'f', 't', NULL),
(20200412, '2020-04-12', 2020, 4, 12, 2, 15, 'Sunday', 'April', 'f', 't', NULL),
(20200413, '2020-04-13', 2020, 4, 13, 2, 16, 'Monday', 'April', 'f', 'f', NULL),
(20200414, '2020-04-14', 2020, 4, 14, 2, 16, 'Tuesday', 'April', 'f', 'f', NULL),
(20200415, '2020-04-15', 2020, 4, 15, 2, 16, 'Wednesday', 'April', 'f', 'f', NULL),
(20200416, '2020-04-16', 2020, 4, 16, 2, 16, 'Thursday', 'April', 'f', 'f', NULL),
(20200417, '2020-04-17', 2020, 4, 17, 2, 16, 'Friday', 'April', 'f', 'f', NULL),
(20200418, '2020-04-18', 2020, 4, 18, 2, 16, 'Saturday', 'April', 'f', 't', NULL),
(20200419, '2020-04-19', 2020, 4, 19, 2, 16, 'Sunday', 'April', 'f', 't', NULL),
(20200420, '2020-04-20', 2020, 4, 20, 2, 17, 'Monday', 'April', 'f', 'f', NULL),
(20200421, '2020-04-21', 2020, 4, 21, 2, 17, 'Tuesday', 'April', 'f', 'f', NULL),
(20200422, '2020-04-22', 2020, 4, 22, 2, 17, 'Wednesday', 'April', 'f', 'f', NULL),
(20200423, '2020-04-23', 2020, 4, 23, 2, 17, 'Thursday', 'April', 'f', 'f', NULL),
(20200424, '2020-04-24', 2020, 4, 24, 2, 17, 'Friday', 'April', 'f', 'f', NULL),
(20200425, '2020-04-25', 2020, 4, 25, 2, 17, 'Saturday', 'April', 'f', 't', NULL),
(20200426, '2020-04-26', 2020, 4, 26, 2, 17, 'Sunday', 'April', 'f', 't', NULL),
(20200427, '2020-04-27', 2020, 4, 27, 2, 18, 'Monday', 'April', 'f', 'f', NULL),
(20200428, '2020-04-28', 2020, 4, 28, 2, 18, 'Tuesday', 'April', 'f', 'f', NULL),
(20200429, '2020-04-29', 2020, 4, 29, 2, 18, 'Wednesday', 'April', 'f', 'f', NULL),
(20200430, '2020-04-30', 2020, 4, 30, 2, 18, 'Thursday', 'April', 'f', 'f', NULL),
(20200501, '2020-05-01', 2020, 5, 1, 2, 18, 'Friday', 'May', 'f', 'f', NULL),
(20200502, '2020-05-02', 2020, 5, 2, 2, 18, 'Saturday', 'May', 'f', 't', NULL),
(20200503, '2020-05-03', 2020, 5, 3, 2, 18, 'Sunday', 'May', 'f', 't', NULL),
(20200504, '2020-05-04', 2020, 5, 4, 2, 19, 'Monday', 'May', 'f', 'f', NULL),
(20200505, '2020-05-05', 2020, 5, 5, 2, 19, 'Tuesday', 'May', 'f', 'f', NULL),
(20200506, '2020-05-06', 2020, 5, 6, 2, 19, 'Wednesday', 'May', 'f', 'f', NULL),
(20200507, '2020-05-07', 2020, 5, 7, 2, 19, 'Thursday', 'May', 'f', 'f', NULL),
(20200508, '2020-05-08', 2020, 5, 8, 2, 19, 'Friday', 'May', 'f', 'f', NULL),
(20200509, '2020-05-09', 2020, 5, 9, 2, 19, 'Saturday', 'May', 'f', 't', NULL),
(20200510, '2020-05-10', 2020, 5, 10, 2, 19, 'Sunday', 'May', 'f', 't', NULL),
(20200511, '2020-05-11', 2020, 5, 11, 2, 20, 'Monday', 'May', 'f', 'f', NULL),
(20200512, '2020-05-12', 2020, 5, 12, 2, 20, 'Tuesday', 'May', 'f', 'f', NULL),
(20200513, '2020-05-13', 2020, 5, 13, 2, 20, 'Wednesday', 'May', 'f', 'f', NULL),
(20200514, '2020-05-14', 2020, 5, 14, 2, 20, 'Thursday', 'May', 'f', 'f', NULL),
(20200515, '2020-05-15', 2020, 5, 15, 2, 20, 'Friday', 'May', 'f', 'f', NULL),
(20200516, '2020-05-16', 2020, 5, 16, 2, 20, 'Saturday', 'May', 'f', 't', NULL),
(20200517, '2020-05-17', 2020, 5, 17, 2, 20, 'Sunday', 'May', 'f', 't', NULL),
(20200518, '2020-05-18', 2020, 5, 18, 2, 21, 'Monday', 'May', 'f', 'f', NULL),
(20200519, '2020-05-19', 2020, 5, 19, 2, 21, 'Tuesday', 'May', 'f', 'f', NULL),
(20200520, '2020-05-20', 2020, 5, 20, 2, 21, 'Wednesday', 'May', 'f', 'f', NULL),
(20200521, '2020-05-21', 2020, 5, 21, 2, 21, 'Thursday', 'May', 'f', 'f', NULL),
(20200522, '2020-05-22', 2020, 5, 22, 2, 21, 'Friday', 'May', 'f', 'f', NULL),
(20200523, '2020-05-23', 2020, 5, 23, 2, 21, 'Saturday', 'May', 'f', 't', NULL),
(20200524, '2020-05-24', 2020, 5, 24, 2, 21, 'Sunday', 'May', 'f', 't', NULL),
(20200525, '2020-05-25', 2020, 5, 25, 2, 22, 'Monday', 'May', 'f', 'f', NULL),
(20200526, '2020-05-26', 2020, 5, 26, 2, 22, 'Tuesday', 'May', 'f', 'f', NULL),
(20200527, '2020-05-27', 2020, 5, 27, 2, 22, 'Wednesday', 'May', 'f', 'f', NULL),
(20200528, '2020-05-28', 2020, 5, 28, 2, 22, 'Thursday', 'May', 'f', 'f', NULL),
(20200529, '2020-05-29', 2020, 5, 29, 2, 22, 'Friday', 'May', 'f', 'f', NULL),
(20200530, '2020-05-30', 2020, 5, 30, 2, 22, 'Saturday', 'May', 'f', 't', NULL),
(20200531, '2020-05-31', 2020, 5, 31, 2, 22, 'Sunday', 'May', 'f', 't', NULL),
(20200601, '2020-06-01', 2020, 6, 1, 2, 23, 'Monday', 'June', 'f', 'f', NULL),
(20200602, '2020-06-02', 2020, 6, 2, 2, 23, 'Tuesday', 'June', 'f', 'f', NULL),
(20200603, '2020-06-03', 2020, 6, 3, 2, 23, 'Wednesday', 'June', 'f', 'f', NULL),
(20200604, '2020-06-04', 2020, 6, 4, 2, 23, 'Thursday', 'June', 'f', 'f', NULL),
(20200605, '2020-06-05', 2020, 6, 5, 2, 23, 'Friday', 'June', 'f', 'f', NULL),
(20200606, '2020-06-06', 2020, 6, 6, 2, 23, 'Saturday', 'June', 'f', 't', NULL),
(20200607, '2020-06-07', 2020, 6, 7, 2, 23, 'Sunday', 'June', 'f', 't', NULL),
(20200608, '2020-06-08', 2020, 6, 8, 2, 24, 'Monday', 'June', 'f', 'f', NULL),
(20200609, '2020-06-09', 2020, 6, 9, 2, 24, 'Tuesday', 'June', 'f', 'f', NULL),
(20200610, '2020-06-10', 2020, 6, 10, 2, 24, 'Wednesday', 'June', 'f', 'f', NULL),
(20200611, '2020-06-11', 2020, 6, 11, 2, 24, 'Thursday', 'June', 'f', 'f', NULL),
(20200612, '2020-06-12', 2020, 6, 12, 2, 24, 'Friday', 'June', 'f', 'f', NULL),
(20200613, '2020-06-13', 2020, 6, 13, 2, 24, 'Saturday', 'June', 'f', 't', NULL),
(20200614, '2020-06-14', 2020, 6, 14, 2, 24, 'Sunday', 'June', 'f', 't', NULL),
(20200615, '2020-06-15', 2020, 6, 15, 2, 25, 'Monday', 'June', 'f', 'f', NULL),
(20200616, '2020-06-16', 2020, 6, 16, 2, 25, 'Tuesday', 'June', 'f', 'f', NULL),
(20200617, '2020-06-17', 2020, 6, 17, 2, 25, 'Wednesday', 'June', 'f', 'f', NULL),
(20200618, '2020-06-18', 2020, 6, 18, 2, 25, 'Thursday', 'June', 'f', 'f', NULL),
(20200619, '2020-06-19', 2020, 6, 19, 2, 25, 'Friday', 'June', 'f', 'f', NULL),
(20200620, '2020-06-20', 2020, 6, 20, 2, 25, 'Saturday', 'June', 'f', 't', NULL),
(20200621, '2020-06-21', 2020, 6, 21, 2, 25, 'Sunday', 'June', 'f', 't', NULL),
(20200622, '2020-06-22', 2020, 6, 22, 2, 26, 'Monday', 'June', 'f', 'f', NULL),
(20200623, '2020-06-23', 2020, 6, 23, 2, 26, 'Tuesday', 'June', 'f', 'f', NULL),
(20200624, '2020-06-24', 2020, 6, 24, 2, 26, 'Wednesday', 'June', 'f', 'f', NULL),
(20200625, '2020-06-25', 2020, 6, 25, 2, 26, 'Thursday', 'June', 'f', 'f', NULL),
(20200626, '2020-06-26', 2020, 6, 26, 2, 26, 'Friday', 'June', 'f', 'f', NULL),
(20200627, '2020-06-27', 2020, 6, 27, 2, 26, 'Saturday', 'June', 'f', 't', NULL),
(20200628, '2020-06-28', 2020, 6, 28, 2, 26, 'Sunday', 'June', 'f', 't', NULL),
(20200629, '2020-06-29', 2020, 6, 29, 2, 27, 'Monday', 'June', 'f', 'f', NULL),
(20200630, '2020-06-30', 2020, 6, 30, 2, 27, 'Tuesday', 'June', 'f', 'f', NULL),
(20200701, '2020-07-01', 2020, 7, 1, 3, 27, 'Wednesday', 'July', 'f', 'f', NULL),
(20200702, '2020-07-02', 2020, 7, 2, 3, 27, 'Thursday', 'July', 'f', 'f', NULL),
(20200703, '2020-07-03', 2020, 7, 3, 3, 27, 'Friday', 'July', 'f', 'f', NULL),
(20200704, '2020-07-04', 2020, 7, 4, 3, 27, 'Saturday', 'July', 'f', 't', NULL),
(20200705, '2020-07-05', 2020, 7, 5, 3, 27, 'Sunday', 'July', 'f', 't', NULL),
(20200706, '2020-07-06', 2020, 7, 6, 3, 28, 'Monday', 'July', 'f', 'f', NULL),
(20200707, '2020-07-07', 2020, 7, 7, 3, 28, 'Tuesday', 'July', 'f', 'f', NULL),
(20200708, '2020-07-08', 2020, 7, 8, 3, 28, 'Wednesday', 'July', 'f', 'f', NULL),
(20200709, '2020-07-09', 2020, 7, 9, 3, 28, 'Thursday', 'July', 'f', 'f', NULL),
(20200710, '2020-07-10', 2020, 7, 10, 3, 28, 'Friday', 'July', 'f', 'f', NULL),
(20200711, '2020-07-11', 2020, 7, 11, 3, 28, 'Saturday', 'July', 'f', 't', NULL),
(20200712, '2020-07-12', 2020, 7, 12, 3, 28, 'Sunday', 'July', 'f', 't', NULL),
(20200713, '2020-07-13', 2020, 7, 13, 3, 29, 'Monday', 'July', 'f', 'f', NULL),
(20200714, '2020-07-14', 2020, 7, 14, 3, 29, 'Tuesday', 'July', 'f', 'f', NULL),
(20200715, '2020-07-15', 2020, 7, 15, 3, 29, 'Wednesday', 'July', 'f', 'f', NULL),
(20200716, '2020-07-16', 2020, 7, 16, 3, 29, 'Thursday', 'July', 'f', 'f', NULL),
(20200717, '2020-07-17', 2020, 7, 17, 3, 29, 'Friday', 'July', 'f', 'f', NULL),
(20200718, '2020-07-18', 2020, 7, 18, 3, 29, 'Saturday', 'July', 'f', 't', NULL),
(20200719, '2020-07-19', 2020, 7, 19, 3, 29, 'Sunday', 'July', 'f', 't', NULL),
(20200720, '2020-07-20', 2020, 7, 20, 3, 30, 'Monday', 'July', 'f', 'f', NULL),
(20200721, '2020-07-21', 2020, 7, 21, 3, 30, 'Tuesday', 'July', 'f', 'f', NULL),
(20200722, '2020-07-22', 2020, 7, 22, 3, 30, 'Wednesday', 'July', 'f', 'f', NULL),
(20200723, '2020-07-23', 2020, 7, 23, 3, 30, 'Thursday', 'July', 'f', 'f', NULL),
(20200724, '2020-07-24', 2020, 7, 24, 3, 30, 'Friday', 'July', 'f', 'f', NULL),
(20200725, '2020-07-25', 2020, 7, 25, 3, 30, 'Saturday', 'July', 'f', 't', NULL),
(20200726, '2020-07-26', 2020, 7, 26, 3, 30, 'Sunday', 'July', 'f', 't', NULL),
(20200727, '2020-07-27', 2020, 7, 27, 3, 31, 'Monday', 'July', 'f', 'f', NULL),
(20200728, '2020-07-28', 2020, 7, 28, 3, 31, 'Tuesday', 'July', 'f', 'f', NULL),
(20200729, '2020-07-29', 2020, 7, 29, 3, 31, 'Wednesday', 'July', 'f', 'f', NULL),
(20200730, '2020-07-30', 2020, 7, 30, 3, 31, 'Thursday', 'July', 'f', 'f', NULL),
(20200731, '2020-07-31', 2020, 7, 31, 3, 31, 'Friday', 'July', 'f', 'f', NULL),
(20200801, '2020-08-01', 2020, 8, 1, 3, 31, 'Saturday', 'August', 'f', 't', NULL),
(20200802, '2020-08-02', 2020, 8, 2, 3, 31, 'Sunday', 'August', 'f', 't', NULL),
(20200803, '2020-08-03', 2020, 8, 3, 3, 32, 'Monday', 'August', 'f', 'f', NULL),
(20200804, '2020-08-04', 2020, 8, 4, 3, 32, 'Tuesday', 'August', 'f', 'f', NULL),
(20200805, '2020-08-05', 2020, 8, 5, 3, 32, 'Wednesday', 'August', 'f', 'f', NULL),
(20200806, '2020-08-06', 2020, 8, 6, 3, 32, 'Thursday', 'August', 'f', 'f', NULL),
(20200807, '2020-08-07', 2020, 8, 7, 3, 32, 'Friday', 'August', 'f', 'f', NULL),
(20200808, '2020-08-08', 2020, 8, 8, 3, 32, 'Saturday', 'August', 'f', 't', NULL),
(20200809, '2020-08-09', 2020, 8, 9, 3, 32, 'Sunday', 'August', 'f', 't', NULL),
(20200810, '2020-08-10', 2020, 8, 10, 3, 33, 'Monday', 'August', 'f', 'f', NULL),
(20200811, '2020-08-11', 2020, 8, 11, 3, 33, 'Tuesday', 'August', 'f', 'f', NULL),
(20200812, '2020-08-12', 2020, 8, 12, 3, 33, 'Wednesday', 'August', 'f', 'f', NULL),
(20200813, '2020-08-13', 2020, 8, 13, 3, 33, 'Thursday', 'August', 'f', 'f', NULL),
(20200814, '2020-08-14', 2020, 8, 14, 3, 33, 'Friday', 'August', 'f', 'f', NULL),
(20200815, '2020-08-15', 2020, 8, 15, 3, 33, 'Saturday', 'August', 'f', 't', NULL),
(20200816, '2020-08-16', 2020, 8, 16, 3, 33, 'Sunday', 'August', 'f', 't', NULL),
(20200817, '2020-08-17', 2020, 8, 17, 3, 34, 'Monday', 'August', 'f', 'f', NULL),
(20200818, '2020-08-18', 2020, 8, 18, 3, 34, 'Tuesday', 'August', 'f', 'f', NULL),
(20200819, '2020-08-19', 2020, 8, 19, 3, 34, 'Wednesday', 'August', 'f', 'f', NULL),
(20200820, '2020-08-20', 2020, 8, 20, 3, 34, 'Thursday', 'August', 'f', 'f', NULL),
(20200821, '2020-08-21', 2020, 8, 21, 3, 34, 'Friday', 'August', 'f', 'f', NULL),
(20200822, '2020-08-22', 2020, 8, 22, 3, 34, 'Saturday', 'August', 'f', 't', NULL),
(20200823, '2020-08-23', 2020, 8, 23, 3, 34, 'Sunday', 'August', 'f', 't', NULL),
(20200824, '2020-08-24', 2020, 8, 24, 3, 35, 'Monday', 'August', 'f', 'f', NULL),
(20200825, '2020-08-25', 2020, 8, 25, 3, 35, 'Tuesday', 'August', 'f', 'f', NULL),
(20200826, '2020-08-26', 2020, 8, 26, 3, 35, 'Wednesday', 'August', 'f', 'f', NULL),
(20200827, '2020-08-27', 2020, 8, 27, 3, 35, 'Thursday', 'August', 'f', 'f', NULL),
(20200828, '2020-08-28', 2020, 8, 28, 3, 35, 'Friday', 'August', 'f', 'f', NULL),
(20200829, '2020-08-29', 2020, 8, 29, 3, 35, 'Saturday', 'August', 'f', 't', NULL),
(20200830, '2020-08-30', 2020, 8, 30, 3, 35, 'Sunday', 'August', 'f', 't', NULL),
(20200831, '2020-08-31', 2020, 8, 31, 3, 36, 'Monday', 'August', 'f', 'f', NULL),
(20200901, '2020-09-01', 2020, 9, 1, 3, 36, 'Tuesday', 'September', 'f', 'f', NULL),
(20200902, '2020-09-02', 2020, 9, 2, 3, 36, 'Wednesday', 'September', 'f', 'f', NULL),
(20200903, '2020-09-03', 2020, 9, 3, 3, 36, 'Thursday', 'September', 'f', 'f', NULL),
(20200904, '2020-09-04', 2020, 9, 4, 3, 36, 'Friday', 'September', 'f', 'f', NULL),
(20200905, '2020-09-05', 2020, 9, 5, 3, 36, 'Saturday', 'September', 'f', 't', NULL),
(20200906, '2020-09-06', 2020, 9, 6, 3, 36, 'Sunday', 'September', 'f', 't', NULL),
(20200907, '2020-09-07', 2020, 9, 7, 3, 37, 'Monday', 'September', 'f', 'f', NULL),
(20200908, '2020-09-08', 2020, 9, 8, 3, 37, 'Tuesday', 'September', 'f', 'f', NULL),
(20200909, '2020-09-09', 2020, 9, 9, 3, 37, 'Wednesday', 'September', 'f', 'f', NULL),
(20200910, '2020-09-10', 2020, 9, 10, 3, 37, 'Thursday', 'September', 'f', 'f', NULL),
(20200911, '2020-09-11', 2020, 9, 11, 3, 37, 'Friday', 'September', 'f', 'f', NULL),
(20200912, '2020-09-12', 2020, 9, 12, 3, 37, 'Saturday', 'September', 'f', 't', NULL),
(20200913, '2020-09-13', 2020, 9, 13, 3, 37, 'Sunday', 'September', 'f', 't', NULL),
(20200914, '2020-09-14', 2020, 9, 14, 3, 38, 'Monday', 'September', 'f', 'f', NULL),
(20200915, '2020-09-15', 2020, 9, 15, 3, 38, 'Tuesday', 'September', 'f', 'f', NULL),
(20200916, '2020-09-16', 2020, 9, 16, 3, 38, 'Wednesday', 'September', 'f', 'f', NULL),
(20200917, '2020-09-17', 2020, 9, 17, 3, 38, 'Thursday', 'September', 'f', 'f', NULL),
(20200918, '2020-09-18', 2020, 9, 18, 3, 38, 'Friday', 'September', 'f', 'f', NULL),
(20200919, '2020-09-19', 2020, 9, 19, 3, 38, 'Saturday', 'September', 'f', 't', NULL),
(20200920, '2020-09-20', 2020, 9, 20, 3, 38, 'Sunday', 'September', 'f', 't', NULL),
(20200921, '2020-09-21', 2020, 9, 21, 3, 39, 'Monday', 'September', 'f', 'f', NULL),
(20200922, '2020-09-22', 2020, 9, 22, 3, 39, 'Tuesday', 'September', 'f', 'f', NULL),
(20200923, '2020-09-23', 2020, 9, 23, 3, 39, 'Wednesday', 'September', 'f', 'f', NULL),
(20200924, '2020-09-24', 2020, 9, 24, 3, 39, 'Thursday', 'September', 'f', 'f', NULL),
(20200925, '2020-09-25', 2020, 9, 25, 3, 39, 'Friday', 'September', 'f', 'f', NULL),
(20200926, '2020-09-26', 2020, 9, 26, 3, 39, 'Saturday', 'September', 'f', 't', NULL),
(20200927, '2020-09-27', 2020, 9, 27, 3, 39, 'Sunday', 'September', 'f', 't', NULL),
(20200928, '2020-09-28', 2020, 9, 28, 3, 40, 'Monday', 'September', 'f', 'f', NULL),
(20200929, '2020-09-29', 2020, 9, 29, 3, 40, 'Tuesday', 'September', 'f', 'f', NULL),
(20200930, '2020-09-30', 2020, 9, 30, 3, 40, 'Wednesday', 'September', 'f', 'f', NULL),
(20201001, '2020-10-01', 2020, 10, 1, 4, 40, 'Thursday', 'October', 'f', 'f', NULL),
(20201002, '2020-10-02', 2020, 10, 2, 4, 40, 'Friday', 'October', 'f', 'f', NULL),
(20201003, '2020-10-03', 2020, 10, 3, 4, 40, 'Saturday', 'October', 'f', 't', NULL),
(20201004, '2020-10-04', 2020, 10, 4, 4, 40, 'Sunday', 'October', 'f', 't', NULL),
(20201005, '2020-10-05', 2020, 10, 5, 4, 41, 'Monday', 'October', 'f', 'f', NULL),
(20201006, '2020-10-06', 2020, 10, 6, 4, 41, 'Tuesday', 'October', 'f', 'f', NULL),
(20201007, '2020-10-07', 2020, 10, 7, 4, 41, 'Wednesday', 'October', 'f', 'f', NULL),
(20201008, '2020-10-08', 2020, 10, 8, 4, 41, 'Thursday', 'October', 'f', 'f', NULL),
(20201009, '2020-10-09', 2020, 10, 9, 4, 41, 'Friday', 'October', 'f', 'f', NULL),
(20201010, '2020-10-10', 2020, 10, 10, 4, 41, 'Saturday', 'October', 'f', 't', NULL),
(20201011, '2020-10-11', 2020, 10, 11, 4, 41, 'Sunday', 'October', 'f', 't', NULL),
(20201012, '2020-10-12', 2020, 10, 12, 4, 42, 'Monday', 'October', 'f', 'f', NULL),
(20201013, '2020-10-13', 2020, 10, 13, 4, 42, 'Tuesday', 'October', 'f', 'f', NULL),
(20201014, '2020-10-14', 2020, 10, 14, 4, 42, 'Wednesday', 'October', 'f', 'f', NULL),
(20201015, '2020-10-15', 2020, 10, 15, 4, 42, 'Thursday', 'October', 'f', 'f', NULL),
(20201016, '2020-10-16', 2020, 10, 16, 4, 42, 'Friday', 'October', 'f', 'f', NULL),
(20201017, '2020-10-17', 2020, 10, 17, 4, 42, 'Saturday', 'October', 'f', 't', NULL),
(20201018, '2020-10-18', 2020, 10, 18, 4, 42, 'Sunday', 'October', 'f', 't', NULL),
(20201019, '2020-10-19', 2020, 10, 19, 4, 43, 'Monday', 'October', 'f', 'f', NULL),
(20201020, '2020-10-20', 2020, 10, 20, 4, 43, 'Tuesday', 'October', 'f', 'f', NULL),
(20201021, '2020-10-21', 2020, 10, 21, 4, 43, 'Wednesday', 'October', 'f', 'f', NULL),
(20201022, '2020-10-22', 2020, 10, 22, 4, 43, 'Thursday', 'October', 'f', 'f', NULL),
(20201023, '2020-10-23', 2020, 10, 23, 4, 43, 'Friday', 'October', 'f', 'f', NULL),
(20201024, '2020-10-24', 2020, 10, 24, 4, 43, 'Saturday', 'October', 'f', 't', NULL),
(20201025, '2020-10-25', 2020, 10, 25, 4, 43, 'Sunday', 'October', 'f', 't', NULL),
(20201026, '2020-10-26', 2020, 10, 26, 4, 44, 'Monday', 'October', 'f', 'f', NULL),
(20201027, '2020-10-27', 2020, 10, 27, 4, 44, 'Tuesday', 'October', 'f', 'f', NULL),
(20201028, '2020-10-28', 2020, 10, 28, 4, 44, 'Wednesday', 'October', 'f', 'f', NULL),
(20201029, '2020-10-29', 2020, 10, 29, 4, 44, 'Thursday', 'October', 'f', 'f', NULL),
(20201030, '2020-10-30', 2020, 10, 30, 4, 44, 'Friday', 'October', 'f', 'f', NULL),
(20201031, '2020-10-31', 2020, 10, 31, 4, 44, 'Saturday', 'October', 'f', 't', NULL),
(20201101, '2020-11-01', 2020, 11, 1, 4, 44, 'Sunday', 'November', 'f', 't', NULL),
(20201102, '2020-11-02', 2020, 11, 2, 4, 45, 'Monday', 'November', 'f', 'f', NULL),
(20201103, '2020-11-03', 2020, 11, 3, 4, 45, 'Tuesday', 'November', 'f', 'f', NULL),
(20201104, '2020-11-04', 2020, 11, 4, 4, 45, 'Wednesday', 'November', 'f', 'f', NULL),
(20201105, '2020-11-05', 2020, 11, 5, 4, 45, 'Thursday', 'November', 'f', 'f', NULL),
(20201106, '2020-11-06', 2020, 11, 6, 4, 45, 'Friday', 'November', 'f', 'f', NULL),
(20201107, '2020-11-07', 2020, 11, 7, 4, 45, 'Saturday', 'November', 'f', 't', NULL),
(20201108, '2020-11-08', 2020, 11, 8, 4, 45, 'Sunday', 'November', 'f', 't', NULL),
(20201109, '2020-11-09', 2020, 11, 9, 4, 46, 'Monday', 'November', 'f', 'f', NULL),
(20201110, '2020-11-10', 2020, 11, 10, 4, 46, 'Tuesday', 'November', 'f', 'f', NULL),
(20201111, '2020-11-11', 2020, 11, 11, 4, 46, 'Wednesday', 'November', 'f', 'f', NULL),
(20201112, '2020-11-12', 2020, 11, 12, 4, 46, 'Thursday', 'November', 'f', 'f', NULL),
(20201113, '2020-11-13', 2020, 11, 13, 4, 46, 'Friday', 'November', 'f', 'f', NULL),
(20201114, '2020-11-14', 2020, 11, 14, 4, 46, 'Saturday', 'November', 'f', 't', NULL),
(20201115, '2020-11-15', 2020, 11, 15, 4, 46, 'Sunday', 'November', 'f', 't', NULL),
(20201116, '2020-11-16', 2020, 11, 16, 4, 47, 'Monday', 'November', 'f', 'f', NULL),
(20201117, '2020-11-17', 2020, 11, 17, 4, 47, 'Tuesday', 'November', 'f', 'f', NULL),
(20201118, '2020-11-18', 2020, 11, 18, 4, 47, 'Wednesday', 'November', 'f', 'f', NULL),
(20201119, '2020-11-19', 2020, 11, 19, 4, 47, 'Thursday', 'November', 'f', 'f', NULL),
(20201120, '2020-11-20', 2020, 11, 20, 4, 47, 'Friday', 'November', 'f', 'f', NULL),
(20201121, '2020-11-21', 2020, 11, 21, 4, 47, 'Saturday', 'November', 'f', 't', NULL),
(20201122, '2020-11-22', 2020, 11, 22, 4, 47, 'Sunday', 'November', 'f', 't', NULL),
(20201123, '2020-11-23', 2020, 11, 23, 4, 48, 'Monday', 'November', 'f', 'f', NULL),
(20201124, '2020-11-24', 2020, 11, 24, 4, 48, 'Tuesday', 'November', 'f', 'f', NULL),
(20201125, '2020-11-25', 2020, 11, 25, 4, 48, 'Wednesday', 'November', 'f', 'f', NULL),
(20201126, '2020-11-26', 2020, 11, 26, 4, 48, 'Thursday', 'November', 'f', 'f', NULL),
(20201127, '2020-11-27', 2020, 11, 27, 4, 48, 'Friday', 'November', 'f', 'f', NULL),
(20201128, '2020-11-28', 2020, 11, 28, 4, 48, 'Saturday', 'November', 'f', 't', NULL),
(20201129, '2020-11-29', 2020, 11, 29, 4, 48, 'Sunday', 'November', 'f', 't', NULL),
(20201130, '2020-11-30', 2020, 11, 30, 4, 49, 'Monday', 'November', 'f', 'f', NULL),
(20201201, '2020-12-01', 2020, 12, 1, 4, 49, 'Tuesday', 'December', 'f', 'f', NULL),
(20201202, '2020-12-02', 2020, 12, 2, 4, 49, 'Wednesday', 'December', 'f', 'f', NULL),
(20201203, '2020-12-03', 2020, 12, 3, 4, 49, 'Thursday', 'December', 'f', 'f', NULL),
(20201204, '2020-12-04', 2020, 12, 4, 4, 49, 'Friday', 'December', 'f', 'f', NULL),
(20201205, '2020-12-05', 2020, 12, 5, 4, 49, 'Saturday', 'December', 'f', 't', NULL),
(20201206, '2020-12-06', 2020, 12, 6, 4, 49, 'Sunday', 'December', 'f', 't', NULL),
(20201207, '2020-12-07', 2020, 12, 7, 4, 50, 'Monday', 'December', 'f', 'f', NULL),
(20201208, '2020-12-08', 2020, 12, 8, 4, 50, 'Tuesday', 'December', 'f', 'f', NULL),
(20201209, '2020-12-09', 2020, 12, 9, 4, 50, 'Wednesday', 'December', 'f', 'f', NULL),
(20201210, '2020-12-10', 2020, 12, 10, 4, 50, 'Thursday', 'December', 'f', 'f', NULL),
(20201211, '2020-12-11', 2020, 12, 11, 4, 50, 'Friday', 'December', 'f', 'f', NULL),
(20201212, '2020-12-12', 2020, 12, 12, 4, 50, 'Saturday', 'December', 'f', 't', NULL),
(20201213, '2020-12-13', 2020, 12, 13, 4, 50, 'Sunday', 'December', 'f', 't', NULL),
(20201214, '2020-12-14', 2020, 12, 14, 4, 51, 'Monday', 'December', 'f', 'f', NULL),
(20201215, '2020-12-15', 2020, 12, 15, 4, 51, 'Tuesday', 'December', 'f', 'f', NULL),
(20201216, '2020-12-16', 2020, 12, 16, 4, 51, 'Wednesday', 'December', 'f', 'f', NULL),
(20201217, '2020-12-17', 2020, 12, 17, 4, 51, 'Thursday', 'December', 'f', 'f', NULL),
(20201218, '2020-12-18', 2020, 12, 18, 4, 51, 'Friday', 'December', 'f', 'f', NULL),
(20201219, '2020-12-19', 2020, 12, 19, 4, 51, 'Saturday', 'December', 'f', 't', NULL),
(20201220, '2020-12-20', 2020, 12, 20, 4, 51, 'Sunday', 'December', 'f', 't', NULL),
(20201221, '2020-12-21', 2020, 12, 21, 4, 52, 'Monday', 'December', 'f', 'f', NULL),
(20201222, '2020-12-22', 2020, 12, 22, 4, 52, 'Tuesday', 'December', 'f', 'f', NULL),
(20201223, '2020-12-23', 2020, 12, 23, 4, 52, 'Wednesday', 'December', 'f', 'f', NULL),
(20201224, '2020-12-24', 2020, 12, 24, 4, 52, 'Thursday', 'December', 'f', 'f', NULL),
(20201225, '2020-12-25', 2020, 12, 25, 4, 52, 'Friday', 'December', 'f', 'f', NULL),
(20201226, '2020-12-26', 2020, 12, 26, 4, 52, 'Saturday', 'December', 'f', 't', NULL),
(20201227, '2020-12-27', 2020, 12, 27, 4, 52, 'Sunday', 'December', 'f', 't', NULL),
(20201228, '2020-12-28', 2020, 12, 28, 4, 53, 'Monday', 'December', 'f', 'f', NULL),
(20201229, '2020-12-29', 2020, 12, 29, 4, 53, 'Tuesday', 'December', 'f', 'f', NULL),
(20201230, '2020-12-30', 2020, 12, 30, 4, 53, 'Wednesday', 'December', 'f', 'f', NULL),
(20201231, '2020-12-31', 2020, 12, 31, 4, 53, 'Thursday', 'December', 'f', 'f', NULL),
(20210101, '2021-01-01', 2021, 1, 1, 1, 53, 'Friday', 'January', 'f', 'f', NULL),
(20210102, '2021-01-02', 2021, 1, 2, 1, 53, 'Saturday', 'January', 'f', 't', NULL),
(20210103, '2021-01-03', 2021, 1, 3, 1, 53, 'Sunday', 'January', 'f', 't', NULL),
(20210104, '2021-01-04', 2021, 1, 4, 1, 1, 'Monday', 'January', 'f', 'f', NULL),
(20210105, '2021-01-05', 2021, 1, 5, 1, 1, 'Tuesday', 'January', 'f', 'f', NULL),
(20210106, '2021-01-06', 2021, 1, 6, 1, 1, 'Wednesday', 'January', 'f', 'f', NULL),
(20210107, '2021-01-07', 2021, 1, 7, 1, 1, 'Thursday', 'January', 'f', 'f', NULL),
(20210108, '2021-01-08', 2021, 1, 8, 1, 1, 'Friday', 'January', 'f', 'f', NULL),
(20210109, '2021-01-09', 2021, 1, 9, 1, 1, 'Saturday', 'January', 'f', 't', NULL),
(20210110, '2021-01-10', 2021, 1, 10, 1, 1, 'Sunday', 'January', 'f', 't', NULL),
(20210111, '2021-01-11', 2021, 1, 11, 1, 2, 'Monday', 'January', 'f', 'f', NULL),
(20210112, '2021-01-12', 2021, 1, 12, 1, 2, 'Tuesday', 'January', 'f', 'f', NULL),
(20210113, '2021-01-13', 2021, 1, 13, 1, 2, 'Wednesday', 'January', 'f', 'f', NULL),
(20210114, '2021-01-14', 2021, 1, 14, 1, 2, 'Thursday', 'January', 'f', 'f', NULL),
(20210115, '2021-01-15', 2021, 1, 15, 1, 2, 'Friday', 'January', 'f', 'f', NULL),
(20210116, '2021-01-16', 2021, 1, 16, 1, 2, 'Saturday', 'January', 'f', 't', NULL),
(20210117, '2021-01-17', 2021, 1, 17, 1, 2, 'Sunday', 'January', 'f', 't', NULL),
(20210118, '2021-01-18', 2021, 1, 18, 1, 3, 'Monday', 'January', 'f', 'f', NULL),
(20210119, '2021-01-19', 2021, 1, 19, 1, 3, 'Tuesday', 'January', 'f', 'f', NULL),
(20210120, '2021-01-20', 2021, 1, 20, 1, 3, 'Wednesday', 'January', 'f', 'f', NULL),
(20210121, '2021-01-21', 2021, 1, 21, 1, 3, 'Thursday', 'January', 'f', 'f', NULL),
(20210122, '2021-01-22', 2021, 1, 22, 1, 3, 'Friday', 'January', 'f', 'f', NULL),
(20210123, '2021-01-23', 2021, 1, 23, 1, 3, 'Saturday', 'January', 'f', 't', NULL),
(20210124, '2021-01-24', 2021, 1, 24, 1, 3, 'Sunday', 'January', 'f', 't', NULL),
(20210125, '2021-01-25', 2021, 1, 25, 1, 4, 'Monday', 'January', 'f', 'f', NULL),
(20210126, '2021-01-26', 2021, 1, 26, 1, 4, 'Tuesday', 'January', 'f', 'f', NULL),
(20210127, '2021-01-27', 2021, 1, 27, 1, 4, 'Wednesday', 'January', 'f', 'f', NULL),
(20210128, '2021-01-28', 2021, 1, 28, 1, 4, 'Thursday', 'January', 'f', 'f', NULL),
(20210129, '2021-01-29', 2021, 1, 29, 1, 4, 'Friday', 'January', 'f', 'f', NULL),
(20210130, '2021-01-30', 2021, 1, 30, 1, 4, 'Saturday', 'January', 'f', 't', NULL),
(20210131, '2021-01-31', 2021, 1, 31, 1, 4, 'Sunday', 'January', 'f', 't', NULL),
(20210201, '2021-02-01', 2021, 2, 1, 1, 5, 'Monday', 'February', 'f', 'f', NULL),
(20210202, '2021-02-02', 2021, 2, 2, 1, 5, 'Tuesday', 'February', 'f', 'f', NULL),
(20210203, '2021-02-03', 2021, 2, 3, 1, 5, 'Wednesday', 'February', 'f', 'f', NULL),
(20210204, '2021-02-04', 2021, 2, 4, 1, 5, 'Thursday', 'February', 'f', 'f', NULL),
(20210205, '2021-02-05', 2021, 2, 5, 1, 5, 'Friday', 'February', 'f', 'f', NULL),
(20210206, '2021-02-06', 2021, 2, 6, 1, 5, 'Saturday', 'February', 'f', 't', NULL),
(20210207, '2021-02-07', 2021, 2, 7, 1, 5, 'Sunday', 'February', 'f', 't', NULL),
(20210208, '2021-02-08', 2021, 2, 8, 1, 6, 'Monday', 'February', 'f', 'f', NULL),
(20210209, '2021-02-09', 2021, 2, 9, 1, 6, 'Tuesday', 'February', 'f', 'f', NULL),
(20210210, '2021-02-10', 2021, 2, 10, 1, 6, 'Wednesday', 'February', 'f', 'f', NULL),
(20210211, '2021-02-11', 2021, 2, 11, 1, 6, 'Thursday', 'February', 'f', 'f', NULL),
(20210212, '2021-02-12', 2021, 2, 12, 1, 6, 'Friday', 'February', 'f', 'f', NULL),
(20210213, '2021-02-13', 2021, 2, 13, 1, 6, 'Saturday', 'February', 'f', 't', NULL),
(20210214, '2021-02-14', 2021, 2, 14, 1, 6, 'Sunday', 'February', 'f', 't', NULL),
(20210215, '2021-02-15', 2021, 2, 15, 1, 7, 'Monday', 'February', 'f', 'f', NULL),
(20210216, '2021-02-16', 2021, 2, 16, 1, 7, 'Tuesday', 'February', 'f', 'f', NULL),
(20210217, '2021-02-17', 2021, 2, 17, 1, 7, 'Wednesday', 'February', 'f', 'f', NULL),
(20210218, '2021-02-18', 2021, 2, 18, 1, 7, 'Thursday', 'February', 'f', 'f', NULL),
(20210219, '2021-02-19', 2021, 2, 19, 1, 7, 'Friday', 'February', 'f', 'f', NULL),
(20210220, '2021-02-20', 2021, 2, 20, 1, 7, 'Saturday', 'February', 'f', 't', NULL),
(20210221, '2021-02-21', 2021, 2, 21, 1, 7, 'Sunday', 'February', 'f', 't', NULL),
(20210222, '2021-02-22', 2021, 2, 22, 1, 8, 'Monday', 'February', 'f', 'f', NULL),
(20210223, '2021-02-23', 2021, 2, 23, 1, 8, 'Tuesday', 'February', 'f', 'f', NULL),
(20210224, '2021-02-24', 2021, 2, 24, 1, 8, 'Wednesday', 'February', 'f', 'f', NULL),
(20210225, '2021-02-25', 2021, 2, 25, 1, 8, 'Thursday', 'February', 'f', 'f', NULL),
(20210226, '2021-02-26', 2021, 2, 26, 1, 8, 'Friday', 'February', 'f', 'f', NULL),
(20210227, '2021-02-27', 2021, 2, 27, 1, 8, 'Saturday', 'February', 'f', 't', NULL),
(20210228, '2021-02-28', 2021, 2, 28, 1, 8, 'Sunday', 'February', 'f', 't', NULL),
(20210301, '2021-03-01', 2021, 3, 1, 1, 9, 'Monday', 'March', 'f', 'f', NULL),
(20210302, '2021-03-02', 2021, 3, 2, 1, 9, 'Tuesday', 'March', 'f', 'f', NULL),
(20210303, '2021-03-03', 2021, 3, 3, 1, 9, 'Wednesday', 'March', 'f', 'f', NULL),
(20210304, '2021-03-04', 2021, 3, 4, 1, 9, 'Thursday', 'March', 'f', 'f', NULL),
(20210305, '2021-03-05', 2021, 3, 5, 1, 9, 'Friday', 'March', 'f', 'f', NULL),
(20210306, '2021-03-06', 2021, 3, 6, 1, 9, 'Saturday', 'March', 'f', 't', NULL),
(20210307, '2021-03-07', 2021, 3, 7, 1, 9, 'Sunday', 'March', 'f', 't', NULL),
(20210308, '2021-03-08', 2021, 3, 8, 1, 10, 'Monday', 'March', 'f', 'f', NULL),
(20210309, '2021-03-09', 2021, 3, 9, 1, 10, 'Tuesday', 'March', 'f', 'f', NULL),
(20210310, '2021-03-10', 2021, 3, 10, 1, 10, 'Wednesday', 'March', 'f', 'f', NULL),
(20210311, '2021-03-11', 2021, 3, 11, 1, 10, 'Thursday', 'March', 'f', 'f', NULL),
(20210312, '2021-03-12', 2021, 3, 12, 1, 10, 'Friday', 'March', 'f', 'f', NULL),
(20210313, '2021-03-13', 2021, 3, 13, 1, 10, 'Saturday', 'March', 'f', 't', NULL),
(20210314, '2021-03-14', 2021, 3, 14, 1, 10, 'Sunday', 'March', 'f', 't', NULL),
(20210315, '2021-03-15', 2021, 3, 15, 1, 11, 'Monday', 'March', 'f', 'f', NULL),
(20210316, '2021-03-16', 2021, 3, 16, 1, 11, 'Tuesday', 'March', 'f', 'f', NULL),
(20210317, '2021-03-17', 2021, 3, 17, 1, 11, 'Wednesday', 'March', 'f', 'f', NULL),
(20210318, '2021-03-18', 2021, 3, 18, 1, 11, 'Thursday', 'March', 'f', 'f', NULL),
(20210319, '2021-03-19', 2021, 3, 19, 1, 11, 'Friday', 'March', 'f', 'f', NULL),
(20210320, '2021-03-20', 2021, 3, 20, 1, 11, 'Saturday', 'March', 'f', 't', NULL),
(20210321, '2021-03-21', 2021, 3, 21, 1, 11, 'Sunday', 'March', 'f', 't', NULL),
(20210322, '2021-03-22', 2021, 3, 22, 1, 12, 'Monday', 'March', 'f', 'f', NULL),
(20210323, '2021-03-23', 2021, 3, 23, 1, 12, 'Tuesday', 'March', 'f', 'f', NULL),
(20210324, '2021-03-24', 2021, 3, 24, 1, 12, 'Wednesday', 'March', 'f', 'f', NULL),
(20210325, '2021-03-25', 2021, 3, 25, 1, 12, 'Thursday', 'March', 'f', 'f', NULL),
(20210326, '2021-03-26', 2021, 3, 26, 1, 12, 'Friday', 'March', 'f', 'f', NULL),
(20210327, '2021-03-27', 2021, 3, 27, 1, 12, 'Saturday', 'March', 'f', 't', NULL),
(20210328, '2021-03-28', 2021, 3, 28, 1, 12, 'Sunday', 'March', 'f', 't', NULL),
(20210329, '2021-03-29', 2021, 3, 29, 1, 13, 'Monday', 'March', 'f', 'f', NULL),
(20210330, '2021-03-30', 2021, 3, 30, 1, 13, 'Tuesday', 'March', 'f', 'f', NULL),
(20210331, '2021-03-31', 2021, 3, 31, 1, 13, 'Wednesday', 'March', 'f', 'f', NULL),
(20210401, '2021-04-01', 2021, 4, 1, 2, 13, 'Thursday', 'April', 'f', 'f', NULL),
(20210402, '2021-04-02', 2021, 4, 2, 2, 13, 'Friday', 'April', 'f', 'f', NULL),
(20210403, '2021-04-03', 2021, 4, 3, 2, 13, 'Saturday', 'April', 'f', 't', NULL),
(20210404, '2021-04-04', 2021, 4, 4, 2, 13, 'Sunday', 'April', 'f', 't', NULL),
(20210405, '2021-04-05', 2021, 4, 5, 2, 14, 'Monday', 'April', 'f', 'f', NULL),
(20210406, '2021-04-06', 2021, 4, 6, 2, 14, 'Tuesday', 'April', 'f', 'f', NULL),
(20210407, '2021-04-07', 2021, 4, 7, 2, 14, 'Wednesday', 'April', 'f', 'f', NULL),
(20210408, '2021-04-08', 2021, 4, 8, 2, 14, 'Thursday', 'April', 'f', 'f', NULL),
(20210409, '2021-04-09', 2021, 4, 9, 2, 14, 'Friday', 'April', 'f', 'f', NULL),
(20210410, '2021-04-10', 2021, 4, 10, 2, 14, 'Saturday', 'April', 'f', 't', NULL),
(20210411, '2021-04-11', 2021, 4, 11, 2, 14, 'Sunday', 'April', 'f', 't', NULL),
(20210412, '2021-04-12', 2021, 4, 12, 2, 15, 'Monday', 'April', 'f', 'f', NULL),
(20210413, '2021-04-13', 2021, 4, 13, 2, 15, 'Tuesday', 'April', 'f', 'f', NULL),
(20210414, '2021-04-14', 2021, 4, 14, 2, 15, 'Wednesday', 'April', 'f', 'f', NULL),
(20210415, '2021-04-15', 2021, 4, 15, 2, 15, 'Thursday', 'April', 'f', 'f', NULL),
(20210416, '2021-04-16', 2021, 4, 16, 2, 15, 'Friday', 'April', 'f', 'f', NULL),
(20210417, '2021-04-17', 2021, 4, 17, 2, 15, 'Saturday', 'April', 'f', 't', NULL),
(20210418, '2021-04-18', 2021, 4, 18, 2, 15, 'Sunday', 'April', 'f', 't', NULL),
(20210419, '2021-04-19', 2021, 4, 19, 2, 16, 'Monday', 'April', 'f', 'f', NULL),
(20210420, '2021-04-20', 2021, 4, 20, 2, 16, 'Tuesday', 'April', 'f', 'f', NULL),
(20210421, '2021-04-21', 2021, 4, 21, 2, 16, 'Wednesday', 'April', 'f', 'f', NULL),
(20210422, '2021-04-22', 2021, 4, 22, 2, 16, 'Thursday', 'April', 'f', 'f', NULL),
(20210423, '2021-04-23', 2021, 4, 23, 2, 16, 'Friday', 'April', 'f', 'f', NULL),
(20210424, '2021-04-24', 2021, 4, 24, 2, 16, 'Saturday', 'April', 'f', 't', NULL),
(20210425, '2021-04-25', 2021, 4, 25, 2, 16, 'Sunday', 'April', 'f', 't', NULL),
(20210426, '2021-04-26', 2021, 4, 26, 2, 17, 'Monday', 'April', 'f', 'f', NULL),
(20210427, '2021-04-27', 2021, 4, 27, 2, 17, 'Tuesday', 'April', 'f', 'f', NULL),
(20210428, '2021-04-28', 2021, 4, 28, 2, 17, 'Wednesday', 'April', 'f', 'f', NULL),
(20210429, '2021-04-29', 2021, 4, 29, 2, 17, 'Thursday', 'April', 'f', 'f', NULL),
(20210430, '2021-04-30', 2021, 4, 30, 2, 17, 'Friday', 'April', 'f', 'f', NULL),
(20210501, '2021-05-01', 2021, 5, 1, 2, 17, 'Saturday', 'May', 'f', 't', NULL),
(20210502, '2021-05-02', 2021, 5, 2, 2, 17, 'Sunday', 'May', 'f', 't', NULL),
(20210503, '2021-05-03', 2021, 5, 3, 2, 18, 'Monday', 'May', 'f', 'f', NULL),
(20210504, '2021-05-04', 2021, 5, 4, 2, 18, 'Tuesday', 'May', 'f', 'f', NULL),
(20210505, '2021-05-05', 2021, 5, 5, 2, 18, 'Wednesday', 'May', 'f', 'f', NULL),
(20210506, '2021-05-06', 2021, 5, 6, 2, 18, 'Thursday', 'May', 'f', 'f', NULL),
(20210507, '2021-05-07', 2021, 5, 7, 2, 18, 'Friday', 'May', 'f', 'f', NULL),
(20210508, '2021-05-08', 2021, 5, 8, 2, 18, 'Saturday', 'May', 'f', 't', NULL),
(20210509, '2021-05-09', 2021, 5, 9, 2, 18, 'Sunday', 'May', 'f', 't', NULL),
(20210510, '2021-05-10', 2021, 5, 10, 2, 19, 'Monday', 'May', 'f', 'f', NULL),
(20210511, '2021-05-11', 2021, 5, 11, 2, 19, 'Tuesday', 'May', 'f', 'f', NULL),
(20210512, '2021-05-12', 2021, 5, 12, 2, 19, 'Wednesday', 'May', 'f', 'f', NULL),
(20210513, '2021-05-13', 2021, 5, 13, 2, 19, 'Thursday', 'May', 'f', 'f', NULL),
(20210514, '2021-05-14', 2021, 5, 14, 2, 19, 'Friday', 'May', 'f', 'f', NULL),
(20210515, '2021-05-15', 2021, 5, 15, 2, 19, 'Saturday', 'May', 'f', 't', NULL);
INSERT INTO `time_dimension` (`id`, `db_date`, `year`, `month`, `day`, `quarter`, `week`, `day_name`, `month_name`, `holiday_flag`, `weekend_flag`, `event`) VALUES
(20210516, '2021-05-16', 2021, 5, 16, 2, 19, 'Sunday', 'May', 'f', 't', NULL),
(20210517, '2021-05-17', 2021, 5, 17, 2, 20, 'Monday', 'May', 'f', 'f', NULL),
(20210518, '2021-05-18', 2021, 5, 18, 2, 20, 'Tuesday', 'May', 'f', 'f', NULL),
(20210519, '2021-05-19', 2021, 5, 19, 2, 20, 'Wednesday', 'May', 'f', 'f', NULL),
(20210520, '2021-05-20', 2021, 5, 20, 2, 20, 'Thursday', 'May', 'f', 'f', NULL),
(20210521, '2021-05-21', 2021, 5, 21, 2, 20, 'Friday', 'May', 'f', 'f', NULL),
(20210522, '2021-05-22', 2021, 5, 22, 2, 20, 'Saturday', 'May', 'f', 't', NULL),
(20210523, '2021-05-23', 2021, 5, 23, 2, 20, 'Sunday', 'May', 'f', 't', NULL),
(20210524, '2021-05-24', 2021, 5, 24, 2, 21, 'Monday', 'May', 'f', 'f', NULL),
(20210525, '2021-05-25', 2021, 5, 25, 2, 21, 'Tuesday', 'May', 'f', 'f', NULL),
(20210526, '2021-05-26', 2021, 5, 26, 2, 21, 'Wednesday', 'May', 'f', 'f', NULL),
(20210527, '2021-05-27', 2021, 5, 27, 2, 21, 'Thursday', 'May', 'f', 'f', NULL),
(20210528, '2021-05-28', 2021, 5, 28, 2, 21, 'Friday', 'May', 'f', 'f', NULL),
(20210529, '2021-05-29', 2021, 5, 29, 2, 21, 'Saturday', 'May', 'f', 't', NULL),
(20210530, '2021-05-30', 2021, 5, 30, 2, 21, 'Sunday', 'May', 'f', 't', NULL),
(20210531, '2021-05-31', 2021, 5, 31, 2, 22, 'Monday', 'May', 'f', 'f', NULL),
(20210601, '2021-06-01', 2021, 6, 1, 2, 22, 'Tuesday', 'June', 'f', 'f', NULL),
(20210602, '2021-06-02', 2021, 6, 2, 2, 22, 'Wednesday', 'June', 'f', 'f', NULL),
(20210603, '2021-06-03', 2021, 6, 3, 2, 22, 'Thursday', 'June', 'f', 'f', NULL),
(20210604, '2021-06-04', 2021, 6, 4, 2, 22, 'Friday', 'June', 'f', 'f', NULL),
(20210605, '2021-06-05', 2021, 6, 5, 2, 22, 'Saturday', 'June', 'f', 't', NULL),
(20210606, '2021-06-06', 2021, 6, 6, 2, 22, 'Sunday', 'June', 'f', 't', NULL),
(20210607, '2021-06-07', 2021, 6, 7, 2, 23, 'Monday', 'June', 'f', 'f', NULL),
(20210608, '2021-06-08', 2021, 6, 8, 2, 23, 'Tuesday', 'June', 'f', 'f', NULL),
(20210609, '2021-06-09', 2021, 6, 9, 2, 23, 'Wednesday', 'June', 'f', 'f', NULL),
(20210610, '2021-06-10', 2021, 6, 10, 2, 23, 'Thursday', 'June', 'f', 'f', NULL),
(20210611, '2021-06-11', 2021, 6, 11, 2, 23, 'Friday', 'June', 'f', 'f', NULL),
(20210612, '2021-06-12', 2021, 6, 12, 2, 23, 'Saturday', 'June', 'f', 't', NULL),
(20210613, '2021-06-13', 2021, 6, 13, 2, 23, 'Sunday', 'June', 'f', 't', NULL),
(20210614, '2021-06-14', 2021, 6, 14, 2, 24, 'Monday', 'June', 'f', 'f', NULL),
(20210615, '2021-06-15', 2021, 6, 15, 2, 24, 'Tuesday', 'June', 'f', 'f', NULL),
(20210616, '2021-06-16', 2021, 6, 16, 2, 24, 'Wednesday', 'June', 'f', 'f', NULL),
(20210617, '2021-06-17', 2021, 6, 17, 2, 24, 'Thursday', 'June', 'f', 'f', NULL),
(20210618, '2021-06-18', 2021, 6, 18, 2, 24, 'Friday', 'June', 'f', 'f', NULL),
(20210619, '2021-06-19', 2021, 6, 19, 2, 24, 'Saturday', 'June', 'f', 't', NULL),
(20210620, '2021-06-20', 2021, 6, 20, 2, 24, 'Sunday', 'June', 'f', 't', NULL),
(20210621, '2021-06-21', 2021, 6, 21, 2, 25, 'Monday', 'June', 'f', 'f', NULL),
(20210622, '2021-06-22', 2021, 6, 22, 2, 25, 'Tuesday', 'June', 'f', 'f', NULL),
(20210623, '2021-06-23', 2021, 6, 23, 2, 25, 'Wednesday', 'June', 'f', 'f', NULL),
(20210624, '2021-06-24', 2021, 6, 24, 2, 25, 'Thursday', 'June', 'f', 'f', NULL),
(20210625, '2021-06-25', 2021, 6, 25, 2, 25, 'Friday', 'June', 'f', 'f', NULL),
(20210626, '2021-06-26', 2021, 6, 26, 2, 25, 'Saturday', 'June', 'f', 't', NULL),
(20210627, '2021-06-27', 2021, 6, 27, 2, 25, 'Sunday', 'June', 'f', 't', NULL),
(20210628, '2021-06-28', 2021, 6, 28, 2, 26, 'Monday', 'June', 'f', 'f', NULL),
(20210629, '2021-06-29', 2021, 6, 29, 2, 26, 'Tuesday', 'June', 'f', 'f', NULL),
(20210630, '2021-06-30', 2021, 6, 30, 2, 26, 'Wednesday', 'June', 'f', 'f', NULL),
(20210701, '2021-07-01', 2021, 7, 1, 3, 26, 'Thursday', 'July', 'f', 'f', NULL),
(20210702, '2021-07-02', 2021, 7, 2, 3, 26, 'Friday', 'July', 'f', 'f', NULL),
(20210703, '2021-07-03', 2021, 7, 3, 3, 26, 'Saturday', 'July', 'f', 't', NULL),
(20210704, '2021-07-04', 2021, 7, 4, 3, 26, 'Sunday', 'July', 'f', 't', NULL),
(20210705, '2021-07-05', 2021, 7, 5, 3, 27, 'Monday', 'July', 'f', 'f', NULL),
(20210706, '2021-07-06', 2021, 7, 6, 3, 27, 'Tuesday', 'July', 'f', 'f', NULL),
(20210707, '2021-07-07', 2021, 7, 7, 3, 27, 'Wednesday', 'July', 'f', 'f', NULL),
(20210708, '2021-07-08', 2021, 7, 8, 3, 27, 'Thursday', 'July', 'f', 'f', NULL),
(20210709, '2021-07-09', 2021, 7, 9, 3, 27, 'Friday', 'July', 'f', 'f', NULL),
(20210710, '2021-07-10', 2021, 7, 10, 3, 27, 'Saturday', 'July', 'f', 't', NULL),
(20210711, '2021-07-11', 2021, 7, 11, 3, 27, 'Sunday', 'July', 'f', 't', NULL),
(20210712, '2021-07-12', 2021, 7, 12, 3, 28, 'Monday', 'July', 'f', 'f', NULL),
(20210713, '2021-07-13', 2021, 7, 13, 3, 28, 'Tuesday', 'July', 'f', 'f', NULL),
(20210714, '2021-07-14', 2021, 7, 14, 3, 28, 'Wednesday', 'July', 'f', 'f', NULL),
(20210715, '2021-07-15', 2021, 7, 15, 3, 28, 'Thursday', 'July', 'f', 'f', NULL),
(20210716, '2021-07-16', 2021, 7, 16, 3, 28, 'Friday', 'July', 'f', 'f', NULL),
(20210717, '2021-07-17', 2021, 7, 17, 3, 28, 'Saturday', 'July', 'f', 't', NULL),
(20210718, '2021-07-18', 2021, 7, 18, 3, 28, 'Sunday', 'July', 'f', 't', NULL),
(20210719, '2021-07-19', 2021, 7, 19, 3, 29, 'Monday', 'July', 'f', 'f', NULL),
(20210720, '2021-07-20', 2021, 7, 20, 3, 29, 'Tuesday', 'July', 'f', 'f', NULL),
(20210721, '2021-07-21', 2021, 7, 21, 3, 29, 'Wednesday', 'July', 'f', 'f', NULL),
(20210722, '2021-07-22', 2021, 7, 22, 3, 29, 'Thursday', 'July', 'f', 'f', NULL),
(20210723, '2021-07-23', 2021, 7, 23, 3, 29, 'Friday', 'July', 'f', 'f', NULL),
(20210724, '2021-07-24', 2021, 7, 24, 3, 29, 'Saturday', 'July', 'f', 't', NULL),
(20210725, '2021-07-25', 2021, 7, 25, 3, 29, 'Sunday', 'July', 'f', 't', NULL),
(20210726, '2021-07-26', 2021, 7, 26, 3, 30, 'Monday', 'July', 'f', 'f', NULL),
(20210727, '2021-07-27', 2021, 7, 27, 3, 30, 'Tuesday', 'July', 'f', 'f', NULL),
(20210728, '2021-07-28', 2021, 7, 28, 3, 30, 'Wednesday', 'July', 'f', 'f', NULL),
(20210729, '2021-07-29', 2021, 7, 29, 3, 30, 'Thursday', 'July', 'f', 'f', NULL),
(20210730, '2021-07-30', 2021, 7, 30, 3, 30, 'Friday', 'July', 'f', 'f', NULL),
(20210731, '2021-07-31', 2021, 7, 31, 3, 30, 'Saturday', 'July', 'f', 't', NULL),
(20210801, '2021-08-01', 2021, 8, 1, 3, 30, 'Sunday', 'August', 'f', 't', NULL),
(20210802, '2021-08-02', 2021, 8, 2, 3, 31, 'Monday', 'August', 'f', 'f', NULL),
(20210803, '2021-08-03', 2021, 8, 3, 3, 31, 'Tuesday', 'August', 'f', 'f', NULL),
(20210804, '2021-08-04', 2021, 8, 4, 3, 31, 'Wednesday', 'August', 'f', 'f', NULL),
(20210805, '2021-08-05', 2021, 8, 5, 3, 31, 'Thursday', 'August', 'f', 'f', NULL),
(20210806, '2021-08-06', 2021, 8, 6, 3, 31, 'Friday', 'August', 'f', 'f', NULL),
(20210807, '2021-08-07', 2021, 8, 7, 3, 31, 'Saturday', 'August', 'f', 't', NULL),
(20210808, '2021-08-08', 2021, 8, 8, 3, 31, 'Sunday', 'August', 'f', 't', NULL),
(20210809, '2021-08-09', 2021, 8, 9, 3, 32, 'Monday', 'August', 'f', 'f', NULL),
(20210810, '2021-08-10', 2021, 8, 10, 3, 32, 'Tuesday', 'August', 'f', 'f', NULL),
(20210811, '2021-08-11', 2021, 8, 11, 3, 32, 'Wednesday', 'August', 'f', 'f', NULL),
(20210812, '2021-08-12', 2021, 8, 12, 3, 32, 'Thursday', 'August', 'f', 'f', NULL),
(20210813, '2021-08-13', 2021, 8, 13, 3, 32, 'Friday', 'August', 'f', 'f', NULL),
(20210814, '2021-08-14', 2021, 8, 14, 3, 32, 'Saturday', 'August', 'f', 't', NULL),
(20210815, '2021-08-15', 2021, 8, 15, 3, 32, 'Sunday', 'August', 'f', 't', NULL),
(20210816, '2021-08-16', 2021, 8, 16, 3, 33, 'Monday', 'August', 'f', 'f', NULL),
(20210817, '2021-08-17', 2021, 8, 17, 3, 33, 'Tuesday', 'August', 'f', 'f', NULL),
(20210818, '2021-08-18', 2021, 8, 18, 3, 33, 'Wednesday', 'August', 'f', 'f', NULL),
(20210819, '2021-08-19', 2021, 8, 19, 3, 33, 'Thursday', 'August', 'f', 'f', NULL),
(20210820, '2021-08-20', 2021, 8, 20, 3, 33, 'Friday', 'August', 'f', 'f', NULL),
(20210821, '2021-08-21', 2021, 8, 21, 3, 33, 'Saturday', 'August', 'f', 't', NULL),
(20210822, '2021-08-22', 2021, 8, 22, 3, 33, 'Sunday', 'August', 'f', 't', NULL),
(20210823, '2021-08-23', 2021, 8, 23, 3, 34, 'Monday', 'August', 'f', 'f', NULL),
(20210824, '2021-08-24', 2021, 8, 24, 3, 34, 'Tuesday', 'August', 'f', 'f', NULL),
(20210825, '2021-08-25', 2021, 8, 25, 3, 34, 'Wednesday', 'August', 'f', 'f', NULL),
(20210826, '2021-08-26', 2021, 8, 26, 3, 34, 'Thursday', 'August', 'f', 'f', NULL),
(20210827, '2021-08-27', 2021, 8, 27, 3, 34, 'Friday', 'August', 'f', 'f', NULL),
(20210828, '2021-08-28', 2021, 8, 28, 3, 34, 'Saturday', 'August', 'f', 't', NULL),
(20210829, '2021-08-29', 2021, 8, 29, 3, 34, 'Sunday', 'August', 'f', 't', NULL),
(20210830, '2021-08-30', 2021, 8, 30, 3, 35, 'Monday', 'August', 'f', 'f', NULL),
(20210831, '2021-08-31', 2021, 8, 31, 3, 35, 'Tuesday', 'August', 'f', 'f', NULL),
(20210901, '2021-09-01', 2021, 9, 1, 3, 35, 'Wednesday', 'September', 'f', 'f', NULL),
(20210902, '2021-09-02', 2021, 9, 2, 3, 35, 'Thursday', 'September', 'f', 'f', NULL),
(20210903, '2021-09-03', 2021, 9, 3, 3, 35, 'Friday', 'September', 'f', 'f', NULL),
(20210904, '2021-09-04', 2021, 9, 4, 3, 35, 'Saturday', 'September', 'f', 't', NULL),
(20210905, '2021-09-05', 2021, 9, 5, 3, 35, 'Sunday', 'September', 'f', 't', NULL),
(20210906, '2021-09-06', 2021, 9, 6, 3, 36, 'Monday', 'September', 'f', 'f', NULL),
(20210907, '2021-09-07', 2021, 9, 7, 3, 36, 'Tuesday', 'September', 'f', 'f', NULL),
(20210908, '2021-09-08', 2021, 9, 8, 3, 36, 'Wednesday', 'September', 'f', 'f', NULL),
(20210909, '2021-09-09', 2021, 9, 9, 3, 36, 'Thursday', 'September', 'f', 'f', NULL),
(20210910, '2021-09-10', 2021, 9, 10, 3, 36, 'Friday', 'September', 'f', 'f', NULL),
(20210911, '2021-09-11', 2021, 9, 11, 3, 36, 'Saturday', 'September', 'f', 't', NULL),
(20210912, '2021-09-12', 2021, 9, 12, 3, 36, 'Sunday', 'September', 'f', 't', NULL),
(20210913, '2021-09-13', 2021, 9, 13, 3, 37, 'Monday', 'September', 'f', 'f', NULL),
(20210914, '2021-09-14', 2021, 9, 14, 3, 37, 'Tuesday', 'September', 'f', 'f', NULL),
(20210915, '2021-09-15', 2021, 9, 15, 3, 37, 'Wednesday', 'September', 'f', 'f', NULL),
(20210916, '2021-09-16', 2021, 9, 16, 3, 37, 'Thursday', 'September', 'f', 'f', NULL),
(20210917, '2021-09-17', 2021, 9, 17, 3, 37, 'Friday', 'September', 'f', 'f', NULL),
(20210918, '2021-09-18', 2021, 9, 18, 3, 37, 'Saturday', 'September', 'f', 't', NULL),
(20210919, '2021-09-19', 2021, 9, 19, 3, 37, 'Sunday', 'September', 'f', 't', NULL),
(20210920, '2021-09-20', 2021, 9, 20, 3, 38, 'Monday', 'September', 'f', 'f', NULL),
(20210921, '2021-09-21', 2021, 9, 21, 3, 38, 'Tuesday', 'September', 'f', 'f', NULL),
(20210922, '2021-09-22', 2021, 9, 22, 3, 38, 'Wednesday', 'September', 'f', 'f', NULL),
(20210923, '2021-09-23', 2021, 9, 23, 3, 38, 'Thursday', 'September', 'f', 'f', NULL),
(20210924, '2021-09-24', 2021, 9, 24, 3, 38, 'Friday', 'September', 'f', 'f', NULL),
(20210925, '2021-09-25', 2021, 9, 25, 3, 38, 'Saturday', 'September', 'f', 't', NULL),
(20210926, '2021-09-26', 2021, 9, 26, 3, 38, 'Sunday', 'September', 'f', 't', NULL),
(20210927, '2021-09-27', 2021, 9, 27, 3, 39, 'Monday', 'September', 'f', 'f', NULL),
(20210928, '2021-09-28', 2021, 9, 28, 3, 39, 'Tuesday', 'September', 'f', 'f', NULL),
(20210929, '2021-09-29', 2021, 9, 29, 3, 39, 'Wednesday', 'September', 'f', 'f', NULL),
(20210930, '2021-09-30', 2021, 9, 30, 3, 39, 'Thursday', 'September', 'f', 'f', NULL),
(20211001, '2021-10-01', 2021, 10, 1, 4, 39, 'Friday', 'October', 'f', 'f', NULL),
(20211002, '2021-10-02', 2021, 10, 2, 4, 39, 'Saturday', 'October', 'f', 't', NULL),
(20211003, '2021-10-03', 2021, 10, 3, 4, 39, 'Sunday', 'October', 'f', 't', NULL),
(20211004, '2021-10-04', 2021, 10, 4, 4, 40, 'Monday', 'October', 'f', 'f', NULL),
(20211005, '2021-10-05', 2021, 10, 5, 4, 40, 'Tuesday', 'October', 'f', 'f', NULL),
(20211006, '2021-10-06', 2021, 10, 6, 4, 40, 'Wednesday', 'October', 'f', 'f', NULL),
(20211007, '2021-10-07', 2021, 10, 7, 4, 40, 'Thursday', 'October', 'f', 'f', NULL),
(20211008, '2021-10-08', 2021, 10, 8, 4, 40, 'Friday', 'October', 'f', 'f', NULL),
(20211009, '2021-10-09', 2021, 10, 9, 4, 40, 'Saturday', 'October', 'f', 't', NULL),
(20211010, '2021-10-10', 2021, 10, 10, 4, 40, 'Sunday', 'October', 'f', 't', NULL),
(20211011, '2021-10-11', 2021, 10, 11, 4, 41, 'Monday', 'October', 'f', 'f', NULL),
(20211012, '2021-10-12', 2021, 10, 12, 4, 41, 'Tuesday', 'October', 'f', 'f', NULL),
(20211013, '2021-10-13', 2021, 10, 13, 4, 41, 'Wednesday', 'October', 'f', 'f', NULL),
(20211014, '2021-10-14', 2021, 10, 14, 4, 41, 'Thursday', 'October', 'f', 'f', NULL),
(20211015, '2021-10-15', 2021, 10, 15, 4, 41, 'Friday', 'October', 'f', 'f', NULL),
(20211016, '2021-10-16', 2021, 10, 16, 4, 41, 'Saturday', 'October', 'f', 't', NULL),
(20211017, '2021-10-17', 2021, 10, 17, 4, 41, 'Sunday', 'October', 'f', 't', NULL),
(20211018, '2021-10-18', 2021, 10, 18, 4, 42, 'Monday', 'October', 'f', 'f', NULL),
(20211019, '2021-10-19', 2021, 10, 19, 4, 42, 'Tuesday', 'October', 'f', 'f', NULL),
(20211020, '2021-10-20', 2021, 10, 20, 4, 42, 'Wednesday', 'October', 'f', 'f', NULL),
(20211021, '2021-10-21', 2021, 10, 21, 4, 42, 'Thursday', 'October', 'f', 'f', NULL),
(20211022, '2021-10-22', 2021, 10, 22, 4, 42, 'Friday', 'October', 'f', 'f', NULL),
(20211023, '2021-10-23', 2021, 10, 23, 4, 42, 'Saturday', 'October', 'f', 't', NULL),
(20211024, '2021-10-24', 2021, 10, 24, 4, 42, 'Sunday', 'October', 'f', 't', NULL),
(20211025, '2021-10-25', 2021, 10, 25, 4, 43, 'Monday', 'October', 'f', 'f', NULL),
(20211026, '2021-10-26', 2021, 10, 26, 4, 43, 'Tuesday', 'October', 'f', 'f', NULL),
(20211027, '2021-10-27', 2021, 10, 27, 4, 43, 'Wednesday', 'October', 'f', 'f', NULL),
(20211028, '2021-10-28', 2021, 10, 28, 4, 43, 'Thursday', 'October', 'f', 'f', NULL),
(20211029, '2021-10-29', 2021, 10, 29, 4, 43, 'Friday', 'October', 'f', 'f', NULL),
(20211030, '2021-10-30', 2021, 10, 30, 4, 43, 'Saturday', 'October', 'f', 't', NULL),
(20211031, '2021-10-31', 2021, 10, 31, 4, 43, 'Sunday', 'October', 'f', 't', NULL),
(20211101, '2021-11-01', 2021, 11, 1, 4, 44, 'Monday', 'November', 'f', 'f', NULL),
(20211102, '2021-11-02', 2021, 11, 2, 4, 44, 'Tuesday', 'November', 'f', 'f', NULL),
(20211103, '2021-11-03', 2021, 11, 3, 4, 44, 'Wednesday', 'November', 'f', 'f', NULL),
(20211104, '2021-11-04', 2021, 11, 4, 4, 44, 'Thursday', 'November', 'f', 'f', NULL),
(20211105, '2021-11-05', 2021, 11, 5, 4, 44, 'Friday', 'November', 'f', 'f', NULL),
(20211106, '2021-11-06', 2021, 11, 6, 4, 44, 'Saturday', 'November', 'f', 't', NULL),
(20211107, '2021-11-07', 2021, 11, 7, 4, 44, 'Sunday', 'November', 'f', 't', NULL),
(20211108, '2021-11-08', 2021, 11, 8, 4, 45, 'Monday', 'November', 'f', 'f', NULL),
(20211109, '2021-11-09', 2021, 11, 9, 4, 45, 'Tuesday', 'November', 'f', 'f', NULL),
(20211110, '2021-11-10', 2021, 11, 10, 4, 45, 'Wednesday', 'November', 'f', 'f', NULL),
(20211111, '2021-11-11', 2021, 11, 11, 4, 45, 'Thursday', 'November', 'f', 'f', NULL),
(20211112, '2021-11-12', 2021, 11, 12, 4, 45, 'Friday', 'November', 'f', 'f', NULL),
(20211113, '2021-11-13', 2021, 11, 13, 4, 45, 'Saturday', 'November', 'f', 't', NULL),
(20211114, '2021-11-14', 2021, 11, 14, 4, 45, 'Sunday', 'November', 'f', 't', NULL),
(20211115, '2021-11-15', 2021, 11, 15, 4, 46, 'Monday', 'November', 'f', 'f', NULL),
(20211116, '2021-11-16', 2021, 11, 16, 4, 46, 'Tuesday', 'November', 'f', 'f', NULL),
(20211117, '2021-11-17', 2021, 11, 17, 4, 46, 'Wednesday', 'November', 'f', 'f', NULL),
(20211118, '2021-11-18', 2021, 11, 18, 4, 46, 'Thursday', 'November', 'f', 'f', NULL),
(20211119, '2021-11-19', 2021, 11, 19, 4, 46, 'Friday', 'November', 'f', 'f', NULL),
(20211120, '2021-11-20', 2021, 11, 20, 4, 46, 'Saturday', 'November', 'f', 't', NULL),
(20211121, '2021-11-21', 2021, 11, 21, 4, 46, 'Sunday', 'November', 'f', 't', NULL),
(20211122, '2021-11-22', 2021, 11, 22, 4, 47, 'Monday', 'November', 'f', 'f', NULL),
(20211123, '2021-11-23', 2021, 11, 23, 4, 47, 'Tuesday', 'November', 'f', 'f', NULL),
(20211124, '2021-11-24', 2021, 11, 24, 4, 47, 'Wednesday', 'November', 'f', 'f', NULL),
(20211125, '2021-11-25', 2021, 11, 25, 4, 47, 'Thursday', 'November', 'f', 'f', NULL),
(20211126, '2021-11-26', 2021, 11, 26, 4, 47, 'Friday', 'November', 'f', 'f', NULL),
(20211127, '2021-11-27', 2021, 11, 27, 4, 47, 'Saturday', 'November', 'f', 't', NULL),
(20211128, '2021-11-28', 2021, 11, 28, 4, 47, 'Sunday', 'November', 'f', 't', NULL),
(20211129, '2021-11-29', 2021, 11, 29, 4, 48, 'Monday', 'November', 'f', 'f', NULL),
(20211130, '2021-11-30', 2021, 11, 30, 4, 48, 'Tuesday', 'November', 'f', 'f', NULL),
(20211201, '2021-12-01', 2021, 12, 1, 4, 48, 'Wednesday', 'December', 'f', 'f', NULL),
(20211202, '2021-12-02', 2021, 12, 2, 4, 48, 'Thursday', 'December', 'f', 'f', NULL),
(20211203, '2021-12-03', 2021, 12, 3, 4, 48, 'Friday', 'December', 'f', 'f', NULL),
(20211204, '2021-12-04', 2021, 12, 4, 4, 48, 'Saturday', 'December', 'f', 't', NULL),
(20211205, '2021-12-05', 2021, 12, 5, 4, 48, 'Sunday', 'December', 'f', 't', NULL),
(20211206, '2021-12-06', 2021, 12, 6, 4, 49, 'Monday', 'December', 'f', 'f', NULL),
(20211207, '2021-12-07', 2021, 12, 7, 4, 49, 'Tuesday', 'December', 'f', 'f', NULL),
(20211208, '2021-12-08', 2021, 12, 8, 4, 49, 'Wednesday', 'December', 'f', 'f', NULL),
(20211209, '2021-12-09', 2021, 12, 9, 4, 49, 'Thursday', 'December', 'f', 'f', NULL),
(20211210, '2021-12-10', 2021, 12, 10, 4, 49, 'Friday', 'December', 'f', 'f', NULL),
(20211211, '2021-12-11', 2021, 12, 11, 4, 49, 'Saturday', 'December', 'f', 't', NULL),
(20211212, '2021-12-12', 2021, 12, 12, 4, 49, 'Sunday', 'December', 'f', 't', NULL),
(20211213, '2021-12-13', 2021, 12, 13, 4, 50, 'Monday', 'December', 'f', 'f', NULL),
(20211214, '2021-12-14', 2021, 12, 14, 4, 50, 'Tuesday', 'December', 'f', 'f', NULL),
(20211215, '2021-12-15', 2021, 12, 15, 4, 50, 'Wednesday', 'December', 'f', 'f', NULL),
(20211216, '2021-12-16', 2021, 12, 16, 4, 50, 'Thursday', 'December', 'f', 'f', NULL),
(20211217, '2021-12-17', 2021, 12, 17, 4, 50, 'Friday', 'December', 'f', 'f', NULL),
(20211218, '2021-12-18', 2021, 12, 18, 4, 50, 'Saturday', 'December', 'f', 't', NULL),
(20211219, '2021-12-19', 2021, 12, 19, 4, 50, 'Sunday', 'December', 'f', 't', NULL),
(20211220, '2021-12-20', 2021, 12, 20, 4, 51, 'Monday', 'December', 'f', 'f', NULL),
(20211221, '2021-12-21', 2021, 12, 21, 4, 51, 'Tuesday', 'December', 'f', 'f', NULL),
(20211222, '2021-12-22', 2021, 12, 22, 4, 51, 'Wednesday', 'December', 'f', 'f', NULL),
(20211223, '2021-12-23', 2021, 12, 23, 4, 51, 'Thursday', 'December', 'f', 'f', NULL),
(20211224, '2021-12-24', 2021, 12, 24, 4, 51, 'Friday', 'December', 'f', 'f', NULL),
(20211225, '2021-12-25', 2021, 12, 25, 4, 51, 'Saturday', 'December', 'f', 't', NULL),
(20211226, '2021-12-26', 2021, 12, 26, 4, 51, 'Sunday', 'December', 'f', 't', NULL),
(20211227, '2021-12-27', 2021, 12, 27, 4, 52, 'Monday', 'December', 'f', 'f', NULL),
(20211228, '2021-12-28', 2021, 12, 28, 4, 52, 'Tuesday', 'December', 'f', 'f', NULL),
(20211229, '2021-12-29', 2021, 12, 29, 4, 52, 'Wednesday', 'December', 'f', 'f', NULL),
(20211230, '2021-12-30', 2021, 12, 30, 4, 52, 'Thursday', 'December', 'f', 'f', NULL),
(20211231, '2021-12-31', 2021, 12, 31, 4, 52, 'Friday', 'December', 'f', 'f', NULL),
(20220101, '2022-01-01', 2022, 1, 1, 1, 52, 'Saturday', 'January', 'f', 't', NULL),
(20220102, '2022-01-02', 2022, 1, 2, 1, 52, 'Sunday', 'January', 'f', 't', NULL),
(20220103, '2022-01-03', 2022, 1, 3, 1, 1, 'Monday', 'January', 'f', 'f', NULL),
(20220104, '2022-01-04', 2022, 1, 4, 1, 1, 'Tuesday', 'January', 'f', 'f', NULL),
(20220105, '2022-01-05', 2022, 1, 5, 1, 1, 'Wednesday', 'January', 'f', 'f', NULL),
(20220106, '2022-01-06', 2022, 1, 6, 1, 1, 'Thursday', 'January', 'f', 'f', NULL),
(20220107, '2022-01-07', 2022, 1, 7, 1, 1, 'Friday', 'January', 'f', 'f', NULL),
(20220108, '2022-01-08', 2022, 1, 8, 1, 1, 'Saturday', 'January', 'f', 't', NULL),
(20220109, '2022-01-09', 2022, 1, 9, 1, 1, 'Sunday', 'January', 'f', 't', NULL),
(20220110, '2022-01-10', 2022, 1, 10, 1, 2, 'Monday', 'January', 'f', 'f', NULL),
(20220111, '2022-01-11', 2022, 1, 11, 1, 2, 'Tuesday', 'January', 'f', 'f', NULL),
(20220112, '2022-01-12', 2022, 1, 12, 1, 2, 'Wednesday', 'January', 'f', 'f', NULL),
(20220113, '2022-01-13', 2022, 1, 13, 1, 2, 'Thursday', 'January', 'f', 'f', NULL),
(20220114, '2022-01-14', 2022, 1, 14, 1, 2, 'Friday', 'January', 'f', 'f', NULL),
(20220115, '2022-01-15', 2022, 1, 15, 1, 2, 'Saturday', 'January', 'f', 't', NULL),
(20220116, '2022-01-16', 2022, 1, 16, 1, 2, 'Sunday', 'January', 'f', 't', NULL),
(20220117, '2022-01-17', 2022, 1, 17, 1, 3, 'Monday', 'January', 'f', 'f', NULL),
(20220118, '2022-01-18', 2022, 1, 18, 1, 3, 'Tuesday', 'January', 'f', 'f', NULL),
(20220119, '2022-01-19', 2022, 1, 19, 1, 3, 'Wednesday', 'January', 'f', 'f', NULL),
(20220120, '2022-01-20', 2022, 1, 20, 1, 3, 'Thursday', 'January', 'f', 'f', NULL),
(20220121, '2022-01-21', 2022, 1, 21, 1, 3, 'Friday', 'January', 'f', 'f', NULL),
(20220122, '2022-01-22', 2022, 1, 22, 1, 3, 'Saturday', 'January', 'f', 't', NULL),
(20220123, '2022-01-23', 2022, 1, 23, 1, 3, 'Sunday', 'January', 'f', 't', NULL),
(20220124, '2022-01-24', 2022, 1, 24, 1, 4, 'Monday', 'January', 'f', 'f', NULL),
(20220125, '2022-01-25', 2022, 1, 25, 1, 4, 'Tuesday', 'January', 'f', 'f', NULL),
(20220126, '2022-01-26', 2022, 1, 26, 1, 4, 'Wednesday', 'January', 'f', 'f', NULL),
(20220127, '2022-01-27', 2022, 1, 27, 1, 4, 'Thursday', 'January', 'f', 'f', NULL),
(20220128, '2022-01-28', 2022, 1, 28, 1, 4, 'Friday', 'January', 'f', 'f', NULL),
(20220129, '2022-01-29', 2022, 1, 29, 1, 4, 'Saturday', 'January', 'f', 't', NULL),
(20220130, '2022-01-30', 2022, 1, 30, 1, 4, 'Sunday', 'January', 'f', 't', NULL),
(20220131, '2022-01-31', 2022, 1, 31, 1, 5, 'Monday', 'January', 'f', 'f', NULL),
(20220201, '2022-02-01', 2022, 2, 1, 1, 5, 'Tuesday', 'February', 'f', 'f', NULL),
(20220202, '2022-02-02', 2022, 2, 2, 1, 5, 'Wednesday', 'February', 'f', 'f', NULL),
(20220203, '2022-02-03', 2022, 2, 3, 1, 5, 'Thursday', 'February', 'f', 'f', NULL),
(20220204, '2022-02-04', 2022, 2, 4, 1, 5, 'Friday', 'February', 'f', 'f', NULL),
(20220205, '2022-02-05', 2022, 2, 5, 1, 5, 'Saturday', 'February', 'f', 't', NULL),
(20220206, '2022-02-06', 2022, 2, 6, 1, 5, 'Sunday', 'February', 'f', 't', NULL),
(20220207, '2022-02-07', 2022, 2, 7, 1, 6, 'Monday', 'February', 'f', 'f', NULL),
(20220208, '2022-02-08', 2022, 2, 8, 1, 6, 'Tuesday', 'February', 'f', 'f', NULL),
(20220209, '2022-02-09', 2022, 2, 9, 1, 6, 'Wednesday', 'February', 'f', 'f', NULL),
(20220210, '2022-02-10', 2022, 2, 10, 1, 6, 'Thursday', 'February', 'f', 'f', NULL),
(20220211, '2022-02-11', 2022, 2, 11, 1, 6, 'Friday', 'February', 'f', 'f', NULL),
(20220212, '2022-02-12', 2022, 2, 12, 1, 6, 'Saturday', 'February', 'f', 't', NULL),
(20220213, '2022-02-13', 2022, 2, 13, 1, 6, 'Sunday', 'February', 'f', 't', NULL),
(20220214, '2022-02-14', 2022, 2, 14, 1, 7, 'Monday', 'February', 'f', 'f', NULL),
(20220215, '2022-02-15', 2022, 2, 15, 1, 7, 'Tuesday', 'February', 'f', 'f', NULL),
(20220216, '2022-02-16', 2022, 2, 16, 1, 7, 'Wednesday', 'February', 'f', 'f', NULL),
(20220217, '2022-02-17', 2022, 2, 17, 1, 7, 'Thursday', 'February', 'f', 'f', NULL),
(20220218, '2022-02-18', 2022, 2, 18, 1, 7, 'Friday', 'February', 'f', 'f', NULL),
(20220219, '2022-02-19', 2022, 2, 19, 1, 7, 'Saturday', 'February', 'f', 't', NULL),
(20220220, '2022-02-20', 2022, 2, 20, 1, 7, 'Sunday', 'February', 'f', 't', NULL),
(20220221, '2022-02-21', 2022, 2, 21, 1, 8, 'Monday', 'February', 'f', 'f', NULL),
(20220222, '2022-02-22', 2022, 2, 22, 1, 8, 'Tuesday', 'February', 'f', 'f', NULL),
(20220223, '2022-02-23', 2022, 2, 23, 1, 8, 'Wednesday', 'February', 'f', 'f', NULL),
(20220224, '2022-02-24', 2022, 2, 24, 1, 8, 'Thursday', 'February', 'f', 'f', NULL),
(20220225, '2022-02-25', 2022, 2, 25, 1, 8, 'Friday', 'February', 'f', 'f', NULL),
(20220226, '2022-02-26', 2022, 2, 26, 1, 8, 'Saturday', 'February', 'f', 't', NULL),
(20220227, '2022-02-27', 2022, 2, 27, 1, 8, 'Sunday', 'February', 'f', 't', NULL),
(20220228, '2022-02-28', 2022, 2, 28, 1, 9, 'Monday', 'February', 'f', 'f', NULL),
(20220301, '2022-03-01', 2022, 3, 1, 1, 9, 'Tuesday', 'March', 'f', 'f', NULL),
(20220302, '2022-03-02', 2022, 3, 2, 1, 9, 'Wednesday', 'March', 'f', 'f', NULL),
(20220303, '2022-03-03', 2022, 3, 3, 1, 9, 'Thursday', 'March', 'f', 'f', NULL),
(20220304, '2022-03-04', 2022, 3, 4, 1, 9, 'Friday', 'March', 'f', 'f', NULL),
(20220305, '2022-03-05', 2022, 3, 5, 1, 9, 'Saturday', 'March', 'f', 't', NULL),
(20220306, '2022-03-06', 2022, 3, 6, 1, 9, 'Sunday', 'March', 'f', 't', NULL),
(20220307, '2022-03-07', 2022, 3, 7, 1, 10, 'Monday', 'March', 'f', 'f', NULL),
(20220308, '2022-03-08', 2022, 3, 8, 1, 10, 'Tuesday', 'March', 'f', 'f', NULL),
(20220309, '2022-03-09', 2022, 3, 9, 1, 10, 'Wednesday', 'March', 'f', 'f', NULL),
(20220310, '2022-03-10', 2022, 3, 10, 1, 10, 'Thursday', 'March', 'f', 'f', NULL),
(20220311, '2022-03-11', 2022, 3, 11, 1, 10, 'Friday', 'March', 'f', 'f', NULL),
(20220312, '2022-03-12', 2022, 3, 12, 1, 10, 'Saturday', 'March', 'f', 't', NULL),
(20220313, '2022-03-13', 2022, 3, 13, 1, 10, 'Sunday', 'March', 'f', 't', NULL),
(20220314, '2022-03-14', 2022, 3, 14, 1, 11, 'Monday', 'March', 'f', 'f', NULL),
(20220315, '2022-03-15', 2022, 3, 15, 1, 11, 'Tuesday', 'March', 'f', 'f', NULL),
(20220316, '2022-03-16', 2022, 3, 16, 1, 11, 'Wednesday', 'March', 'f', 'f', NULL),
(20220317, '2022-03-17', 2022, 3, 17, 1, 11, 'Thursday', 'March', 'f', 'f', NULL),
(20220318, '2022-03-18', 2022, 3, 18, 1, 11, 'Friday', 'March', 'f', 'f', NULL),
(20220319, '2022-03-19', 2022, 3, 19, 1, 11, 'Saturday', 'March', 'f', 't', NULL),
(20220320, '2022-03-20', 2022, 3, 20, 1, 11, 'Sunday', 'March', 'f', 't', NULL),
(20220321, '2022-03-21', 2022, 3, 21, 1, 12, 'Monday', 'March', 'f', 'f', NULL),
(20220322, '2022-03-22', 2022, 3, 22, 1, 12, 'Tuesday', 'March', 'f', 'f', NULL),
(20220323, '2022-03-23', 2022, 3, 23, 1, 12, 'Wednesday', 'March', 'f', 'f', NULL),
(20220324, '2022-03-24', 2022, 3, 24, 1, 12, 'Thursday', 'March', 'f', 'f', NULL),
(20220325, '2022-03-25', 2022, 3, 25, 1, 12, 'Friday', 'March', 'f', 'f', NULL),
(20220326, '2022-03-26', 2022, 3, 26, 1, 12, 'Saturday', 'March', 'f', 't', NULL),
(20220327, '2022-03-27', 2022, 3, 27, 1, 12, 'Sunday', 'March', 'f', 't', NULL),
(20220328, '2022-03-28', 2022, 3, 28, 1, 13, 'Monday', 'March', 'f', 'f', NULL),
(20220329, '2022-03-29', 2022, 3, 29, 1, 13, 'Tuesday', 'March', 'f', 'f', NULL),
(20220330, '2022-03-30', 2022, 3, 30, 1, 13, 'Wednesday', 'March', 'f', 'f', NULL),
(20220331, '2022-03-31', 2022, 3, 31, 1, 13, 'Thursday', 'March', 'f', 'f', NULL),
(20220401, '2022-04-01', 2022, 4, 1, 2, 13, 'Friday', 'April', 'f', 'f', NULL),
(20220402, '2022-04-02', 2022, 4, 2, 2, 13, 'Saturday', 'April', 'f', 't', NULL),
(20220403, '2022-04-03', 2022, 4, 3, 2, 13, 'Sunday', 'April', 'f', 't', NULL),
(20220404, '2022-04-04', 2022, 4, 4, 2, 14, 'Monday', 'April', 'f', 'f', NULL),
(20220405, '2022-04-05', 2022, 4, 5, 2, 14, 'Tuesday', 'April', 'f', 'f', NULL),
(20220406, '2022-04-06', 2022, 4, 6, 2, 14, 'Wednesday', 'April', 'f', 'f', NULL),
(20220407, '2022-04-07', 2022, 4, 7, 2, 14, 'Thursday', 'April', 'f', 'f', NULL),
(20220408, '2022-04-08', 2022, 4, 8, 2, 14, 'Friday', 'April', 'f', 'f', NULL),
(20220409, '2022-04-09', 2022, 4, 9, 2, 14, 'Saturday', 'April', 'f', 't', NULL),
(20220410, '2022-04-10', 2022, 4, 10, 2, 14, 'Sunday', 'April', 'f', 't', NULL),
(20220411, '2022-04-11', 2022, 4, 11, 2, 15, 'Monday', 'April', 'f', 'f', NULL),
(20220412, '2022-04-12', 2022, 4, 12, 2, 15, 'Tuesday', 'April', 'f', 'f', NULL),
(20220413, '2022-04-13', 2022, 4, 13, 2, 15, 'Wednesday', 'April', 'f', 'f', NULL),
(20220414, '2022-04-14', 2022, 4, 14, 2, 15, 'Thursday', 'April', 'f', 'f', NULL),
(20220415, '2022-04-15', 2022, 4, 15, 2, 15, 'Friday', 'April', 'f', 'f', NULL),
(20220416, '2022-04-16', 2022, 4, 16, 2, 15, 'Saturday', 'April', 'f', 't', NULL),
(20220417, '2022-04-17', 2022, 4, 17, 2, 15, 'Sunday', 'April', 'f', 't', NULL),
(20220418, '2022-04-18', 2022, 4, 18, 2, 16, 'Monday', 'April', 'f', 'f', NULL),
(20220419, '2022-04-19', 2022, 4, 19, 2, 16, 'Tuesday', 'April', 'f', 'f', NULL),
(20220420, '2022-04-20', 2022, 4, 20, 2, 16, 'Wednesday', 'April', 'f', 'f', NULL),
(20220421, '2022-04-21', 2022, 4, 21, 2, 16, 'Thursday', 'April', 'f', 'f', NULL),
(20220422, '2022-04-22', 2022, 4, 22, 2, 16, 'Friday', 'April', 'f', 'f', NULL),
(20220423, '2022-04-23', 2022, 4, 23, 2, 16, 'Saturday', 'April', 'f', 't', NULL),
(20220424, '2022-04-24', 2022, 4, 24, 2, 16, 'Sunday', 'April', 'f', 't', NULL),
(20220425, '2022-04-25', 2022, 4, 25, 2, 17, 'Monday', 'April', 'f', 'f', NULL),
(20220426, '2022-04-26', 2022, 4, 26, 2, 17, 'Tuesday', 'April', 'f', 'f', NULL),
(20220427, '2022-04-27', 2022, 4, 27, 2, 17, 'Wednesday', 'April', 'f', 'f', NULL),
(20220428, '2022-04-28', 2022, 4, 28, 2, 17, 'Thursday', 'April', 'f', 'f', NULL),
(20220429, '2022-04-29', 2022, 4, 29, 2, 17, 'Friday', 'April', 'f', 'f', NULL),
(20220430, '2022-04-30', 2022, 4, 30, 2, 17, 'Saturday', 'April', 'f', 't', NULL),
(20220501, '2022-05-01', 2022, 5, 1, 2, 17, 'Sunday', 'May', 'f', 't', NULL),
(20220502, '2022-05-02', 2022, 5, 2, 2, 18, 'Monday', 'May', 'f', 'f', NULL),
(20220503, '2022-05-03', 2022, 5, 3, 2, 18, 'Tuesday', 'May', 'f', 'f', NULL),
(20220504, '2022-05-04', 2022, 5, 4, 2, 18, 'Wednesday', 'May', 'f', 'f', NULL),
(20220505, '2022-05-05', 2022, 5, 5, 2, 18, 'Thursday', 'May', 'f', 'f', NULL),
(20220506, '2022-05-06', 2022, 5, 6, 2, 18, 'Friday', 'May', 'f', 'f', NULL),
(20220507, '2022-05-07', 2022, 5, 7, 2, 18, 'Saturday', 'May', 'f', 't', NULL),
(20220508, '2022-05-08', 2022, 5, 8, 2, 18, 'Sunday', 'May', 'f', 't', NULL),
(20220509, '2022-05-09', 2022, 5, 9, 2, 19, 'Monday', 'May', 'f', 'f', NULL),
(20220510, '2022-05-10', 2022, 5, 10, 2, 19, 'Tuesday', 'May', 'f', 'f', NULL),
(20220511, '2022-05-11', 2022, 5, 11, 2, 19, 'Wednesday', 'May', 'f', 'f', NULL),
(20220512, '2022-05-12', 2022, 5, 12, 2, 19, 'Thursday', 'May', 'f', 'f', NULL),
(20220513, '2022-05-13', 2022, 5, 13, 2, 19, 'Friday', 'May', 'f', 'f', NULL),
(20220514, '2022-05-14', 2022, 5, 14, 2, 19, 'Saturday', 'May', 'f', 't', NULL),
(20220515, '2022-05-15', 2022, 5, 15, 2, 19, 'Sunday', 'May', 'f', 't', NULL),
(20220516, '2022-05-16', 2022, 5, 16, 2, 20, 'Monday', 'May', 'f', 'f', NULL),
(20220517, '2022-05-17', 2022, 5, 17, 2, 20, 'Tuesday', 'May', 'f', 'f', NULL),
(20220518, '2022-05-18', 2022, 5, 18, 2, 20, 'Wednesday', 'May', 'f', 'f', NULL),
(20220519, '2022-05-19', 2022, 5, 19, 2, 20, 'Thursday', 'May', 'f', 'f', NULL),
(20220520, '2022-05-20', 2022, 5, 20, 2, 20, 'Friday', 'May', 'f', 'f', NULL),
(20220521, '2022-05-21', 2022, 5, 21, 2, 20, 'Saturday', 'May', 'f', 't', NULL),
(20220522, '2022-05-22', 2022, 5, 22, 2, 20, 'Sunday', 'May', 'f', 't', NULL),
(20220523, '2022-05-23', 2022, 5, 23, 2, 21, 'Monday', 'May', 'f', 'f', NULL),
(20220524, '2022-05-24', 2022, 5, 24, 2, 21, 'Tuesday', 'May', 'f', 'f', NULL),
(20220525, '2022-05-25', 2022, 5, 25, 2, 21, 'Wednesday', 'May', 'f', 'f', NULL),
(20220526, '2022-05-26', 2022, 5, 26, 2, 21, 'Thursday', 'May', 'f', 'f', NULL),
(20220527, '2022-05-27', 2022, 5, 27, 2, 21, 'Friday', 'May', 'f', 'f', NULL),
(20220528, '2022-05-28', 2022, 5, 28, 2, 21, 'Saturday', 'May', 'f', 't', NULL),
(20220529, '2022-05-29', 2022, 5, 29, 2, 21, 'Sunday', 'May', 'f', 't', NULL),
(20220530, '2022-05-30', 2022, 5, 30, 2, 22, 'Monday', 'May', 'f', 'f', NULL),
(20220531, '2022-05-31', 2022, 5, 31, 2, 22, 'Tuesday', 'May', 'f', 'f', NULL),
(20220601, '2022-06-01', 2022, 6, 1, 2, 22, 'Wednesday', 'June', 'f', 'f', NULL),
(20220602, '2022-06-02', 2022, 6, 2, 2, 22, 'Thursday', 'June', 'f', 'f', NULL),
(20220603, '2022-06-03', 2022, 6, 3, 2, 22, 'Friday', 'June', 'f', 'f', NULL),
(20220604, '2022-06-04', 2022, 6, 4, 2, 22, 'Saturday', 'June', 'f', 't', NULL),
(20220605, '2022-06-05', 2022, 6, 5, 2, 22, 'Sunday', 'June', 'f', 't', NULL),
(20220606, '2022-06-06', 2022, 6, 6, 2, 23, 'Monday', 'June', 'f', 'f', NULL),
(20220607, '2022-06-07', 2022, 6, 7, 2, 23, 'Tuesday', 'June', 'f', 'f', NULL),
(20220608, '2022-06-08', 2022, 6, 8, 2, 23, 'Wednesday', 'June', 'f', 'f', NULL),
(20220609, '2022-06-09', 2022, 6, 9, 2, 23, 'Thursday', 'June', 'f', 'f', NULL),
(20220610, '2022-06-10', 2022, 6, 10, 2, 23, 'Friday', 'June', 'f', 'f', NULL),
(20220611, '2022-06-11', 2022, 6, 11, 2, 23, 'Saturday', 'June', 'f', 't', NULL),
(20220612, '2022-06-12', 2022, 6, 12, 2, 23, 'Sunday', 'June', 'f', 't', NULL),
(20220613, '2022-06-13', 2022, 6, 13, 2, 24, 'Monday', 'June', 'f', 'f', NULL),
(20220614, '2022-06-14', 2022, 6, 14, 2, 24, 'Tuesday', 'June', 'f', 'f', NULL),
(20220615, '2022-06-15', 2022, 6, 15, 2, 24, 'Wednesday', 'June', 'f', 'f', NULL),
(20220616, '2022-06-16', 2022, 6, 16, 2, 24, 'Thursday', 'June', 'f', 'f', NULL),
(20220617, '2022-06-17', 2022, 6, 17, 2, 24, 'Friday', 'June', 'f', 'f', NULL),
(20220618, '2022-06-18', 2022, 6, 18, 2, 24, 'Saturday', 'June', 'f', 't', NULL),
(20220619, '2022-06-19', 2022, 6, 19, 2, 24, 'Sunday', 'June', 'f', 't', NULL),
(20220620, '2022-06-20', 2022, 6, 20, 2, 25, 'Monday', 'June', 'f', 'f', NULL),
(20220621, '2022-06-21', 2022, 6, 21, 2, 25, 'Tuesday', 'June', 'f', 'f', NULL),
(20220622, '2022-06-22', 2022, 6, 22, 2, 25, 'Wednesday', 'June', 'f', 'f', NULL),
(20220623, '2022-06-23', 2022, 6, 23, 2, 25, 'Thursday', 'June', 'f', 'f', NULL),
(20220624, '2022-06-24', 2022, 6, 24, 2, 25, 'Friday', 'June', 'f', 'f', NULL),
(20220625, '2022-06-25', 2022, 6, 25, 2, 25, 'Saturday', 'June', 'f', 't', NULL),
(20220626, '2022-06-26', 2022, 6, 26, 2, 25, 'Sunday', 'June', 'f', 't', NULL),
(20220627, '2022-06-27', 2022, 6, 27, 2, 26, 'Monday', 'June', 'f', 'f', NULL),
(20220628, '2022-06-28', 2022, 6, 28, 2, 26, 'Tuesday', 'June', 'f', 'f', NULL),
(20220629, '2022-06-29', 2022, 6, 29, 2, 26, 'Wednesday', 'June', 'f', 'f', NULL),
(20220630, '2022-06-30', 2022, 6, 30, 2, 26, 'Thursday', 'June', 'f', 'f', NULL),
(20220701, '2022-07-01', 2022, 7, 1, 3, 26, 'Friday', 'July', 'f', 'f', NULL),
(20220702, '2022-07-02', 2022, 7, 2, 3, 26, 'Saturday', 'July', 'f', 't', NULL),
(20220703, '2022-07-03', 2022, 7, 3, 3, 26, 'Sunday', 'July', 'f', 't', NULL),
(20220704, '2022-07-04', 2022, 7, 4, 3, 27, 'Monday', 'July', 'f', 'f', NULL),
(20220705, '2022-07-05', 2022, 7, 5, 3, 27, 'Tuesday', 'July', 'f', 'f', NULL),
(20220706, '2022-07-06', 2022, 7, 6, 3, 27, 'Wednesday', 'July', 'f', 'f', NULL),
(20220707, '2022-07-07', 2022, 7, 7, 3, 27, 'Thursday', 'July', 'f', 'f', NULL),
(20220708, '2022-07-08', 2022, 7, 8, 3, 27, 'Friday', 'July', 'f', 'f', NULL),
(20220709, '2022-07-09', 2022, 7, 9, 3, 27, 'Saturday', 'July', 'f', 't', NULL),
(20220710, '2022-07-10', 2022, 7, 10, 3, 27, 'Sunday', 'July', 'f', 't', NULL),
(20220711, '2022-07-11', 2022, 7, 11, 3, 28, 'Monday', 'July', 'f', 'f', NULL),
(20220712, '2022-07-12', 2022, 7, 12, 3, 28, 'Tuesday', 'July', 'f', 'f', NULL),
(20220713, '2022-07-13', 2022, 7, 13, 3, 28, 'Wednesday', 'July', 'f', 'f', NULL),
(20220714, '2022-07-14', 2022, 7, 14, 3, 28, 'Thursday', 'July', 'f', 'f', NULL),
(20220715, '2022-07-15', 2022, 7, 15, 3, 28, 'Friday', 'July', 'f', 'f', NULL),
(20220716, '2022-07-16', 2022, 7, 16, 3, 28, 'Saturday', 'July', 'f', 't', NULL),
(20220717, '2022-07-17', 2022, 7, 17, 3, 28, 'Sunday', 'July', 'f', 't', NULL),
(20220718, '2022-07-18', 2022, 7, 18, 3, 29, 'Monday', 'July', 'f', 'f', NULL),
(20220719, '2022-07-19', 2022, 7, 19, 3, 29, 'Tuesday', 'July', 'f', 'f', NULL),
(20220720, '2022-07-20', 2022, 7, 20, 3, 29, 'Wednesday', 'July', 'f', 'f', NULL),
(20220721, '2022-07-21', 2022, 7, 21, 3, 29, 'Thursday', 'July', 'f', 'f', NULL),
(20220722, '2022-07-22', 2022, 7, 22, 3, 29, 'Friday', 'July', 'f', 'f', NULL),
(20220723, '2022-07-23', 2022, 7, 23, 3, 29, 'Saturday', 'July', 'f', 't', NULL),
(20220724, '2022-07-24', 2022, 7, 24, 3, 29, 'Sunday', 'July', 'f', 't', NULL),
(20220725, '2022-07-25', 2022, 7, 25, 3, 30, 'Monday', 'July', 'f', 'f', NULL),
(20220726, '2022-07-26', 2022, 7, 26, 3, 30, 'Tuesday', 'July', 'f', 'f', NULL),
(20220727, '2022-07-27', 2022, 7, 27, 3, 30, 'Wednesday', 'July', 'f', 'f', NULL),
(20220728, '2022-07-28', 2022, 7, 28, 3, 30, 'Thursday', 'July', 'f', 'f', NULL),
(20220729, '2022-07-29', 2022, 7, 29, 3, 30, 'Friday', 'July', 'f', 'f', NULL),
(20220730, '2022-07-30', 2022, 7, 30, 3, 30, 'Saturday', 'July', 'f', 't', NULL),
(20220731, '2022-07-31', 2022, 7, 31, 3, 30, 'Sunday', 'July', 'f', 't', NULL),
(20220801, '2022-08-01', 2022, 8, 1, 3, 31, 'Monday', 'August', 'f', 'f', NULL),
(20220802, '2022-08-02', 2022, 8, 2, 3, 31, 'Tuesday', 'August', 'f', 'f', NULL),
(20220803, '2022-08-03', 2022, 8, 3, 3, 31, 'Wednesday', 'August', 'f', 'f', NULL),
(20220804, '2022-08-04', 2022, 8, 4, 3, 31, 'Thursday', 'August', 'f', 'f', NULL),
(20220805, '2022-08-05', 2022, 8, 5, 3, 31, 'Friday', 'August', 'f', 'f', NULL),
(20220806, '2022-08-06', 2022, 8, 6, 3, 31, 'Saturday', 'August', 'f', 't', NULL),
(20220807, '2022-08-07', 2022, 8, 7, 3, 31, 'Sunday', 'August', 'f', 't', NULL),
(20220808, '2022-08-08', 2022, 8, 8, 3, 32, 'Monday', 'August', 'f', 'f', NULL),
(20220809, '2022-08-09', 2022, 8, 9, 3, 32, 'Tuesday', 'August', 'f', 'f', NULL),
(20220810, '2022-08-10', 2022, 8, 10, 3, 32, 'Wednesday', 'August', 'f', 'f', NULL),
(20220811, '2022-08-11', 2022, 8, 11, 3, 32, 'Thursday', 'August', 'f', 'f', NULL),
(20220812, '2022-08-12', 2022, 8, 12, 3, 32, 'Friday', 'August', 'f', 'f', NULL),
(20220813, '2022-08-13', 2022, 8, 13, 3, 32, 'Saturday', 'August', 'f', 't', NULL),
(20220814, '2022-08-14', 2022, 8, 14, 3, 32, 'Sunday', 'August', 'f', 't', NULL),
(20220815, '2022-08-15', 2022, 8, 15, 3, 33, 'Monday', 'August', 'f', 'f', NULL),
(20220816, '2022-08-16', 2022, 8, 16, 3, 33, 'Tuesday', 'August', 'f', 'f', NULL),
(20220817, '2022-08-17', 2022, 8, 17, 3, 33, 'Wednesday', 'August', 'f', 'f', NULL),
(20220818, '2022-08-18', 2022, 8, 18, 3, 33, 'Thursday', 'August', 'f', 'f', NULL),
(20220819, '2022-08-19', 2022, 8, 19, 3, 33, 'Friday', 'August', 'f', 'f', NULL),
(20220820, '2022-08-20', 2022, 8, 20, 3, 33, 'Saturday', 'August', 'f', 't', NULL),
(20220821, '2022-08-21', 2022, 8, 21, 3, 33, 'Sunday', 'August', 'f', 't', NULL),
(20220822, '2022-08-22', 2022, 8, 22, 3, 34, 'Monday', 'August', 'f', 'f', NULL),
(20220823, '2022-08-23', 2022, 8, 23, 3, 34, 'Tuesday', 'August', 'f', 'f', NULL),
(20220824, '2022-08-24', 2022, 8, 24, 3, 34, 'Wednesday', 'August', 'f', 'f', NULL),
(20220825, '2022-08-25', 2022, 8, 25, 3, 34, 'Thursday', 'August', 'f', 'f', NULL),
(20220826, '2022-08-26', 2022, 8, 26, 3, 34, 'Friday', 'August', 'f', 'f', NULL),
(20220827, '2022-08-27', 2022, 8, 27, 3, 34, 'Saturday', 'August', 'f', 't', NULL),
(20220828, '2022-08-28', 2022, 8, 28, 3, 34, 'Sunday', 'August', 'f', 't', NULL),
(20220829, '2022-08-29', 2022, 8, 29, 3, 35, 'Monday', 'August', 'f', 'f', NULL),
(20220830, '2022-08-30', 2022, 8, 30, 3, 35, 'Tuesday', 'August', 'f', 'f', NULL),
(20220831, '2022-08-31', 2022, 8, 31, 3, 35, 'Wednesday', 'August', 'f', 'f', NULL),
(20220901, '2022-09-01', 2022, 9, 1, 3, 35, 'Thursday', 'September', 'f', 'f', NULL),
(20220902, '2022-09-02', 2022, 9, 2, 3, 35, 'Friday', 'September', 'f', 'f', NULL),
(20220903, '2022-09-03', 2022, 9, 3, 3, 35, 'Saturday', 'September', 'f', 't', NULL),
(20220904, '2022-09-04', 2022, 9, 4, 3, 35, 'Sunday', 'September', 'f', 't', NULL),
(20220905, '2022-09-05', 2022, 9, 5, 3, 36, 'Monday', 'September', 'f', 'f', NULL),
(20220906, '2022-09-06', 2022, 9, 6, 3, 36, 'Tuesday', 'September', 'f', 'f', NULL),
(20220907, '2022-09-07', 2022, 9, 7, 3, 36, 'Wednesday', 'September', 'f', 'f', NULL),
(20220908, '2022-09-08', 2022, 9, 8, 3, 36, 'Thursday', 'September', 'f', 'f', NULL),
(20220909, '2022-09-09', 2022, 9, 9, 3, 36, 'Friday', 'September', 'f', 'f', NULL),
(20220910, '2022-09-10', 2022, 9, 10, 3, 36, 'Saturday', 'September', 'f', 't', NULL),
(20220911, '2022-09-11', 2022, 9, 11, 3, 36, 'Sunday', 'September', 'f', 't', NULL),
(20220912, '2022-09-12', 2022, 9, 12, 3, 37, 'Monday', 'September', 'f', 'f', NULL),
(20220913, '2022-09-13', 2022, 9, 13, 3, 37, 'Tuesday', 'September', 'f', 'f', NULL),
(20220914, '2022-09-14', 2022, 9, 14, 3, 37, 'Wednesday', 'September', 'f', 'f', NULL),
(20220915, '2022-09-15', 2022, 9, 15, 3, 37, 'Thursday', 'September', 'f', 'f', NULL),
(20220916, '2022-09-16', 2022, 9, 16, 3, 37, 'Friday', 'September', 'f', 'f', NULL),
(20220917, '2022-09-17', 2022, 9, 17, 3, 37, 'Saturday', 'September', 'f', 't', NULL),
(20220918, '2022-09-18', 2022, 9, 18, 3, 37, 'Sunday', 'September', 'f', 't', NULL),
(20220919, '2022-09-19', 2022, 9, 19, 3, 38, 'Monday', 'September', 'f', 'f', NULL),
(20220920, '2022-09-20', 2022, 9, 20, 3, 38, 'Tuesday', 'September', 'f', 'f', NULL),
(20220921, '2022-09-21', 2022, 9, 21, 3, 38, 'Wednesday', 'September', 'f', 'f', NULL),
(20220922, '2022-09-22', 2022, 9, 22, 3, 38, 'Thursday', 'September', 'f', 'f', NULL),
(20220923, '2022-09-23', 2022, 9, 23, 3, 38, 'Friday', 'September', 'f', 'f', NULL),
(20220924, '2022-09-24', 2022, 9, 24, 3, 38, 'Saturday', 'September', 'f', 't', NULL),
(20220925, '2022-09-25', 2022, 9, 25, 3, 38, 'Sunday', 'September', 'f', 't', NULL),
(20220926, '2022-09-26', 2022, 9, 26, 3, 39, 'Monday', 'September', 'f', 'f', NULL),
(20220927, '2022-09-27', 2022, 9, 27, 3, 39, 'Tuesday', 'September', 'f', 'f', NULL),
(20220928, '2022-09-28', 2022, 9, 28, 3, 39, 'Wednesday', 'September', 'f', 'f', NULL),
(20220929, '2022-09-29', 2022, 9, 29, 3, 39, 'Thursday', 'September', 'f', 'f', NULL),
(20220930, '2022-09-30', 2022, 9, 30, 3, 39, 'Friday', 'September', 'f', 'f', NULL),
(20221001, '2022-10-01', 2022, 10, 1, 4, 39, 'Saturday', 'October', 'f', 't', NULL),
(20221002, '2022-10-02', 2022, 10, 2, 4, 39, 'Sunday', 'October', 'f', 't', NULL),
(20221003, '2022-10-03', 2022, 10, 3, 4, 40, 'Monday', 'October', 'f', 'f', NULL),
(20221004, '2022-10-04', 2022, 10, 4, 4, 40, 'Tuesday', 'October', 'f', 'f', NULL),
(20221005, '2022-10-05', 2022, 10, 5, 4, 40, 'Wednesday', 'October', 'f', 'f', NULL),
(20221006, '2022-10-06', 2022, 10, 6, 4, 40, 'Thursday', 'October', 'f', 'f', NULL),
(20221007, '2022-10-07', 2022, 10, 7, 4, 40, 'Friday', 'October', 'f', 'f', NULL),
(20221008, '2022-10-08', 2022, 10, 8, 4, 40, 'Saturday', 'October', 'f', 't', NULL),
(20221009, '2022-10-09', 2022, 10, 9, 4, 40, 'Sunday', 'October', 'f', 't', NULL),
(20221010, '2022-10-10', 2022, 10, 10, 4, 41, 'Monday', 'October', 'f', 'f', NULL),
(20221011, '2022-10-11', 2022, 10, 11, 4, 41, 'Tuesday', 'October', 'f', 'f', NULL),
(20221012, '2022-10-12', 2022, 10, 12, 4, 41, 'Wednesday', 'October', 'f', 'f', NULL),
(20221013, '2022-10-13', 2022, 10, 13, 4, 41, 'Thursday', 'October', 'f', 'f', NULL),
(20221014, '2022-10-14', 2022, 10, 14, 4, 41, 'Friday', 'October', 'f', 'f', NULL),
(20221015, '2022-10-15', 2022, 10, 15, 4, 41, 'Saturday', 'October', 'f', 't', NULL),
(20221016, '2022-10-16', 2022, 10, 16, 4, 41, 'Sunday', 'October', 'f', 't', NULL),
(20221017, '2022-10-17', 2022, 10, 17, 4, 42, 'Monday', 'October', 'f', 'f', NULL),
(20221018, '2022-10-18', 2022, 10, 18, 4, 42, 'Tuesday', 'October', 'f', 'f', NULL),
(20221019, '2022-10-19', 2022, 10, 19, 4, 42, 'Wednesday', 'October', 'f', 'f', NULL),
(20221020, '2022-10-20', 2022, 10, 20, 4, 42, 'Thursday', 'October', 'f', 'f', NULL),
(20221021, '2022-10-21', 2022, 10, 21, 4, 42, 'Friday', 'October', 'f', 'f', NULL),
(20221022, '2022-10-22', 2022, 10, 22, 4, 42, 'Saturday', 'October', 'f', 't', NULL),
(20221023, '2022-10-23', 2022, 10, 23, 4, 42, 'Sunday', 'October', 'f', 't', NULL),
(20221024, '2022-10-24', 2022, 10, 24, 4, 43, 'Monday', 'October', 'f', 'f', NULL),
(20221025, '2022-10-25', 2022, 10, 25, 4, 43, 'Tuesday', 'October', 'f', 'f', NULL),
(20221026, '2022-10-26', 2022, 10, 26, 4, 43, 'Wednesday', 'October', 'f', 'f', NULL),
(20221027, '2022-10-27', 2022, 10, 27, 4, 43, 'Thursday', 'October', 'f', 'f', NULL),
(20221028, '2022-10-28', 2022, 10, 28, 4, 43, 'Friday', 'October', 'f', 'f', NULL),
(20221029, '2022-10-29', 2022, 10, 29, 4, 43, 'Saturday', 'October', 'f', 't', NULL),
(20221030, '2022-10-30', 2022, 10, 30, 4, 43, 'Sunday', 'October', 'f', 't', NULL),
(20221031, '2022-10-31', 2022, 10, 31, 4, 44, 'Monday', 'October', 'f', 'f', NULL),
(20221101, '2022-11-01', 2022, 11, 1, 4, 44, 'Tuesday', 'November', 'f', 'f', NULL),
(20221102, '2022-11-02', 2022, 11, 2, 4, 44, 'Wednesday', 'November', 'f', 'f', NULL),
(20221103, '2022-11-03', 2022, 11, 3, 4, 44, 'Thursday', 'November', 'f', 'f', NULL),
(20221104, '2022-11-04', 2022, 11, 4, 4, 44, 'Friday', 'November', 'f', 'f', NULL),
(20221105, '2022-11-05', 2022, 11, 5, 4, 44, 'Saturday', 'November', 'f', 't', NULL),
(20221106, '2022-11-06', 2022, 11, 6, 4, 44, 'Sunday', 'November', 'f', 't', NULL),
(20221107, '2022-11-07', 2022, 11, 7, 4, 45, 'Monday', 'November', 'f', 'f', NULL),
(20221108, '2022-11-08', 2022, 11, 8, 4, 45, 'Tuesday', 'November', 'f', 'f', NULL),
(20221109, '2022-11-09', 2022, 11, 9, 4, 45, 'Wednesday', 'November', 'f', 'f', NULL),
(20221110, '2022-11-10', 2022, 11, 10, 4, 45, 'Thursday', 'November', 'f', 'f', NULL),
(20221111, '2022-11-11', 2022, 11, 11, 4, 45, 'Friday', 'November', 'f', 'f', NULL),
(20221112, '2022-11-12', 2022, 11, 12, 4, 45, 'Saturday', 'November', 'f', 't', NULL),
(20221113, '2022-11-13', 2022, 11, 13, 4, 45, 'Sunday', 'November', 'f', 't', NULL),
(20221114, '2022-11-14', 2022, 11, 14, 4, 46, 'Monday', 'November', 'f', 'f', NULL),
(20221115, '2022-11-15', 2022, 11, 15, 4, 46, 'Tuesday', 'November', 'f', 'f', NULL),
(20221116, '2022-11-16', 2022, 11, 16, 4, 46, 'Wednesday', 'November', 'f', 'f', NULL),
(20221117, '2022-11-17', 2022, 11, 17, 4, 46, 'Thursday', 'November', 'f', 'f', NULL),
(20221118, '2022-11-18', 2022, 11, 18, 4, 46, 'Friday', 'November', 'f', 'f', NULL),
(20221119, '2022-11-19', 2022, 11, 19, 4, 46, 'Saturday', 'November', 'f', 't', NULL),
(20221120, '2022-11-20', 2022, 11, 20, 4, 46, 'Sunday', 'November', 'f', 't', NULL),
(20221121, '2022-11-21', 2022, 11, 21, 4, 47, 'Monday', 'November', 'f', 'f', NULL),
(20221122, '2022-11-22', 2022, 11, 22, 4, 47, 'Tuesday', 'November', 'f', 'f', NULL),
(20221123, '2022-11-23', 2022, 11, 23, 4, 47, 'Wednesday', 'November', 'f', 'f', NULL),
(20221124, '2022-11-24', 2022, 11, 24, 4, 47, 'Thursday', 'November', 'f', 'f', NULL),
(20221125, '2022-11-25', 2022, 11, 25, 4, 47, 'Friday', 'November', 'f', 'f', NULL),
(20221126, '2022-11-26', 2022, 11, 26, 4, 47, 'Saturday', 'November', 'f', 't', NULL),
(20221127, '2022-11-27', 2022, 11, 27, 4, 47, 'Sunday', 'November', 'f', 't', NULL),
(20221128, '2022-11-28', 2022, 11, 28, 4, 48, 'Monday', 'November', 'f', 'f', NULL),
(20221129, '2022-11-29', 2022, 11, 29, 4, 48, 'Tuesday', 'November', 'f', 'f', NULL),
(20221130, '2022-11-30', 2022, 11, 30, 4, 48, 'Wednesday', 'November', 'f', 'f', NULL),
(20221201, '2022-12-01', 2022, 12, 1, 4, 48, 'Thursday', 'December', 'f', 'f', NULL),
(20221202, '2022-12-02', 2022, 12, 2, 4, 48, 'Friday', 'December', 'f', 'f', NULL),
(20221203, '2022-12-03', 2022, 12, 3, 4, 48, 'Saturday', 'December', 'f', 't', NULL),
(20221204, '2022-12-04', 2022, 12, 4, 4, 48, 'Sunday', 'December', 'f', 't', NULL),
(20221205, '2022-12-05', 2022, 12, 5, 4, 49, 'Monday', 'December', 'f', 'f', NULL),
(20221206, '2022-12-06', 2022, 12, 6, 4, 49, 'Tuesday', 'December', 'f', 'f', NULL),
(20221207, '2022-12-07', 2022, 12, 7, 4, 49, 'Wednesday', 'December', 'f', 'f', NULL),
(20221208, '2022-12-08', 2022, 12, 8, 4, 49, 'Thursday', 'December', 'f', 'f', NULL),
(20221209, '2022-12-09', 2022, 12, 9, 4, 49, 'Friday', 'December', 'f', 'f', NULL),
(20221210, '2022-12-10', 2022, 12, 10, 4, 49, 'Saturday', 'December', 'f', 't', NULL),
(20221211, '2022-12-11', 2022, 12, 11, 4, 49, 'Sunday', 'December', 'f', 't', NULL),
(20221212, '2022-12-12', 2022, 12, 12, 4, 50, 'Monday', 'December', 'f', 'f', NULL),
(20221213, '2022-12-13', 2022, 12, 13, 4, 50, 'Tuesday', 'December', 'f', 'f', NULL),
(20221214, '2022-12-14', 2022, 12, 14, 4, 50, 'Wednesday', 'December', 'f', 'f', NULL),
(20221215, '2022-12-15', 2022, 12, 15, 4, 50, 'Thursday', 'December', 'f', 'f', NULL),
(20221216, '2022-12-16', 2022, 12, 16, 4, 50, 'Friday', 'December', 'f', 'f', NULL),
(20221217, '2022-12-17', 2022, 12, 17, 4, 50, 'Saturday', 'December', 'f', 't', NULL),
(20221218, '2022-12-18', 2022, 12, 18, 4, 50, 'Sunday', 'December', 'f', 't', NULL),
(20221219, '2022-12-19', 2022, 12, 19, 4, 51, 'Monday', 'December', 'f', 'f', NULL),
(20221220, '2022-12-20', 2022, 12, 20, 4, 51, 'Tuesday', 'December', 'f', 'f', NULL),
(20221221, '2022-12-21', 2022, 12, 21, 4, 51, 'Wednesday', 'December', 'f', 'f', NULL),
(20221222, '2022-12-22', 2022, 12, 22, 4, 51, 'Thursday', 'December', 'f', 'f', NULL),
(20221223, '2022-12-23', 2022, 12, 23, 4, 51, 'Friday', 'December', 'f', 'f', NULL),
(20221224, '2022-12-24', 2022, 12, 24, 4, 51, 'Saturday', 'December', 'f', 't', NULL),
(20221225, '2022-12-25', 2022, 12, 25, 4, 51, 'Sunday', 'December', 'f', 't', NULL),
(20221226, '2022-12-26', 2022, 12, 26, 4, 52, 'Monday', 'December', 'f', 'f', NULL),
(20221227, '2022-12-27', 2022, 12, 27, 4, 52, 'Tuesday', 'December', 'f', 'f', NULL),
(20221228, '2022-12-28', 2022, 12, 28, 4, 52, 'Wednesday', 'December', 'f', 'f', NULL),
(20221229, '2022-12-29', 2022, 12, 29, 4, 52, 'Thursday', 'December', 'f', 'f', NULL),
(20221230, '2022-12-30', 2022, 12, 30, 4, 52, 'Friday', 'December', 'f', 'f', NULL),
(20221231, '2022-12-31', 2022, 12, 31, 4, 52, 'Saturday', 'December', 'f', 't', NULL),
(20230101, '2023-01-01', 2023, 1, 1, 1, 52, 'Sunday', 'January', 'f', 't', NULL),
(20230102, '2023-01-02', 2023, 1, 2, 1, 1, 'Monday', 'January', 'f', 'f', NULL),
(20230103, '2023-01-03', 2023, 1, 3, 1, 1, 'Tuesday', 'January', 'f', 'f', NULL),
(20230104, '2023-01-04', 2023, 1, 4, 1, 1, 'Wednesday', 'January', 'f', 'f', NULL),
(20230105, '2023-01-05', 2023, 1, 5, 1, 1, 'Thursday', 'January', 'f', 'f', NULL),
(20230106, '2023-01-06', 2023, 1, 6, 1, 1, 'Friday', 'January', 'f', 'f', NULL),
(20230107, '2023-01-07', 2023, 1, 7, 1, 1, 'Saturday', 'January', 'f', 't', NULL),
(20230108, '2023-01-08', 2023, 1, 8, 1, 1, 'Sunday', 'January', 'f', 't', NULL),
(20230109, '2023-01-09', 2023, 1, 9, 1, 2, 'Monday', 'January', 'f', 'f', NULL),
(20230110, '2023-01-10', 2023, 1, 10, 1, 2, 'Tuesday', 'January', 'f', 'f', NULL),
(20230111, '2023-01-11', 2023, 1, 11, 1, 2, 'Wednesday', 'January', 'f', 'f', NULL),
(20230112, '2023-01-12', 2023, 1, 12, 1, 2, 'Thursday', 'January', 'f', 'f', NULL),
(20230113, '2023-01-13', 2023, 1, 13, 1, 2, 'Friday', 'January', 'f', 'f', NULL),
(20230114, '2023-01-14', 2023, 1, 14, 1, 2, 'Saturday', 'January', 'f', 't', NULL),
(20230115, '2023-01-15', 2023, 1, 15, 1, 2, 'Sunday', 'January', 'f', 't', NULL),
(20230116, '2023-01-16', 2023, 1, 16, 1, 3, 'Monday', 'January', 'f', 'f', NULL),
(20230117, '2023-01-17', 2023, 1, 17, 1, 3, 'Tuesday', 'January', 'f', 'f', NULL),
(20230118, '2023-01-18', 2023, 1, 18, 1, 3, 'Wednesday', 'January', 'f', 'f', NULL);
INSERT INTO `time_dimension` (`id`, `db_date`, `year`, `month`, `day`, `quarter`, `week`, `day_name`, `month_name`, `holiday_flag`, `weekend_flag`, `event`) VALUES
(20230119, '2023-01-19', 2023, 1, 19, 1, 3, 'Thursday', 'January', 'f', 'f', NULL),
(20230120, '2023-01-20', 2023, 1, 20, 1, 3, 'Friday', 'January', 'f', 'f', NULL),
(20230121, '2023-01-21', 2023, 1, 21, 1, 3, 'Saturday', 'January', 'f', 't', NULL),
(20230122, '2023-01-22', 2023, 1, 22, 1, 3, 'Sunday', 'January', 'f', 't', NULL),
(20230123, '2023-01-23', 2023, 1, 23, 1, 4, 'Monday', 'January', 'f', 'f', NULL),
(20230124, '2023-01-24', 2023, 1, 24, 1, 4, 'Tuesday', 'January', 'f', 'f', NULL),
(20230125, '2023-01-25', 2023, 1, 25, 1, 4, 'Wednesday', 'January', 'f', 'f', NULL),
(20230126, '2023-01-26', 2023, 1, 26, 1, 4, 'Thursday', 'January', 'f', 'f', NULL),
(20230127, '2023-01-27', 2023, 1, 27, 1, 4, 'Friday', 'January', 'f', 'f', NULL),
(20230128, '2023-01-28', 2023, 1, 28, 1, 4, 'Saturday', 'January', 'f', 't', NULL),
(20230129, '2023-01-29', 2023, 1, 29, 1, 4, 'Sunday', 'January', 'f', 't', NULL),
(20230130, '2023-01-30', 2023, 1, 30, 1, 5, 'Monday', 'January', 'f', 'f', NULL),
(20230131, '2023-01-31', 2023, 1, 31, 1, 5, 'Tuesday', 'January', 'f', 'f', NULL),
(20230201, '2023-02-01', 2023, 2, 1, 1, 5, 'Wednesday', 'February', 'f', 'f', NULL),
(20230202, '2023-02-02', 2023, 2, 2, 1, 5, 'Thursday', 'February', 'f', 'f', NULL),
(20230203, '2023-02-03', 2023, 2, 3, 1, 5, 'Friday', 'February', 'f', 'f', NULL),
(20230204, '2023-02-04', 2023, 2, 4, 1, 5, 'Saturday', 'February', 'f', 't', NULL),
(20230205, '2023-02-05', 2023, 2, 5, 1, 5, 'Sunday', 'February', 'f', 't', NULL),
(20230206, '2023-02-06', 2023, 2, 6, 1, 6, 'Monday', 'February', 'f', 'f', NULL),
(20230207, '2023-02-07', 2023, 2, 7, 1, 6, 'Tuesday', 'February', 'f', 'f', NULL),
(20230208, '2023-02-08', 2023, 2, 8, 1, 6, 'Wednesday', 'February', 'f', 'f', NULL),
(20230209, '2023-02-09', 2023, 2, 9, 1, 6, 'Thursday', 'February', 'f', 'f', NULL),
(20230210, '2023-02-10', 2023, 2, 10, 1, 6, 'Friday', 'February', 'f', 'f', NULL),
(20230211, '2023-02-11', 2023, 2, 11, 1, 6, 'Saturday', 'February', 'f', 't', NULL),
(20230212, '2023-02-12', 2023, 2, 12, 1, 6, 'Sunday', 'February', 'f', 't', NULL),
(20230213, '2023-02-13', 2023, 2, 13, 1, 7, 'Monday', 'February', 'f', 'f', NULL),
(20230214, '2023-02-14', 2023, 2, 14, 1, 7, 'Tuesday', 'February', 'f', 'f', NULL),
(20230215, '2023-02-15', 2023, 2, 15, 1, 7, 'Wednesday', 'February', 'f', 'f', NULL),
(20230216, '2023-02-16', 2023, 2, 16, 1, 7, 'Thursday', 'February', 'f', 'f', NULL),
(20230217, '2023-02-17', 2023, 2, 17, 1, 7, 'Friday', 'February', 'f', 'f', NULL),
(20230218, '2023-02-18', 2023, 2, 18, 1, 7, 'Saturday', 'February', 'f', 't', NULL),
(20230219, '2023-02-19', 2023, 2, 19, 1, 7, 'Sunday', 'February', 'f', 't', NULL),
(20230220, '2023-02-20', 2023, 2, 20, 1, 8, 'Monday', 'February', 'f', 'f', NULL),
(20230221, '2023-02-21', 2023, 2, 21, 1, 8, 'Tuesday', 'February', 'f', 'f', NULL),
(20230222, '2023-02-22', 2023, 2, 22, 1, 8, 'Wednesday', 'February', 'f', 'f', NULL),
(20230223, '2023-02-23', 2023, 2, 23, 1, 8, 'Thursday', 'February', 'f', 'f', NULL),
(20230224, '2023-02-24', 2023, 2, 24, 1, 8, 'Friday', 'February', 'f', 'f', NULL),
(20230225, '2023-02-25', 2023, 2, 25, 1, 8, 'Saturday', 'February', 'f', 't', NULL),
(20230226, '2023-02-26', 2023, 2, 26, 1, 8, 'Sunday', 'February', 'f', 't', NULL),
(20230227, '2023-02-27', 2023, 2, 27, 1, 9, 'Monday', 'February', 'f', 'f', NULL),
(20230228, '2023-02-28', 2023, 2, 28, 1, 9, 'Tuesday', 'February', 'f', 'f', NULL),
(20230301, '2023-03-01', 2023, 3, 1, 1, 9, 'Wednesday', 'March', 'f', 'f', NULL),
(20230302, '2023-03-02', 2023, 3, 2, 1, 9, 'Thursday', 'March', 'f', 'f', NULL),
(20230303, '2023-03-03', 2023, 3, 3, 1, 9, 'Friday', 'March', 'f', 'f', NULL),
(20230304, '2023-03-04', 2023, 3, 4, 1, 9, 'Saturday', 'March', 'f', 't', NULL),
(20230305, '2023-03-05', 2023, 3, 5, 1, 9, 'Sunday', 'March', 'f', 't', NULL),
(20230306, '2023-03-06', 2023, 3, 6, 1, 10, 'Monday', 'March', 'f', 'f', NULL),
(20230307, '2023-03-07', 2023, 3, 7, 1, 10, 'Tuesday', 'March', 'f', 'f', NULL),
(20230308, '2023-03-08', 2023, 3, 8, 1, 10, 'Wednesday', 'March', 'f', 'f', NULL),
(20230309, '2023-03-09', 2023, 3, 9, 1, 10, 'Thursday', 'March', 'f', 'f', NULL),
(20230310, '2023-03-10', 2023, 3, 10, 1, 10, 'Friday', 'March', 'f', 'f', NULL),
(20230311, '2023-03-11', 2023, 3, 11, 1, 10, 'Saturday', 'March', 'f', 't', NULL),
(20230312, '2023-03-12', 2023, 3, 12, 1, 10, 'Sunday', 'March', 'f', 't', NULL),
(20230313, '2023-03-13', 2023, 3, 13, 1, 11, 'Monday', 'March', 'f', 'f', NULL),
(20230314, '2023-03-14', 2023, 3, 14, 1, 11, 'Tuesday', 'March', 'f', 'f', NULL),
(20230315, '2023-03-15', 2023, 3, 15, 1, 11, 'Wednesday', 'March', 'f', 'f', NULL),
(20230316, '2023-03-16', 2023, 3, 16, 1, 11, 'Thursday', 'March', 'f', 'f', NULL),
(20230317, '2023-03-17', 2023, 3, 17, 1, 11, 'Friday', 'March', 'f', 'f', NULL),
(20230318, '2023-03-18', 2023, 3, 18, 1, 11, 'Saturday', 'March', 'f', 't', NULL),
(20230319, '2023-03-19', 2023, 3, 19, 1, 11, 'Sunday', 'March', 'f', 't', NULL),
(20230320, '2023-03-20', 2023, 3, 20, 1, 12, 'Monday', 'March', 'f', 'f', NULL),
(20230321, '2023-03-21', 2023, 3, 21, 1, 12, 'Tuesday', 'March', 'f', 'f', NULL),
(20230322, '2023-03-22', 2023, 3, 22, 1, 12, 'Wednesday', 'March', 'f', 'f', NULL),
(20230323, '2023-03-23', 2023, 3, 23, 1, 12, 'Thursday', 'March', 'f', 'f', NULL),
(20230324, '2023-03-24', 2023, 3, 24, 1, 12, 'Friday', 'March', 'f', 'f', NULL),
(20230325, '2023-03-25', 2023, 3, 25, 1, 12, 'Saturday', 'March', 'f', 't', NULL),
(20230326, '2023-03-26', 2023, 3, 26, 1, 12, 'Sunday', 'March', 'f', 't', NULL),
(20230327, '2023-03-27', 2023, 3, 27, 1, 13, 'Monday', 'March', 'f', 'f', NULL),
(20230328, '2023-03-28', 2023, 3, 28, 1, 13, 'Tuesday', 'March', 'f', 'f', NULL),
(20230329, '2023-03-29', 2023, 3, 29, 1, 13, 'Wednesday', 'March', 'f', 'f', NULL),
(20230330, '2023-03-30', 2023, 3, 30, 1, 13, 'Thursday', 'March', 'f', 'f', NULL),
(20230331, '2023-03-31', 2023, 3, 31, 1, 13, 'Friday', 'March', 'f', 'f', NULL),
(20230401, '2023-04-01', 2023, 4, 1, 2, 13, 'Saturday', 'April', 'f', 't', NULL),
(20230402, '2023-04-02', 2023, 4, 2, 2, 13, 'Sunday', 'April', 'f', 't', NULL),
(20230403, '2023-04-03', 2023, 4, 3, 2, 14, 'Monday', 'April', 'f', 'f', NULL),
(20230404, '2023-04-04', 2023, 4, 4, 2, 14, 'Tuesday', 'April', 'f', 'f', NULL),
(20230405, '2023-04-05', 2023, 4, 5, 2, 14, 'Wednesday', 'April', 'f', 'f', NULL),
(20230406, '2023-04-06', 2023, 4, 6, 2, 14, 'Thursday', 'April', 'f', 'f', NULL),
(20230407, '2023-04-07', 2023, 4, 7, 2, 14, 'Friday', 'April', 'f', 'f', NULL),
(20230408, '2023-04-08', 2023, 4, 8, 2, 14, 'Saturday', 'April', 'f', 't', NULL),
(20230409, '2023-04-09', 2023, 4, 9, 2, 14, 'Sunday', 'April', 'f', 't', NULL),
(20230410, '2023-04-10', 2023, 4, 10, 2, 15, 'Monday', 'April', 'f', 'f', NULL),
(20230411, '2023-04-11', 2023, 4, 11, 2, 15, 'Tuesday', 'April', 'f', 'f', NULL),
(20230412, '2023-04-12', 2023, 4, 12, 2, 15, 'Wednesday', 'April', 'f', 'f', NULL),
(20230413, '2023-04-13', 2023, 4, 13, 2, 15, 'Thursday', 'April', 'f', 'f', NULL),
(20230414, '2023-04-14', 2023, 4, 14, 2, 15, 'Friday', 'April', 'f', 'f', NULL),
(20230415, '2023-04-15', 2023, 4, 15, 2, 15, 'Saturday', 'April', 'f', 't', NULL),
(20230416, '2023-04-16', 2023, 4, 16, 2, 15, 'Sunday', 'April', 'f', 't', NULL),
(20230417, '2023-04-17', 2023, 4, 17, 2, 16, 'Monday', 'April', 'f', 'f', NULL),
(20230418, '2023-04-18', 2023, 4, 18, 2, 16, 'Tuesday', 'April', 'f', 'f', NULL),
(20230419, '2023-04-19', 2023, 4, 19, 2, 16, 'Wednesday', 'April', 'f', 'f', NULL),
(20230420, '2023-04-20', 2023, 4, 20, 2, 16, 'Thursday', 'April', 'f', 'f', NULL),
(20230421, '2023-04-21', 2023, 4, 21, 2, 16, 'Friday', 'April', 'f', 'f', NULL),
(20230422, '2023-04-22', 2023, 4, 22, 2, 16, 'Saturday', 'April', 'f', 't', NULL),
(20230423, '2023-04-23', 2023, 4, 23, 2, 16, 'Sunday', 'April', 'f', 't', NULL),
(20230424, '2023-04-24', 2023, 4, 24, 2, 17, 'Monday', 'April', 'f', 'f', NULL),
(20230425, '2023-04-25', 2023, 4, 25, 2, 17, 'Tuesday', 'April', 'f', 'f', NULL),
(20230426, '2023-04-26', 2023, 4, 26, 2, 17, 'Wednesday', 'April', 'f', 'f', NULL),
(20230427, '2023-04-27', 2023, 4, 27, 2, 17, 'Thursday', 'April', 'f', 'f', NULL),
(20230428, '2023-04-28', 2023, 4, 28, 2, 17, 'Friday', 'April', 'f', 'f', NULL),
(20230429, '2023-04-29', 2023, 4, 29, 2, 17, 'Saturday', 'April', 'f', 't', NULL),
(20230430, '2023-04-30', 2023, 4, 30, 2, 17, 'Sunday', 'April', 'f', 't', NULL),
(20230501, '2023-05-01', 2023, 5, 1, 2, 18, 'Monday', 'May', 'f', 'f', NULL),
(20230502, '2023-05-02', 2023, 5, 2, 2, 18, 'Tuesday', 'May', 'f', 'f', NULL),
(20230503, '2023-05-03', 2023, 5, 3, 2, 18, 'Wednesday', 'May', 'f', 'f', NULL),
(20230504, '2023-05-04', 2023, 5, 4, 2, 18, 'Thursday', 'May', 'f', 'f', NULL),
(20230505, '2023-05-05', 2023, 5, 5, 2, 18, 'Friday', 'May', 'f', 'f', NULL),
(20230506, '2023-05-06', 2023, 5, 6, 2, 18, 'Saturday', 'May', 'f', 't', NULL),
(20230507, '2023-05-07', 2023, 5, 7, 2, 18, 'Sunday', 'May', 'f', 't', NULL),
(20230508, '2023-05-08', 2023, 5, 8, 2, 19, 'Monday', 'May', 'f', 'f', NULL),
(20230509, '2023-05-09', 2023, 5, 9, 2, 19, 'Tuesday', 'May', 'f', 'f', NULL),
(20230510, '2023-05-10', 2023, 5, 10, 2, 19, 'Wednesday', 'May', 'f', 'f', NULL),
(20230511, '2023-05-11', 2023, 5, 11, 2, 19, 'Thursday', 'May', 'f', 'f', NULL),
(20230512, '2023-05-12', 2023, 5, 12, 2, 19, 'Friday', 'May', 'f', 'f', NULL),
(20230513, '2023-05-13', 2023, 5, 13, 2, 19, 'Saturday', 'May', 'f', 't', NULL),
(20230514, '2023-05-14', 2023, 5, 14, 2, 19, 'Sunday', 'May', 'f', 't', NULL),
(20230515, '2023-05-15', 2023, 5, 15, 2, 20, 'Monday', 'May', 'f', 'f', NULL),
(20230516, '2023-05-16', 2023, 5, 16, 2, 20, 'Tuesday', 'May', 'f', 'f', NULL),
(20230517, '2023-05-17', 2023, 5, 17, 2, 20, 'Wednesday', 'May', 'f', 'f', NULL),
(20230518, '2023-05-18', 2023, 5, 18, 2, 20, 'Thursday', 'May', 'f', 'f', NULL),
(20230519, '2023-05-19', 2023, 5, 19, 2, 20, 'Friday', 'May', 'f', 'f', NULL),
(20230520, '2023-05-20', 2023, 5, 20, 2, 20, 'Saturday', 'May', 'f', 't', NULL),
(20230521, '2023-05-21', 2023, 5, 21, 2, 20, 'Sunday', 'May', 'f', 't', NULL),
(20230522, '2023-05-22', 2023, 5, 22, 2, 21, 'Monday', 'May', 'f', 'f', NULL),
(20230523, '2023-05-23', 2023, 5, 23, 2, 21, 'Tuesday', 'May', 'f', 'f', NULL),
(20230524, '2023-05-24', 2023, 5, 24, 2, 21, 'Wednesday', 'May', 'f', 'f', NULL),
(20230525, '2023-05-25', 2023, 5, 25, 2, 21, 'Thursday', 'May', 'f', 'f', NULL),
(20230526, '2023-05-26', 2023, 5, 26, 2, 21, 'Friday', 'May', 'f', 'f', NULL),
(20230527, '2023-05-27', 2023, 5, 27, 2, 21, 'Saturday', 'May', 'f', 't', NULL),
(20230528, '2023-05-28', 2023, 5, 28, 2, 21, 'Sunday', 'May', 'f', 't', NULL),
(20230529, '2023-05-29', 2023, 5, 29, 2, 22, 'Monday', 'May', 'f', 'f', NULL),
(20230530, '2023-05-30', 2023, 5, 30, 2, 22, 'Tuesday', 'May', 'f', 'f', NULL),
(20230531, '2023-05-31', 2023, 5, 31, 2, 22, 'Wednesday', 'May', 'f', 'f', NULL),
(20230601, '2023-06-01', 2023, 6, 1, 2, 22, 'Thursday', 'June', 'f', 'f', NULL),
(20230602, '2023-06-02', 2023, 6, 2, 2, 22, 'Friday', 'June', 'f', 'f', NULL),
(20230603, '2023-06-03', 2023, 6, 3, 2, 22, 'Saturday', 'June', 'f', 't', NULL),
(20230604, '2023-06-04', 2023, 6, 4, 2, 22, 'Sunday', 'June', 'f', 't', NULL),
(20230605, '2023-06-05', 2023, 6, 5, 2, 23, 'Monday', 'June', 'f', 'f', NULL),
(20230606, '2023-06-06', 2023, 6, 6, 2, 23, 'Tuesday', 'June', 'f', 'f', NULL),
(20230607, '2023-06-07', 2023, 6, 7, 2, 23, 'Wednesday', 'June', 'f', 'f', NULL),
(20230608, '2023-06-08', 2023, 6, 8, 2, 23, 'Thursday', 'June', 'f', 'f', NULL),
(20230609, '2023-06-09', 2023, 6, 9, 2, 23, 'Friday', 'June', 'f', 'f', NULL),
(20230610, '2023-06-10', 2023, 6, 10, 2, 23, 'Saturday', 'June', 'f', 't', NULL),
(20230611, '2023-06-11', 2023, 6, 11, 2, 23, 'Sunday', 'June', 'f', 't', NULL),
(20230612, '2023-06-12', 2023, 6, 12, 2, 24, 'Monday', 'June', 'f', 'f', NULL),
(20230613, '2023-06-13', 2023, 6, 13, 2, 24, 'Tuesday', 'June', 'f', 'f', NULL),
(20230614, '2023-06-14', 2023, 6, 14, 2, 24, 'Wednesday', 'June', 'f', 'f', NULL),
(20230615, '2023-06-15', 2023, 6, 15, 2, 24, 'Thursday', 'June', 'f', 'f', NULL),
(20230616, '2023-06-16', 2023, 6, 16, 2, 24, 'Friday', 'June', 'f', 'f', NULL),
(20230617, '2023-06-17', 2023, 6, 17, 2, 24, 'Saturday', 'June', 'f', 't', NULL),
(20230618, '2023-06-18', 2023, 6, 18, 2, 24, 'Sunday', 'June', 'f', 't', NULL),
(20230619, '2023-06-19', 2023, 6, 19, 2, 25, 'Monday', 'June', 'f', 'f', NULL),
(20230620, '2023-06-20', 2023, 6, 20, 2, 25, 'Tuesday', 'June', 'f', 'f', NULL),
(20230621, '2023-06-21', 2023, 6, 21, 2, 25, 'Wednesday', 'June', 'f', 'f', NULL),
(20230622, '2023-06-22', 2023, 6, 22, 2, 25, 'Thursday', 'June', 'f', 'f', NULL),
(20230623, '2023-06-23', 2023, 6, 23, 2, 25, 'Friday', 'June', 'f', 'f', NULL),
(20230624, '2023-06-24', 2023, 6, 24, 2, 25, 'Saturday', 'June', 'f', 't', NULL),
(20230625, '2023-06-25', 2023, 6, 25, 2, 25, 'Sunday', 'June', 'f', 't', NULL),
(20230626, '2023-06-26', 2023, 6, 26, 2, 26, 'Monday', 'June', 'f', 'f', NULL),
(20230627, '2023-06-27', 2023, 6, 27, 2, 26, 'Tuesday', 'June', 'f', 'f', NULL),
(20230628, '2023-06-28', 2023, 6, 28, 2, 26, 'Wednesday', 'June', 'f', 'f', NULL),
(20230629, '2023-06-29', 2023, 6, 29, 2, 26, 'Thursday', 'June', 'f', 'f', NULL),
(20230630, '2023-06-30', 2023, 6, 30, 2, 26, 'Friday', 'June', 'f', 'f', NULL),
(20230701, '2023-07-01', 2023, 7, 1, 3, 26, 'Saturday', 'July', 'f', 't', NULL),
(20230702, '2023-07-02', 2023, 7, 2, 3, 26, 'Sunday', 'July', 'f', 't', NULL),
(20230703, '2023-07-03', 2023, 7, 3, 3, 27, 'Monday', 'July', 'f', 'f', NULL),
(20230704, '2023-07-04', 2023, 7, 4, 3, 27, 'Tuesday', 'July', 'f', 'f', NULL),
(20230705, '2023-07-05', 2023, 7, 5, 3, 27, 'Wednesday', 'July', 'f', 'f', NULL),
(20230706, '2023-07-06', 2023, 7, 6, 3, 27, 'Thursday', 'July', 'f', 'f', NULL),
(20230707, '2023-07-07', 2023, 7, 7, 3, 27, 'Friday', 'July', 'f', 'f', NULL),
(20230708, '2023-07-08', 2023, 7, 8, 3, 27, 'Saturday', 'July', 'f', 't', NULL),
(20230709, '2023-07-09', 2023, 7, 9, 3, 27, 'Sunday', 'July', 'f', 't', NULL),
(20230710, '2023-07-10', 2023, 7, 10, 3, 28, 'Monday', 'July', 'f', 'f', NULL),
(20230711, '2023-07-11', 2023, 7, 11, 3, 28, 'Tuesday', 'July', 'f', 'f', NULL),
(20230712, '2023-07-12', 2023, 7, 12, 3, 28, 'Wednesday', 'July', 'f', 'f', NULL),
(20230713, '2023-07-13', 2023, 7, 13, 3, 28, 'Thursday', 'July', 'f', 'f', NULL),
(20230714, '2023-07-14', 2023, 7, 14, 3, 28, 'Friday', 'July', 'f', 'f', NULL),
(20230715, '2023-07-15', 2023, 7, 15, 3, 28, 'Saturday', 'July', 'f', 't', NULL),
(20230716, '2023-07-16', 2023, 7, 16, 3, 28, 'Sunday', 'July', 'f', 't', NULL),
(20230717, '2023-07-17', 2023, 7, 17, 3, 29, 'Monday', 'July', 'f', 'f', NULL),
(20230718, '2023-07-18', 2023, 7, 18, 3, 29, 'Tuesday', 'July', 'f', 'f', NULL),
(20230719, '2023-07-19', 2023, 7, 19, 3, 29, 'Wednesday', 'July', 'f', 'f', NULL),
(20230720, '2023-07-20', 2023, 7, 20, 3, 29, 'Thursday', 'July', 'f', 'f', NULL),
(20230721, '2023-07-21', 2023, 7, 21, 3, 29, 'Friday', 'July', 'f', 'f', NULL),
(20230722, '2023-07-22', 2023, 7, 22, 3, 29, 'Saturday', 'July', 'f', 't', NULL),
(20230723, '2023-07-23', 2023, 7, 23, 3, 29, 'Sunday', 'July', 'f', 't', NULL),
(20230724, '2023-07-24', 2023, 7, 24, 3, 30, 'Monday', 'July', 'f', 'f', NULL),
(20230725, '2023-07-25', 2023, 7, 25, 3, 30, 'Tuesday', 'July', 'f', 'f', NULL),
(20230726, '2023-07-26', 2023, 7, 26, 3, 30, 'Wednesday', 'July', 'f', 'f', NULL),
(20230727, '2023-07-27', 2023, 7, 27, 3, 30, 'Thursday', 'July', 'f', 'f', NULL),
(20230728, '2023-07-28', 2023, 7, 28, 3, 30, 'Friday', 'July', 'f', 'f', NULL),
(20230729, '2023-07-29', 2023, 7, 29, 3, 30, 'Saturday', 'July', 'f', 't', NULL),
(20230730, '2023-07-30', 2023, 7, 30, 3, 30, 'Sunday', 'July', 'f', 't', NULL),
(20230731, '2023-07-31', 2023, 7, 31, 3, 31, 'Monday', 'July', 'f', 'f', NULL),
(20230801, '2023-08-01', 2023, 8, 1, 3, 31, 'Tuesday', 'August', 'f', 'f', NULL),
(20230802, '2023-08-02', 2023, 8, 2, 3, 31, 'Wednesday', 'August', 'f', 'f', NULL),
(20230803, '2023-08-03', 2023, 8, 3, 3, 31, 'Thursday', 'August', 'f', 'f', NULL),
(20230804, '2023-08-04', 2023, 8, 4, 3, 31, 'Friday', 'August', 'f', 'f', NULL),
(20230805, '2023-08-05', 2023, 8, 5, 3, 31, 'Saturday', 'August', 'f', 't', NULL),
(20230806, '2023-08-06', 2023, 8, 6, 3, 31, 'Sunday', 'August', 'f', 't', NULL),
(20230807, '2023-08-07', 2023, 8, 7, 3, 32, 'Monday', 'August', 'f', 'f', NULL),
(20230808, '2023-08-08', 2023, 8, 8, 3, 32, 'Tuesday', 'August', 'f', 'f', NULL),
(20230809, '2023-08-09', 2023, 8, 9, 3, 32, 'Wednesday', 'August', 'f', 'f', NULL),
(20230810, '2023-08-10', 2023, 8, 10, 3, 32, 'Thursday', 'August', 'f', 'f', NULL),
(20230811, '2023-08-11', 2023, 8, 11, 3, 32, 'Friday', 'August', 'f', 'f', NULL),
(20230812, '2023-08-12', 2023, 8, 12, 3, 32, 'Saturday', 'August', 'f', 't', NULL),
(20230813, '2023-08-13', 2023, 8, 13, 3, 32, 'Sunday', 'August', 'f', 't', NULL),
(20230814, '2023-08-14', 2023, 8, 14, 3, 33, 'Monday', 'August', 'f', 'f', NULL),
(20230815, '2023-08-15', 2023, 8, 15, 3, 33, 'Tuesday', 'August', 'f', 'f', NULL),
(20230816, '2023-08-16', 2023, 8, 16, 3, 33, 'Wednesday', 'August', 'f', 'f', NULL),
(20230817, '2023-08-17', 2023, 8, 17, 3, 33, 'Thursday', 'August', 'f', 'f', NULL),
(20230818, '2023-08-18', 2023, 8, 18, 3, 33, 'Friday', 'August', 'f', 'f', NULL),
(20230819, '2023-08-19', 2023, 8, 19, 3, 33, 'Saturday', 'August', 'f', 't', NULL),
(20230820, '2023-08-20', 2023, 8, 20, 3, 33, 'Sunday', 'August', 'f', 't', NULL),
(20230821, '2023-08-21', 2023, 8, 21, 3, 34, 'Monday', 'August', 'f', 'f', NULL),
(20230822, '2023-08-22', 2023, 8, 22, 3, 34, 'Tuesday', 'August', 'f', 'f', NULL),
(20230823, '2023-08-23', 2023, 8, 23, 3, 34, 'Wednesday', 'August', 'f', 'f', NULL),
(20230824, '2023-08-24', 2023, 8, 24, 3, 34, 'Thursday', 'August', 'f', 'f', NULL),
(20230825, '2023-08-25', 2023, 8, 25, 3, 34, 'Friday', 'August', 'f', 'f', NULL),
(20230826, '2023-08-26', 2023, 8, 26, 3, 34, 'Saturday', 'August', 'f', 't', NULL),
(20230827, '2023-08-27', 2023, 8, 27, 3, 34, 'Sunday', 'August', 'f', 't', NULL),
(20230828, '2023-08-28', 2023, 8, 28, 3, 35, 'Monday', 'August', 'f', 'f', NULL),
(20230829, '2023-08-29', 2023, 8, 29, 3, 35, 'Tuesday', 'August', 'f', 'f', NULL),
(20230830, '2023-08-30', 2023, 8, 30, 3, 35, 'Wednesday', 'August', 'f', 'f', NULL),
(20230831, '2023-08-31', 2023, 8, 31, 3, 35, 'Thursday', 'August', 'f', 'f', NULL),
(20230901, '2023-09-01', 2023, 9, 1, 3, 35, 'Friday', 'September', 'f', 'f', NULL),
(20230902, '2023-09-02', 2023, 9, 2, 3, 35, 'Saturday', 'September', 'f', 't', NULL),
(20230903, '2023-09-03', 2023, 9, 3, 3, 35, 'Sunday', 'September', 'f', 't', NULL),
(20230904, '2023-09-04', 2023, 9, 4, 3, 36, 'Monday', 'September', 'f', 'f', NULL),
(20230905, '2023-09-05', 2023, 9, 5, 3, 36, 'Tuesday', 'September', 'f', 'f', NULL),
(20230906, '2023-09-06', 2023, 9, 6, 3, 36, 'Wednesday', 'September', 'f', 'f', NULL),
(20230907, '2023-09-07', 2023, 9, 7, 3, 36, 'Thursday', 'September', 'f', 'f', NULL),
(20230908, '2023-09-08', 2023, 9, 8, 3, 36, 'Friday', 'September', 'f', 'f', NULL),
(20230909, '2023-09-09', 2023, 9, 9, 3, 36, 'Saturday', 'September', 'f', 't', NULL),
(20230910, '2023-09-10', 2023, 9, 10, 3, 36, 'Sunday', 'September', 'f', 't', NULL),
(20230911, '2023-09-11', 2023, 9, 11, 3, 37, 'Monday', 'September', 'f', 'f', NULL),
(20230912, '2023-09-12', 2023, 9, 12, 3, 37, 'Tuesday', 'September', 'f', 'f', NULL),
(20230913, '2023-09-13', 2023, 9, 13, 3, 37, 'Wednesday', 'September', 'f', 'f', NULL),
(20230914, '2023-09-14', 2023, 9, 14, 3, 37, 'Thursday', 'September', 'f', 'f', NULL),
(20230915, '2023-09-15', 2023, 9, 15, 3, 37, 'Friday', 'September', 'f', 'f', NULL),
(20230916, '2023-09-16', 2023, 9, 16, 3, 37, 'Saturday', 'September', 'f', 't', NULL),
(20230917, '2023-09-17', 2023, 9, 17, 3, 37, 'Sunday', 'September', 'f', 't', NULL),
(20230918, '2023-09-18', 2023, 9, 18, 3, 38, 'Monday', 'September', 'f', 'f', NULL),
(20230919, '2023-09-19', 2023, 9, 19, 3, 38, 'Tuesday', 'September', 'f', 'f', NULL),
(20230920, '2023-09-20', 2023, 9, 20, 3, 38, 'Wednesday', 'September', 'f', 'f', NULL),
(20230921, '2023-09-21', 2023, 9, 21, 3, 38, 'Thursday', 'September', 'f', 'f', NULL),
(20230922, '2023-09-22', 2023, 9, 22, 3, 38, 'Friday', 'September', 'f', 'f', NULL),
(20230923, '2023-09-23', 2023, 9, 23, 3, 38, 'Saturday', 'September', 'f', 't', NULL),
(20230924, '2023-09-24', 2023, 9, 24, 3, 38, 'Sunday', 'September', 'f', 't', NULL),
(20230925, '2023-09-25', 2023, 9, 25, 3, 39, 'Monday', 'September', 'f', 'f', NULL),
(20230926, '2023-09-26', 2023, 9, 26, 3, 39, 'Tuesday', 'September', 'f', 'f', NULL),
(20230927, '2023-09-27', 2023, 9, 27, 3, 39, 'Wednesday', 'September', 'f', 'f', NULL),
(20230928, '2023-09-28', 2023, 9, 28, 3, 39, 'Thursday', 'September', 'f', 'f', NULL),
(20230929, '2023-09-29', 2023, 9, 29, 3, 39, 'Friday', 'September', 'f', 'f', NULL),
(20230930, '2023-09-30', 2023, 9, 30, 3, 39, 'Saturday', 'September', 'f', 't', NULL),
(20231001, '2023-10-01', 2023, 10, 1, 4, 39, 'Sunday', 'October', 'f', 't', NULL),
(20231002, '2023-10-02', 2023, 10, 2, 4, 40, 'Monday', 'October', 'f', 'f', NULL),
(20231003, '2023-10-03', 2023, 10, 3, 4, 40, 'Tuesday', 'October', 'f', 'f', NULL),
(20231004, '2023-10-04', 2023, 10, 4, 4, 40, 'Wednesday', 'October', 'f', 'f', NULL),
(20231005, '2023-10-05', 2023, 10, 5, 4, 40, 'Thursday', 'October', 'f', 'f', NULL),
(20231006, '2023-10-06', 2023, 10, 6, 4, 40, 'Friday', 'October', 'f', 'f', NULL),
(20231007, '2023-10-07', 2023, 10, 7, 4, 40, 'Saturday', 'October', 'f', 't', NULL),
(20231008, '2023-10-08', 2023, 10, 8, 4, 40, 'Sunday', 'October', 'f', 't', NULL),
(20231009, '2023-10-09', 2023, 10, 9, 4, 41, 'Monday', 'October', 'f', 'f', NULL),
(20231010, '2023-10-10', 2023, 10, 10, 4, 41, 'Tuesday', 'October', 'f', 'f', NULL),
(20231011, '2023-10-11', 2023, 10, 11, 4, 41, 'Wednesday', 'October', 'f', 'f', NULL),
(20231012, '2023-10-12', 2023, 10, 12, 4, 41, 'Thursday', 'October', 'f', 'f', NULL),
(20231013, '2023-10-13', 2023, 10, 13, 4, 41, 'Friday', 'October', 'f', 'f', NULL),
(20231014, '2023-10-14', 2023, 10, 14, 4, 41, 'Saturday', 'October', 'f', 't', NULL),
(20231015, '2023-10-15', 2023, 10, 15, 4, 41, 'Sunday', 'October', 'f', 't', NULL),
(20231016, '2023-10-16', 2023, 10, 16, 4, 42, 'Monday', 'October', 'f', 'f', NULL),
(20231017, '2023-10-17', 2023, 10, 17, 4, 42, 'Tuesday', 'October', 'f', 'f', NULL),
(20231018, '2023-10-18', 2023, 10, 18, 4, 42, 'Wednesday', 'October', 'f', 'f', NULL),
(20231019, '2023-10-19', 2023, 10, 19, 4, 42, 'Thursday', 'October', 'f', 'f', NULL),
(20231020, '2023-10-20', 2023, 10, 20, 4, 42, 'Friday', 'October', 'f', 'f', NULL),
(20231021, '2023-10-21', 2023, 10, 21, 4, 42, 'Saturday', 'October', 'f', 't', NULL),
(20231022, '2023-10-22', 2023, 10, 22, 4, 42, 'Sunday', 'October', 'f', 't', NULL),
(20231023, '2023-10-23', 2023, 10, 23, 4, 43, 'Monday', 'October', 'f', 'f', NULL),
(20231024, '2023-10-24', 2023, 10, 24, 4, 43, 'Tuesday', 'October', 'f', 'f', NULL),
(20231025, '2023-10-25', 2023, 10, 25, 4, 43, 'Wednesday', 'October', 'f', 'f', NULL),
(20231026, '2023-10-26', 2023, 10, 26, 4, 43, 'Thursday', 'October', 'f', 'f', NULL),
(20231027, '2023-10-27', 2023, 10, 27, 4, 43, 'Friday', 'October', 'f', 'f', NULL),
(20231028, '2023-10-28', 2023, 10, 28, 4, 43, 'Saturday', 'October', 'f', 't', NULL),
(20231029, '2023-10-29', 2023, 10, 29, 4, 43, 'Sunday', 'October', 'f', 't', NULL),
(20231030, '2023-10-30', 2023, 10, 30, 4, 44, 'Monday', 'October', 'f', 'f', NULL),
(20231031, '2023-10-31', 2023, 10, 31, 4, 44, 'Tuesday', 'October', 'f', 'f', NULL),
(20231101, '2023-11-01', 2023, 11, 1, 4, 44, 'Wednesday', 'November', 'f', 'f', NULL),
(20231102, '2023-11-02', 2023, 11, 2, 4, 44, 'Thursday', 'November', 'f', 'f', NULL),
(20231103, '2023-11-03', 2023, 11, 3, 4, 44, 'Friday', 'November', 'f', 'f', NULL),
(20231104, '2023-11-04', 2023, 11, 4, 4, 44, 'Saturday', 'November', 'f', 't', NULL),
(20231105, '2023-11-05', 2023, 11, 5, 4, 44, 'Sunday', 'November', 'f', 't', NULL),
(20231106, '2023-11-06', 2023, 11, 6, 4, 45, 'Monday', 'November', 'f', 'f', NULL),
(20231107, '2023-11-07', 2023, 11, 7, 4, 45, 'Tuesday', 'November', 'f', 'f', NULL),
(20231108, '2023-11-08', 2023, 11, 8, 4, 45, 'Wednesday', 'November', 'f', 'f', NULL),
(20231109, '2023-11-09', 2023, 11, 9, 4, 45, 'Thursday', 'November', 'f', 'f', NULL),
(20231110, '2023-11-10', 2023, 11, 10, 4, 45, 'Friday', 'November', 'f', 'f', NULL),
(20231111, '2023-11-11', 2023, 11, 11, 4, 45, 'Saturday', 'November', 'f', 't', NULL),
(20231112, '2023-11-12', 2023, 11, 12, 4, 45, 'Sunday', 'November', 'f', 't', NULL),
(20231113, '2023-11-13', 2023, 11, 13, 4, 46, 'Monday', 'November', 'f', 'f', NULL),
(20231114, '2023-11-14', 2023, 11, 14, 4, 46, 'Tuesday', 'November', 'f', 'f', NULL),
(20231115, '2023-11-15', 2023, 11, 15, 4, 46, 'Wednesday', 'November', 'f', 'f', NULL),
(20231116, '2023-11-16', 2023, 11, 16, 4, 46, 'Thursday', 'November', 'f', 'f', NULL),
(20231117, '2023-11-17', 2023, 11, 17, 4, 46, 'Friday', 'November', 'f', 'f', NULL),
(20231118, '2023-11-18', 2023, 11, 18, 4, 46, 'Saturday', 'November', 'f', 't', NULL),
(20231119, '2023-11-19', 2023, 11, 19, 4, 46, 'Sunday', 'November', 'f', 't', NULL),
(20231120, '2023-11-20', 2023, 11, 20, 4, 47, 'Monday', 'November', 'f', 'f', NULL),
(20231121, '2023-11-21', 2023, 11, 21, 4, 47, 'Tuesday', 'November', 'f', 'f', NULL),
(20231122, '2023-11-22', 2023, 11, 22, 4, 47, 'Wednesday', 'November', 'f', 'f', NULL),
(20231123, '2023-11-23', 2023, 11, 23, 4, 47, 'Thursday', 'November', 'f', 'f', NULL),
(20231124, '2023-11-24', 2023, 11, 24, 4, 47, 'Friday', 'November', 'f', 'f', NULL),
(20231125, '2023-11-25', 2023, 11, 25, 4, 47, 'Saturday', 'November', 'f', 't', NULL),
(20231126, '2023-11-26', 2023, 11, 26, 4, 47, 'Sunday', 'November', 'f', 't', NULL),
(20231127, '2023-11-27', 2023, 11, 27, 4, 48, 'Monday', 'November', 'f', 'f', NULL),
(20231128, '2023-11-28', 2023, 11, 28, 4, 48, 'Tuesday', 'November', 'f', 'f', NULL),
(20231129, '2023-11-29', 2023, 11, 29, 4, 48, 'Wednesday', 'November', 'f', 'f', NULL),
(20231130, '2023-11-30', 2023, 11, 30, 4, 48, 'Thursday', 'November', 'f', 'f', NULL),
(20231201, '2023-12-01', 2023, 12, 1, 4, 48, 'Friday', 'December', 'f', 'f', NULL),
(20231202, '2023-12-02', 2023, 12, 2, 4, 48, 'Saturday', 'December', 'f', 't', NULL),
(20231203, '2023-12-03', 2023, 12, 3, 4, 48, 'Sunday', 'December', 'f', 't', NULL),
(20231204, '2023-12-04', 2023, 12, 4, 4, 49, 'Monday', 'December', 'f', 'f', NULL),
(20231205, '2023-12-05', 2023, 12, 5, 4, 49, 'Tuesday', 'December', 'f', 'f', NULL),
(20231206, '2023-12-06', 2023, 12, 6, 4, 49, 'Wednesday', 'December', 'f', 'f', NULL),
(20231207, '2023-12-07', 2023, 12, 7, 4, 49, 'Thursday', 'December', 'f', 'f', NULL),
(20231208, '2023-12-08', 2023, 12, 8, 4, 49, 'Friday', 'December', 'f', 'f', NULL),
(20231209, '2023-12-09', 2023, 12, 9, 4, 49, 'Saturday', 'December', 'f', 't', NULL),
(20231210, '2023-12-10', 2023, 12, 10, 4, 49, 'Sunday', 'December', 'f', 't', NULL),
(20231211, '2023-12-11', 2023, 12, 11, 4, 50, 'Monday', 'December', 'f', 'f', NULL),
(20231212, '2023-12-12', 2023, 12, 12, 4, 50, 'Tuesday', 'December', 'f', 'f', NULL),
(20231213, '2023-12-13', 2023, 12, 13, 4, 50, 'Wednesday', 'December', 'f', 'f', NULL),
(20231214, '2023-12-14', 2023, 12, 14, 4, 50, 'Thursday', 'December', 'f', 'f', NULL),
(20231215, '2023-12-15', 2023, 12, 15, 4, 50, 'Friday', 'December', 'f', 'f', NULL),
(20231216, '2023-12-16', 2023, 12, 16, 4, 50, 'Saturday', 'December', 'f', 't', NULL),
(20231217, '2023-12-17', 2023, 12, 17, 4, 50, 'Sunday', 'December', 'f', 't', NULL),
(20231218, '2023-12-18', 2023, 12, 18, 4, 51, 'Monday', 'December', 'f', 'f', NULL),
(20231219, '2023-12-19', 2023, 12, 19, 4, 51, 'Tuesday', 'December', 'f', 'f', NULL),
(20231220, '2023-12-20', 2023, 12, 20, 4, 51, 'Wednesday', 'December', 'f', 'f', NULL),
(20231221, '2023-12-21', 2023, 12, 21, 4, 51, 'Thursday', 'December', 'f', 'f', NULL),
(20231222, '2023-12-22', 2023, 12, 22, 4, 51, 'Friday', 'December', 'f', 'f', NULL),
(20231223, '2023-12-23', 2023, 12, 23, 4, 51, 'Saturday', 'December', 'f', 't', NULL),
(20231224, '2023-12-24', 2023, 12, 24, 4, 51, 'Sunday', 'December', 'f', 't', NULL),
(20231225, '2023-12-25', 2023, 12, 25, 4, 52, 'Monday', 'December', 'f', 'f', NULL),
(20231226, '2023-12-26', 2023, 12, 26, 4, 52, 'Tuesday', 'December', 'f', 'f', NULL),
(20231227, '2023-12-27', 2023, 12, 27, 4, 52, 'Wednesday', 'December', 'f', 'f', NULL),
(20231228, '2023-12-28', 2023, 12, 28, 4, 52, 'Thursday', 'December', 'f', 'f', NULL),
(20231229, '2023-12-29', 2023, 12, 29, 4, 52, 'Friday', 'December', 'f', 'f', NULL),
(20231230, '2023-12-30', 2023, 12, 30, 4, 52, 'Saturday', 'December', 'f', 't', NULL),
(20231231, '2023-12-31', 2023, 12, 31, 4, 52, 'Sunday', 'December', 'f', 't', NULL),
(20240101, '2024-01-01', 2024, 1, 1, 1, 1, 'Monday', 'January', 'f', 'f', NULL),
(20240102, '2024-01-02', 2024, 1, 2, 1, 1, 'Tuesday', 'January', 'f', 'f', NULL),
(20240103, '2024-01-03', 2024, 1, 3, 1, 1, 'Wednesday', 'January', 'f', 'f', NULL),
(20240104, '2024-01-04', 2024, 1, 4, 1, 1, 'Thursday', 'January', 'f', 'f', NULL),
(20240105, '2024-01-05', 2024, 1, 5, 1, 1, 'Friday', 'January', 'f', 'f', NULL),
(20240106, '2024-01-06', 2024, 1, 6, 1, 1, 'Saturday', 'January', 'f', 't', NULL),
(20240107, '2024-01-07', 2024, 1, 7, 1, 1, 'Sunday', 'January', 'f', 't', NULL),
(20240108, '2024-01-08', 2024, 1, 8, 1, 2, 'Monday', 'January', 'f', 'f', NULL),
(20240109, '2024-01-09', 2024, 1, 9, 1, 2, 'Tuesday', 'January', 'f', 'f', NULL),
(20240110, '2024-01-10', 2024, 1, 10, 1, 2, 'Wednesday', 'January', 'f', 'f', NULL),
(20240111, '2024-01-11', 2024, 1, 11, 1, 2, 'Thursday', 'January', 'f', 'f', NULL),
(20240112, '2024-01-12', 2024, 1, 12, 1, 2, 'Friday', 'January', 'f', 'f', NULL),
(20240113, '2024-01-13', 2024, 1, 13, 1, 2, 'Saturday', 'January', 'f', 't', NULL),
(20240114, '2024-01-14', 2024, 1, 14, 1, 2, 'Sunday', 'January', 'f', 't', NULL),
(20240115, '2024-01-15', 2024, 1, 15, 1, 3, 'Monday', 'January', 'f', 'f', NULL),
(20240116, '2024-01-16', 2024, 1, 16, 1, 3, 'Tuesday', 'January', 'f', 'f', NULL),
(20240117, '2024-01-17', 2024, 1, 17, 1, 3, 'Wednesday', 'January', 'f', 'f', NULL),
(20240118, '2024-01-18', 2024, 1, 18, 1, 3, 'Thursday', 'January', 'f', 'f', NULL),
(20240119, '2024-01-19', 2024, 1, 19, 1, 3, 'Friday', 'January', 'f', 'f', NULL),
(20240120, '2024-01-20', 2024, 1, 20, 1, 3, 'Saturday', 'January', 'f', 't', NULL),
(20240121, '2024-01-21', 2024, 1, 21, 1, 3, 'Sunday', 'January', 'f', 't', NULL),
(20240122, '2024-01-22', 2024, 1, 22, 1, 4, 'Monday', 'January', 'f', 'f', NULL),
(20240123, '2024-01-23', 2024, 1, 23, 1, 4, 'Tuesday', 'January', 'f', 'f', NULL),
(20240124, '2024-01-24', 2024, 1, 24, 1, 4, 'Wednesday', 'January', 'f', 'f', NULL),
(20240125, '2024-01-25', 2024, 1, 25, 1, 4, 'Thursday', 'January', 'f', 'f', NULL),
(20240126, '2024-01-26', 2024, 1, 26, 1, 4, 'Friday', 'January', 'f', 'f', NULL),
(20240127, '2024-01-27', 2024, 1, 27, 1, 4, 'Saturday', 'January', 'f', 't', NULL),
(20240128, '2024-01-28', 2024, 1, 28, 1, 4, 'Sunday', 'January', 'f', 't', NULL),
(20240129, '2024-01-29', 2024, 1, 29, 1, 5, 'Monday', 'January', 'f', 'f', NULL),
(20240130, '2024-01-30', 2024, 1, 30, 1, 5, 'Tuesday', 'January', 'f', 'f', NULL),
(20240131, '2024-01-31', 2024, 1, 31, 1, 5, 'Wednesday', 'January', 'f', 'f', NULL),
(20240201, '2024-02-01', 2024, 2, 1, 1, 5, 'Thursday', 'February', 'f', 'f', NULL),
(20240202, '2024-02-02', 2024, 2, 2, 1, 5, 'Friday', 'February', 'f', 'f', NULL),
(20240203, '2024-02-03', 2024, 2, 3, 1, 5, 'Saturday', 'February', 'f', 't', NULL),
(20240204, '2024-02-04', 2024, 2, 4, 1, 5, 'Sunday', 'February', 'f', 't', NULL),
(20240205, '2024-02-05', 2024, 2, 5, 1, 6, 'Monday', 'February', 'f', 'f', NULL),
(20240206, '2024-02-06', 2024, 2, 6, 1, 6, 'Tuesday', 'February', 'f', 'f', NULL),
(20240207, '2024-02-07', 2024, 2, 7, 1, 6, 'Wednesday', 'February', 'f', 'f', NULL),
(20240208, '2024-02-08', 2024, 2, 8, 1, 6, 'Thursday', 'February', 'f', 'f', NULL),
(20240209, '2024-02-09', 2024, 2, 9, 1, 6, 'Friday', 'February', 'f', 'f', NULL),
(20240210, '2024-02-10', 2024, 2, 10, 1, 6, 'Saturday', 'February', 'f', 't', NULL),
(20240211, '2024-02-11', 2024, 2, 11, 1, 6, 'Sunday', 'February', 'f', 't', NULL),
(20240212, '2024-02-12', 2024, 2, 12, 1, 7, 'Monday', 'February', 'f', 'f', NULL),
(20240213, '2024-02-13', 2024, 2, 13, 1, 7, 'Tuesday', 'February', 'f', 'f', NULL),
(20240214, '2024-02-14', 2024, 2, 14, 1, 7, 'Wednesday', 'February', 'f', 'f', NULL),
(20240215, '2024-02-15', 2024, 2, 15, 1, 7, 'Thursday', 'February', 'f', 'f', NULL),
(20240216, '2024-02-16', 2024, 2, 16, 1, 7, 'Friday', 'February', 'f', 'f', NULL),
(20240217, '2024-02-17', 2024, 2, 17, 1, 7, 'Saturday', 'February', 'f', 't', NULL),
(20240218, '2024-02-18', 2024, 2, 18, 1, 7, 'Sunday', 'February', 'f', 't', NULL),
(20240219, '2024-02-19', 2024, 2, 19, 1, 8, 'Monday', 'February', 'f', 'f', NULL),
(20240220, '2024-02-20', 2024, 2, 20, 1, 8, 'Tuesday', 'February', 'f', 'f', NULL),
(20240221, '2024-02-21', 2024, 2, 21, 1, 8, 'Wednesday', 'February', 'f', 'f', NULL),
(20240222, '2024-02-22', 2024, 2, 22, 1, 8, 'Thursday', 'February', 'f', 'f', NULL),
(20240223, '2024-02-23', 2024, 2, 23, 1, 8, 'Friday', 'February', 'f', 'f', NULL),
(20240224, '2024-02-24', 2024, 2, 24, 1, 8, 'Saturday', 'February', 'f', 't', NULL),
(20240225, '2024-02-25', 2024, 2, 25, 1, 8, 'Sunday', 'February', 'f', 't', NULL),
(20240226, '2024-02-26', 2024, 2, 26, 1, 9, 'Monday', 'February', 'f', 'f', NULL),
(20240227, '2024-02-27', 2024, 2, 27, 1, 9, 'Tuesday', 'February', 'f', 'f', NULL),
(20240228, '2024-02-28', 2024, 2, 28, 1, 9, 'Wednesday', 'February', 'f', 'f', NULL),
(20240229, '2024-02-29', 2024, 2, 29, 1, 9, 'Thursday', 'February', 'f', 'f', NULL),
(20240301, '2024-03-01', 2024, 3, 1, 1, 9, 'Friday', 'March', 'f', 'f', NULL),
(20240302, '2024-03-02', 2024, 3, 2, 1, 9, 'Saturday', 'March', 'f', 't', NULL),
(20240303, '2024-03-03', 2024, 3, 3, 1, 9, 'Sunday', 'March', 'f', 't', NULL),
(20240304, '2024-03-04', 2024, 3, 4, 1, 10, 'Monday', 'March', 'f', 'f', NULL),
(20240305, '2024-03-05', 2024, 3, 5, 1, 10, 'Tuesday', 'March', 'f', 'f', NULL),
(20240306, '2024-03-06', 2024, 3, 6, 1, 10, 'Wednesday', 'March', 'f', 'f', NULL),
(20240307, '2024-03-07', 2024, 3, 7, 1, 10, 'Thursday', 'March', 'f', 'f', NULL),
(20240308, '2024-03-08', 2024, 3, 8, 1, 10, 'Friday', 'March', 'f', 'f', NULL),
(20240309, '2024-03-09', 2024, 3, 9, 1, 10, 'Saturday', 'March', 'f', 't', NULL),
(20240310, '2024-03-10', 2024, 3, 10, 1, 10, 'Sunday', 'March', 'f', 't', NULL),
(20240311, '2024-03-11', 2024, 3, 11, 1, 11, 'Monday', 'March', 'f', 'f', NULL),
(20240312, '2024-03-12', 2024, 3, 12, 1, 11, 'Tuesday', 'March', 'f', 'f', NULL),
(20240313, '2024-03-13', 2024, 3, 13, 1, 11, 'Wednesday', 'March', 'f', 'f', NULL),
(20240314, '2024-03-14', 2024, 3, 14, 1, 11, 'Thursday', 'March', 'f', 'f', NULL),
(20240315, '2024-03-15', 2024, 3, 15, 1, 11, 'Friday', 'March', 'f', 'f', NULL),
(20240316, '2024-03-16', 2024, 3, 16, 1, 11, 'Saturday', 'March', 'f', 't', NULL),
(20240317, '2024-03-17', 2024, 3, 17, 1, 11, 'Sunday', 'March', 'f', 't', NULL),
(20240318, '2024-03-18', 2024, 3, 18, 1, 12, 'Monday', 'March', 'f', 'f', NULL),
(20240319, '2024-03-19', 2024, 3, 19, 1, 12, 'Tuesday', 'March', 'f', 'f', NULL),
(20240320, '2024-03-20', 2024, 3, 20, 1, 12, 'Wednesday', 'March', 'f', 'f', NULL),
(20240321, '2024-03-21', 2024, 3, 21, 1, 12, 'Thursday', 'March', 'f', 'f', NULL),
(20240322, '2024-03-22', 2024, 3, 22, 1, 12, 'Friday', 'March', 'f', 'f', NULL),
(20240323, '2024-03-23', 2024, 3, 23, 1, 12, 'Saturday', 'March', 'f', 't', NULL),
(20240324, '2024-03-24', 2024, 3, 24, 1, 12, 'Sunday', 'March', 'f', 't', NULL),
(20240325, '2024-03-25', 2024, 3, 25, 1, 13, 'Monday', 'March', 'f', 'f', NULL),
(20240326, '2024-03-26', 2024, 3, 26, 1, 13, 'Tuesday', 'March', 'f', 'f', NULL),
(20240327, '2024-03-27', 2024, 3, 27, 1, 13, 'Wednesday', 'March', 'f', 'f', NULL),
(20240328, '2024-03-28', 2024, 3, 28, 1, 13, 'Thursday', 'March', 'f', 'f', NULL),
(20240329, '2024-03-29', 2024, 3, 29, 1, 13, 'Friday', 'March', 'f', 'f', NULL),
(20240330, '2024-03-30', 2024, 3, 30, 1, 13, 'Saturday', 'March', 'f', 't', NULL),
(20240331, '2024-03-31', 2024, 3, 31, 1, 13, 'Sunday', 'March', 'f', 't', NULL),
(20240401, '2024-04-01', 2024, 4, 1, 2, 14, 'Monday', 'April', 'f', 'f', NULL),
(20240402, '2024-04-02', 2024, 4, 2, 2, 14, 'Tuesday', 'April', 'f', 'f', NULL),
(20240403, '2024-04-03', 2024, 4, 3, 2, 14, 'Wednesday', 'April', 'f', 'f', NULL),
(20240404, '2024-04-04', 2024, 4, 4, 2, 14, 'Thursday', 'April', 'f', 'f', NULL),
(20240405, '2024-04-05', 2024, 4, 5, 2, 14, 'Friday', 'April', 'f', 'f', NULL),
(20240406, '2024-04-06', 2024, 4, 6, 2, 14, 'Saturday', 'April', 'f', 't', NULL),
(20240407, '2024-04-07', 2024, 4, 7, 2, 14, 'Sunday', 'April', 'f', 't', NULL),
(20240408, '2024-04-08', 2024, 4, 8, 2, 15, 'Monday', 'April', 'f', 'f', NULL),
(20240409, '2024-04-09', 2024, 4, 9, 2, 15, 'Tuesday', 'April', 'f', 'f', NULL),
(20240410, '2024-04-10', 2024, 4, 10, 2, 15, 'Wednesday', 'April', 'f', 'f', NULL),
(20240411, '2024-04-11', 2024, 4, 11, 2, 15, 'Thursday', 'April', 'f', 'f', NULL),
(20240412, '2024-04-12', 2024, 4, 12, 2, 15, 'Friday', 'April', 'f', 'f', NULL),
(20240413, '2024-04-13', 2024, 4, 13, 2, 15, 'Saturday', 'April', 'f', 't', NULL),
(20240414, '2024-04-14', 2024, 4, 14, 2, 15, 'Sunday', 'April', 'f', 't', NULL),
(20240415, '2024-04-15', 2024, 4, 15, 2, 16, 'Monday', 'April', 'f', 'f', NULL),
(20240416, '2024-04-16', 2024, 4, 16, 2, 16, 'Tuesday', 'April', 'f', 'f', NULL),
(20240417, '2024-04-17', 2024, 4, 17, 2, 16, 'Wednesday', 'April', 'f', 'f', NULL),
(20240418, '2024-04-18', 2024, 4, 18, 2, 16, 'Thursday', 'April', 'f', 'f', NULL),
(20240419, '2024-04-19', 2024, 4, 19, 2, 16, 'Friday', 'April', 'f', 'f', NULL),
(20240420, '2024-04-20', 2024, 4, 20, 2, 16, 'Saturday', 'April', 'f', 't', NULL),
(20240421, '2024-04-21', 2024, 4, 21, 2, 16, 'Sunday', 'April', 'f', 't', NULL),
(20240422, '2024-04-22', 2024, 4, 22, 2, 17, 'Monday', 'April', 'f', 'f', NULL),
(20240423, '2024-04-23', 2024, 4, 23, 2, 17, 'Tuesday', 'April', 'f', 'f', NULL),
(20240424, '2024-04-24', 2024, 4, 24, 2, 17, 'Wednesday', 'April', 'f', 'f', NULL),
(20240425, '2024-04-25', 2024, 4, 25, 2, 17, 'Thursday', 'April', 'f', 'f', NULL),
(20240426, '2024-04-26', 2024, 4, 26, 2, 17, 'Friday', 'April', 'f', 'f', NULL),
(20240427, '2024-04-27', 2024, 4, 27, 2, 17, 'Saturday', 'April', 'f', 't', NULL),
(20240428, '2024-04-28', 2024, 4, 28, 2, 17, 'Sunday', 'April', 'f', 't', NULL),
(20240429, '2024-04-29', 2024, 4, 29, 2, 18, 'Monday', 'April', 'f', 'f', NULL),
(20240430, '2024-04-30', 2024, 4, 30, 2, 18, 'Tuesday', 'April', 'f', 'f', NULL),
(20240501, '2024-05-01', 2024, 5, 1, 2, 18, 'Wednesday', 'May', 'f', 'f', NULL),
(20240502, '2024-05-02', 2024, 5, 2, 2, 18, 'Thursday', 'May', 'f', 'f', NULL),
(20240503, '2024-05-03', 2024, 5, 3, 2, 18, 'Friday', 'May', 'f', 'f', NULL),
(20240504, '2024-05-04', 2024, 5, 4, 2, 18, 'Saturday', 'May', 'f', 't', NULL),
(20240505, '2024-05-05', 2024, 5, 5, 2, 18, 'Sunday', 'May', 'f', 't', NULL),
(20240506, '2024-05-06', 2024, 5, 6, 2, 19, 'Monday', 'May', 'f', 'f', NULL),
(20240507, '2024-05-07', 2024, 5, 7, 2, 19, 'Tuesday', 'May', 'f', 'f', NULL),
(20240508, '2024-05-08', 2024, 5, 8, 2, 19, 'Wednesday', 'May', 'f', 'f', NULL),
(20240509, '2024-05-09', 2024, 5, 9, 2, 19, 'Thursday', 'May', 'f', 'f', NULL),
(20240510, '2024-05-10', 2024, 5, 10, 2, 19, 'Friday', 'May', 'f', 'f', NULL),
(20240511, '2024-05-11', 2024, 5, 11, 2, 19, 'Saturday', 'May', 'f', 't', NULL),
(20240512, '2024-05-12', 2024, 5, 12, 2, 19, 'Sunday', 'May', 'f', 't', NULL),
(20240513, '2024-05-13', 2024, 5, 13, 2, 20, 'Monday', 'May', 'f', 'f', NULL),
(20240514, '2024-05-14', 2024, 5, 14, 2, 20, 'Tuesday', 'May', 'f', 'f', NULL),
(20240515, '2024-05-15', 2024, 5, 15, 2, 20, 'Wednesday', 'May', 'f', 'f', NULL),
(20240516, '2024-05-16', 2024, 5, 16, 2, 20, 'Thursday', 'May', 'f', 'f', NULL),
(20240517, '2024-05-17', 2024, 5, 17, 2, 20, 'Friday', 'May', 'f', 'f', NULL),
(20240518, '2024-05-18', 2024, 5, 18, 2, 20, 'Saturday', 'May', 'f', 't', NULL),
(20240519, '2024-05-19', 2024, 5, 19, 2, 20, 'Sunday', 'May', 'f', 't', NULL),
(20240520, '2024-05-20', 2024, 5, 20, 2, 21, 'Monday', 'May', 'f', 'f', NULL),
(20240521, '2024-05-21', 2024, 5, 21, 2, 21, 'Tuesday', 'May', 'f', 'f', NULL),
(20240522, '2024-05-22', 2024, 5, 22, 2, 21, 'Wednesday', 'May', 'f', 'f', NULL),
(20240523, '2024-05-23', 2024, 5, 23, 2, 21, 'Thursday', 'May', 'f', 'f', NULL),
(20240524, '2024-05-24', 2024, 5, 24, 2, 21, 'Friday', 'May', 'f', 'f', NULL),
(20240525, '2024-05-25', 2024, 5, 25, 2, 21, 'Saturday', 'May', 'f', 't', NULL),
(20240526, '2024-05-26', 2024, 5, 26, 2, 21, 'Sunday', 'May', 'f', 't', NULL),
(20240527, '2024-05-27', 2024, 5, 27, 2, 22, 'Monday', 'May', 'f', 'f', NULL),
(20240528, '2024-05-28', 2024, 5, 28, 2, 22, 'Tuesday', 'May', 'f', 'f', NULL),
(20240529, '2024-05-29', 2024, 5, 29, 2, 22, 'Wednesday', 'May', 'f', 'f', NULL),
(20240530, '2024-05-30', 2024, 5, 30, 2, 22, 'Thursday', 'May', 'f', 'f', NULL),
(20240531, '2024-05-31', 2024, 5, 31, 2, 22, 'Friday', 'May', 'f', 'f', NULL),
(20240601, '2024-06-01', 2024, 6, 1, 2, 22, 'Saturday', 'June', 'f', 't', NULL),
(20240602, '2024-06-02', 2024, 6, 2, 2, 22, 'Sunday', 'June', 'f', 't', NULL),
(20240603, '2024-06-03', 2024, 6, 3, 2, 23, 'Monday', 'June', 'f', 'f', NULL),
(20240604, '2024-06-04', 2024, 6, 4, 2, 23, 'Tuesday', 'June', 'f', 'f', NULL),
(20240605, '2024-06-05', 2024, 6, 5, 2, 23, 'Wednesday', 'June', 'f', 'f', NULL),
(20240606, '2024-06-06', 2024, 6, 6, 2, 23, 'Thursday', 'June', 'f', 'f', NULL),
(20240607, '2024-06-07', 2024, 6, 7, 2, 23, 'Friday', 'June', 'f', 'f', NULL),
(20240608, '2024-06-08', 2024, 6, 8, 2, 23, 'Saturday', 'June', 'f', 't', NULL),
(20240609, '2024-06-09', 2024, 6, 9, 2, 23, 'Sunday', 'June', 'f', 't', NULL),
(20240610, '2024-06-10', 2024, 6, 10, 2, 24, 'Monday', 'June', 'f', 'f', NULL),
(20240611, '2024-06-11', 2024, 6, 11, 2, 24, 'Tuesday', 'June', 'f', 'f', NULL),
(20240612, '2024-06-12', 2024, 6, 12, 2, 24, 'Wednesday', 'June', 'f', 'f', NULL),
(20240613, '2024-06-13', 2024, 6, 13, 2, 24, 'Thursday', 'June', 'f', 'f', NULL),
(20240614, '2024-06-14', 2024, 6, 14, 2, 24, 'Friday', 'June', 'f', 'f', NULL),
(20240615, '2024-06-15', 2024, 6, 15, 2, 24, 'Saturday', 'June', 'f', 't', NULL),
(20240616, '2024-06-16', 2024, 6, 16, 2, 24, 'Sunday', 'June', 'f', 't', NULL),
(20240617, '2024-06-17', 2024, 6, 17, 2, 25, 'Monday', 'June', 'f', 'f', NULL),
(20240618, '2024-06-18', 2024, 6, 18, 2, 25, 'Tuesday', 'June', 'f', 'f', NULL),
(20240619, '2024-06-19', 2024, 6, 19, 2, 25, 'Wednesday', 'June', 'f', 'f', NULL),
(20240620, '2024-06-20', 2024, 6, 20, 2, 25, 'Thursday', 'June', 'f', 'f', NULL),
(20240621, '2024-06-21', 2024, 6, 21, 2, 25, 'Friday', 'June', 'f', 'f', NULL),
(20240622, '2024-06-22', 2024, 6, 22, 2, 25, 'Saturday', 'June', 'f', 't', NULL),
(20240623, '2024-06-23', 2024, 6, 23, 2, 25, 'Sunday', 'June', 'f', 't', NULL),
(20240624, '2024-06-24', 2024, 6, 24, 2, 26, 'Monday', 'June', 'f', 'f', NULL),
(20240625, '2024-06-25', 2024, 6, 25, 2, 26, 'Tuesday', 'June', 'f', 'f', NULL),
(20240626, '2024-06-26', 2024, 6, 26, 2, 26, 'Wednesday', 'June', 'f', 'f', NULL),
(20240627, '2024-06-27', 2024, 6, 27, 2, 26, 'Thursday', 'June', 'f', 'f', NULL),
(20240628, '2024-06-28', 2024, 6, 28, 2, 26, 'Friday', 'June', 'f', 'f', NULL),
(20240629, '2024-06-29', 2024, 6, 29, 2, 26, 'Saturday', 'June', 'f', 't', NULL),
(20240630, '2024-06-30', 2024, 6, 30, 2, 26, 'Sunday', 'June', 'f', 't', NULL),
(20240701, '2024-07-01', 2024, 7, 1, 3, 27, 'Monday', 'July', 'f', 'f', NULL),
(20240702, '2024-07-02', 2024, 7, 2, 3, 27, 'Tuesday', 'July', 'f', 'f', NULL),
(20240703, '2024-07-03', 2024, 7, 3, 3, 27, 'Wednesday', 'July', 'f', 'f', NULL),
(20240704, '2024-07-04', 2024, 7, 4, 3, 27, 'Thursday', 'July', 'f', 'f', NULL),
(20240705, '2024-07-05', 2024, 7, 5, 3, 27, 'Friday', 'July', 'f', 'f', NULL),
(20240706, '2024-07-06', 2024, 7, 6, 3, 27, 'Saturday', 'July', 'f', 't', NULL),
(20240707, '2024-07-07', 2024, 7, 7, 3, 27, 'Sunday', 'July', 'f', 't', NULL),
(20240708, '2024-07-08', 2024, 7, 8, 3, 28, 'Monday', 'July', 'f', 'f', NULL),
(20240709, '2024-07-09', 2024, 7, 9, 3, 28, 'Tuesday', 'July', 'f', 'f', NULL),
(20240710, '2024-07-10', 2024, 7, 10, 3, 28, 'Wednesday', 'July', 'f', 'f', NULL),
(20240711, '2024-07-11', 2024, 7, 11, 3, 28, 'Thursday', 'July', 'f', 'f', NULL),
(20240712, '2024-07-12', 2024, 7, 12, 3, 28, 'Friday', 'July', 'f', 'f', NULL),
(20240713, '2024-07-13', 2024, 7, 13, 3, 28, 'Saturday', 'July', 'f', 't', NULL),
(20240714, '2024-07-14', 2024, 7, 14, 3, 28, 'Sunday', 'July', 'f', 't', NULL),
(20240715, '2024-07-15', 2024, 7, 15, 3, 29, 'Monday', 'July', 'f', 'f', NULL),
(20240716, '2024-07-16', 2024, 7, 16, 3, 29, 'Tuesday', 'July', 'f', 'f', NULL),
(20240717, '2024-07-17', 2024, 7, 17, 3, 29, 'Wednesday', 'July', 'f', 'f', NULL),
(20240718, '2024-07-18', 2024, 7, 18, 3, 29, 'Thursday', 'July', 'f', 'f', NULL),
(20240719, '2024-07-19', 2024, 7, 19, 3, 29, 'Friday', 'July', 'f', 'f', NULL),
(20240720, '2024-07-20', 2024, 7, 20, 3, 29, 'Saturday', 'July', 'f', 't', NULL),
(20240721, '2024-07-21', 2024, 7, 21, 3, 29, 'Sunday', 'July', 'f', 't', NULL),
(20240722, '2024-07-22', 2024, 7, 22, 3, 30, 'Monday', 'July', 'f', 'f', NULL),
(20240723, '2024-07-23', 2024, 7, 23, 3, 30, 'Tuesday', 'July', 'f', 'f', NULL),
(20240724, '2024-07-24', 2024, 7, 24, 3, 30, 'Wednesday', 'July', 'f', 'f', NULL),
(20240725, '2024-07-25', 2024, 7, 25, 3, 30, 'Thursday', 'July', 'f', 'f', NULL),
(20240726, '2024-07-26', 2024, 7, 26, 3, 30, 'Friday', 'July', 'f', 'f', NULL),
(20240727, '2024-07-27', 2024, 7, 27, 3, 30, 'Saturday', 'July', 'f', 't', NULL),
(20240728, '2024-07-28', 2024, 7, 28, 3, 30, 'Sunday', 'July', 'f', 't', NULL),
(20240729, '2024-07-29', 2024, 7, 29, 3, 31, 'Monday', 'July', 'f', 'f', NULL),
(20240730, '2024-07-30', 2024, 7, 30, 3, 31, 'Tuesday', 'July', 'f', 'f', NULL),
(20240731, '2024-07-31', 2024, 7, 31, 3, 31, 'Wednesday', 'July', 'f', 'f', NULL),
(20240801, '2024-08-01', 2024, 8, 1, 3, 31, 'Thursday', 'August', 'f', 'f', NULL),
(20240802, '2024-08-02', 2024, 8, 2, 3, 31, 'Friday', 'August', 'f', 'f', NULL),
(20240803, '2024-08-03', 2024, 8, 3, 3, 31, 'Saturday', 'August', 'f', 't', NULL),
(20240804, '2024-08-04', 2024, 8, 4, 3, 31, 'Sunday', 'August', 'f', 't', NULL),
(20240805, '2024-08-05', 2024, 8, 5, 3, 32, 'Monday', 'August', 'f', 'f', NULL),
(20240806, '2024-08-06', 2024, 8, 6, 3, 32, 'Tuesday', 'August', 'f', 'f', NULL),
(20240807, '2024-08-07', 2024, 8, 7, 3, 32, 'Wednesday', 'August', 'f', 'f', NULL),
(20240808, '2024-08-08', 2024, 8, 8, 3, 32, 'Thursday', 'August', 'f', 'f', NULL),
(20240809, '2024-08-09', 2024, 8, 9, 3, 32, 'Friday', 'August', 'f', 'f', NULL),
(20240810, '2024-08-10', 2024, 8, 10, 3, 32, 'Saturday', 'August', 'f', 't', NULL),
(20240811, '2024-08-11', 2024, 8, 11, 3, 32, 'Sunday', 'August', 'f', 't', NULL),
(20240812, '2024-08-12', 2024, 8, 12, 3, 33, 'Monday', 'August', 'f', 'f', NULL),
(20240813, '2024-08-13', 2024, 8, 13, 3, 33, 'Tuesday', 'August', 'f', 'f', NULL),
(20240814, '2024-08-14', 2024, 8, 14, 3, 33, 'Wednesday', 'August', 'f', 'f', NULL),
(20240815, '2024-08-15', 2024, 8, 15, 3, 33, 'Thursday', 'August', 'f', 'f', NULL),
(20240816, '2024-08-16', 2024, 8, 16, 3, 33, 'Friday', 'August', 'f', 'f', NULL),
(20240817, '2024-08-17', 2024, 8, 17, 3, 33, 'Saturday', 'August', 'f', 't', NULL),
(20240818, '2024-08-18', 2024, 8, 18, 3, 33, 'Sunday', 'August', 'f', 't', NULL),
(20240819, '2024-08-19', 2024, 8, 19, 3, 34, 'Monday', 'August', 'f', 'f', NULL),
(20240820, '2024-08-20', 2024, 8, 20, 3, 34, 'Tuesday', 'August', 'f', 'f', NULL),
(20240821, '2024-08-21', 2024, 8, 21, 3, 34, 'Wednesday', 'August', 'f', 'f', NULL),
(20240822, '2024-08-22', 2024, 8, 22, 3, 34, 'Thursday', 'August', 'f', 'f', NULL),
(20240823, '2024-08-23', 2024, 8, 23, 3, 34, 'Friday', 'August', 'f', 'f', NULL),
(20240824, '2024-08-24', 2024, 8, 24, 3, 34, 'Saturday', 'August', 'f', 't', NULL),
(20240825, '2024-08-25', 2024, 8, 25, 3, 34, 'Sunday', 'August', 'f', 't', NULL),
(20240826, '2024-08-26', 2024, 8, 26, 3, 35, 'Monday', 'August', 'f', 'f', NULL),
(20240827, '2024-08-27', 2024, 8, 27, 3, 35, 'Tuesday', 'August', 'f', 'f', NULL),
(20240828, '2024-08-28', 2024, 8, 28, 3, 35, 'Wednesday', 'August', 'f', 'f', NULL),
(20240829, '2024-08-29', 2024, 8, 29, 3, 35, 'Thursday', 'August', 'f', 'f', NULL),
(20240830, '2024-08-30', 2024, 8, 30, 3, 35, 'Friday', 'August', 'f', 'f', NULL),
(20240831, '2024-08-31', 2024, 8, 31, 3, 35, 'Saturday', 'August', 'f', 't', NULL),
(20240901, '2024-09-01', 2024, 9, 1, 3, 35, 'Sunday', 'September', 'f', 't', NULL),
(20240902, '2024-09-02', 2024, 9, 2, 3, 36, 'Monday', 'September', 'f', 'f', NULL),
(20240903, '2024-09-03', 2024, 9, 3, 3, 36, 'Tuesday', 'September', 'f', 'f', NULL),
(20240904, '2024-09-04', 2024, 9, 4, 3, 36, 'Wednesday', 'September', 'f', 'f', NULL),
(20240905, '2024-09-05', 2024, 9, 5, 3, 36, 'Thursday', 'September', 'f', 'f', NULL),
(20240906, '2024-09-06', 2024, 9, 6, 3, 36, 'Friday', 'September', 'f', 'f', NULL),
(20240907, '2024-09-07', 2024, 9, 7, 3, 36, 'Saturday', 'September', 'f', 't', NULL),
(20240908, '2024-09-08', 2024, 9, 8, 3, 36, 'Sunday', 'September', 'f', 't', NULL),
(20240909, '2024-09-09', 2024, 9, 9, 3, 37, 'Monday', 'September', 'f', 'f', NULL),
(20240910, '2024-09-10', 2024, 9, 10, 3, 37, 'Tuesday', 'September', 'f', 'f', NULL),
(20240911, '2024-09-11', 2024, 9, 11, 3, 37, 'Wednesday', 'September', 'f', 'f', NULL),
(20240912, '2024-09-12', 2024, 9, 12, 3, 37, 'Thursday', 'September', 'f', 'f', NULL),
(20240913, '2024-09-13', 2024, 9, 13, 3, 37, 'Friday', 'September', 'f', 'f', NULL),
(20240914, '2024-09-14', 2024, 9, 14, 3, 37, 'Saturday', 'September', 'f', 't', NULL),
(20240915, '2024-09-15', 2024, 9, 15, 3, 37, 'Sunday', 'September', 'f', 't', NULL),
(20240916, '2024-09-16', 2024, 9, 16, 3, 38, 'Monday', 'September', 'f', 'f', NULL),
(20240917, '2024-09-17', 2024, 9, 17, 3, 38, 'Tuesday', 'September', 'f', 'f', NULL),
(20240918, '2024-09-18', 2024, 9, 18, 3, 38, 'Wednesday', 'September', 'f', 'f', NULL),
(20240919, '2024-09-19', 2024, 9, 19, 3, 38, 'Thursday', 'September', 'f', 'f', NULL),
(20240920, '2024-09-20', 2024, 9, 20, 3, 38, 'Friday', 'September', 'f', 'f', NULL),
(20240921, '2024-09-21', 2024, 9, 21, 3, 38, 'Saturday', 'September', 'f', 't', NULL),
(20240922, '2024-09-22', 2024, 9, 22, 3, 38, 'Sunday', 'September', 'f', 't', NULL),
(20240923, '2024-09-23', 2024, 9, 23, 3, 39, 'Monday', 'September', 'f', 'f', NULL),
(20240924, '2024-09-24', 2024, 9, 24, 3, 39, 'Tuesday', 'September', 'f', 'f', NULL),
(20240925, '2024-09-25', 2024, 9, 25, 3, 39, 'Wednesday', 'September', 'f', 'f', NULL),
(20240926, '2024-09-26', 2024, 9, 26, 3, 39, 'Thursday', 'September', 'f', 'f', NULL);
INSERT INTO `time_dimension` (`id`, `db_date`, `year`, `month`, `day`, `quarter`, `week`, `day_name`, `month_name`, `holiday_flag`, `weekend_flag`, `event`) VALUES
(20240927, '2024-09-27', 2024, 9, 27, 3, 39, 'Friday', 'September', 'f', 'f', NULL),
(20240928, '2024-09-28', 2024, 9, 28, 3, 39, 'Saturday', 'September', 'f', 't', NULL),
(20240929, '2024-09-29', 2024, 9, 29, 3, 39, 'Sunday', 'September', 'f', 't', NULL),
(20240930, '2024-09-30', 2024, 9, 30, 3, 40, 'Monday', 'September', 'f', 'f', NULL),
(20241001, '2024-10-01', 2024, 10, 1, 4, 40, 'Tuesday', 'October', 'f', 'f', NULL),
(20241002, '2024-10-02', 2024, 10, 2, 4, 40, 'Wednesday', 'October', 'f', 'f', NULL),
(20241003, '2024-10-03', 2024, 10, 3, 4, 40, 'Thursday', 'October', 'f', 'f', NULL),
(20241004, '2024-10-04', 2024, 10, 4, 4, 40, 'Friday', 'October', 'f', 'f', NULL),
(20241005, '2024-10-05', 2024, 10, 5, 4, 40, 'Saturday', 'October', 'f', 't', NULL),
(20241006, '2024-10-06', 2024, 10, 6, 4, 40, 'Sunday', 'October', 'f', 't', NULL),
(20241007, '2024-10-07', 2024, 10, 7, 4, 41, 'Monday', 'October', 'f', 'f', NULL),
(20241008, '2024-10-08', 2024, 10, 8, 4, 41, 'Tuesday', 'October', 'f', 'f', NULL),
(20241009, '2024-10-09', 2024, 10, 9, 4, 41, 'Wednesday', 'October', 'f', 'f', NULL),
(20241010, '2024-10-10', 2024, 10, 10, 4, 41, 'Thursday', 'October', 'f', 'f', NULL),
(20241011, '2024-10-11', 2024, 10, 11, 4, 41, 'Friday', 'October', 'f', 'f', NULL),
(20241012, '2024-10-12', 2024, 10, 12, 4, 41, 'Saturday', 'October', 'f', 't', NULL),
(20241013, '2024-10-13', 2024, 10, 13, 4, 41, 'Sunday', 'October', 'f', 't', NULL),
(20241014, '2024-10-14', 2024, 10, 14, 4, 42, 'Monday', 'October', 'f', 'f', NULL),
(20241015, '2024-10-15', 2024, 10, 15, 4, 42, 'Tuesday', 'October', 'f', 'f', NULL),
(20241016, '2024-10-16', 2024, 10, 16, 4, 42, 'Wednesday', 'October', 'f', 'f', NULL),
(20241017, '2024-10-17', 2024, 10, 17, 4, 42, 'Thursday', 'October', 'f', 'f', NULL),
(20241018, '2024-10-18', 2024, 10, 18, 4, 42, 'Friday', 'October', 'f', 'f', NULL),
(20241019, '2024-10-19', 2024, 10, 19, 4, 42, 'Saturday', 'October', 'f', 't', NULL),
(20241020, '2024-10-20', 2024, 10, 20, 4, 42, 'Sunday', 'October', 'f', 't', NULL),
(20241021, '2024-10-21', 2024, 10, 21, 4, 43, 'Monday', 'October', 'f', 'f', NULL),
(20241022, '2024-10-22', 2024, 10, 22, 4, 43, 'Tuesday', 'October', 'f', 'f', NULL),
(20241023, '2024-10-23', 2024, 10, 23, 4, 43, 'Wednesday', 'October', 'f', 'f', NULL),
(20241024, '2024-10-24', 2024, 10, 24, 4, 43, 'Thursday', 'October', 'f', 'f', NULL),
(20241025, '2024-10-25', 2024, 10, 25, 4, 43, 'Friday', 'October', 'f', 'f', NULL),
(20241026, '2024-10-26', 2024, 10, 26, 4, 43, 'Saturday', 'October', 'f', 't', NULL),
(20241027, '2024-10-27', 2024, 10, 27, 4, 43, 'Sunday', 'October', 'f', 't', NULL),
(20241028, '2024-10-28', 2024, 10, 28, 4, 44, 'Monday', 'October', 'f', 'f', NULL),
(20241029, '2024-10-29', 2024, 10, 29, 4, 44, 'Tuesday', 'October', 'f', 'f', NULL),
(20241030, '2024-10-30', 2024, 10, 30, 4, 44, 'Wednesday', 'October', 'f', 'f', NULL),
(20241031, '2024-10-31', 2024, 10, 31, 4, 44, 'Thursday', 'October', 'f', 'f', NULL),
(20241101, '2024-11-01', 2024, 11, 1, 4, 44, 'Friday', 'November', 'f', 'f', NULL),
(20241102, '2024-11-02', 2024, 11, 2, 4, 44, 'Saturday', 'November', 'f', 't', NULL),
(20241103, '2024-11-03', 2024, 11, 3, 4, 44, 'Sunday', 'November', 'f', 't', NULL),
(20241104, '2024-11-04', 2024, 11, 4, 4, 45, 'Monday', 'November', 'f', 'f', NULL),
(20241105, '2024-11-05', 2024, 11, 5, 4, 45, 'Tuesday', 'November', 'f', 'f', NULL),
(20241106, '2024-11-06', 2024, 11, 6, 4, 45, 'Wednesday', 'November', 'f', 'f', NULL),
(20241107, '2024-11-07', 2024, 11, 7, 4, 45, 'Thursday', 'November', 'f', 'f', NULL),
(20241108, '2024-11-08', 2024, 11, 8, 4, 45, 'Friday', 'November', 'f', 'f', NULL),
(20241109, '2024-11-09', 2024, 11, 9, 4, 45, 'Saturday', 'November', 'f', 't', NULL),
(20241110, '2024-11-10', 2024, 11, 10, 4, 45, 'Sunday', 'November', 'f', 't', NULL),
(20241111, '2024-11-11', 2024, 11, 11, 4, 46, 'Monday', 'November', 'f', 'f', NULL),
(20241112, '2024-11-12', 2024, 11, 12, 4, 46, 'Tuesday', 'November', 'f', 'f', NULL),
(20241113, '2024-11-13', 2024, 11, 13, 4, 46, 'Wednesday', 'November', 'f', 'f', NULL),
(20241114, '2024-11-14', 2024, 11, 14, 4, 46, 'Thursday', 'November', 'f', 'f', NULL),
(20241115, '2024-11-15', 2024, 11, 15, 4, 46, 'Friday', 'November', 'f', 'f', NULL),
(20241116, '2024-11-16', 2024, 11, 16, 4, 46, 'Saturday', 'November', 'f', 't', NULL),
(20241117, '2024-11-17', 2024, 11, 17, 4, 46, 'Sunday', 'November', 'f', 't', NULL),
(20241118, '2024-11-18', 2024, 11, 18, 4, 47, 'Monday', 'November', 'f', 'f', NULL),
(20241119, '2024-11-19', 2024, 11, 19, 4, 47, 'Tuesday', 'November', 'f', 'f', NULL),
(20241120, '2024-11-20', 2024, 11, 20, 4, 47, 'Wednesday', 'November', 'f', 'f', NULL),
(20241121, '2024-11-21', 2024, 11, 21, 4, 47, 'Thursday', 'November', 'f', 'f', NULL),
(20241122, '2024-11-22', 2024, 11, 22, 4, 47, 'Friday', 'November', 'f', 'f', NULL),
(20241123, '2024-11-23', 2024, 11, 23, 4, 47, 'Saturday', 'November', 'f', 't', NULL),
(20241124, '2024-11-24', 2024, 11, 24, 4, 47, 'Sunday', 'November', 'f', 't', NULL),
(20241125, '2024-11-25', 2024, 11, 25, 4, 48, 'Monday', 'November', 'f', 'f', NULL),
(20241126, '2024-11-26', 2024, 11, 26, 4, 48, 'Tuesday', 'November', 'f', 'f', NULL),
(20241127, '2024-11-27', 2024, 11, 27, 4, 48, 'Wednesday', 'November', 'f', 'f', NULL),
(20241128, '2024-11-28', 2024, 11, 28, 4, 48, 'Thursday', 'November', 'f', 'f', NULL),
(20241129, '2024-11-29', 2024, 11, 29, 4, 48, 'Friday', 'November', 'f', 'f', NULL),
(20241130, '2024-11-30', 2024, 11, 30, 4, 48, 'Saturday', 'November', 'f', 't', NULL),
(20241201, '2024-12-01', 2024, 12, 1, 4, 48, 'Sunday', 'December', 'f', 't', NULL),
(20241202, '2024-12-02', 2024, 12, 2, 4, 49, 'Monday', 'December', 'f', 'f', NULL),
(20241203, '2024-12-03', 2024, 12, 3, 4, 49, 'Tuesday', 'December', 'f', 'f', NULL),
(20241204, '2024-12-04', 2024, 12, 4, 4, 49, 'Wednesday', 'December', 'f', 'f', NULL),
(20241205, '2024-12-05', 2024, 12, 5, 4, 49, 'Thursday', 'December', 'f', 'f', NULL),
(20241206, '2024-12-06', 2024, 12, 6, 4, 49, 'Friday', 'December', 'f', 'f', NULL),
(20241207, '2024-12-07', 2024, 12, 7, 4, 49, 'Saturday', 'December', 'f', 't', NULL),
(20241208, '2024-12-08', 2024, 12, 8, 4, 49, 'Sunday', 'December', 'f', 't', NULL),
(20241209, '2024-12-09', 2024, 12, 9, 4, 50, 'Monday', 'December', 'f', 'f', NULL),
(20241210, '2024-12-10', 2024, 12, 10, 4, 50, 'Tuesday', 'December', 'f', 'f', NULL),
(20241211, '2024-12-11', 2024, 12, 11, 4, 50, 'Wednesday', 'December', 'f', 'f', NULL),
(20241212, '2024-12-12', 2024, 12, 12, 4, 50, 'Thursday', 'December', 'f', 'f', NULL),
(20241213, '2024-12-13', 2024, 12, 13, 4, 50, 'Friday', 'December', 'f', 'f', NULL),
(20241214, '2024-12-14', 2024, 12, 14, 4, 50, 'Saturday', 'December', 'f', 't', NULL),
(20241215, '2024-12-15', 2024, 12, 15, 4, 50, 'Sunday', 'December', 'f', 't', NULL),
(20241216, '2024-12-16', 2024, 12, 16, 4, 51, 'Monday', 'December', 'f', 'f', NULL),
(20241217, '2024-12-17', 2024, 12, 17, 4, 51, 'Tuesday', 'December', 'f', 'f', NULL),
(20241218, '2024-12-18', 2024, 12, 18, 4, 51, 'Wednesday', 'December', 'f', 'f', NULL),
(20241219, '2024-12-19', 2024, 12, 19, 4, 51, 'Thursday', 'December', 'f', 'f', NULL),
(20241220, '2024-12-20', 2024, 12, 20, 4, 51, 'Friday', 'December', 'f', 'f', NULL),
(20241221, '2024-12-21', 2024, 12, 21, 4, 51, 'Saturday', 'December', 'f', 't', NULL),
(20241222, '2024-12-22', 2024, 12, 22, 4, 51, 'Sunday', 'December', 'f', 't', NULL),
(20241223, '2024-12-23', 2024, 12, 23, 4, 52, 'Monday', 'December', 'f', 'f', NULL),
(20241224, '2024-12-24', 2024, 12, 24, 4, 52, 'Tuesday', 'December', 'f', 'f', NULL),
(20241225, '2024-12-25', 2024, 12, 25, 4, 52, 'Wednesday', 'December', 'f', 'f', NULL),
(20241226, '2024-12-26', 2024, 12, 26, 4, 52, 'Thursday', 'December', 'f', 'f', NULL),
(20241227, '2024-12-27', 2024, 12, 27, 4, 52, 'Friday', 'December', 'f', 'f', NULL),
(20241228, '2024-12-28', 2024, 12, 28, 4, 52, 'Saturday', 'December', 'f', 't', NULL),
(20241229, '2024-12-29', 2024, 12, 29, 4, 52, 'Sunday', 'December', 'f', 't', NULL),
(20241230, '2024-12-30', 2024, 12, 30, 4, 1, 'Monday', 'December', 'f', 'f', NULL),
(20241231, '2024-12-31', 2024, 12, 31, 4, 1, 'Tuesday', 'December', 'f', 'f', NULL),
(20250101, '2025-01-01', 2025, 1, 1, 1, 1, 'Wednesday', 'January', 'f', 'f', NULL),
(20250102, '2025-01-02', 2025, 1, 2, 1, 1, 'Thursday', 'January', 'f', 'f', NULL),
(20250103, '2025-01-03', 2025, 1, 3, 1, 1, 'Friday', 'January', 'f', 'f', NULL),
(20250104, '2025-01-04', 2025, 1, 4, 1, 1, 'Saturday', 'January', 'f', 't', NULL),
(20250105, '2025-01-05', 2025, 1, 5, 1, 1, 'Sunday', 'January', 'f', 't', NULL),
(20250106, '2025-01-06', 2025, 1, 6, 1, 2, 'Monday', 'January', 'f', 'f', NULL),
(20250107, '2025-01-07', 2025, 1, 7, 1, 2, 'Tuesday', 'January', 'f', 'f', NULL),
(20250108, '2025-01-08', 2025, 1, 8, 1, 2, 'Wednesday', 'January', 'f', 'f', NULL),
(20250109, '2025-01-09', 2025, 1, 9, 1, 2, 'Thursday', 'January', 'f', 'f', NULL),
(20250110, '2025-01-10', 2025, 1, 10, 1, 2, 'Friday', 'January', 'f', 'f', NULL),
(20250111, '2025-01-11', 2025, 1, 11, 1, 2, 'Saturday', 'January', 'f', 't', NULL),
(20250112, '2025-01-12', 2025, 1, 12, 1, 2, 'Sunday', 'January', 'f', 't', NULL),
(20250113, '2025-01-13', 2025, 1, 13, 1, 3, 'Monday', 'January', 'f', 'f', NULL),
(20250114, '2025-01-14', 2025, 1, 14, 1, 3, 'Tuesday', 'January', 'f', 'f', NULL),
(20250115, '2025-01-15', 2025, 1, 15, 1, 3, 'Wednesday', 'January', 'f', 'f', NULL),
(20250116, '2025-01-16', 2025, 1, 16, 1, 3, 'Thursday', 'January', 'f', 'f', NULL),
(20250117, '2025-01-17', 2025, 1, 17, 1, 3, 'Friday', 'January', 'f', 'f', NULL),
(20250118, '2025-01-18', 2025, 1, 18, 1, 3, 'Saturday', 'January', 'f', 't', NULL),
(20250119, '2025-01-19', 2025, 1, 19, 1, 3, 'Sunday', 'January', 'f', 't', NULL),
(20250120, '2025-01-20', 2025, 1, 20, 1, 4, 'Monday', 'January', 'f', 'f', NULL),
(20250121, '2025-01-21', 2025, 1, 21, 1, 4, 'Tuesday', 'January', 'f', 'f', NULL),
(20250122, '2025-01-22', 2025, 1, 22, 1, 4, 'Wednesday', 'January', 'f', 'f', NULL),
(20250123, '2025-01-23', 2025, 1, 23, 1, 4, 'Thursday', 'January', 'f', 'f', NULL),
(20250124, '2025-01-24', 2025, 1, 24, 1, 4, 'Friday', 'January', 'f', 'f', NULL),
(20250125, '2025-01-25', 2025, 1, 25, 1, 4, 'Saturday', 'January', 'f', 't', NULL),
(20250126, '2025-01-26', 2025, 1, 26, 1, 4, 'Sunday', 'January', 'f', 't', NULL),
(20250127, '2025-01-27', 2025, 1, 27, 1, 5, 'Monday', 'January', 'f', 'f', NULL),
(20250128, '2025-01-28', 2025, 1, 28, 1, 5, 'Tuesday', 'January', 'f', 'f', NULL),
(20250129, '2025-01-29', 2025, 1, 29, 1, 5, 'Wednesday', 'January', 'f', 'f', NULL),
(20250130, '2025-01-30', 2025, 1, 30, 1, 5, 'Thursday', 'January', 'f', 'f', NULL),
(20250131, '2025-01-31', 2025, 1, 31, 1, 5, 'Friday', 'January', 'f', 'f', NULL),
(20250201, '2025-02-01', 2025, 2, 1, 1, 5, 'Saturday', 'February', 'f', 't', NULL),
(20250202, '2025-02-02', 2025, 2, 2, 1, 5, 'Sunday', 'February', 'f', 't', NULL),
(20250203, '2025-02-03', 2025, 2, 3, 1, 6, 'Monday', 'February', 'f', 'f', NULL),
(20250204, '2025-02-04', 2025, 2, 4, 1, 6, 'Tuesday', 'February', 'f', 'f', NULL),
(20250205, '2025-02-05', 2025, 2, 5, 1, 6, 'Wednesday', 'February', 'f', 'f', NULL),
(20250206, '2025-02-06', 2025, 2, 6, 1, 6, 'Thursday', 'February', 'f', 'f', NULL),
(20250207, '2025-02-07', 2025, 2, 7, 1, 6, 'Friday', 'February', 'f', 'f', NULL),
(20250208, '2025-02-08', 2025, 2, 8, 1, 6, 'Saturday', 'February', 'f', 't', NULL),
(20250209, '2025-02-09', 2025, 2, 9, 1, 6, 'Sunday', 'February', 'f', 't', NULL),
(20250210, '2025-02-10', 2025, 2, 10, 1, 7, 'Monday', 'February', 'f', 'f', NULL),
(20250211, '2025-02-11', 2025, 2, 11, 1, 7, 'Tuesday', 'February', 'f', 'f', NULL),
(20250212, '2025-02-12', 2025, 2, 12, 1, 7, 'Wednesday', 'February', 'f', 'f', NULL),
(20250213, '2025-02-13', 2025, 2, 13, 1, 7, 'Thursday', 'February', 'f', 'f', NULL),
(20250214, '2025-02-14', 2025, 2, 14, 1, 7, 'Friday', 'February', 'f', 'f', NULL),
(20250215, '2025-02-15', 2025, 2, 15, 1, 7, 'Saturday', 'February', 'f', 't', NULL),
(20250216, '2025-02-16', 2025, 2, 16, 1, 7, 'Sunday', 'February', 'f', 't', NULL),
(20250217, '2025-02-17', 2025, 2, 17, 1, 8, 'Monday', 'February', 'f', 'f', NULL),
(20250218, '2025-02-18', 2025, 2, 18, 1, 8, 'Tuesday', 'February', 'f', 'f', NULL),
(20250219, '2025-02-19', 2025, 2, 19, 1, 8, 'Wednesday', 'February', 'f', 'f', NULL),
(20250220, '2025-02-20', 2025, 2, 20, 1, 8, 'Thursday', 'February', 'f', 'f', NULL),
(20250221, '2025-02-21', 2025, 2, 21, 1, 8, 'Friday', 'February', 'f', 'f', NULL),
(20250222, '2025-02-22', 2025, 2, 22, 1, 8, 'Saturday', 'February', 'f', 't', NULL),
(20250223, '2025-02-23', 2025, 2, 23, 1, 8, 'Sunday', 'February', 'f', 't', NULL),
(20250224, '2025-02-24', 2025, 2, 24, 1, 9, 'Monday', 'February', 'f', 'f', NULL),
(20250225, '2025-02-25', 2025, 2, 25, 1, 9, 'Tuesday', 'February', 'f', 'f', NULL),
(20250226, '2025-02-26', 2025, 2, 26, 1, 9, 'Wednesday', 'February', 'f', 'f', NULL),
(20250227, '2025-02-27', 2025, 2, 27, 1, 9, 'Thursday', 'February', 'f', 'f', NULL),
(20250228, '2025-02-28', 2025, 2, 28, 1, 9, 'Friday', 'February', 'f', 'f', NULL),
(20250301, '2025-03-01', 2025, 3, 1, 1, 9, 'Saturday', 'March', 'f', 't', NULL),
(20250302, '2025-03-02', 2025, 3, 2, 1, 9, 'Sunday', 'March', 'f', 't', NULL),
(20250303, '2025-03-03', 2025, 3, 3, 1, 10, 'Monday', 'March', 'f', 'f', NULL),
(20250304, '2025-03-04', 2025, 3, 4, 1, 10, 'Tuesday', 'March', 'f', 'f', NULL),
(20250305, '2025-03-05', 2025, 3, 5, 1, 10, 'Wednesday', 'March', 'f', 'f', NULL),
(20250306, '2025-03-06', 2025, 3, 6, 1, 10, 'Thursday', 'March', 'f', 'f', NULL),
(20250307, '2025-03-07', 2025, 3, 7, 1, 10, 'Friday', 'March', 'f', 'f', NULL),
(20250308, '2025-03-08', 2025, 3, 8, 1, 10, 'Saturday', 'March', 'f', 't', NULL),
(20250309, '2025-03-09', 2025, 3, 9, 1, 10, 'Sunday', 'March', 'f', 't', NULL),
(20250310, '2025-03-10', 2025, 3, 10, 1, 11, 'Monday', 'March', 'f', 'f', NULL),
(20250311, '2025-03-11', 2025, 3, 11, 1, 11, 'Tuesday', 'March', 'f', 'f', NULL),
(20250312, '2025-03-12', 2025, 3, 12, 1, 11, 'Wednesday', 'March', 'f', 'f', NULL),
(20250313, '2025-03-13', 2025, 3, 13, 1, 11, 'Thursday', 'March', 'f', 'f', NULL),
(20250314, '2025-03-14', 2025, 3, 14, 1, 11, 'Friday', 'March', 'f', 'f', NULL),
(20250315, '2025-03-15', 2025, 3, 15, 1, 11, 'Saturday', 'March', 'f', 't', NULL),
(20250316, '2025-03-16', 2025, 3, 16, 1, 11, 'Sunday', 'March', 'f', 't', NULL),
(20250317, '2025-03-17', 2025, 3, 17, 1, 12, 'Monday', 'March', 'f', 'f', NULL),
(20250318, '2025-03-18', 2025, 3, 18, 1, 12, 'Tuesday', 'March', 'f', 'f', NULL),
(20250319, '2025-03-19', 2025, 3, 19, 1, 12, 'Wednesday', 'March', 'f', 'f', NULL),
(20250320, '2025-03-20', 2025, 3, 20, 1, 12, 'Thursday', 'March', 'f', 'f', NULL),
(20250321, '2025-03-21', 2025, 3, 21, 1, 12, 'Friday', 'March', 'f', 'f', NULL),
(20250322, '2025-03-22', 2025, 3, 22, 1, 12, 'Saturday', 'March', 'f', 't', NULL),
(20250323, '2025-03-23', 2025, 3, 23, 1, 12, 'Sunday', 'March', 'f', 't', NULL),
(20250324, '2025-03-24', 2025, 3, 24, 1, 13, 'Monday', 'March', 'f', 'f', NULL),
(20250325, '2025-03-25', 2025, 3, 25, 1, 13, 'Tuesday', 'March', 'f', 'f', NULL),
(20250326, '2025-03-26', 2025, 3, 26, 1, 13, 'Wednesday', 'March', 'f', 'f', NULL),
(20250327, '2025-03-27', 2025, 3, 27, 1, 13, 'Thursday', 'March', 'f', 'f', NULL),
(20250328, '2025-03-28', 2025, 3, 28, 1, 13, 'Friday', 'March', 'f', 'f', NULL),
(20250329, '2025-03-29', 2025, 3, 29, 1, 13, 'Saturday', 'March', 'f', 't', NULL),
(20250330, '2025-03-30', 2025, 3, 30, 1, 13, 'Sunday', 'March', 'f', 't', NULL),
(20250331, '2025-03-31', 2025, 3, 31, 1, 14, 'Monday', 'March', 'f', 'f', NULL),
(20250401, '2025-04-01', 2025, 4, 1, 2, 14, 'Tuesday', 'April', 'f', 'f', NULL),
(20250402, '2025-04-02', 2025, 4, 2, 2, 14, 'Wednesday', 'April', 'f', 'f', NULL),
(20250403, '2025-04-03', 2025, 4, 3, 2, 14, 'Thursday', 'April', 'f', 'f', NULL),
(20250404, '2025-04-04', 2025, 4, 4, 2, 14, 'Friday', 'April', 'f', 'f', NULL),
(20250405, '2025-04-05', 2025, 4, 5, 2, 14, 'Saturday', 'April', 'f', 't', NULL),
(20250406, '2025-04-06', 2025, 4, 6, 2, 14, 'Sunday', 'April', 'f', 't', NULL),
(20250407, '2025-04-07', 2025, 4, 7, 2, 15, 'Monday', 'April', 'f', 'f', NULL),
(20250408, '2025-04-08', 2025, 4, 8, 2, 15, 'Tuesday', 'April', 'f', 'f', NULL),
(20250409, '2025-04-09', 2025, 4, 9, 2, 15, 'Wednesday', 'April', 'f', 'f', NULL),
(20250410, '2025-04-10', 2025, 4, 10, 2, 15, 'Thursday', 'April', 'f', 'f', NULL),
(20250411, '2025-04-11', 2025, 4, 11, 2, 15, 'Friday', 'April', 'f', 'f', NULL),
(20250412, '2025-04-12', 2025, 4, 12, 2, 15, 'Saturday', 'April', 'f', 't', NULL),
(20250413, '2025-04-13', 2025, 4, 13, 2, 15, 'Sunday', 'April', 'f', 't', NULL),
(20250414, '2025-04-14', 2025, 4, 14, 2, 16, 'Monday', 'April', 'f', 'f', NULL),
(20250415, '2025-04-15', 2025, 4, 15, 2, 16, 'Tuesday', 'April', 'f', 'f', NULL),
(20250416, '2025-04-16', 2025, 4, 16, 2, 16, 'Wednesday', 'April', 'f', 'f', NULL),
(20250417, '2025-04-17', 2025, 4, 17, 2, 16, 'Thursday', 'April', 'f', 'f', NULL),
(20250418, '2025-04-18', 2025, 4, 18, 2, 16, 'Friday', 'April', 'f', 'f', NULL),
(20250419, '2025-04-19', 2025, 4, 19, 2, 16, 'Saturday', 'April', 'f', 't', NULL),
(20250420, '2025-04-20', 2025, 4, 20, 2, 16, 'Sunday', 'April', 'f', 't', NULL),
(20250421, '2025-04-21', 2025, 4, 21, 2, 17, 'Monday', 'April', 'f', 'f', NULL),
(20250422, '2025-04-22', 2025, 4, 22, 2, 17, 'Tuesday', 'April', 'f', 'f', NULL),
(20250423, '2025-04-23', 2025, 4, 23, 2, 17, 'Wednesday', 'April', 'f', 'f', NULL),
(20250424, '2025-04-24', 2025, 4, 24, 2, 17, 'Thursday', 'April', 'f', 'f', NULL),
(20250425, '2025-04-25', 2025, 4, 25, 2, 17, 'Friday', 'April', 'f', 'f', NULL),
(20250426, '2025-04-26', 2025, 4, 26, 2, 17, 'Saturday', 'April', 'f', 't', NULL),
(20250427, '2025-04-27', 2025, 4, 27, 2, 17, 'Sunday', 'April', 'f', 't', NULL),
(20250428, '2025-04-28', 2025, 4, 28, 2, 18, 'Monday', 'April', 'f', 'f', NULL),
(20250429, '2025-04-29', 2025, 4, 29, 2, 18, 'Tuesday', 'April', 'f', 'f', NULL),
(20250430, '2025-04-30', 2025, 4, 30, 2, 18, 'Wednesday', 'April', 'f', 'f', NULL),
(20250501, '2025-05-01', 2025, 5, 1, 2, 18, 'Thursday', 'May', 'f', 'f', NULL),
(20250502, '2025-05-02', 2025, 5, 2, 2, 18, 'Friday', 'May', 'f', 'f', NULL),
(20250503, '2025-05-03', 2025, 5, 3, 2, 18, 'Saturday', 'May', 'f', 't', NULL),
(20250504, '2025-05-04', 2025, 5, 4, 2, 18, 'Sunday', 'May', 'f', 't', NULL),
(20250505, '2025-05-05', 2025, 5, 5, 2, 19, 'Monday', 'May', 'f', 'f', NULL),
(20250506, '2025-05-06', 2025, 5, 6, 2, 19, 'Tuesday', 'May', 'f', 'f', NULL),
(20250507, '2025-05-07', 2025, 5, 7, 2, 19, 'Wednesday', 'May', 'f', 'f', NULL),
(20250508, '2025-05-08', 2025, 5, 8, 2, 19, 'Thursday', 'May', 'f', 'f', NULL),
(20250509, '2025-05-09', 2025, 5, 9, 2, 19, 'Friday', 'May', 'f', 'f', NULL),
(20250510, '2025-05-10', 2025, 5, 10, 2, 19, 'Saturday', 'May', 'f', 't', NULL),
(20250511, '2025-05-11', 2025, 5, 11, 2, 19, 'Sunday', 'May', 'f', 't', NULL),
(20250512, '2025-05-12', 2025, 5, 12, 2, 20, 'Monday', 'May', 'f', 'f', NULL),
(20250513, '2025-05-13', 2025, 5, 13, 2, 20, 'Tuesday', 'May', 'f', 'f', NULL),
(20250514, '2025-05-14', 2025, 5, 14, 2, 20, 'Wednesday', 'May', 'f', 'f', NULL),
(20250515, '2025-05-15', 2025, 5, 15, 2, 20, 'Thursday', 'May', 'f', 'f', NULL),
(20250516, '2025-05-16', 2025, 5, 16, 2, 20, 'Friday', 'May', 'f', 'f', NULL),
(20250517, '2025-05-17', 2025, 5, 17, 2, 20, 'Saturday', 'May', 'f', 't', NULL),
(20250518, '2025-05-18', 2025, 5, 18, 2, 20, 'Sunday', 'May', 'f', 't', NULL),
(20250519, '2025-05-19', 2025, 5, 19, 2, 21, 'Monday', 'May', 'f', 'f', NULL),
(20250520, '2025-05-20', 2025, 5, 20, 2, 21, 'Tuesday', 'May', 'f', 'f', NULL),
(20250521, '2025-05-21', 2025, 5, 21, 2, 21, 'Wednesday', 'May', 'f', 'f', NULL),
(20250522, '2025-05-22', 2025, 5, 22, 2, 21, 'Thursday', 'May', 'f', 'f', NULL),
(20250523, '2025-05-23', 2025, 5, 23, 2, 21, 'Friday', 'May', 'f', 'f', NULL),
(20250524, '2025-05-24', 2025, 5, 24, 2, 21, 'Saturday', 'May', 'f', 't', NULL),
(20250525, '2025-05-25', 2025, 5, 25, 2, 21, 'Sunday', 'May', 'f', 't', NULL),
(20250526, '2025-05-26', 2025, 5, 26, 2, 22, 'Monday', 'May', 'f', 'f', NULL),
(20250527, '2025-05-27', 2025, 5, 27, 2, 22, 'Tuesday', 'May', 'f', 'f', NULL),
(20250528, '2025-05-28', 2025, 5, 28, 2, 22, 'Wednesday', 'May', 'f', 'f', NULL),
(20250529, '2025-05-29', 2025, 5, 29, 2, 22, 'Thursday', 'May', 'f', 'f', NULL),
(20250530, '2025-05-30', 2025, 5, 30, 2, 22, 'Friday', 'May', 'f', 'f', NULL),
(20250531, '2025-05-31', 2025, 5, 31, 2, 22, 'Saturday', 'May', 'f', 't', NULL),
(20250601, '2025-06-01', 2025, 6, 1, 2, 22, 'Sunday', 'June', 'f', 't', NULL),
(20250602, '2025-06-02', 2025, 6, 2, 2, 23, 'Monday', 'June', 'f', 'f', NULL),
(20250603, '2025-06-03', 2025, 6, 3, 2, 23, 'Tuesday', 'June', 'f', 'f', NULL),
(20250604, '2025-06-04', 2025, 6, 4, 2, 23, 'Wednesday', 'June', 'f', 'f', NULL),
(20250605, '2025-06-05', 2025, 6, 5, 2, 23, 'Thursday', 'June', 'f', 'f', NULL),
(20250606, '2025-06-06', 2025, 6, 6, 2, 23, 'Friday', 'June', 'f', 'f', NULL),
(20250607, '2025-06-07', 2025, 6, 7, 2, 23, 'Saturday', 'June', 'f', 't', NULL),
(20250608, '2025-06-08', 2025, 6, 8, 2, 23, 'Sunday', 'June', 'f', 't', NULL),
(20250609, '2025-06-09', 2025, 6, 9, 2, 24, 'Monday', 'June', 'f', 'f', NULL),
(20250610, '2025-06-10', 2025, 6, 10, 2, 24, 'Tuesday', 'June', 'f', 'f', NULL),
(20250611, '2025-06-11', 2025, 6, 11, 2, 24, 'Wednesday', 'June', 'f', 'f', NULL),
(20250612, '2025-06-12', 2025, 6, 12, 2, 24, 'Thursday', 'June', 'f', 'f', NULL),
(20250613, '2025-06-13', 2025, 6, 13, 2, 24, 'Friday', 'June', 'f', 'f', NULL),
(20250614, '2025-06-14', 2025, 6, 14, 2, 24, 'Saturday', 'June', 'f', 't', NULL),
(20250615, '2025-06-15', 2025, 6, 15, 2, 24, 'Sunday', 'June', 'f', 't', NULL),
(20250616, '2025-06-16', 2025, 6, 16, 2, 25, 'Monday', 'June', 'f', 'f', NULL),
(20250617, '2025-06-17', 2025, 6, 17, 2, 25, 'Tuesday', 'June', 'f', 'f', NULL),
(20250618, '2025-06-18', 2025, 6, 18, 2, 25, 'Wednesday', 'June', 'f', 'f', NULL),
(20250619, '2025-06-19', 2025, 6, 19, 2, 25, 'Thursday', 'June', 'f', 'f', NULL),
(20250620, '2025-06-20', 2025, 6, 20, 2, 25, 'Friday', 'June', 'f', 'f', NULL),
(20250621, '2025-06-21', 2025, 6, 21, 2, 25, 'Saturday', 'June', 'f', 't', NULL),
(20250622, '2025-06-22', 2025, 6, 22, 2, 25, 'Sunday', 'June', 'f', 't', NULL),
(20250623, '2025-06-23', 2025, 6, 23, 2, 26, 'Monday', 'June', 'f', 'f', NULL),
(20250624, '2025-06-24', 2025, 6, 24, 2, 26, 'Tuesday', 'June', 'f', 'f', NULL),
(20250625, '2025-06-25', 2025, 6, 25, 2, 26, 'Wednesday', 'June', 'f', 'f', NULL),
(20250626, '2025-06-26', 2025, 6, 26, 2, 26, 'Thursday', 'June', 'f', 'f', NULL),
(20250627, '2025-06-27', 2025, 6, 27, 2, 26, 'Friday', 'June', 'f', 'f', NULL),
(20250628, '2025-06-28', 2025, 6, 28, 2, 26, 'Saturday', 'June', 'f', 't', NULL),
(20250629, '2025-06-29', 2025, 6, 29, 2, 26, 'Sunday', 'June', 'f', 't', NULL),
(20250630, '2025-06-30', 2025, 6, 30, 2, 27, 'Monday', 'June', 'f', 'f', NULL),
(20250701, '2025-07-01', 2025, 7, 1, 3, 27, 'Tuesday', 'July', 'f', 'f', NULL),
(20250702, '2025-07-02', 2025, 7, 2, 3, 27, 'Wednesday', 'July', 'f', 'f', NULL),
(20250703, '2025-07-03', 2025, 7, 3, 3, 27, 'Thursday', 'July', 'f', 'f', NULL),
(20250704, '2025-07-04', 2025, 7, 4, 3, 27, 'Friday', 'July', 'f', 'f', NULL),
(20250705, '2025-07-05', 2025, 7, 5, 3, 27, 'Saturday', 'July', 'f', 't', NULL),
(20250706, '2025-07-06', 2025, 7, 6, 3, 27, 'Sunday', 'July', 'f', 't', NULL),
(20250707, '2025-07-07', 2025, 7, 7, 3, 28, 'Monday', 'July', 'f', 'f', NULL),
(20250708, '2025-07-08', 2025, 7, 8, 3, 28, 'Tuesday', 'July', 'f', 'f', NULL),
(20250709, '2025-07-09', 2025, 7, 9, 3, 28, 'Wednesday', 'July', 'f', 'f', NULL),
(20250710, '2025-07-10', 2025, 7, 10, 3, 28, 'Thursday', 'July', 'f', 'f', NULL),
(20250711, '2025-07-11', 2025, 7, 11, 3, 28, 'Friday', 'July', 'f', 'f', NULL),
(20250712, '2025-07-12', 2025, 7, 12, 3, 28, 'Saturday', 'July', 'f', 't', NULL),
(20250713, '2025-07-13', 2025, 7, 13, 3, 28, 'Sunday', 'July', 'f', 't', NULL),
(20250714, '2025-07-14', 2025, 7, 14, 3, 29, 'Monday', 'July', 'f', 'f', NULL),
(20250715, '2025-07-15', 2025, 7, 15, 3, 29, 'Tuesday', 'July', 'f', 'f', NULL),
(20250716, '2025-07-16', 2025, 7, 16, 3, 29, 'Wednesday', 'July', 'f', 'f', NULL),
(20250717, '2025-07-17', 2025, 7, 17, 3, 29, 'Thursday', 'July', 'f', 'f', NULL),
(20250718, '2025-07-18', 2025, 7, 18, 3, 29, 'Friday', 'July', 'f', 'f', NULL),
(20250719, '2025-07-19', 2025, 7, 19, 3, 29, 'Saturday', 'July', 'f', 't', NULL),
(20250720, '2025-07-20', 2025, 7, 20, 3, 29, 'Sunday', 'July', 'f', 't', NULL),
(20250721, '2025-07-21', 2025, 7, 21, 3, 30, 'Monday', 'July', 'f', 'f', NULL),
(20250722, '2025-07-22', 2025, 7, 22, 3, 30, 'Tuesday', 'July', 'f', 'f', NULL),
(20250723, '2025-07-23', 2025, 7, 23, 3, 30, 'Wednesday', 'July', 'f', 'f', NULL),
(20250724, '2025-07-24', 2025, 7, 24, 3, 30, 'Thursday', 'July', 'f', 'f', NULL),
(20250725, '2025-07-25', 2025, 7, 25, 3, 30, 'Friday', 'July', 'f', 'f', NULL),
(20250726, '2025-07-26', 2025, 7, 26, 3, 30, 'Saturday', 'July', 'f', 't', NULL),
(20250727, '2025-07-27', 2025, 7, 27, 3, 30, 'Sunday', 'July', 'f', 't', NULL),
(20250728, '2025-07-28', 2025, 7, 28, 3, 31, 'Monday', 'July', 'f', 'f', NULL),
(20250729, '2025-07-29', 2025, 7, 29, 3, 31, 'Tuesday', 'July', 'f', 'f', NULL),
(20250730, '2025-07-30', 2025, 7, 30, 3, 31, 'Wednesday', 'July', 'f', 'f', NULL),
(20250731, '2025-07-31', 2025, 7, 31, 3, 31, 'Thursday', 'July', 'f', 'f', NULL),
(20250801, '2025-08-01', 2025, 8, 1, 3, 31, 'Friday', 'August', 'f', 'f', NULL),
(20250802, '2025-08-02', 2025, 8, 2, 3, 31, 'Saturday', 'August', 'f', 't', NULL),
(20250803, '2025-08-03', 2025, 8, 3, 3, 31, 'Sunday', 'August', 'f', 't', NULL),
(20250804, '2025-08-04', 2025, 8, 4, 3, 32, 'Monday', 'August', 'f', 'f', NULL),
(20250805, '2025-08-05', 2025, 8, 5, 3, 32, 'Tuesday', 'August', 'f', 'f', NULL),
(20250806, '2025-08-06', 2025, 8, 6, 3, 32, 'Wednesday', 'August', 'f', 'f', NULL),
(20250807, '2025-08-07', 2025, 8, 7, 3, 32, 'Thursday', 'August', 'f', 'f', NULL),
(20250808, '2025-08-08', 2025, 8, 8, 3, 32, 'Friday', 'August', 'f', 'f', NULL),
(20250809, '2025-08-09', 2025, 8, 9, 3, 32, 'Saturday', 'August', 'f', 't', NULL),
(20250810, '2025-08-10', 2025, 8, 10, 3, 32, 'Sunday', 'August', 'f', 't', NULL),
(20250811, '2025-08-11', 2025, 8, 11, 3, 33, 'Monday', 'August', 'f', 'f', NULL),
(20250812, '2025-08-12', 2025, 8, 12, 3, 33, 'Tuesday', 'August', 'f', 'f', NULL),
(20250813, '2025-08-13', 2025, 8, 13, 3, 33, 'Wednesday', 'August', 'f', 'f', NULL),
(20250814, '2025-08-14', 2025, 8, 14, 3, 33, 'Thursday', 'August', 'f', 'f', NULL),
(20250815, '2025-08-15', 2025, 8, 15, 3, 33, 'Friday', 'August', 'f', 'f', NULL),
(20250816, '2025-08-16', 2025, 8, 16, 3, 33, 'Saturday', 'August', 'f', 't', NULL),
(20250817, '2025-08-17', 2025, 8, 17, 3, 33, 'Sunday', 'August', 'f', 't', NULL),
(20250818, '2025-08-18', 2025, 8, 18, 3, 34, 'Monday', 'August', 'f', 'f', NULL),
(20250819, '2025-08-19', 2025, 8, 19, 3, 34, 'Tuesday', 'August', 'f', 'f', NULL),
(20250820, '2025-08-20', 2025, 8, 20, 3, 34, 'Wednesday', 'August', 'f', 'f', NULL),
(20250821, '2025-08-21', 2025, 8, 21, 3, 34, 'Thursday', 'August', 'f', 'f', NULL),
(20250822, '2025-08-22', 2025, 8, 22, 3, 34, 'Friday', 'August', 'f', 'f', NULL),
(20250823, '2025-08-23', 2025, 8, 23, 3, 34, 'Saturday', 'August', 'f', 't', NULL),
(20250824, '2025-08-24', 2025, 8, 24, 3, 34, 'Sunday', 'August', 'f', 't', NULL),
(20250825, '2025-08-25', 2025, 8, 25, 3, 35, 'Monday', 'August', 'f', 'f', NULL),
(20250826, '2025-08-26', 2025, 8, 26, 3, 35, 'Tuesday', 'August', 'f', 'f', NULL),
(20250827, '2025-08-27', 2025, 8, 27, 3, 35, 'Wednesday', 'August', 'f', 'f', NULL),
(20250828, '2025-08-28', 2025, 8, 28, 3, 35, 'Thursday', 'August', 'f', 'f', NULL),
(20250829, '2025-08-29', 2025, 8, 29, 3, 35, 'Friday', 'August', 'f', 'f', NULL),
(20250830, '2025-08-30', 2025, 8, 30, 3, 35, 'Saturday', 'August', 'f', 't', NULL),
(20250831, '2025-08-31', 2025, 8, 31, 3, 35, 'Sunday', 'August', 'f', 't', NULL),
(20250901, '2025-09-01', 2025, 9, 1, 3, 36, 'Monday', 'September', 'f', 'f', NULL),
(20250902, '2025-09-02', 2025, 9, 2, 3, 36, 'Tuesday', 'September', 'f', 'f', NULL),
(20250903, '2025-09-03', 2025, 9, 3, 3, 36, 'Wednesday', 'September', 'f', 'f', NULL),
(20250904, '2025-09-04', 2025, 9, 4, 3, 36, 'Thursday', 'September', 'f', 'f', NULL),
(20250905, '2025-09-05', 2025, 9, 5, 3, 36, 'Friday', 'September', 'f', 'f', NULL),
(20250906, '2025-09-06', 2025, 9, 6, 3, 36, 'Saturday', 'September', 'f', 't', NULL),
(20250907, '2025-09-07', 2025, 9, 7, 3, 36, 'Sunday', 'September', 'f', 't', NULL),
(20250908, '2025-09-08', 2025, 9, 8, 3, 37, 'Monday', 'September', 'f', 'f', NULL),
(20250909, '2025-09-09', 2025, 9, 9, 3, 37, 'Tuesday', 'September', 'f', 'f', NULL),
(20250910, '2025-09-10', 2025, 9, 10, 3, 37, 'Wednesday', 'September', 'f', 'f', NULL),
(20250911, '2025-09-11', 2025, 9, 11, 3, 37, 'Thursday', 'September', 'f', 'f', NULL),
(20250912, '2025-09-12', 2025, 9, 12, 3, 37, 'Friday', 'September', 'f', 'f', NULL),
(20250913, '2025-09-13', 2025, 9, 13, 3, 37, 'Saturday', 'September', 'f', 't', NULL),
(20250914, '2025-09-14', 2025, 9, 14, 3, 37, 'Sunday', 'September', 'f', 't', NULL),
(20250915, '2025-09-15', 2025, 9, 15, 3, 38, 'Monday', 'September', 'f', 'f', NULL),
(20250916, '2025-09-16', 2025, 9, 16, 3, 38, 'Tuesday', 'September', 'f', 'f', NULL),
(20250917, '2025-09-17', 2025, 9, 17, 3, 38, 'Wednesday', 'September', 'f', 'f', NULL),
(20250918, '2025-09-18', 2025, 9, 18, 3, 38, 'Thursday', 'September', 'f', 'f', NULL),
(20250919, '2025-09-19', 2025, 9, 19, 3, 38, 'Friday', 'September', 'f', 'f', NULL),
(20250920, '2025-09-20', 2025, 9, 20, 3, 38, 'Saturday', 'September', 'f', 't', NULL),
(20250921, '2025-09-21', 2025, 9, 21, 3, 38, 'Sunday', 'September', 'f', 't', NULL),
(20250922, '2025-09-22', 2025, 9, 22, 3, 39, 'Monday', 'September', 'f', 'f', NULL),
(20250923, '2025-09-23', 2025, 9, 23, 3, 39, 'Tuesday', 'September', 'f', 'f', NULL),
(20250924, '2025-09-24', 2025, 9, 24, 3, 39, 'Wednesday', 'September', 'f', 'f', NULL),
(20250925, '2025-09-25', 2025, 9, 25, 3, 39, 'Thursday', 'September', 'f', 'f', NULL),
(20250926, '2025-09-26', 2025, 9, 26, 3, 39, 'Friday', 'September', 'f', 'f', NULL),
(20250927, '2025-09-27', 2025, 9, 27, 3, 39, 'Saturday', 'September', 'f', 't', NULL),
(20250928, '2025-09-28', 2025, 9, 28, 3, 39, 'Sunday', 'September', 'f', 't', NULL),
(20250929, '2025-09-29', 2025, 9, 29, 3, 40, 'Monday', 'September', 'f', 'f', NULL),
(20250930, '2025-09-30', 2025, 9, 30, 3, 40, 'Tuesday', 'September', 'f', 'f', NULL),
(20251001, '2025-10-01', 2025, 10, 1, 4, 40, 'Wednesday', 'October', 'f', 'f', NULL),
(20251002, '2025-10-02', 2025, 10, 2, 4, 40, 'Thursday', 'October', 'f', 'f', NULL),
(20251003, '2025-10-03', 2025, 10, 3, 4, 40, 'Friday', 'October', 'f', 'f', NULL),
(20251004, '2025-10-04', 2025, 10, 4, 4, 40, 'Saturday', 'October', 'f', 't', NULL),
(20251005, '2025-10-05', 2025, 10, 5, 4, 40, 'Sunday', 'October', 'f', 't', NULL),
(20251006, '2025-10-06', 2025, 10, 6, 4, 41, 'Monday', 'October', 'f', 'f', NULL),
(20251007, '2025-10-07', 2025, 10, 7, 4, 41, 'Tuesday', 'October', 'f', 'f', NULL),
(20251008, '2025-10-08', 2025, 10, 8, 4, 41, 'Wednesday', 'October', 'f', 'f', NULL),
(20251009, '2025-10-09', 2025, 10, 9, 4, 41, 'Thursday', 'October', 'f', 'f', NULL),
(20251010, '2025-10-10', 2025, 10, 10, 4, 41, 'Friday', 'October', 'f', 'f', NULL),
(20251011, '2025-10-11', 2025, 10, 11, 4, 41, 'Saturday', 'October', 'f', 't', NULL),
(20251012, '2025-10-12', 2025, 10, 12, 4, 41, 'Sunday', 'October', 'f', 't', NULL),
(20251013, '2025-10-13', 2025, 10, 13, 4, 42, 'Monday', 'October', 'f', 'f', NULL),
(20251014, '2025-10-14', 2025, 10, 14, 4, 42, 'Tuesday', 'October', 'f', 'f', NULL),
(20251015, '2025-10-15', 2025, 10, 15, 4, 42, 'Wednesday', 'October', 'f', 'f', NULL),
(20251016, '2025-10-16', 2025, 10, 16, 4, 42, 'Thursday', 'October', 'f', 'f', NULL),
(20251017, '2025-10-17', 2025, 10, 17, 4, 42, 'Friday', 'October', 'f', 'f', NULL),
(20251018, '2025-10-18', 2025, 10, 18, 4, 42, 'Saturday', 'October', 'f', 't', NULL),
(20251019, '2025-10-19', 2025, 10, 19, 4, 42, 'Sunday', 'October', 'f', 't', NULL),
(20251020, '2025-10-20', 2025, 10, 20, 4, 43, 'Monday', 'October', 'f', 'f', NULL),
(20251021, '2025-10-21', 2025, 10, 21, 4, 43, 'Tuesday', 'October', 'f', 'f', NULL),
(20251022, '2025-10-22', 2025, 10, 22, 4, 43, 'Wednesday', 'October', 'f', 'f', NULL),
(20251023, '2025-10-23', 2025, 10, 23, 4, 43, 'Thursday', 'October', 'f', 'f', NULL),
(20251024, '2025-10-24', 2025, 10, 24, 4, 43, 'Friday', 'October', 'f', 'f', NULL),
(20251025, '2025-10-25', 2025, 10, 25, 4, 43, 'Saturday', 'October', 'f', 't', NULL),
(20251026, '2025-10-26', 2025, 10, 26, 4, 43, 'Sunday', 'October', 'f', 't', NULL),
(20251027, '2025-10-27', 2025, 10, 27, 4, 44, 'Monday', 'October', 'f', 'f', NULL),
(20251028, '2025-10-28', 2025, 10, 28, 4, 44, 'Tuesday', 'October', 'f', 'f', NULL),
(20251029, '2025-10-29', 2025, 10, 29, 4, 44, 'Wednesday', 'October', 'f', 'f', NULL),
(20251030, '2025-10-30', 2025, 10, 30, 4, 44, 'Thursday', 'October', 'f', 'f', NULL),
(20251031, '2025-10-31', 2025, 10, 31, 4, 44, 'Friday', 'October', 'f', 'f', NULL),
(20251101, '2025-11-01', 2025, 11, 1, 4, 44, 'Saturday', 'November', 'f', 't', NULL),
(20251102, '2025-11-02', 2025, 11, 2, 4, 44, 'Sunday', 'November', 'f', 't', NULL),
(20251103, '2025-11-03', 2025, 11, 3, 4, 45, 'Monday', 'November', 'f', 'f', NULL),
(20251104, '2025-11-04', 2025, 11, 4, 4, 45, 'Tuesday', 'November', 'f', 'f', NULL),
(20251105, '2025-11-05', 2025, 11, 5, 4, 45, 'Wednesday', 'November', 'f', 'f', NULL),
(20251106, '2025-11-06', 2025, 11, 6, 4, 45, 'Thursday', 'November', 'f', 'f', NULL),
(20251107, '2025-11-07', 2025, 11, 7, 4, 45, 'Friday', 'November', 'f', 'f', NULL),
(20251108, '2025-11-08', 2025, 11, 8, 4, 45, 'Saturday', 'November', 'f', 't', NULL),
(20251109, '2025-11-09', 2025, 11, 9, 4, 45, 'Sunday', 'November', 'f', 't', NULL),
(20251110, '2025-11-10', 2025, 11, 10, 4, 46, 'Monday', 'November', 'f', 'f', NULL),
(20251111, '2025-11-11', 2025, 11, 11, 4, 46, 'Tuesday', 'November', 'f', 'f', NULL),
(20251112, '2025-11-12', 2025, 11, 12, 4, 46, 'Wednesday', 'November', 'f', 'f', NULL),
(20251113, '2025-11-13', 2025, 11, 13, 4, 46, 'Thursday', 'November', 'f', 'f', NULL),
(20251114, '2025-11-14', 2025, 11, 14, 4, 46, 'Friday', 'November', 'f', 'f', NULL),
(20251115, '2025-11-15', 2025, 11, 15, 4, 46, 'Saturday', 'November', 'f', 't', NULL),
(20251116, '2025-11-16', 2025, 11, 16, 4, 46, 'Sunday', 'November', 'f', 't', NULL),
(20251117, '2025-11-17', 2025, 11, 17, 4, 47, 'Monday', 'November', 'f', 'f', NULL),
(20251118, '2025-11-18', 2025, 11, 18, 4, 47, 'Tuesday', 'November', 'f', 'f', NULL),
(20251119, '2025-11-19', 2025, 11, 19, 4, 47, 'Wednesday', 'November', 'f', 'f', NULL),
(20251120, '2025-11-20', 2025, 11, 20, 4, 47, 'Thursday', 'November', 'f', 'f', NULL),
(20251121, '2025-11-21', 2025, 11, 21, 4, 47, 'Friday', 'November', 'f', 'f', NULL),
(20251122, '2025-11-22', 2025, 11, 22, 4, 47, 'Saturday', 'November', 'f', 't', NULL),
(20251123, '2025-11-23', 2025, 11, 23, 4, 47, 'Sunday', 'November', 'f', 't', NULL),
(20251124, '2025-11-24', 2025, 11, 24, 4, 48, 'Monday', 'November', 'f', 'f', NULL),
(20251125, '2025-11-25', 2025, 11, 25, 4, 48, 'Tuesday', 'November', 'f', 'f', NULL),
(20251126, '2025-11-26', 2025, 11, 26, 4, 48, 'Wednesday', 'November', 'f', 'f', NULL),
(20251127, '2025-11-27', 2025, 11, 27, 4, 48, 'Thursday', 'November', 'f', 'f', NULL),
(20251128, '2025-11-28', 2025, 11, 28, 4, 48, 'Friday', 'November', 'f', 'f', NULL),
(20251129, '2025-11-29', 2025, 11, 29, 4, 48, 'Saturday', 'November', 'f', 't', NULL),
(20251130, '2025-11-30', 2025, 11, 30, 4, 48, 'Sunday', 'November', 'f', 't', NULL),
(20251201, '2025-12-01', 2025, 12, 1, 4, 49, 'Monday', 'December', 'f', 'f', NULL),
(20251202, '2025-12-02', 2025, 12, 2, 4, 49, 'Tuesday', 'December', 'f', 'f', NULL),
(20251203, '2025-12-03', 2025, 12, 3, 4, 49, 'Wednesday', 'December', 'f', 'f', NULL),
(20251204, '2025-12-04', 2025, 12, 4, 4, 49, 'Thursday', 'December', 'f', 'f', NULL),
(20251205, '2025-12-05', 2025, 12, 5, 4, 49, 'Friday', 'December', 'f', 'f', NULL),
(20251206, '2025-12-06', 2025, 12, 6, 4, 49, 'Saturday', 'December', 'f', 't', NULL),
(20251207, '2025-12-07', 2025, 12, 7, 4, 49, 'Sunday', 'December', 'f', 't', NULL),
(20251208, '2025-12-08', 2025, 12, 8, 4, 50, 'Monday', 'December', 'f', 'f', NULL),
(20251209, '2025-12-09', 2025, 12, 9, 4, 50, 'Tuesday', 'December', 'f', 'f', NULL),
(20251210, '2025-12-10', 2025, 12, 10, 4, 50, 'Wednesday', 'December', 'f', 'f', NULL),
(20251211, '2025-12-11', 2025, 12, 11, 4, 50, 'Thursday', 'December', 'f', 'f', NULL),
(20251212, '2025-12-12', 2025, 12, 12, 4, 50, 'Friday', 'December', 'f', 'f', NULL),
(20251213, '2025-12-13', 2025, 12, 13, 4, 50, 'Saturday', 'December', 'f', 't', NULL),
(20251214, '2025-12-14', 2025, 12, 14, 4, 50, 'Sunday', 'December', 'f', 't', NULL),
(20251215, '2025-12-15', 2025, 12, 15, 4, 51, 'Monday', 'December', 'f', 'f', NULL),
(20251216, '2025-12-16', 2025, 12, 16, 4, 51, 'Tuesday', 'December', 'f', 'f', NULL),
(20251217, '2025-12-17', 2025, 12, 17, 4, 51, 'Wednesday', 'December', 'f', 'f', NULL),
(20251218, '2025-12-18', 2025, 12, 18, 4, 51, 'Thursday', 'December', 'f', 'f', NULL),
(20251219, '2025-12-19', 2025, 12, 19, 4, 51, 'Friday', 'December', 'f', 'f', NULL),
(20251220, '2025-12-20', 2025, 12, 20, 4, 51, 'Saturday', 'December', 'f', 't', NULL),
(20251221, '2025-12-21', 2025, 12, 21, 4, 51, 'Sunday', 'December', 'f', 't', NULL),
(20251222, '2025-12-22', 2025, 12, 22, 4, 52, 'Monday', 'December', 'f', 'f', NULL),
(20251223, '2025-12-23', 2025, 12, 23, 4, 52, 'Tuesday', 'December', 'f', 'f', NULL),
(20251224, '2025-12-24', 2025, 12, 24, 4, 52, 'Wednesday', 'December', 'f', 'f', NULL),
(20251225, '2025-12-25', 2025, 12, 25, 4, 52, 'Thursday', 'December', 'f', 'f', NULL),
(20251226, '2025-12-26', 2025, 12, 26, 4, 52, 'Friday', 'December', 'f', 'f', NULL),
(20251227, '2025-12-27', 2025, 12, 27, 4, 52, 'Saturday', 'December', 'f', 't', NULL),
(20251228, '2025-12-28', 2025, 12, 28, 4, 52, 'Sunday', 'December', 'f', 't', NULL),
(20251229, '2025-12-29', 2025, 12, 29, 4, 1, 'Monday', 'December', 'f', 'f', NULL),
(20251230, '2025-12-30', 2025, 12, 30, 4, 1, 'Tuesday', 'December', 'f', 'f', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tr_aset`
--

CREATE TABLE IF NOT EXISTS `tr_aset` (
`id_aset` int(11) NOT NULL,
  `id_stock` int(11) DEFAULT NULL,
  `kd_barang` varchar(255) DEFAULT NULL,
  `nm_barang` varchar(255) DEFAULT NULL,
  `id_satuan` int(11) DEFAULT NULL,
  `harga` decimal(20,0) DEFAULT NULL,
  `gambar` decimal(20,0) DEFAULT NULL,
  `deskripsi` text,
  `active` tinyint(1) DEFAULT NULL,
  `cuser` int(11) DEFAULT NULL,
  `id_perusahaan` int(11) DEFAULT NULL,
  `cdate` datetime DEFAULT NULL,
  `id_bu` int(11) DEFAULT NULL,
  `id_lokasi` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tr_order_pembelian`
--

CREATE TABLE IF NOT EXISTS `tr_order_pembelian` (
`id_order_pembelian` int(11) NOT NULL,
  `id_supplier` int(11) DEFAULT NULL,
  `id_divisi` int(11) DEFAULT NULL,
  `id_bu` int(11) DEFAULT NULL,
  `note` text,
  `active` tinyint(1) DEFAULT NULL,
  `cuser` int(11) DEFAULT NULL,
  `id_perusahaan` int(11) DEFAULT NULL,
  `cdate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tr_order_pembelian_detail`
--

CREATE TABLE IF NOT EXISTS `tr_order_pembelian_detail` (
`id_order_pembelian_detail` int(11) NOT NULL,
  `id_order_pembelian` int(11) DEFAULT NULL,
  `id_permintaan_pembelian_detail` int(11) DEFAULT NULL,
  `kd_barang` varchar(255) DEFAULT NULL,
  `nm_barang` varchar(255) DEFAULT NULL,
  `id_satuan` int(11) DEFAULT NULL,
  `harga` decimal(20,0) DEFAULT NULL,
  `jumlah` decimal(20,0) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `deskripsi` text,
  `active` tinyint(1) DEFAULT NULL,
  `cuser` int(11) DEFAULT NULL,
  `id_perusahaan` int(11) DEFAULT NULL,
  `cdate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tr_penerimaan`
--

CREATE TABLE IF NOT EXISTS `tr_penerimaan` (
`id_penerimaan` int(11) NOT NULL,
  `no_pengiriman` varchar(255) DEFAULT NULL,
  `id_bu` int(11) DEFAULT NULL,
  `id_supplier` int(11) DEFAULT NULL,
  `note` text,
  `active` tinyint(1) DEFAULT NULL,
  `cuser` int(11) DEFAULT NULL,
  `id_perusahaan` int(11) DEFAULT NULL,
  `cdate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tr_penerimaan_detail`
--

CREATE TABLE IF NOT EXISTS `tr_penerimaan_detail` (
`id_penerimaan_detail` int(11) NOT NULL,
  `id_penerimaan` int(11) DEFAULT NULL,
  `id_order_pembelian_detail` int(11) DEFAULT NULL,
  `id_order_pembelian` int(11) DEFAULT NULL,
  `kd_barang` varchar(255) DEFAULT NULL,
  `nm_barang` varchar(255) DEFAULT NULL,
  `id_satuan` int(11) DEFAULT NULL,
  `harga` decimal(20,0) DEFAULT NULL,
  `jumlah` decimal(20,0) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `deskripsi` text,
  `active` int(11) DEFAULT NULL,
  `cuser` int(11) DEFAULT NULL,
  `id_perusahaan` int(11) DEFAULT NULL,
  `cdate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tr_pengeluaran`
--

CREATE TABLE IF NOT EXISTS `tr_pengeluaran` (
`id_pengeluaran` int(11) NOT NULL,
  `id_bu` int(11) DEFAULT NULL,
  `note` text,
  `active` tinyint(1) DEFAULT NULL,
  `cuser` int(11) DEFAULT NULL,
  `id_perusahaan` int(11) DEFAULT NULL,
  `cdate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tr_pengeluaran_detail`
--

CREATE TABLE IF NOT EXISTS `tr_pengeluaran_detail` (
`id_pengeluaran_detail` int(11) NOT NULL,
  `id_pengeluaran` int(11) DEFAULT NULL,
  `id_perminataan_material_detail` int(11) DEFAULT NULL,
  `id_perminataan_material` int(11) DEFAULT NULL,
  `kd_barang` varchar(255) DEFAULT NULL,
  `jumlah` decimal(20,0) DEFAULT NULL,
  `harga` decimal(20,0) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `cuser` int(11) DEFAULT NULL,
  `id_perusahaan` int(11) DEFAULT NULL,
  `cdate` datetime DEFAULT NULL,
  `id_bu` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tr_permintaan_material`
--

CREATE TABLE IF NOT EXISTS `tr_permintaan_material` (
`id_permintaan_material` int(11) NOT NULL,
  `id_divisi` int(11) DEFAULT NULL,
  `id_bu` int(11) DEFAULT NULL,
  `note` text,
  `active` tinyint(1) DEFAULT NULL,
  `cuser` int(11) DEFAULT NULL,
  `id_perusahaan` int(11) DEFAULT NULL,
  `cdate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tr_permintaan_material_detail`
--

CREATE TABLE IF NOT EXISTS `tr_permintaan_material_detail` (
`id_permintaan_material_detail` int(11) NOT NULL,
  `id_permintaan_material` int(11) DEFAULT NULL,
  `kd_barang` varchar(255) DEFAULT NULL,
  `nm_barang` varchar(255) DEFAULT NULL,
  `id_satuan` int(11) DEFAULT NULL,
  `harga` decimal(20,0) DEFAULT NULL,
  `jumlah` decimal(20,0) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `deskripsi` text,
  `active` tinyint(1) DEFAULT NULL,
  `cuser` int(11) DEFAULT NULL,
  `id_perusahaan` int(11) DEFAULT NULL,
  `cdate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tr_permintaan_pembelian`
--

CREATE TABLE IF NOT EXISTS `tr_permintaan_pembelian` (
`id_permintaan_pembelian` int(11) NOT NULL,
  `id_divisi` int(11) DEFAULT NULL,
  `id_bu` int(11) DEFAULT NULL,
  `note` text,
  `active` tinyint(1) DEFAULT NULL,
  `cuser` int(11) DEFAULT NULL,
  `id_perusahaan` int(11) DEFAULT NULL,
  `cdate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tr_permintaan_pembelian_detail`
--

CREATE TABLE IF NOT EXISTS `tr_permintaan_pembelian_detail` (
`id_permintaan_pembelian_detail` int(11) NOT NULL,
  `id_permintaan_pembelian` int(11) DEFAULT NULL,
  `kd_barang` varchar(255) DEFAULT NULL,
  `nm_barang` varchar(255) DEFAULT NULL,
  `id_satuan` int(11) DEFAULT NULL,
  `harga` decimal(20,0) DEFAULT NULL,
  `jumlah` decimal(20,0) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `deskripsi` text,
  `active` tinyint(1) DEFAULT NULL,
  `cuser` int(11) DEFAULT NULL,
  `id_perusahaan` int(11) DEFAULT NULL,
  `cdate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tr_persetujuan`
--

CREATE TABLE IF NOT EXISTS `tr_persetujuan` (
`id_persetujuan` int(11) NOT NULL,
  `id_permintaan_material` int(11) DEFAULT NULL,
  `id_permintaan_pembelian` int(11) DEFAULT NULL,
  `type_catatan` varchar(255) DEFAULT NULL,
  `id_pegawai` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `cuser` int(11) DEFAULT NULL,
  `id_perusahaan` int(11) DEFAULT NULL,
  `cdate` datetime DEFAULT NULL,
  `id_bu` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tr_persetujuan_catatan`
--

CREATE TABLE IF NOT EXISTS `tr_persetujuan_catatan` (
`id_persetujuan_catatan` int(11) NOT NULL,
  `id_permintaan_material` int(11) DEFAULT NULL,
  `id_permintaan_pembelian` int(11) DEFAULT NULL,
  `type_catatan` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `note` text,
  `active` tinyint(1) DEFAULT NULL,
  `cuser` int(11) DEFAULT NULL,
  `id_perusahaan` int(11) DEFAULT NULL,
  `cdate` datetime DEFAULT NULL,
  `id_bu` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tr_stock`
--

CREATE TABLE IF NOT EXISTS `tr_stock` (
`id_stock` int(11) NOT NULL,
  `id_penerimaan` int(11) DEFAULT NULL,
  `id_penerimaan_detail` int(11) DEFAULT NULL,
  `type_aset` int(11) DEFAULT NULL,
  `kd_barang` varchar(255) DEFAULT NULL,
  `nm_barang` varchar(255) DEFAULT NULL,
  `id_satuan` int(11) DEFAULT NULL,
  `harga` decimal(20,0) DEFAULT NULL,
  `jumlah` decimal(20,0) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `deskripsi` text,
  `active` tinyint(1) DEFAULT NULL,
  `cuser` int(11) DEFAULT NULL,
  `id_perusahaan` int(11) DEFAULT NULL,
  `cdate` datetime DEFAULT NULL,
  `id_bu` int(11) DEFAULT NULL,
  `id_lokasi` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tr_stock_card`
--

CREATE TABLE IF NOT EXISTS `tr_stock_card` (
`id_stock_card` int(11) NOT NULL,
  `id_stock` int(11) DEFAULT NULL,
  `kd_barang` varchar(255) DEFAULT NULL,
  `nm_barang` varchar(255) DEFAULT NULL,
  `id_satuan` int(11) DEFAULT NULL,
  `harga` decimal(20,0) DEFAULT NULL,
  `jumlah` decimal(20,0) DEFAULT NULL,
  `type_stock` int(11) DEFAULT NULL,
  `id_pemasukan` int(11) DEFAULT NULL,
  `id_pemasukan_detail` int(11) DEFAULT NULL,
  `id_pengeluaran_detail` int(11) DEFAULT NULL,
  `id_pengeluaran` int(11) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `cuser` int(11) DEFAULT NULL,
  `id_perusahaan` int(11) DEFAULT NULL,
  `cdate` datetime DEFAULT NULL,
  `id_bu` int(11) DEFAULT NULL,
  `id_lokasi` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `langs`
--
ALTER TABLE `langs`
 ADD PRIMARY KEY (`lang_srl`) USING BTREE, ADD UNIQUE KEY `inkomaro_UNIQUE` (`inkomaro`) USING BTREE;

--
-- Indexes for table `ref_barang`
--
ALTER TABLE `ref_barang`
 ADD PRIMARY KEY (`id_barang`) USING BTREE, ADD UNIQUE KEY `uq_kd_barang` (`kd_barang`) USING BTREE;

--
-- Indexes for table `ref_bu`
--
ALTER TABLE `ref_bu`
 ADD PRIMARY KEY (`id_bu`) USING BTREE;

--
-- Indexes for table `ref_bu_access`
--
ALTER TABLE `ref_bu_access`
 ADD PRIMARY KEY (`id_bu_access`) USING BTREE, ADD UNIQUE KEY `uq_bu_access` (`id_bu`,`id_user`,`id_perusahaan`) USING BTREE;

--
-- Indexes for table `ref_divisi`
--
ALTER TABLE `ref_divisi`
 ADD PRIMARY KEY (`id_divisi`);

--
-- Indexes for table `ref_divisi_sub`
--
ALTER TABLE `ref_divisi_sub`
 ADD PRIMARY KEY (`id_divisi_sub`) USING BTREE;

--
-- Indexes for table `ref_divre`
--
ALTER TABLE `ref_divre`
 ADD PRIMARY KEY (`id_divre`) USING BTREE;

--
-- Indexes for table `ref_kategori`
--
ALTER TABLE `ref_kategori`
 ADD PRIMARY KEY (`id_kategori`) USING BTREE;

--
-- Indexes for table `ref_level`
--
ALTER TABLE `ref_level`
 ADD PRIMARY KEY (`id_level`) USING BTREE, ADD KEY `fk_id_user_ref_level_idx` (`cuser`) USING BTREE;

--
-- Indexes for table `ref_lokasi`
--
ALTER TABLE `ref_lokasi`
 ADD PRIMARY KEY (`id_lokasi`) USING BTREE;

--
-- Indexes for table `ref_menu_details`
--
ALTER TABLE `ref_menu_details`
 ADD PRIMARY KEY (`id_menu_details`) USING BTREE;

--
-- Indexes for table `ref_menu_details_access`
--
ALTER TABLE `ref_menu_details_access`
 ADD PRIMARY KEY (`id_menu_details_access`) USING BTREE;

--
-- Indexes for table `ref_menu_groups`
--
ALTER TABLE `ref_menu_groups`
 ADD PRIMARY KEY (`id_menu_groups`) USING BTREE, ADD KEY `fk_id_user_ref_menu_groups_idx` (`cuser`) USING BTREE;

--
-- Indexes for table `ref_menu_groups_access`
--
ALTER TABLE `ref_menu_groups_access`
 ADD PRIMARY KEY (`id_menu_groups_access`) USING BTREE;

--
-- Indexes for table `ref_pegawai`
--
ALTER TABLE `ref_pegawai`
 ADD PRIMARY KEY (`id_driver`) USING BTREE;

--
-- Indexes for table `ref_perusahaan`
--
ALTER TABLE `ref_perusahaan`
 ADD PRIMARY KEY (`id_perusahaan`) USING BTREE, ADD KEY `fk_id_user_ref_perusahaan_idx` (`cuser`) USING BTREE;

--
-- Indexes for table `ref_satuan`
--
ALTER TABLE `ref_satuan`
 ADD PRIMARY KEY (`id_satuan`) USING BTREE;

--
-- Indexes for table `ref_stok`
--
ALTER TABLE `ref_stok`
 ADD PRIMARY KEY (`id_stok`) USING BTREE;

--
-- Indexes for table `ref_supplier`
--
ALTER TABLE `ref_supplier`
 ADD PRIMARY KEY (`id_supplier`) USING BTREE;

--
-- Indexes for table `ref_supplier_kategori`
--
ALTER TABLE `ref_supplier_kategori`
 ADD PRIMARY KEY (`id_supplier_kategori`) USING BTREE;

--
-- Indexes for table `ref_user`
--
ALTER TABLE `ref_user`
 ADD PRIMARY KEY (`id_user`) USING BTREE, ADD UNIQUE KEY `fk_username` (`username`) USING BTREE, ADD KEY `fk_id_perusahaan_ref_user_idx` (`id_perusahaan`) USING BTREE, ADD KEY `fk_id_level_ref_level_idx` (`id_level`) USING BTREE, ADD KEY `fk_id_user_ref_user` (`cuser`) USING BTREE, ADD KEY `fk_id_atasan` (`id_atasan`) USING BTREE;

--
-- Indexes for table `time_dimension`
--
ALTER TABLE `time_dimension`
 ADD PRIMARY KEY (`id`) USING BTREE, ADD UNIQUE KEY `td_ymd_idx` (`year`,`month`,`day`) USING BTREE, ADD UNIQUE KEY `td_dbdate_idx` (`db_date`) USING BTREE;

--
-- Indexes for table `tr_aset`
--
ALTER TABLE `tr_aset`
 ADD PRIMARY KEY (`id_aset`) USING BTREE;

--
-- Indexes for table `tr_order_pembelian`
--
ALTER TABLE `tr_order_pembelian`
 ADD PRIMARY KEY (`id_order_pembelian`);

--
-- Indexes for table `tr_order_pembelian_detail`
--
ALTER TABLE `tr_order_pembelian_detail`
 ADD PRIMARY KEY (`id_order_pembelian_detail`);

--
-- Indexes for table `tr_penerimaan`
--
ALTER TABLE `tr_penerimaan`
 ADD PRIMARY KEY (`id_penerimaan`);

--
-- Indexes for table `tr_penerimaan_detail`
--
ALTER TABLE `tr_penerimaan_detail`
 ADD PRIMARY KEY (`id_penerimaan_detail`);

--
-- Indexes for table `tr_pengeluaran`
--
ALTER TABLE `tr_pengeluaran`
 ADD PRIMARY KEY (`id_pengeluaran`) USING BTREE;

--
-- Indexes for table `tr_pengeluaran_detail`
--
ALTER TABLE `tr_pengeluaran_detail`
 ADD PRIMARY KEY (`id_pengeluaran_detail`) USING BTREE;

--
-- Indexes for table `tr_permintaan_material`
--
ALTER TABLE `tr_permintaan_material`
 ADD PRIMARY KEY (`id_permintaan_material`);

--
-- Indexes for table `tr_permintaan_material_detail`
--
ALTER TABLE `tr_permintaan_material_detail`
 ADD PRIMARY KEY (`id_permintaan_material_detail`);

--
-- Indexes for table `tr_permintaan_pembelian`
--
ALTER TABLE `tr_permintaan_pembelian`
 ADD PRIMARY KEY (`id_permintaan_pembelian`);

--
-- Indexes for table `tr_permintaan_pembelian_detail`
--
ALTER TABLE `tr_permintaan_pembelian_detail`
 ADD PRIMARY KEY (`id_permintaan_pembelian_detail`);

--
-- Indexes for table `tr_persetujuan`
--
ALTER TABLE `tr_persetujuan`
 ADD PRIMARY KEY (`id_persetujuan`) USING BTREE;

--
-- Indexes for table `tr_persetujuan_catatan`
--
ALTER TABLE `tr_persetujuan_catatan`
 ADD PRIMARY KEY (`id_persetujuan_catatan`) USING BTREE;

--
-- Indexes for table `tr_stock`
--
ALTER TABLE `tr_stock`
 ADD PRIMARY KEY (`id_stock`);

--
-- Indexes for table `tr_stock_card`
--
ALTER TABLE `tr_stock_card`
 ADD PRIMARY KEY (`id_stock_card`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `langs`
--
ALTER TABLE `langs`
MODIFY `lang_srl` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `ref_barang`
--
ALTER TABLE `ref_barang`
MODIFY `id_barang` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `ref_bu`
--
ALTER TABLE `ref_bu`
MODIFY `id_bu` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=66;
--
-- AUTO_INCREMENT for table `ref_bu_access`
--
ALTER TABLE `ref_bu_access`
MODIFY `id_bu_access` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=453;
--
-- AUTO_INCREMENT for table `ref_divisi`
--
ALTER TABLE `ref_divisi`
MODIFY `id_divisi` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `ref_divisi_sub`
--
ALTER TABLE `ref_divisi_sub`
MODIFY `id_divisi_sub` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `ref_divre`
--
ALTER TABLE `ref_divre`
MODIFY `id_divre` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `ref_kategori`
--
ALTER TABLE `ref_kategori`
MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `ref_level`
--
ALTER TABLE `ref_level`
MODIFY `id_level` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `ref_lokasi`
--
ALTER TABLE `ref_lokasi`
MODIFY `id_lokasi` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `ref_menu_details`
--
ALTER TABLE `ref_menu_details`
MODIFY `id_menu_details` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=57;
--
-- AUTO_INCREMENT for table `ref_menu_details_access`
--
ALTER TABLE `ref_menu_details_access`
MODIFY `id_menu_details_access` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=284;
--
-- AUTO_INCREMENT for table `ref_menu_groups`
--
ALTER TABLE `ref_menu_groups`
MODIFY `id_menu_groups` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `ref_menu_groups_access`
--
ALTER TABLE `ref_menu_groups_access`
MODIFY `id_menu_groups_access` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=44;
--
-- AUTO_INCREMENT for table `ref_pegawai`
--
ALTER TABLE `ref_pegawai`
MODIFY `id_driver` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `ref_perusahaan`
--
ALTER TABLE `ref_perusahaan`
MODIFY `id_perusahaan` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=78;
--
-- AUTO_INCREMENT for table `ref_satuan`
--
ALTER TABLE `ref_satuan`
MODIFY `id_satuan` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `ref_stok`
--
ALTER TABLE `ref_stok`
MODIFY `id_stok` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `ref_supplier`
--
ALTER TABLE `ref_supplier`
MODIFY `id_supplier` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `ref_supplier_kategori`
--
ALTER TABLE `ref_supplier_kategori`
MODIFY `id_supplier_kategori` int(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `ref_user`
--
ALTER TABLE `ref_user`
MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=103;
--
-- AUTO_INCREMENT for table `tr_aset`
--
ALTER TABLE `tr_aset`
MODIFY `id_aset` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tr_order_pembelian`
--
ALTER TABLE `tr_order_pembelian`
MODIFY `id_order_pembelian` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tr_order_pembelian_detail`
--
ALTER TABLE `tr_order_pembelian_detail`
MODIFY `id_order_pembelian_detail` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tr_penerimaan`
--
ALTER TABLE `tr_penerimaan`
MODIFY `id_penerimaan` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tr_penerimaan_detail`
--
ALTER TABLE `tr_penerimaan_detail`
MODIFY `id_penerimaan_detail` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tr_pengeluaran`
--
ALTER TABLE `tr_pengeluaran`
MODIFY `id_pengeluaran` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tr_pengeluaran_detail`
--
ALTER TABLE `tr_pengeluaran_detail`
MODIFY `id_pengeluaran_detail` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tr_permintaan_material`
--
ALTER TABLE `tr_permintaan_material`
MODIFY `id_permintaan_material` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tr_permintaan_material_detail`
--
ALTER TABLE `tr_permintaan_material_detail`
MODIFY `id_permintaan_material_detail` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tr_permintaan_pembelian`
--
ALTER TABLE `tr_permintaan_pembelian`
MODIFY `id_permintaan_pembelian` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tr_permintaan_pembelian_detail`
--
ALTER TABLE `tr_permintaan_pembelian_detail`
MODIFY `id_permintaan_pembelian_detail` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tr_persetujuan`
--
ALTER TABLE `tr_persetujuan`
MODIFY `id_persetujuan` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tr_persetujuan_catatan`
--
ALTER TABLE `tr_persetujuan_catatan`
MODIFY `id_persetujuan_catatan` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tr_stock`
--
ALTER TABLE `tr_stock`
MODIFY `id_stock` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tr_stock_card`
--
ALTER TABLE `tr_stock_card`
MODIFY `id_stock_card` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
