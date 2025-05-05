-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 05, 2025 at 09:34 PM
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
-- Database: `project_final`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `details` text NOT NULL,
  `max_hours` int(11) NOT NULL,
  `hours` int(11) NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`id`, `image`, `name`, `location`, `details`, `max_hours`, `hours`, `status`) VALUES
(1, '', 'เกี่ยวกับ การบริจาคร่างกาย', '', '', 9, 0, ''),
(2, '', 'เกี่ยวกับ ทำความสะอาด', '', '', 4, 0, ''),
(3, '', 'เกี่ยวกับ การให้ความรู้', '', '', 4, 0, ''),
(4, '', 'เกี่ยวกับ การบริจาคสิ่งของภายนอกมหาวิทยาลัย', '', '', 4, 0, ''),
(5, '', 'เกี่ยวกับ การดูแล', '', '', 6, 0, ''),
(6, '', 'เกี่ยวกับ อาสาสมัคร (เฉพาะผู้มีบัตรจิตอาสา)', '', '', 18, 0, ''),
(7, '', 'เกี่ยวกับ งานหน่วยงานภายในมหาวิทยาลัย', '', '', 18, 0, ''),
(8, '', 'เกี่ยวกับ การออมเงิน กอช.', '', '', 5, 0, ''),
(9, '', 'เกี่ยวกับ สิ่งแวดล้อม', '', '', 4, 0, ''),
(10, '', 'เกี่ยวกับ หน่วยงานภายนอก', '', '', 8, 0, ''),
(11, '', 'เกี่ยวกับ e-learning', '', '', 18, 0, ''),
(12, '', 'เกี่ยวกับ ช่วยสร้างขาเทียมช่วยผู้พิการ', '', '', 18, 0, ''),
(13, '', 'เกี่ยวกับ ขอขวดเป็นของขวัญ', '', '', 18, 0, ''),
(14, '', 'เกี่ยวกับ รู้รักสามัคคี รักษ์สิ่งแวดล้อม พัฒนาคุณภาพชีวิต', '', '', 4, 0, ''),
(15, '', 'เกี่ยวกับ ความมั่นคง', '', '', 8, 0, ''),
(16, '', 'เกี่ยวกับ กิจกรรม 100 ปี กาชาด', '', '', 6, 0, ''),
(17, '', 'เกี่ยวกับ งานรับปริญญา', '', '', 18, 0, ''),
(18, '', 'เกี่ยวกับ Music Interventions for Enhancing Children Development', '', '', 3, 0, ''),
(19, '', 'เกี่ยวกับ มหกรรมกีฬา สุขภาพดี วิถีไทย', '', '', 8, 0, ''),
(20, '', 'เกี่ยวกับ สถาปนิก 67', '', '', 18, 0, ''),
(21, '', 'เกี่ยวกับ วันพยาบาลสากลและวันงดสูบบุหรี่โลก', '', '', 6, 0, ''),
(22, '', 'เกี่ยวกับ ลอยกระทง', '', '', 18, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `admin_email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `username`, `admin_email`, `password`) VALUES
(0, 'DevBabe', 'moombabe24468@gmail.com', '$2y$10$Flaqw9CP7eEdftYYoLS/selUAHTJvuCUe.Vg4GszNxMFfutlkvspi'),
(0, 'devbabe', '', '$2y$10$aeqEg/p3kXgkFtwOVdRSLO3fXER6Eo60s85JhfxGKD2dinzRV5bgm');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(255) NOT NULL,
  `faculty_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='สาขา';

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`department_id`, `department_name`, `faculty_id`) VALUES
(1, 'วิศวกรรมคอมพิวเตอร์', 1),
(2, 'วิศวกรรมเครื่องกล', 1),
(3, 'วิทยาการคอมพิวเตอร์', 2);

-- --------------------------------------------------------

--
-- Table structure for table `endorsee`
--

