-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 24, 2025 at 04:39 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_knowledge`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_detail`
--

CREATE TABLE `tbl_detail` (
  `id_detail` int(11) NOT NULL,
  `id_topic` int(11) NOT NULL,
  `detail` text DEFAULT NULL,
  `upload_file_comment` longtext DEFAULT NULL,
  `id_member` int(11) NOT NULL,
  `dateSave_comment` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `tbl_detail`
--
DELIMITER $$
CREATE TRIGGER `update_reply_after_delete` AFTER DELETE ON `tbl_detail` FOR EACH ROW BEGIN
    UPDATE tbl_topic
    SET reply = (SELECT COUNT(*) FROM tbl_detail WHERE id_topic = OLD.id_topic)
    WHERE id_topic = OLD.id_topic;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_reply_after_insert` AFTER INSERT ON `tbl_detail` FOR EACH ROW BEGIN
    UPDATE tbl_topic
    SET reply = (SELECT COUNT(*) FROM tbl_detail WHERE id_topic = NEW.id_topic)
    WHERE id_topic = NEW.id_topic;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_reply_after_update` AFTER UPDATE ON `tbl_detail` FOR EACH ROW BEGIN
    UPDATE tbl_topic
    SET reply = (SELECT COUNT(*) FROM tbl_detail WHERE id_topic = NEW.id_topic)
    WHERE id_topic = NEW.id_topic;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_rating`
--

CREATE TABLE `tbl_rating` (
  `id_rating` int(11) NOT NULL,
  `id_detail` int(11) NOT NULL,
  `star_score` int(11) DEFAULT NULL,
  `dateScore` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_topic`
--

CREATE TABLE `tbl_topic` (
  `id_topic` int(11) NOT NULL,
  `topic` varchar(250) NOT NULL,
  `type_topic` varchar(80) DEFAULT NULL,
  `detail_topic` text NOT NULL,
  `upload_file` longtext DEFAULT NULL,
  `views` int(11) NOT NULL DEFAULT 0,
  `reply` int(5) NOT NULL DEFAULT 0,
  `id_member` int(11) NOT NULL,
  `dateSave` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id_member` int(11) NOT NULL,
  `username` varchar(100) NOT NULL COMMENT 'md5',
  `password` varchar(100) NOT NULL,
  `user_id` varchar(50) NOT NULL COMMENT 'รหัสพนักงาน',
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `user_level` varchar(30) NOT NULL COMMENT 'สิทธิ์การเข้าถึงข้อมูล,user,admin,NT',
  `user_tel` varchar(22) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_type` varchar(30) NOT NULL COMMENT 'ประเภทของ user เช่น NT, สพฐ.',
  `user_region` varchar(50) NOT NULL COMMENT 'ภูมิภาคของ user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id_member`, `username`, `password`, `user_id`, `firstname`, `lastname`, `user_level`, `user_tel`, `user_email`, `user_type`, `user_region`) VALUES
(2, 'admin01', '18c6d818ae35a3e8279b5330eda01498', '1234', 'admin01', 'admin01', 'admin', '0124578435', 'admin01@gmail.com', 'NT', 'กรุงเทพ'),
(3, 'admin02', '6e60a28384bc05fa5b33cc579d040c56', '4567', 'admin02', 'admin02', 'admin', '0124578435', 'admin02@gmail.com', 'NT', 'กรุงเทพ'),
(4, 'user01', 'b75705d7e35e7014521a46b532236ec3', '9874', 'user01', 'user01', 'user', '0845237819', 'user01@gmail.com', 'สพฐ', 'เชียงใหม่'),
(5, 'admin03', '7dc2466ad3ff5911f6a5e47e043e0abc', 'admin03', 'admin03', 'admin03', 'admin', '06784521659', 'admin03@test.com', 'NT', 'ขอนแก่น');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_detail`
--
ALTER TABLE `tbl_detail`
  ADD PRIMARY KEY (`id_detail`);

--
-- Indexes for table `tbl_rating`
--
ALTER TABLE `tbl_rating`
  ADD PRIMARY KEY (`id_rating`);

--
-- Indexes for table `tbl_topic`
--
ALTER TABLE `tbl_topic`
  ADD PRIMARY KEY (`id_topic`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id_member`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_detail`
--
ALTER TABLE `tbl_detail`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_rating`
--
ALTER TABLE `tbl_rating`
  MODIFY `id_rating` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_topic`
--
ALTER TABLE `tbl_topic`
  MODIFY `id_topic` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id_member` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
