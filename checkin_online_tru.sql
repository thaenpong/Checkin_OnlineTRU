-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 17, 2024 at 08:24 PM
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
-- Database: `checkin_online_tru`
--

-- --------------------------------------------------------

--
-- Table structure for table `branchs`
--

CREATE TABLE `branchs` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branchs`
--

INSERT INTO `branchs` (`id`, `name`, `is_active`) VALUES
(1, 'คอมพิวเตอร์ธุรกิจดิจิทัล', 1),
(2, 'เทคโนโลยีการจัดการอุตสาหกรรม', 1),
(3, 'การจัดการ', 1),
(4, 'บัญชี', 1);

-- --------------------------------------------------------

--
-- Table structure for table `checkin`
--

CREATE TABLE `checkin` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `sessions_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `register`
--

CREATE TABLE `register` (
  `id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `year_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `created_date` date NOT NULL DEFAULT current_timestamp(),
  `created_time` time NOT NULL DEFAULT current_timestamp(),
  `end_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `sessions`
--
DELIMITER $$
CREATE TRIGGER `before_insert_sessions` BEFORE INSERT ON `sessions` FOR EACH ROW SET NEW.end_time = DATE_ADD(NOW(), INTERVAL 15 MINUTE)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `id_card` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `year_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `code`, `id_card`, `name`, `branch_id`, `year_id`) VALUES
(2, '6401102053104', '1103700405423', 'นางสาว ภูษณิศา จำรัส', 1, 1),
(3, '6401102053105', '1103700405424', 'นางสาว กชกร พรมพลายแก้ว', 1, 1),
(4, '6401102053108', '1103700405425', 'นางสาว จุฑาทิพย์ นันตากาศ', 1, 1),
(5, '6402202057005', '1103700405426', 'นาย ณัทกร สมศรีชัย', 1, 1),
(6, '6402202057006', '1103700405427', 'นางสาว จิณณ์ณิตา กรัณย์พิริยะ', 1, 1),
(7, '6501202057501', '1103700405428', 'นาย สฏวรรษ ไชยเกิด', 1, 1),
(8, '6402202057003', '1103700405429', 'นาย วรไตร รันนารัตน์', 1, 1),
(9, '6402202057004', '1103700405430', 'นางสาว ปรางทิพย์ ทนันชัย', 1, 1),
(10, '6402202057001', '1103700405431', 'นาย อภิรักษ์ อินทารส', 1, 1),
(11, '6402202057002', '1103700405432', 'นางสาว รัญชิดา ชมภูงาม', 1, 1),
(12, '6501102057502', '1103700405433', 'นางสาว กรกวรรณ ทัศนพันธ์เพชร', 1, 1),
(13, '6501102057503', '1103700405434', 'นาย อภิสิทธิ์ ขันนา', 1, 1),
(14, '6501102057504', '1103700405435', 'นางสาว ศุภรัตน์ ตาธารา', 1, 1),
(15, '6602102057001', '1103700405436', 'นางสาว จุฑามาศ ต๊ะปัญญา', 1, 1),
(16, '6602102057005', '1103700405437', 'นาย ภูริช ต้วมสูงเนิน', 1, 1),
(17, '6602102057007', '1103700405438', 'นางสาว ลลนา เล่าสกุลม', 1, 1),
(18, '6602102057008', '1103700405439', 'นางสาว ดวงใจ แก้วเสถียร', 1, 1),
(19, '6602102057009', '1103700405440', 'นาย ปฏิภาณร์ ทิตา', 1, 1),
(20, '6602102057011', '1103700405441', 'นาย ปิยะภัทร ก่อเกิด', 1, 1),
(21, '6602102057012', '1103700405442', 'นาย นพเก้า คำฤาเกียน', 1, 1),
(22, '6602202057001', '1103700405443', 'นาย สัญชัย ฐานโพธิ์', 1, 1),
(23, '6602202057002', '1103700405444', 'นางสาว จีรประภา อิ้งศิลป์ศรีกุล', 1, 1),
(24, '6602202057003', '1103700405445', 'นาย อานนท์ เนื่องกระโทก', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `code`, `name`, `branch_id`, `is_active`) VALUES
(1, '251202167', 'เทคโนโลยีฮาร์ดแวร์และซอฟต์แวร์ระบบ', 1, 1),
(2, '251204168', 'การออกแบบงานกราฟิกสำหรับธุรกิจดิจิทัล', 1, 1),
(3, '252201169', 'เครือข่ายคอมพิวเตอร์', 1, 1),
(4, '252202170', 'การเขียนโปรแกรมเชิงวัตถุ', 1, 1),
(5, '252203171', 'แอนิเมชันสำหรับธุรกิจดิจิทัล', 1, 1),
(6, '252206172', 'โครงสร้างข้อมูลและอัลกอริทึมเพื่องานธุรกิจ', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `role` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `code`, `name`, `role`, `is_active`) VALUES
(1, '650101', 'สงกรานต์ อินขัน', 1, 1),
(2, '650102', 'อุเทน ว่องไว', 1, 1),
(3, '650103', 'ทินกร สาระเวียง', 1, 1),
(4, '650104', 'เวโรจน์ พงษ์บุพศิริกุล', 1, 1),
(5, '650105', 'อำนาจ พงษ์กลาง', 1, 1),
(6, '650106', 'ธีรนันท์ เงินกระโทก', 1, 1),
(7, '650107', 'ปทุมวัลย์ เตโช', 1, 1),
(8, '650108', 'ดร.จอมขวัญ ศุภศิริกิจเจริญ', 1, 1),
(9, '650109', 'ดร.ฟ้าวิกร อินลวง', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `teacher_role`
--

CREATE TABLE `teacher_role` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teacher_role`
--

INSERT INTO `teacher_role` (`id`, `name`) VALUES
(1, 'อาจารย์'),
(2, 'อาจารย์พิเศษ');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` int(11) NOT NULL,
  `teacher` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `teacher`) VALUES