CREATE TABLE `endorsee` (
  `endorser_id` varchar(13) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone_number` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ผู้รับรองรายได้ครอบครัว';

--
-- Dumping data for table `endorsee`
--

INSERT INTO `endorsee` (`endorser_id`, `full_name`, `address`, `phone_number`) VALUES
('1', 'นายสมชาย ใจดี', '123 หมู่ 4 ตำบลทดสอบ อำเภอเมือง จังหวัดกรุงเทพ', '0812345678'),
('2', 'นางสาวสมหญิง รักเรียน', '456 หมู่ 8 ตำบลทดสอบ อำเภอเมือง จังหวัดเชียงใหม่', '0898765432'),
('3', 'นายวิทยา เก่งงาน', '789 หมู่ 5 ตำบลทดสอบ อำเภอเมือง จังหวัดขอนแก่น', '0865432198');

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `faculty_id` int(11) NOT NULL,
  `faculty_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='คณะ';

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`faculty_id`, `faculty_name`) VALUES
(1, 'คณะวิศวกรรมศาสตร์'),
(2, 'คณะวิทยาศาสตร์');

-- --------------------------------------------------------

--
-- Table structure for table `family_status_code`
--

CREATE TABLE `family_status_code` (
  `status_code_id` varchar(10) NOT NULL,
  `description` varchar(255) NOT NULL COMMENT 'คำอธิบายสถานะครอบครัว'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `family_status_code`
--

INSERT INTO `family_status_code` (`status_code_id`, `description`) VALUES
('1.1', 'บิดาและมารดาประกอบอาชีพอิสระ'),
('1.2', 'บิดาประกอบอาชีพอิสระ มารดามีเงินเดือนประจำ'),
('1.3', 'บิดาประกอบอาชีพอิสระ มารดาไม่ประกอบอาชีพ'),
('1.4', 'มารดาประกอบอาชีพอิสระ บิดามีเงินเดือนประจำ'),
('1.5', 'บิดาและมารดาประกอบอาชีพมีเงินเดือนประจำ'),
('1.6', 'บิดามีเงินเดือนประจำ มารดาไม่ประกอบอาชีพ'),
('1.7', 'มารดาประกอบอาชีพอิสระ บิดาไม่ประกอบอาชีพ'),
('2.1', 'บิดาประกอบอาชีพอิสระ มารดาแยกทาง หรือหย่าร้าง หรือไม่ส่งเสียเลี้ยงดู'),
('2.2', 'บิดาประกอบอาชีพอิสระ มารดาเสียชีวิต'),
('2.3', 'บิดาประกอบอาชีพมีเงินเดือนประจำ มารดาแยกทาง หรือหย่าร้าง หรือไม่ส่งเสียเลี้ยงดู'),
('2.4', 'บิดาประกอบอาชีพมีเงินเดือนประจำ มารดาเสียชีวิต'),
('3.1', 'มารดาประกอบอาชีพอิสระ บิดาแยกทาง หรือหย่าร้าง หรือไม่ส่งเสียเลี้ยงดู'),
('3.2', 'มารดาประกอบอาชีพมีเงินเดือนประจำ บิดาแยกทาง หรือหย่าร้าง หรือไม่ส่งเสียเลี้ยงดู'),
('3.3', 'มารดาประกอบอาชีพอิสระ บิดาเสียชีวิต'),
('3.4', 'มารดาประกอบอาชีพมีเงินเดือนประจำ บิดาเสียชีวิต'),
('4.1', 'บิดาไม่ประกอบอาชีพ มารดาแยกทาง หรือหย่าร้าง หรือไม่ส่งเสียเลี้ยงดู หรือเสียชีวิต'),
('4.2', 'บิดาไม่ประกอบอาชีพ มารดาเสียชีวิต');

-- --------------------------------------------------------

--
-- Table structure for table `father`
--

CREATE TABLE `father` (
  `father_id` varchar(13) NOT NULL,
  `father_name` varchar(255) NOT NULL COMMENT 'ชื่อ',
  `father_last_name` varchar(255) NOT NULL COMMENT 'นามสกุล',
  `father_occupation` varchar(255) NOT NULL,
  `father_address` varchar(255) NOT NULL,
  `father_income` decimal(10,2) NOT NULL,
  `father_phone_number` varchar(10) NOT NULL,
  `father_education_level` varchar(255) NOT NULL,
  `father_school_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `father`
--

INSERT INTO `father` (`father_id`, `father_name`, `father_last_name`, `father_occupation`, `father_address`, `father_income`, `father_phone_number`, `father_education_level`, `father_school_name`) VALUES
('1', 'สมชาย', 'ใจดี', 'พนักงานบริษัท', '123 หมู่ 4 ตำบลทดสอบ อำเภอเมือง จังหวัดกรุงเทพ', 25000.00, '0812345678', 'ปริญญาตรี', 'มหาวิทยาลัย A'),
('2', 'อนันต์', 'สุขสันต์', 'ค้าขาย', '456 หมู่ 8 ตำบลทดสอบ อำเภอเมือง จังหวัดเชียงใหม่', 18000.00, '0898765432', 'มัธยมปลาย', 'โรงเรียน B');

-- --------------------------------------------------------

--
-- Table structure for table `guardian`
--

CREATE TABLE `guardian` (
  `guardian_id` varchar(13) NOT NULL,
  `f_name` varchar(255) NOT NULL COMMENT 'ชื่อ',
  `l_name` varchar(255) NOT NULL COMMENT 'นามสกุล',
  `occupation` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `income` decimal(10,2) NOT NULL,
  `phone_number` varchar(10) NOT NULL,
  `education_level` varchar(255) NOT NULL,
  `school_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ผู้ปกครอง';

--
-- Dumping data for table `guardian`
--

INSERT INTO `guardian` (`guardian_id`, `f_name`, `l_name`, `occupation`, `address`, `income`, `phone_number`, `education_level`, `school_name`) VALUES
('G123', 'สมศักดิ์', 'ทองดี', 'รับราชการ', '123 หมู่ 4 ตำบลทดสอบ อำเภอเมือง จังหวัดกรุงเทพ', 35000.00, '0812345678', 'ปริญญาตรี', 'มหาวิทยาลัย A'),
('G234', 'วิชัย', 'สุขใจ', 'เกษตรกร', '456 หมู่ 8 ตำบลทดสอบ อำเภอเมือง จังหวัดเชียงใหม่', 20000.00, '0898765432', 'มัธยมปลาย', 'โรงเรียน B'),
('G345', 'สมหมาย', 'พงษ์เพียร', 'ค้าขาย', '789 หมู่ 5 ตำบลทดสอบ อำเภอเมือง จังหวัดขอนแก่น', 25000.00, '0865432198', 'ปริญญาโท', 'มหาวิทยาลัย C');

-- --------------------------------------------------------

--
-- Table structure for table `money`
--

CREATE TABLE `money` (
  `contract_id` int(11) NOT NULL,
  `academic_year` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='จำนวนเงิน';

--
-- Dumping data for table `money`
--

INSERT INTO `money` (`contract_id`, `academic_year`, `amount`) VALUES
(1, 2023, 50000.00),
(2, 2024, 60000.00),
(3, 2025, 70000.00);

-- --------------------------------------------------------

--
-- Table structure for table `mother`
--

CREATE TABLE `mother` (
  `mother_id` varchar(13) NOT NULL,
  `mother_name` varchar(255) NOT NULL COMMENT 'ชื่อ',
  `mother_last_name` varchar(255) NOT NULL COMMENT 'นามสกุล',
  `mother_occupation` varchar(255) NOT NULL,
  `mother_address` varchar(255) NOT NULL,
  `mother_income` decimal(10,2) NOT NULL,
  `mother_phone_number` varchar(10) NOT NULL,
  `mother_education_level` varchar(255) NOT NULL,
  `mother_school_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mother`
--

INSERT INTO `mother` (`mother_id`, `mother_name`, `mother_last_name`, `mother_occupation`, `mother_address`, `mother_income`, `mother_phone_number`, `mother_education_level`, `mother_school_name`) VALUES
('1', 'สมฤดี', 'ใจดี', 'พนักงานบริษัท', '500 หมู่ 7 ตำบลทดสอบ อำเภอเมือง จังหวัดเชียงใหม่', 35000.00, '0845556664', 'ปริญญาตรี', 'มหาวิทยาลัย GHJ'),
('2', 'วรรณา', 'สุขสันต์', 'แพทย์', '600 หมู่ 8 ตำบลทดสอบ อำเภอเมือง จังหวัดนครราชสีมา', 70000.00, '0854445553', 'ปริญญาโท', 'มหาวิทยาลัย KLM'),
('3', 'สุภาวดี', 'พงษ์สวัสดิ์', 'อาจารย์', '700 หมู่ 9 ตำบลทดสอบ อำเภอเมือง จังหวัดขอนแก่น', 45000.00, '0863334442', 'ปริญญาเอก', 'มหาวิทยาลัย NOP');

-- --------------------------------------------------------

--
-- Table structure for table `new_user_activities`
--

CREATE TABLE `new_user_activities` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `activity_name` varchar(255) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `hours` int(11) NOT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `terms` int(11) NOT NULL COMMENT 'เทอม',
  `activity_id` int(11) NOT NULL,
  `status` enum('checked','unchecked') DEFAULT 'unchecked'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `new_user_activities`
--

INSERT INTO `new_user_activities` (`id`, `username`, `activity_name`, `location`, `details`, `image_path`, `hours`, `start_date`, `end_date`, `created_at`, `terms`, `activity_id`, `status`) VALUES
(1, '1234567890123', 'เกี่ยวกับ การบริจาคร่างกาย', 'าา', 'หหห', '../uploads/ER DIAGRAM_page-0001.jpg', 27, '2024-11-01 00:00:00', '2025-05-03 00:00:00', '2025-03-19 04:59:33', 1, 1, 'checked'),
(4, '1234567890123', 'เกี่ยวกับ การบริจาคร่างกาย', 'บ้าน', 'ไม่บอก', '../uploads/messageImage_1670308402138.jpg', 27, '2024-11-01 00:00:00', '2025-05-03 00:00:00', '2025-03-19 05:04:41', 1, 1, 'checked'),
(5, '1103703175319', 'เกี่ยวกับ การบริจาคร่างกาย', 'บ้าน', '123', '../uploads/line_oa_chat_221129_173000.jpg', 13, '2024-11-01 00:00:00', '2025-05-03 00:00:00', '2025-03-19 05:10:22', 2, 1, 'checked'),
(6, '1619400005271', 'เกี่ยวกับ การบริจาคร่างกาย', 'รพ.นวมินทร์2', 'บริจาคเลือด', '../uploads/messageImage_1670308402138.jpg', 9, '2024-11-01 00:00:00', '2025-05-03 00:00:00', '2025-03-19 05:57:42', 1, 1, 'checked'),
(7, '1103703175319', 'เกี่ยวกับ การบริจาคร่างกาย', 'รพ.นวมินทร์2', 'บริจาคร่างกาย', '../uploads/line_oa_chat_221129_173000.jpg', 13, '2024-11-01 00:00:00', '2025-05-03 00:00:00', '2025-03-19 06:47:18', 1, 1, 'checked'),
(8, '1103703175319', 'เกี่ยวกับ การบริจาคร่างกาย', 'dasdas', 'dsadsads', '../uploads/482356587_949553540691511_4827138769507947260_n.jpg', 13, '2024-11-01 00:00:00', '2025-05-03 00:00:00', '2025-03-19 08:09:50', 1, 1, 'checked'),
(9, '1103703175319', 'เกี่ยวกับ การบริจาคร่างกาย', 'รพ.นวมินทร์2', 'หก', '../uploads/HBDPwy.jpg', 13, '2024-11-01 00:00:00', '2025-05-03 00:00:00', '2025-03-25 09:55:36', 1, 9, 'checked'),
(10, '1103703175319', 'เกี่ยวกับ การบริจาคร่างกาย', 'รพ.นวมินทร์2', 'กหกฟห', '../uploads/98184109_895677447563683_1104361066759979008_n.jpg', 13, '2024-11-01 00:00:00', '2025-05-03 00:00:00', '2025-03-25 09:56:26', 2, 1, 'checked'),
(12, '1103703175319', 'เกี่ยวกับ การบริจาคร่างกาย', 'รพ.นวมินทร์2', 'แกแกด', '../uploads/476496160_1361638081502156_57973.jpg', 13, '2024-11-01 00:00:00', '2025-05-03 00:00:00', '2025-03-25 11:04:13', 2, 1, 'checked'),
(13, '1234567890123', 'เกี่ยวกับ การให้ความรู้', 'หกฟห', 'กฟหก', '../uploads/476496160_1361638081502156_57973.jpg', 22, '2024-11-01 00:00:00', '2025-05-03 00:00:00', '2025-03-25 11:32:51', 2, 3, 'checked'),
(14, '1103703175319', 'เกี่ยวกับ วันพยาบาลสากลและวันงดสูบบุหรี่โลก', 'บ้าน', 'ทำจิตอาสา 1 ชั่วโมง', '../uploads/bios.png', 13, '2024-11-01 00:00:00', '2025-05-03 00:00:00', '2025-04-09 15:24:17', 0, 1, 'checked'),
(15, '1103703175319', 'เกี่ยวกับ ลอยกระทง', 'บ้าน', 'ทำกระทงขาย', '../uploads/2.ปาย 3x4.jpg', 8, '2024-11-01 00:00:00', '2025-05-03 00:00:00', '2025-04-19 21:52:43', 0, 0, 'checked'),
(16, '1103703175319', 'ลอยกระทง', 'บ้าน', 'ช่วยทำกระทงขายที่วัด', '../uploads/น้ำท่วมสอง.jpg', 4, '2024-11-01 00:00:00', '2025-05-03 00:00:00', '2025-04-19 23:14:05', 0, 0, 'checked'),
(18, '1234567890123', 'เกี่ยวกับ การบริจาคสิ่งของภายนอกมหาวิทยาลัย', 'กรุงเทพ', 'บริจาคของน้ำท่วม', '../uploads/น้ำท่วม.jpg', 22, '2024-11-01 00:00:00', '2025-05-03 00:00:00', '2025-04-20 23:26:51', 0, 4, 'checked'),
(19, '1234567890123', 'เกี่ยวกับ ทำความสะอาด', 'วัด', 'ล้างห้องน้ำ', '../uploads/น้ำท่วม.jpg', 22, '2024-11-01 00:00:00', '2025-05-03 00:00:00', '2025-04-21 05:53:56', 0, 2, 'checked'),
(20, '1234567890123', 'เกี่ยวกับ งานรับปริญญา', 'มหาวิทยาลัยเกษมบัณฑิต', 'ช่วยจัดงานรับปริญญา', '../uploads/น้ำท่วม.jpg', 36, '2024-11-01 00:00:00', '2025-05-03 00:00:00', '2025-04-21 17:50:58', 0, 17, 'checked');

-- --------------------------------------------------------

--
-- Table structure for table `pact`
--

CREATE TABLE `pact` (
  `contract_id` int(11) NOT NULL,
  `contract_date` datetime NOT NULL,
  `loan_limit` decimal(10,2) NOT NULL,
  `student_id` varchar(13) NOT NULL,
  `officer_id` varchar(13) NOT NULL,
  `academic_year` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='สัญญากู้ยืม';

--
-- Dumping data for table `pact`
--

INSERT INTO `pact` (`contract_id`, `contract_date`, `loan_limit`, `student_id`, `officer_id`, `academic_year`) VALUES
(1, '2023-01-15 00:00:00', 100000.00, '1234567890123', '1', '2023'),
(2, '2024-02-10 00:00:00', 120000.00, '2345678901234', '2', '2024'),
(3, '2025-03-05 00:00:00', 150000.00, '3456789012345', '3', '2025');

-- --------------------------------------------------------

--
-- Table structure for table `sibling`
--

CREATE TABLE `sibling` (
  `full_name` varchar(255) NOT NULL,
  `age` int(11) NOT NULL,
  `total_siblings` int(11) NOT NULL,
  `current_year` varchar(255) NOT NULL,
  `school_name` varchar(255) NOT NULL,
  `gender` varchar(20) NOT NULL,
  `workplace` varchar(255) NOT NULL,
  `income` decimal(10,2) NOT NULL,
  `education_level` varchar(255) NOT NULL,
  `student_id` varchar(13) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ข้อมูลพี่น้อง';

--
-- Dumping data for table `sibling`
--

INSERT INTO `sibling` (`full_name`, `age`, `total_siblings`, `current_year`, `school_name`, `gender`, `workplace`, `income`, `education_level`, `student_id`) VALUES
('สมชาย ใจดี', 15, 3, '9', 'โรงเรียนมัธยม A', 'ชาย', '', 0.00, 'มัธยมศึกษาตอนต้น', '1234567890123'),
('สมหญิง ใจดี', 18, 3, '12', 'โรงเรียนมัธยม B', 'หญิง', '', 0.00, 'มัธยมศึกษาตอนปลาย', '1234567890123'),
('สมปอง ขยันเรียน', 22, 2, '', '', 'ชาย', 'บริษัท XYZ', 20000.00, 'ปริญญาตรี', '2345678901234');

-- --------------------------------------------------------

--
-- Table structure for table `spouse`
--

CREATE TABLE `spouse` (
  `spouse_id` varchar(13) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `occupation` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `income` decimal(10,2) NOT NULL,
  `phone_number` varchar(10) NOT NULL,
  `education_level` varchar(255) NOT NULL,
  `school_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ข้อมูลคู่สมรส';

--
-- Dumping data for table `spouse`
--

INSERT INTO `spouse` (`spouse_id`, `full_name`, `occupation`, `address`, `income`, `phone_number`, `education_level`, `school_name`) VALUES
('1', 'สมศรี ใจดี', 'พนักงานบริษัท', '456 ถนนรัชดา กรุงเทพฯ', 30000.00, '0812345678', 'ปริญญาตรี', 'มหาวิทยาลัย A'),
('2', 'สมหมาย แสนดี', 'ข้าราชการ', '789 ถนนลาดพร้าว กรุงเทพฯ', 40000.00, '0898765432', 'ปริญญาโท', 'มหาวิทยาลัย B'),
('3', 'สมปอง รักเรียน', 'เจ้าของธุรกิจ', '123 ถนนสีลม กรุงเทพฯ', 50000.00, '0876543210', 'ปริญญาตรี', 'มหาวิทยาลัย C');

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `family_status_id` varchar(255) NOT NULL,
  `status_description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='สถานภาพครอบครัว';

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`family_status_id`, `status_description`) VALUES
('1', 'โสด'),
('2', 'แต่งงานแล้ว');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `student_id` varchar(13) NOT NULL,
  `student_code` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL COMMENT 'รหัสผ่าน',
  `f_name` varchar(255) NOT NULL COMMENT 'ชื่อ',
  `l_name` varchar(255) NOT NULL COMMENT 'นามสกุล',
  `address` varchar(255) NOT NULL,
  `phone_number` varchar(10) NOT NULL,
  `phone_number_home` varchar(13) NOT NULL,
  `email` varchar(255) NOT NULL,
  `profile_image` longtext DEFAULT NULL,
  `spouse_id` varchar(13) DEFAULT NULL,
  `father_id` varchar(13) DEFAULT NULL,
  `mother_id` varchar(13) DEFAULT NULL,
  `guardian_id` varchar(13) DEFAULT NULL,
  `endorser_id` varchar(13) DEFAULT NULL,
  `department_id` varchar(155) DEFAULT NULL,
  `s_faculty` varchar(255) NOT NULL COMMENT 'คณะ',
  `s_department` varchar(255) NOT NULL COMMENT 'สาขา',
  `status_code_id` varchar(255) DEFAULT NULL,
  `family_status` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='นักศึกษา';

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`student_id`, `student_code`, `password`, `f_name`, `l_name`, `address`, `phone_number`, `phone_number_home`, `email`, `profile_image`, `spouse_id`, `father_id`, `mother_id`, `guardian_id`, `endorser_id`, `department_id`, `s_faculty`, `s_department`, `status_code_id`, `family_status`) VALUES
('1103703175319', '1103703175', '1234', 'กฤติน', 'ทองดี', '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, ''),
('123123123123', '620406400929', '$2y$10$bU5GP9lPAmi5faoPaRhhKeljA8jVSfNPpfe/ZIY.sFC2pcJsVu0dm', 'Trisit', 'Worrakittongau', 'ก ท ม ร้มเกล้า', '0954454289', '-', 'moombabe24468@gmail.com', NULL, '2', '2', '2', 'G234', '2', '1', '1', '1', NULL, 'สมรส'),
('1234', NULL, '$2y$10$W6xv1CHxj923A4SxugJRveHtiex.x1wMXy937ZQyKfkti7nTpYUYy', 'sdasd', 'sdasd', '', '0099999999', '', 'moombabe24468@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, ''),
('123444333', '', '$2y$10$QUaOMyFgOYK6NVV6R7t/fe4aqdEvkAIlwzJw60/oyLj7GG4SNr.Yy', 'sdas', 'asdasd', '', '123122', '', 'moombabe24468@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, ''),
('1234567890123', '1234567890', '$2y$10$i6ykgJ4tRkUmv7TpjG3TUu3UXw75G9FD.KbkMgefXMeoYUweoSkIG', 'กานต์สุดา', 'แสงเทียน', '', '1234567890', '', 'test@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, ''),
('1619400005271', '', '$2y$10$OdMMGVcNZj51LhCv/BBn0.hRj93ayu.lkU48m69MPH9b.tQyz6zdO', 'นิฌา', 'ภุมรินทร์', '', '0813194772', '', 'pummarin2003@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, ''),
('2131231', '620406400925', '$2y$10$i6ykgJ4tRkUmv7TpjG3TUu3UXw75G9FD.KbkMgefXMeoYUweoSkIG', 'dasd', 'sdasds', '', 'sad', '', 'tanapatdaengniam@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, ''),
('2345678901234', '1002', 'password234', 'สมหญิง', 'สบายใจ', '456 ถนนพหลโยธิน กรุงเทพฯ', '0898765432', '', 'somying@email.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '2', ''),
('3456789012345', '1003', 'password345', 'สมศักดิ์', 'สุดยอด', '789 ถนนพลโยธิน กรุงเทพฯ', '0876543210', '', 'anan@email.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', '1', '');

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--

CREATE TABLE `teacher` (
  `officer_id` varchar(13) NOT NULL,
  `password` varchar(255) NOT NULL COMMENT 'รหัสผ่าน',
  `f_name` varchar(255) NOT NULL COMMENT 'ชื่อ',
  `l_name` varchar(255) NOT NULL COMMENT 'นามสกุล',
  `campus` varchar(255) NOT NULL,
  `room_number` varchar(10) NOT NULL,
  `position` varchar(255) NOT NULL,
  `profile_image` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='อาจารย์';

--
-- Dumping data for table `teacher`
--

INSERT INTO `teacher` (`officer_id`, `password`, `f_name`, `l_name`, `campus`, `room_number`, `position`, `profile_image`) VALUES
('1', 'pass123', 'สมชาย', 'วิทยา', 'Main Campus', '101', 'อาจารย์', NULL),
('2', 'pass234', 'สมหญิง', 'ศึกษา', 'City Campus', '202', 'อาจารย์', NULL),
('3', 'pass345', 'อนันต์', 'ครูดี', 'Science Campus', '303', 'หัวหน้าแผนก', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_activities`
--

CREATE TABLE `user_activities` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `activity_name` varchar(255) NOT NULL,
  `max_hours` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_activities`
--

INSERT INTO `user_activities` (`id`, `username`, `activity_id`, `activity_name`, `max_hours`, `created_at`) VALUES
(1, '1234567890123', 3, 'เกี่ยวกับ การให้ความรู้', 4, '2025-03-25 11:04:18'),
(48, '1103703175319', 4, 'เกี่ยวกับ การบริจาคสิ่งของภายนอกมหาวิทยาลัย', 4, '2025-03-25 11:26:24'),
(49, '1103703175319', 8, 'เกี่ยวกับ การออมเงิน กอช.', 5, '2025-03-25 11:27:02'),
(50, '1103703175319', 1, 'เกี่ยวกับ การบริจาคร่างกาย', 9, '2025-03-25 11:32:41'),
(51, '1103703175319', 1, 'เกี่ยวกับ การบริจาคร่างกาย', 9, '2025-03-25 11:32:43'),
(52, '1103703175319', 9, 'เกี่ยวกับ สิ่งแวดล้อม', 4, '2025-03-25 11:34:11');

-- --------------------------------------------------------

--
-- Table structure for table `year`
--

CREATE TABLE `year` (
  `year_id` int(11) NOT NULL,
  `data_start` datetime NOT NULL COMMENT 'วันที่เริ่ม',
  `data_end` datetime NOT NULL COMMENT 'วันที่สิ้นสุด',
  `term` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ปีการศึกษา';

--
-- Dumping data for table `year`
--

INSERT INTO `year` (`year_id`, `data_start`, `data_end`, `term`) VALUES
(2023, '2024-11-01 00:00:00', '2025-05-01 23:59:59', 2),
(2024, '2023-07-01 00:00:00', '2023-12-31 00:00:00', 1),
(2025, '2025-06-01 00:00:00', '2025-10-31 23:59:59', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`department_id`),
  ADD KEY `department_ibfk_1` (`faculty_id`);

--
-- Indexes for table `endorsee`
--
ALTER TABLE `endorsee`
  ADD PRIMARY KEY (`endorser_id`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`faculty_id`);

--
-- Indexes for table `family_status_code`
--
ALTER TABLE `family_status_code`
  ADD PRIMARY KEY (`status_code_id`);

--
-- Indexes for table `father`
--
ALTER TABLE `father`
  ADD PRIMARY KEY (`father_id`);

--
-- Indexes for table `guardian`
--
ALTER TABLE `guardian`
  ADD PRIMARY KEY (`guardian_id`);

--
-- Indexes for table `money`
--
ALTER TABLE `money`
  ADD PRIMARY KEY (`contract_id`,`academic_year`);

--
-- Indexes for table `mother`
--
ALTER TABLE `mother`
  ADD PRIMARY KEY (`mother_id`);

--
-- Indexes for table `new_user_activities`
--
ALTER TABLE `new_user_activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pact`
--
ALTER TABLE `pact`
  ADD PRIMARY KEY (`contract_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `officer_id` (`officer_id`);

--
-- Indexes for table `sibling`
--
ALTER TABLE `sibling`
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `spouse`
--
ALTER TABLE `spouse`
  ADD PRIMARY KEY (`spouse_id`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`family_status_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`student_id`),
  ADD KEY `family_status_id` (`status_code_id`),
  ADD KEY `father_id` (`father_id`),
  ADD KEY `mother_id` (`mother_id`),
  ADD KEY `guardian_id` (`guardian_id`),
  ADD KEY `spouse_id` (`spouse_id`),
  ADD KEY `endorser_id` (`endorser_id`);

--
-- Indexes for table `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`officer_id`);

--
-- Indexes for table `user_activities`
--
ALTER TABLE `user_activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `year`
--
ALTER TABLE `year`
  ADD PRIMARY KEY (`year_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `faculty_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `new_user_activities`
--
ALTER TABLE `new_user_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `pact`
--
ALTER TABLE `pact`
  MODIFY `contract_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_activities`
--
ALTER TABLE `user_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `department`
--
ALTER TABLE `department`
  ADD CONSTRAINT `department_ibfk_1` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`faculty_id`);

--
-- Constraints for table `money`
--
ALTER TABLE `money`
  ADD CONSTRAINT `money_ibfk_1` FOREIGN KEY (`contract_id`) REFERENCES `pact` (`contract_id`);

--
-- Constraints for table `pact`
--
ALTER TABLE `pact`
  ADD CONSTRAINT `pact_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`),
  ADD CONSTRAINT `pact_ibfk_2` FOREIGN KEY (`officer_id`) REFERENCES `teacher` (`officer_id`);

--
-- Constraints for table `sibling`
--
ALTER TABLE `sibling`
  ADD CONSTRAINT `sibling_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`);

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`status_code_id`) REFERENCES `status` (`family_status_id`),
  ADD CONSTRAINT `student_ibfk_2` FOREIGN KEY (`father_id`) REFERENCES `father` (`father_id`),
  ADD CONSTRAINT `student_ibfk_3` FOREIGN KEY (`mother_id`) REFERENCES `mother` (`mother_id`),
  ADD CONSTRAINT `student_ibfk_4` FOREIGN KEY (`guardian_id`) REFERENCES `guardian` (`guardian_id`),
  ADD CONSTRAINT `student_ibfk_5` FOREIGN KEY (`spouse_id`) REFERENCES `spouse` (`spouse_id`),
  ADD CONSTRAINT `student_ibfk_6` FOREIGN KEY (`endorser_id`) REFERENCES `endorsee` (`endorser_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
