-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 13, 2024 at 03:28 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.2.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hospital`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `checkout_operasi` (IN `p_id_operasi` INT)  BEGIN
    DECLARE v_jam_keluar TIME;
    
    -- Set the jam_keluar to current time
    SET v_jam_keluar = CURRENT_TIME();

    -- Update the operasi table with jam_keluar
    UPDATE operasi 
    SET jam_keluar = v_jam_keluar
    WHERE id_operasi = p_id_operasi;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkout_rawat_inap` (IN `p_id_rawat_inap` INT)  BEGIN
	    DECLARE v_tanggal_keluar DATETIME;

	    -- Update the operasi table with jam_keluar
	    UPDATE rawat_inap 
	    SET tanggal_keluar = now()
	    WHERE id_rawat_inap = p_id_rawat_inap;
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_operasi` (IN `p_id_pasien` INT, IN `p_id_dokter` INT, IN `p_jenis_operasi` VARCHAR(255))  BEGIN
    -- Insert the data into operasi table
    INSERT INTO operasi (id_pasien, id_dokter, jenis_operasi, tanggal_operasi, jam_masuk)
    VALUES (p_id_pasien, p_id_dokter, p_jenis_operasi, CURRENT_DATE(), CURRENT_TIME());
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_pasien` (IN `p_nik` VARCHAR(16), IN `p_nama_pasien` VARCHAR(100), IN `p_tanggal_lahir` DATE, IN `p_no_telepon` VARCHAR(20), IN `p_alamat` VARCHAR(50), IN `p_jenis_kelamin` ENUM('Laki - Laki','Perempuan'))  BEGIN
    DECLARE msg VARCHAR(255);

    -- Check if NIK has exactly 16 digits
    IF LENGTH(p_nik) <> 16 THEN
        SET msg = 'NIK harus memiliki panjang 16 digit.';
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;

    -- Check if tanggal_lahir is greater than today
    ELSEIF p_tanggal_lahir > CURDATE() THEN
        SET msg = 'Tanggal lahir tidak boleh melebihi hari ini.';
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;
        
    ELSE
        -- Insert the data into pasien table
        INSERT INTO pasien (nik, nama_pasien, tanggal_lahir, no_telepon, alamat, jenis_kelamin)
        VALUES (p_nik, p_nama_pasien, p_tanggal_lahir, p_no_telepon, p_alamat, p_jenis_kelamin);
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_rawat_inap` (IN `p_id_pasien` INT, IN `p_id_dokter` INT, IN `p_id_ruang` INT, IN `p_diagnosa` VARCHAR(255))  BEGIN
    DECLARE v_tanggal_masuk DATETIME;

    -- Insert the data into rawat_inap table
    INSERT INTO rawat_inap (id_pasien, id_dokter, id_ruang, tanggal_masuk, diagnosa)
    VALUES (p_id_pasien, p_id_dokter, p_id_ruang, now(), p_diagnosa);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_reservasi` (IN `p_id_pasien` INT, IN `p_id_dokter` INT, IN `p_tanggal_temu_reservasi` DATETIME, IN `p_poli` VARCHAR(50))  BEGIN
    -- Insert the data into reservasi table
    INSERT INTO reservasi (id_pasien, id_dokter, tanggal_reservasi, tanggal_temu_reservasi, poli)
    VALUES (p_id_pasien, p_id_dokter, NOW(), p_tanggal_temu_reservasi, p_poli);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ubah_status_dokter` (IN `p_id_dokter` INT, IN `p_status` ENUM('Tersedia','Tidak Tersedia'))  BEGIN
	    UPDATE dokter
	    SET status = p_status
	    WHERE id_dokter = p_id_dokter;
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ubah_status_ruang_perawatan` (IN `p_id_ruang` INT, IN `p_status` ENUM('Tersedia','Tidak Tersedia'))  BEGIN
	    UPDATE ruang_perawatan
	    SET status = p_status
	    WHERE id_ruang = p_id_ruang;
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `view_login` ()  BEGIN
    DECLARE ROW_COUNT INT;
    
    -- Hitung jumlah baris dalam tabel login
    SELECT COUNT(*) INTO ROW_COUNT FROM login;
    
    IF ROW_COUNT > 0 THEN
        -- Tampilkan isi tabel login jika ada data
        SELECT * FROM login;
    ELSE
        -- Tampilkan pesan jika tabel login kosong
        SELECT 'Tabel login kosong' AS Message;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `view_operasi` ()  BEGIN
  SELECT 
    o.id_operasi,
    p.nama_pasien,
    d.nama_dokter,
    o.jenis_operasi,
    o.tanggal_operasi,
    o.jam_masuk,
    o.jam_keluar,
    o.biaya_operasi
  FROM 
    operasi o
    INNER JOIN pasien p ON o.id_pasien = p.id_pasien
    INNER JOIN dokter d ON o.id_dokter = d.id_dokter;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `view_ruang_perawatan` (IN `limitt` INT)  BEGIN
        DECLARE i INT;
        SET i = 1;
        WHILE i <= limitt DO
            SET i = i + 1;
        END WHILE;
        SELECT * FROM ruang_perawatan WHERE id_ruang <= limitt ORDER BY id_ruang ASC;
    END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `dokter`