(1, '650101', '0123', 1, 1),
(2, '650102', '0123', 2, 2),
(3, '650103', '0123', 2, 3),
(4, '650104', '0123', 2, 4),
(5, '650105', '0123', 2, 5),
(6, '650106', '0123', 2, 6),
(7, '650107', '0123', 1, 7),
(8, '650108', '0123', 1, 8),
(9, '650109', '0123', 1, 9);

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`id`, `name`) VALUES
(1, 'admin'),
(2, 'user');

-- --------------------------------------------------------

--
-- Table structure for table `years`
--

CREATE TABLE `years` (
  `id` int(11) NOT NULL,
  `name` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `years`
--

INSERT INTO `years` (`id`, `name`) VALUES
(1, 2565);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branchs`
--
ALTER TABLE `branchs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uc_branch_name` (`name`);

--
-- Indexes for table `checkin`
--
ALTER TABLE `checkin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_id` (`sessions_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `register`
--
ALTER TABLE `register`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `year_id` (`year_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`,`id_card`),
  ADD KEY `branch_id` (`branch_id`),
  ADD KEY `year_id` (`year_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uc_subject_code` (`code`),
  ADD KEY `branch_id` (`branch_id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_code` (`code`),
  ADD KEY `role` (`role`);

--
-- Indexes for table `teacher_role`
--
ALTER TABLE `teacher_role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role` (`role`),
  ADD KEY `teacher` (`teacher`);

--
-- Indexes for table `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `years`
--
ALTER TABLE `years`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branchs`
--
ALTER TABLE `branchs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `checkin`
--
ALTER TABLE `checkin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `register`
--
ALTER TABLE `register`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `teacher_role`
--
ALTER TABLE `teacher_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user_role`
--
ALTER TABLE `user_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `years`
--
ALTER TABLE `years`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `checkin`
--
ALTER TABLE `checkin`
  ADD CONSTRAINT `checkin_ibfk_1` FOREIGN KEY (`sessions_id`) REFERENCES `sessions` (`id`),
  ADD CONSTRAINT `checkin_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);

--
-- Constraints for table `register`
--
ALTER TABLE `register`
  ADD CONSTRAINT `register_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`),
  ADD CONSTRAINT `register_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`);

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`),
  ADD CONSTRAINT `sessions_ibfk_2` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`),
  ADD CONSTRAINT `sessions_ibfk_3` FOREIGN KEY (`year_id`) REFERENCES `years` (`id`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branchs` (`id`),
  ADD CONSTRAINT `students_ibfk_2` FOREIGN KEY (`year_id`) REFERENCES `years` (`id`);

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branchs` (`id`);

--
-- Constraints for table `teachers`
--
ALTER TABLE `teachers`
  ADD CONSTRAINT `teachers_ibfk_1` FOREIGN KEY (`role`) REFERENCES `teacher_role` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role`) REFERENCES `user_role` (`id`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`teacher`) REFERENCES `teachers` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