--

CREATE TABLE `dokter` (
  `id_dokter` int(11) NOT NULL,
  `nama_dokter` varchar(50) NOT NULL,
  `jenis_kelamin` enum('Laki - Laki','Perempuan') NOT NULL,
  `no_telepon` varchar(20) NOT NULL,
  `spesialis` varchar(20) NOT NULL,
  `harga_bayar` int(10) NOT NULL,
  `status` enum('Tersedia','Tidak Tersedia') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dokter`
--

INSERT INTO `dokter` (`id_dokter`, `nama_dokter`, `jenis_kelamin`, `no_telepon`, `spesialis`, `harga_bayar`, `status`) VALUES
(1, 'Dr. Ahmad Sulaiman', 'Laki - Laki', '081234567890', 'Umum', 200000, 'Tersedia'),
(2, 'Dr. Budi Sansusi', 'Perempuan', '081234567891', 'Gigi', 250000, 'Tersedia'),
(3, 'Dr. Cindy Wijaya', 'Perempuan', '081234567892', 'Anak', 250000, 'Tersedia'),
(5, 'Dr. Eko Purnomo', 'Laki - Laki', '081234567894', 'Bedah', 350000, 'Tersedia'),
(7, 'Dr. Galih Saputra', 'Laki - Laki', '081234567896', 'Mata', 280000, 'Tersedia'),
(8, 'Dr. Hendra Wijaya', 'Laki - Laki', '081234567897', 'THT', 290000, 'Tersedia'),
(9, 'Dr. Irma Kusuma', 'Perempuan', '081234567898', 'Saraf', 310000, 'Tersedia'),
(13, 'Dr. Mulyadi', 'Laki - Laki', '081234567902', 'Kandungan', 400000, 'Tersedia'),
(14, 'Dr. Nina Permata', 'Perempuan', '081234567903', 'Bedah', 350000, 'Tidak Tersedia'),
(17, 'Dr. Aan', 'Laki - Laki', '123412341234', 'Kulit', 250000, 'Tersedia');

--
-- Triggers `dokter`
--
DELIMITER $$
CREATE TRIGGER `delete_dokter_trigger` BEFORE DELETE ON `dokter` FOR EACH ROW BEGIN
	    DELETE FROM reservasi WHERE id_dokter = OLD.id_dokter;
	    DELETE FROM rawat_jalan WHERE id_dokter = OLD.id_dokter;
	    DELETE FROM rawat_inap WHERE id_dokter = OLD.id_dokter;
	    DELETE FROM operasi WHERE id_dokter = OLD.id_dokter;
	END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id_login` int(5) NOT NULL,
  `username` varchar(15) NOT NULL,
  `password` varchar(15) NOT NULL,
  `role` enum('user','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id_login`, `username`, `password`, `role`) VALUES
(1, 'user', 'user', 'user'),
(2, 'admin', 'admin', 'admin'),
(3, 'smbd', 'smbd', 'user'),
(4, 'abc', 'abc', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `operasi`
--

CREATE TABLE `operasi` (
  `id_operasi` int(11) NOT NULL,
  `id_pasien` int(11) NOT NULL,
  `id_dokter` int(11) NOT NULL,
  `jenis_operasi` enum('Bedah','Mata','Saraf','Kandungan') NOT NULL,
  `tanggal_operasi` date NOT NULL,
  `jam_masuk` time NOT NULL,
  `jam_keluar` time DEFAULT NULL,
  `biaya_operasi` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Triggers `operasi`
--
DELIMITER $$
CREATE TRIGGER `biaya_operasi` BEFORE UPDATE ON `operasi` FOR EACH ROW BEGIN
    DECLARE v_harga_bayar DECIMAL(10,2);
    DECLARE v_jam_masuk TIME;

    -- Ensure the update is setting the jam_keluar
    IF NEW.jam_keluar IS NOT NULL THEN
        -- Get the harga_bayar for the doctor
        SELECT harga_bayar INTO v_harga_bayar
        FROM dokter
        WHERE id_dokter = NEW.id_dokter;

        -- Get the jam_masuk
        SET v_jam_masuk = NEW.jam_masuk;

        -- Calculate the biaya_operasi
        SET NEW.biaya_operasi = v_harga_bayar * TIMESTAMPDIFF(HOUR, v_jam_masuk, NEW.jam_keluar);
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `pasien`
--

CREATE TABLE `pasien` (
  `id_pasien` int(11) NOT NULL,
  `nik` varchar(16) NOT NULL,
  `nama_pasien` varchar(50) NOT NULL,
  `alamat` varchar(50) NOT NULL,
  `no_telepon` varchar(20) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` enum('Laki - Laki','Perempuan') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pasien`
--

INSERT INTO `pasien` (`id_pasien`, `nik`, `nama_pasien`, `alamat`, `no_telepon`, `tanggal_lahir`, `jenis_kelamin`) VALUES
(7, '1111111111111111', 'tejo', 'user', '123412341234', '2024-06-06', 'Perempuan'),
(8, '999999999999', 'user', 'user', '123412341234', '2024-06-01', 'Laki - Laki'),
(12, '2222222222222222', 'P02', 'user', '123412341234', '2024-06-01', 'Laki - Laki'),
(13, '8888888888888888', 'aku', 'user', '123412341234', '2024-06-01', 'Laki - Laki'),
(14, '1234123412341234', 'user', 'user', '123412341234', '2024-06-01', 'Laki - Laki');

--
-- Triggers `pasien`
--
DELIMITER $$
CREATE TRIGGER `cek_insert_nik` BEFORE INSERT ON `pasien` FOR EACH ROW BEGIN
	    IF LENGTH(NEW.nik) != 16 THEN
		SIGNAL SQLSTATE '45000'
		SET MESSAGE_TEXT = 'NIK harus terdiri dari 16 digit';
	    END IF;
	END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `delete_pasien_trigger` BEFORE DELETE ON `pasien` FOR EACH ROW BEGIN
	    DELETE FROM reservasi WHERE id_pasien = OLD.id_pasien;
	    DELETE FROM rawat_jalan WHERE id_pasien = OLD.id_pasien;
	    DELETE FROM rawat_inap WHERE id_pasien = OLD.id_pasien;
	    DELETE FROM operasi WHERE id_pasien = OLD.id_pasien;
	END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `rawat_inap`
--

CREATE TABLE `rawat_inap` (
  `id_rawat_inap` int(11) NOT NULL,
  `id_pasien` int(11) NOT NULL,
  `id_dokter` int(11) NOT NULL,
  `id_ruang` int(11) NOT NULL,
  `diagnosa` varchar(10) NOT NULL,
  `tanggal_masuk` datetime NOT NULL,
  `tanggal_keluar` datetime DEFAULT NULL,
  `total_hari` int(11) DEFAULT NULL,
  `biaya_rawat_inap` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rawat_inap`
--

INSERT INTO `rawat_inap` (`id_rawat_inap`, `id_pasien`, `id_dokter`, `id_ruang`, `diagnosa`, `tanggal_masuk`, `tanggal_keluar`, `total_hari`, `biaya_rawat_inap`) VALUES
(3, 7, 2, 6, 'Gigi', '2024-06-01 09:50:59', '2024-06-11 14:43:24', 10, 3000000),
(4, 8, 2, 9, 'Gigi', '2024-06-02 09:52:24', '2024-06-12 00:00:00', 10, 5000000);

--
-- Triggers `rawat_inap`
--
DELIMITER $$
CREATE TRIGGER `total_hari_biaya_rawat_inap` BEFORE UPDATE ON `rawat_inap` FOR EACH ROW BEGIN
    DECLARE total_hari INT;
    DECLARE biaya_perhari INT;
    
    -- Memastikan bahwa kolom tanggal_keluar berubah atau diisi dengan nilai baru
    IF NEW.tanggal_keluar <> OLD.tanggal_keluar OR NEW.tanggal_keluar IS NOT NULL THEN
        -- Menghitung total hari rawat inap
        SET total_hari = DATEDIFF(NEW.tanggal_keluar, NEW.tanggal_masuk);
        SET NEW.total_hari = total_hari;
        
        -- Mengambil biaya per hari dari tabel ruang_perawatan berdasarkan ID ruang
        SELECT biaya INTO biaya_perhari
        FROM ruang_perawatan
        WHERE id_ruang = NEW.id_ruang;
        
        -- Menghitung total biaya rawat inap berdasarkan total hari dan biaya per hari
        SET NEW.biaya_rawat_inap = total_hari * biaya_perhari;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_status_ruang` AFTER UPDATE ON `rawat_inap` FOR EACH ROW BEGIN
    DECLARE tanggal_keluar TIMESTAMP;
    DECLARE status_ruang VARCHAR(20);

    -- Ambil tanggal_keluar dari tabel ruang_perawatan untuk ruang yang bersangkutan
    SELECT tanggal_keluar INTO tanggal_keluar
    FROM ruang_perawatan
    WHERE id_ruang = NEW.id_ruang;

    -- Set nilai status_ruang berdasarkan tanggal_keluar dari rawat_inap
    IF NEW.tanggal_keluar IS NULL OR NEW.tanggal_keluar > NOW() THEN
        SET status_ruang = 'Tidak Tersedia';
    ELSE
        SET status_ruang = 'Tersedia';
    END IF;

    -- Update status di ruang_perawatan
    UPDATE ruang_perawatan
    SET status = status_ruang
    WHERE id_ruang = NEW.id_ruang;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `rawat_jalan`
--

CREATE TABLE `rawat_jalan` (
  `id_rawat_jalan` int(11) NOT NULL,
  `id_pasien` int(11) NOT NULL,
  `id_dokter` int(11) NOT NULL,
  `diagnosa` varchar(50) NOT NULL,
  `tanggal_periksa` datetime NOT NULL,
  `biaya_periksa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rawat_jalan`
--

INSERT INTO `rawat_jalan` (`id_rawat_jalan`, `id_pasien`, `id_dokter`, `diagnosa`, `tanggal_periksa`, `biaya_periksa`) VALUES
(19, 7, 1, 'Umum', '2024-06-13 03:02:00', 200000);

-- --------------------------------------------------------

--
-- Table structure for table `reservasi`
--

CREATE TABLE `reservasi` (
  `id_reservasi` int(11) NOT NULL,
  `id_pasien` int(11) NOT NULL,
  `id_dokter` int(11) NOT NULL,
  `tanggal_reservasi` datetime NOT NULL,
  `tanggal_temu_reservasi` datetime NOT NULL,
  `poli` varchar(20) NOT NULL,
  `status` enum('Aktif','Tidak Aktif') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reservasi`
--

INSERT INTO `reservasi` (`id_reservasi`, `id_pasien`, `id_dokter`, `tanggal_reservasi`, `tanggal_temu_reservasi`, `poli`, `status`) VALUES
(15, 12, 14, '2024-06-13 12:25:00', '2024-06-13 00:00:00', 'Bedah', 'Tidak Aktif'),
(16, 7, 1, '2024-06-13 15:07:00', '2024-06-13 03:02:00', 'Umum', 'Tidak Aktif'),
(17, 14, 14, '2024-06-13 19:00:31', '2024-06-13 00:00:00', 'Bedah', 'Tidak Aktif');

--
-- Triggers `reservasi`
--
DELIMITER $$
CREATE TRIGGER `insert_rawat_jalan` AFTER UPDATE ON `reservasi` FOR EACH ROW BEGIN
	    DECLARE doctor_fee INT;

	    -- Check jika status reservasi menjadi tidak aktif dan tanggal temu reservasi sudah lewat dari waktu sekarang
	    IF NEW.status = 'Tidak Aktif' AND NEW.tanggal_temu_reservasi < NOW() THEN

		-- Ambil biaya dokter dari tabel dokter
		SELECT harga_bayar INTO doctor_fee
		FROM dokter
		WHERE id_dokter = NEW.id_dokter;

		-- Insert data ke dalam tabel rawat_jalan
		INSERT INTO rawat_jalan (id_pasien, id_dokter, diagnosa, tanggal_periksa, biaya_periksa)
		VALUES (NEW.id_pasien, NEW.id_dokter, NEW.poli, NEW.tanggal_temu_reservasi, doctor_fee);

	    END IF;
	END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_status_dokter` BEFORE UPDATE ON `reservasi` FOR EACH ROW BEGIN
    DECLARE tanggal_temu datetime;
    DECLARE status_dokter VARCHAR(20);

    -- Ambil tanggal_temu_reservasi dari tabel reservasi untuk dokter yang bersangkutan
    SELECT tanggal_temu_reservasi INTO tanggal_temu
    FROM reservasi
    WHERE id_dokter = NEW.id_dokter;

    -- Set nilai status_dokter berdasarkan tanggal_temu_reservasi
    IF NEW.tanggal_temu_reservasi IS NULL OR NEW.tanggal_temu_reservasi > NOW() THEN
        SET status_dokter = 'Tidak Tersedia';
    ELSE
        SET status_dokter = 'Tersedia';
    END IF;

    -- Update status di tabel dokter
    UPDATE dokter
    SET status = status_dokter
    WHERE id_dokter = NEW.id_dokter;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_status_reservasi` BEFORE UPDATE ON `reservasi` FOR EACH ROW BEGIN
    IF NEW.tanggal_temu_reservasi < NOW() THEN
        SET NEW.status = 'Tidak Aktif';
    ELSE
        SET NEW.status = 'Aktif';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `ruang_perawatan`
--

CREATE TABLE `ruang_perawatan` (
  `id_ruang` int(11) NOT NULL,
  `nama_ruang` varchar(20) NOT NULL,
  `jenis_ruang` enum('VVIP','VIP','REGULAR') NOT NULL,
  `biaya` int(11) NOT NULL,
  `status` enum('Tersedia','Tidak Tersedia') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ruang_perawatan`
--

INSERT INTO `ruang_perawatan` (`id_ruang`, `nama_ruang`, `jenis_ruang`, `biaya`, `status`) VALUES
(1, 'Ruang VIP 1', 'VVIP', 500000, 'Tersedia'),
(2, 'Ruang VIP 2', 'VIP', 500000, 'Tersedia'),
(3, 'Ruang VVIP 1', 'VVIP', 1000000, 'Tersedia'),
(4, 'Ruang VVIP 2', 'VVIP', 1000000, 'Tersedia'),
(5, 'Ruang Regular 1', 'REGULAR', 300000, 'Tersedia'),
(6, 'Ruang Regular 2', 'REGULAR', 300000, 'Tersedia'),
(7, 'Ruang Regular 3', 'REGULAR', 300000, 'Tersedia'),
(8, 'Ruang Regular 4', 'REGULAR', 300000, 'Tersedia'),
(9, 'Ruang VIP 3', 'VIP', 500000, 'Tersedia'),
(10, 'Ruang VVIP 3', 'VVIP', 1000000, 'Tersedia');

--
-- Triggers `ruang_perawatan`
--
DELIMITER $$
CREATE TRIGGER `delete_ruang_trigger` BEFORE DELETE ON `ruang_perawatan` FOR EACH ROW BEGIN
	    DELETE FROM rawat_inap WHERE id_ruang = OLD.id_ruang;
	END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_dokter`
-- (See below for the actual view)
--
CREATE TABLE `view_dokter` (
`id_dokter` int(11)
,`nama_dokter` varchar(50)
,`jenis_kelamin` enum('Laki - Laki','Perempuan')
,`no_telepon` varchar(20)
,`spesialis` varchar(20)
,`harga_bayar` int(10)
,`status` enum('Tersedia','Tidak Tersedia')
,`pasien_rawat_jalan` bigint(21)
,`pasien_rawat_inap` bigint(21)
,`total_pasien` bigint(22)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_pasien`
-- (See below for the actual view)
--
CREATE TABLE `view_pasien` (
`id_pasien` int(11)
,`nik` varchar(16)
,`nama_pasien` varchar(50)
,`alamat` varchar(50)
,`no_telepon` varchar(20)
,`tanggal_lahir` date
,`jenis_kelamin` enum('Laki - Laki','Perempuan')
,`jumlah_rawat_inap` bigint(21)
,`jumlah_rawat_jalan` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_rawat_inap`
-- (See below for the actual view)
--
CREATE TABLE `view_rawat_inap` (
`id_rawat_inap` int(11)
,`nama_pasien` varchar(50)
,`nama_dokter` varchar(50)
,`nama_ruang` varchar(20)
,`diagnosa` varchar(10)
,`tanggal_masuk` datetime
,`tanggal_keluar` datetime
,`total_hari` int(11)
,`biaya_rawat_inap` int(11)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_rawat_jalan`
-- (See below for the actual view)
--
CREATE TABLE `view_rawat_jalan` (
`id_rawat_jalan` int(11)
,`nama_pasien` varchar(50)
,`nama_dokter` varchar(50)
,`diagnosa` varchar(50)
,`tanggal_periksa` datetime
,`biaya_periksa` int(11)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_reservasi`
-- (See below for the actual view)
--
CREATE TABLE `view_reservasi` (
`id_reservasi` int(11)
,`nama_pasien` varchar(50)
,`nama_dokter` varchar(50)
,`tanggal_reservasi` datetime
,`tanggal_temu_reservasi` datetime
,`poli` varchar(20)
,`status` enum('Aktif','Tidak Aktif')
);

-- --------------------------------------------------------

--
-- Structure for view `view_dokter`
--
DROP TABLE IF EXISTS `view_dokter`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_dokter`  AS  select `d`.`id_dokter` AS `id_dokter`,`d`.`nama_dokter` AS `nama_dokter`,`d`.`jenis_kelamin` AS `jenis_kelamin`,`d`.`no_telepon` AS `no_telepon`,`d`.`spesialis` AS `spesialis`,`d`.`harga_bayar` AS `harga_bayar`,`d`.`status` AS `status`,coalesce(`rj`.`pasien_rawat_jalan`,0) AS `pasien_rawat_jalan`,coalesce(`ri`.`pasien_rawat_inap`,0) AS `pasien_rawat_inap`,coalesce(`rj`.`pasien_rawat_jalan`,0) + coalesce(`ri`.`pasien_rawat_inap`,0) AS `total_pasien` from ((`dokter` `d` left join (select `rawat_jalan`.`id_dokter` AS `id_dokter`,count(0) AS `pasien_rawat_jalan` from `rawat_jalan` group by `rawat_jalan`.`id_dokter`) `rj` on(`d`.`id_dokter` = `rj`.`id_dokter`)) left join (select `rawat_inap`.`id_dokter` AS `id_dokter`,count(0) AS `pasien_rawat_inap` from `rawat_inap` group by `rawat_inap`.`id_dokter`) `ri` on(`d`.`id_dokter` = `ri`.`id_dokter`)) ;

-- --------------------------------------------------------

--
-- Structure for view `view_pasien`
--
DROP TABLE IF EXISTS `view_pasien`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_pasien`  AS  select `pasien`.`id_pasien` AS `id_pasien`,`pasien`.`nik` AS `nik`,`pasien`.`nama_pasien` AS `nama_pasien`,`pasien`.`alamat` AS `alamat`,`pasien`.`no_telepon` AS `no_telepon`,`pasien`.`tanggal_lahir` AS `tanggal_lahir`,`pasien`.`jenis_kelamin` AS `jenis_kelamin`,(select count(0) from `rawat_inap` where `rawat_inap`.`id_pasien` = `pasien`.`id_pasien`) AS `jumlah_rawat_inap`,(select count(0) from `rawat_jalan` where `rawat_jalan`.`id_pasien` = `pasien`.`id_pasien`) AS `jumlah_rawat_jalan` from `pasien` ;

-- --------------------------------------------------------

--
-- Structure for view `view_rawat_inap`
--
DROP TABLE IF EXISTS `view_rawat_inap`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_rawat_inap`  AS  select `r`.`id_rawat_inap` AS `id_rawat_inap`,`p`.`nama_pasien` AS `nama_pasien`,`d`.`nama_dokter` AS `nama_dokter`,`q`.`nama_ruang` AS `nama_ruang`,`r`.`diagnosa` AS `diagnosa`,`r`.`tanggal_masuk` AS `tanggal_masuk`,`r`.`tanggal_keluar` AS `tanggal_keluar`,`r`.`total_hari` AS `total_hari`,`r`.`biaya_rawat_inap` AS `biaya_rawat_inap` from (((`rawat_inap` `r` join `pasien` `p` on(`r`.`id_pasien` = `p`.`id_pasien`)) join `dokter` `d` on(`r`.`id_dokter` = `d`.`id_dokter`)) join `ruang_perawatan` `q` on(`r`.`id_dokter` = `q`.`id_ruang`)) ;

-- --------------------------------------------------------

--
-- Structure for view `view_rawat_jalan`
--
DROP TABLE IF EXISTS `view_rawat_jalan`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_rawat_jalan`  AS  select `r`.`id_rawat_jalan` AS `id_rawat_jalan`,`p`.`nama_pasien` AS `nama_pasien`,`d`.`nama_dokter` AS `nama_dokter`,`r`.`diagnosa` AS `diagnosa`,`r`.`tanggal_periksa` AS `tanggal_periksa`,`r`.`biaya_periksa` AS `biaya_periksa` from ((`rawat_jalan` `r` join `pasien` `p` on(`r`.`id_pasien` = `p`.`id_pasien`)) join `dokter` `d` on(`r`.`id_dokter` = `d`.`id_dokter`)) ;

-- --------------------------------------------------------

--
-- Structure for view `view_reservasi`
--
DROP TABLE IF EXISTS `view_reservasi`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_reservasi`  AS  select `r`.`id_reservasi` AS `id_reservasi`,`p`.`nama_pasien` AS `nama_pasien`,`d`.`nama_dokter` AS `nama_dokter`,`r`.`tanggal_reservasi` AS `tanggal_reservasi`,`r`.`tanggal_temu_reservasi` AS `tanggal_temu_reservasi`,`r`.`poli` AS `poli`,`r`.`status` AS `status` from ((`reservasi` `r` join `pasien` `p` on(`r`.`id_pasien` = `p`.`id_pasien`)) join `dokter` `d` on(`r`.`id_dokter` = `d`.`id_dokter`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dokter`
--
ALTER TABLE `dokter`
  ADD PRIMARY KEY (`id_dokter`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id_login`);

--
-- Indexes for table `operasi`
--
ALTER TABLE `operasi`
  ADD PRIMARY KEY (`id_operasi`),
  ADD KEY `fk_operasi_pasien` (`id_pasien`),
  ADD KEY `fk_operasi_dokter` (`id_dokter`);

--
-- Indexes for table `pasien`
--
ALTER TABLE `pasien`
  ADD PRIMARY KEY (`id_pasien`);

--
-- Indexes for table `rawat_inap`
--
ALTER TABLE `rawat_inap`
  ADD PRIMARY KEY (`id_rawat_inap`),
  ADD KEY `fk_rawat_inap_pasien` (`id_pasien`),
  ADD KEY `fk_rawat_inap_dokter` (`id_dokter`),
  ADD KEY `fk_rawat_inap_ruang_perawatan` (`id_ruang`);

--
-- Indexes for table `rawat_jalan`
--
ALTER TABLE `rawat_jalan`
  ADD PRIMARY KEY (`id_rawat_jalan`),
  ADD KEY `fk_rawat_jalan_pasien` (`id_pasien`),
  ADD KEY `fk_rawat_jalan_dokter` (`id_dokter`);

--
-- Indexes for table `reservasi`
--
ALTER TABLE `reservasi`
  ADD PRIMARY KEY (`id_reservasi`),
  ADD KEY `fk_reservasi_pasien` (`id_pasien`),
  ADD KEY `fk_reservasi_dokter` (`id_dokter`);

--
-- Indexes for table `ruang_perawatan`
--
ALTER TABLE `ruang_perawatan`
  ADD PRIMARY KEY (`id_ruang`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dokter`
--
ALTER TABLE `dokter`
  MODIFY `id_dokter` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id_login` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `operasi`
--
ALTER TABLE `operasi`
  MODIFY `id_operasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pasien`
--
ALTER TABLE `pasien`
  MODIFY `id_pasien` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `rawat_inap`
--
ALTER TABLE `rawat_inap`
  MODIFY `id_rawat_inap` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `rawat_jalan`
--
ALTER TABLE `rawat_jalan`
  MODIFY `id_rawat_jalan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `reservasi`
--
ALTER TABLE `reservasi`
  MODIFY `id_reservasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `ruang_perawatan`
--
ALTER TABLE `ruang_perawatan`
  MODIFY `id_ruang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `operasi`
--
ALTER TABLE `operasi`
  ADD CONSTRAINT `fk_operasi_dokter` FOREIGN KEY (`id_dokter`) REFERENCES `dokter` (`id_dokter`),
  ADD CONSTRAINT `fk_operasi_pasien` FOREIGN KEY (`id_pasien`) REFERENCES `pasien` (`id_pasien`),
  ADD CONSTRAINT `operasi_ibfk_1` FOREIGN KEY (`id_pasien`) REFERENCES `pasien` (`id_pasien`),
  ADD CONSTRAINT `operasi_ibfk_2` FOREIGN KEY (`id_dokter`) REFERENCES `dokter` (`id_dokter`);

--
-- Constraints for table `rawat_inap`
--
ALTER TABLE `rawat_inap`
  ADD CONSTRAINT `fk_rawat_inap_dokter` FOREIGN KEY (`id_dokter`) REFERENCES `dokter` (`id_dokter`),
  ADD CONSTRAINT `fk_rawat_inap_pasien` FOREIGN KEY (`id_pasien`) REFERENCES `pasien` (`id_pasien`),
  ADD CONSTRAINT `fk_rawat_inap_ruang_perawatan` FOREIGN KEY (`id_ruang`) REFERENCES `ruang_perawatan` (`id_ruang`);

--
-- Constraints for table `rawat_jalan`
--
ALTER TABLE `rawat_jalan`
  ADD CONSTRAINT `fk_rawat_jalan_dokter` FOREIGN KEY (`id_dokter`) REFERENCES `dokter` (`id_dokter`),
  ADD CONSTRAINT `fk_rawat_jalan_pasien` FOREIGN KEY (`id_pasien`) REFERENCES `pasien` (`id_pasien`);

--
-- Constraints for table `reservasi`
--
ALTER TABLE `reservasi`
  ADD CONSTRAINT `fk_reservasi_dokter` FOREIGN KEY (`id_dokter`) REFERENCES `dokter` (`id_dokter`),
  ADD CONSTRAINT `fk_reservasi_pasien` FOREIGN KEY (`id_pasien`) REFERENCES `pasien` (`id_pasien`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
