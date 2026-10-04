-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 03, 2026 at 11:01 AM
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
-- Database: `projecttest`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_tbl`
--

CREATE TABLE `admin_tbl` (
  `account_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `availabilitystatus_tbl`
--

CREATE TABLE `availabilitystatus_tbl` (
  `availabilitystatus_id` int(11) NOT NULL,
  `availability_status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `building_tbl`
--

CREATE TABLE `building_tbl` (
  `building_id` int(11) NOT NULL,
  `building_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classday_tbl`
--

CREATE TABLE `classday_tbl` (
  `classday_id` int(11) NOT NULL,
  `day` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classhour_tbl`
--

CREATE TABLE `classhour_tbl` (
  `classhour_id` int(11) NOT NULL,
  `start` time NOT NULL,
  `end` time NOT NULL,
  `timeofday_id` int(11) NOT NULL,
  `start_end` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classtimeofday_tbl`
--

CREATE TABLE `classtimeofday_tbl` (
  `timeofday_id` int(11) NOT NULL,
  `time_of_day` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `confirmedrequests_tbl`
--

CREATE TABLE `confirmedrequests_tbl` (
  `reservation_id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `reviewed_date` date NOT NULL,
  `reviewed_by` int(11) UNSIGNED NOT NULL,
  `status_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course_tbl`
--

CREATE TABLE `course_tbl` (
  `course_id` int(11) NOT NULL,
  `course` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `floor_tbl`
--

CREATE TABLE `floor_tbl` (
  `floor_id` int(11) NOT NULL,
  `floor_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `instructor_tbl`
--

CREATE TABLE `instructor_tbl` (
  `account_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `instructor_tbl`
--
DELIMITER $$
CREATE TRIGGER `trg_instructor_role_ins` BEFORE INSERT ON `instructor_tbl` FOR EACH ROW BEGIN
  IF (SELECT role_id FROM users_tbl WHERE account_id = NEW.account_id) <> 3
     OR NOT EXISTS (SELECT 1 FROM users_tbl WHERE account_id = NEW.account_id) THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'User must have role_id = 3 to be an instructor';
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `purpose_tbl`
--

CREATE TABLE `purpose_tbl` (
  `purpose_id` int(11) NOT NULL,
  `purpose` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `requeststatus_tbl`
--

CREATE TABLE `requeststatus_tbl` (
  `status_id` int(11) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `request_tbl`
--

CREATE TABLE `request_tbl` (
  `request_id` int(11) NOT NULL,
  `account_id` int(11) UNSIGNED NOT NULL,
  `submission_date` date NOT NULL,
  `reservation_date` date NOT NULL,
  `schedule_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `purpose_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_tbl`
--

CREATE TABLE `role_tbl` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roomtype_tbl`
--

CREATE TABLE `roomtype_tbl` (
  `roomtype_id` int(11) NOT NULL,
  `room_type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `room_tbl`
--

CREATE TABLE `room_tbl` (
  `room_id` int(11) NOT NULL,
  `building_id` int(11) DEFAULT NULL,
  `floor_id` int(11) DEFAULT NULL,
  `room_number` varchar(255) NOT NULL,
  `roomtype_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedule_tbl`
--

CREATE TABLE `schedule_tbl` (
  `schedule_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `classday_id` int(11) NOT NULL,
  `classhour_id` int(11) NOT NULL,
  `reservation_id` int(11) DEFAULT NULL,
  `availabilitystatus_id` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schoolyear_tbl`
--

CREATE TABLE `schoolyear_tbl` (
  `schoolyear_id` int(11) NOT NULL,
  `school_year` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `section_tbl`
--

CREATE TABLE `section_tbl` (
  `section_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `schoolyear_id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `semester_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `semester_tbl`
--

CREATE TABLE `semester_tbl` (
  `semester_id` int(11) NOT NULL,
  `semester` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `userstatus_tbl`
--

CREATE TABLE `userstatus_tbl` (
  `userstatus_id` tinyint(1) NOT NULL,
  `user_status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_tbl`
--

CREATE TABLE `users_tbl` (
  `account_id` int(11) UNSIGNED NOT NULL,
  `ncst_id` char(10) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `given_name` varchar(255) NOT NULL,
  `ncst_email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  `otp` varchar(10) DEFAULT NULL,
  `otp_expiry` datetime DEFAULT NULL,
  `must_change_password` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `users_tbl`
--
DELIMITER $$
CREATE TRIGGER `trg_users_after_insert` AFTER INSERT ON `users_tbl` FOR EACH ROW BEGIN
  IF NEW.role_id = 1 THEN
    INSERT INTO admin_tbl (account_id) VALUES (NEW.account_id);
  ELSEIF NEW.role_id = 3 THEN
    INSERT INTO instructor_tbl (account_id) VALUES (NEW.account_id);
  END IF;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_tbl`
--
ALTER TABLE `admin_tbl`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `availabilitystatus_tbl`
--
ALTER TABLE `availabilitystatus_tbl`
  ADD PRIMARY KEY (`availabilitystatus_id`),
  ADD UNIQUE KEY `availability_status` (`availability_status`);

--
-- Indexes for table `building_tbl`
--
ALTER TABLE `building_tbl`
  ADD PRIMARY KEY (`building_id`);

--
-- Indexes for table `classday_tbl`
--
ALTER TABLE `classday_tbl`
  ADD PRIMARY KEY (`classday_id`);

--
-- Indexes for table `classhour_tbl`
--
ALTER TABLE `classhour_tbl`
  ADD PRIMARY KEY (`classhour_id`),
  ADD KEY `Timeofday_ID` (`timeofday_id`);

--
-- Indexes for table `classtimeofday_tbl`
--
ALTER TABLE `classtimeofday_tbl`
  ADD PRIMARY KEY (`timeofday_id`);

--
-- Indexes for table `confirmedrequests_tbl`
--
ALTER TABLE `confirmedrequests_tbl`
  ADD PRIMARY KEY (`reservation_id`),
  ADD UNIQUE KEY `request_id` (`request_id`),
  ADD KEY `confirmedrequests_status_id_fk` (`status_id`),
  ADD KEY `confirmedrequests_reviwed_by_admin_id_fk` (`reviewed_by`);

--
-- Indexes for table `course_tbl`
--
ALTER TABLE `course_tbl`
  ADD PRIMARY KEY (`course_id`),
  ADD UNIQUE KEY `course` (`course`);

--
-- Indexes for table `floor_tbl`
--
ALTER TABLE `floor_tbl`
  ADD PRIMARY KEY (`floor_id`);

--
-- Indexes for table `instructor_tbl`
--
ALTER TABLE `instructor_tbl`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `purpose_tbl`
--
ALTER TABLE `purpose_tbl`
  ADD PRIMARY KEY (`purpose_id`),
  ADD UNIQUE KEY `purpose` (`purpose`);

--
-- Indexes for table `requeststatus_tbl`
--
ALTER TABLE `requeststatus_tbl`
  ADD PRIMARY KEY (`status_id`),
  ADD UNIQUE KEY `status` (`status`);

--
-- Indexes for table `request_tbl`
--
ALTER TABLE `request_tbl`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `schedule_id` (`schedule_id`),
  ADD KEY `section_id` (`section_id`),
  ADD KEY `purpose_id` (`purpose_id`),
  ADD KEY `status_id` (`status_id`),
  ADD KEY `request_tbl_account_id` (`account_id`);

--
-- Indexes for table `role_tbl`
--
ALTER TABLE `role_tbl`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `roomtype_tbl`
--
ALTER TABLE `roomtype_tbl`
  ADD PRIMARY KEY (`roomtype_id`);

--
-- Indexes for table `room_tbl`
--
ALTER TABLE `room_tbl`
  ADD PRIMARY KEY (`room_id`),
  ADD KEY `room_tbl_building_id` (`building_id`),
  ADD KEY `room_tbl_floor_id` (`floor_id`),
  ADD KEY `room_tbl_roomtype_id` (`roomtype_id`);

--
-- Indexes for table `schedule_tbl`
--
ALTER TABLE `schedule_tbl`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `schedule_tbl_reservation_id` (`reservation_id`),
  ADD KEY `schedule_tbl_room_id` (`room_id`),
  ADD KEY `schedule_tbl_classday_id` (`classday_id`),
  ADD KEY `schedule_tbl_classhour_id` (`classhour_id`),
  ADD KEY `schedule_tbl_availabilitystatus_id` (`availabilitystatus_id`);

--
-- Indexes for table `schoolyear_tbl`
--
ALTER TABLE `schoolyear_tbl`
  ADD PRIMARY KEY (`schoolyear_id`);

--
-- Indexes for table `section_tbl`
--
ALTER TABLE `section_tbl`
  ADD PRIMARY KEY (`section_id`),
  ADD UNIQUE KEY `semester_name` (`semester_name`),
  ADD KEY `schoolyear_id` (`schoolyear_id`),
  ADD KEY `semester_id` (`semester_id`),
  ADD KEY `section_tbl_ibfk_3` (`course_id`);

--
-- Indexes for table `semester_tbl`
--
ALTER TABLE `semester_tbl`
  ADD PRIMARY KEY (`semester_id`);

--
-- Indexes for table `userstatus_tbl`
--
ALTER TABLE `userstatus_tbl`
  ADD PRIMARY KEY (`userstatus_id`);

--
-- Indexes for table `users_tbl`
--
ALTER TABLE `users_tbl`
  ADD PRIMARY KEY (`account_id`),
  ADD UNIQUE KEY `uq_users_ncst_id` (`ncst_id`),
  ADD UNIQUE KEY `uq_users_email` (`ncst_email`),
  ADD KEY `user_tbl_role_id_fk` (`role_id`),
  ADD KEY `user_tbl_user_active_id` (`is_active`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `availabilitystatus_tbl`
--
ALTER TABLE `availabilitystatus_tbl`
  MODIFY `availabilitystatus_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `building_tbl`
--
ALTER TABLE `building_tbl`
  MODIFY `building_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `classday_tbl`
--
ALTER TABLE `classday_tbl`
  MODIFY `classday_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `classhour_tbl`
--
ALTER TABLE `classhour_tbl`
  MODIFY `classhour_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `classtimeofday_tbl`
--
ALTER TABLE `classtimeofday_tbl`
  MODIFY `timeofday_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `confirmedrequests_tbl`
--
ALTER TABLE `confirmedrequests_tbl`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `course_tbl`
--
ALTER TABLE `course_tbl`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `floor_tbl`
--
ALTER TABLE `floor_tbl`
  MODIFY `floor_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purpose_tbl`
--
ALTER TABLE `purpose_tbl`
  MODIFY `purpose_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `requeststatus_tbl`
--
ALTER TABLE `requeststatus_tbl`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `request_tbl`
--
ALTER TABLE `request_tbl`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role_tbl`
--
ALTER TABLE `role_tbl`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roomtype_tbl`
--
ALTER TABLE `roomtype_tbl`
  MODIFY `roomtype_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `room_tbl`
--
ALTER TABLE `room_tbl`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `schedule_tbl`
--
ALTER TABLE `schedule_tbl`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `schoolyear_tbl`
--
ALTER TABLE `schoolyear_tbl`
  MODIFY `schoolyear_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `section_tbl`
--
ALTER TABLE `section_tbl`
  MODIFY `section_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `semester_tbl`
--
ALTER TABLE `semester_tbl`
  MODIFY `semester_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `userstatus_tbl`
--
ALTER TABLE `userstatus_tbl`
  MODIFY `userstatus_id` tinyint(1) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users_tbl`
--
ALTER TABLE `users_tbl`
  MODIFY `account_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_tbl`
--
ALTER TABLE `admin_tbl`
  ADD CONSTRAINT `admin_user_fk` FOREIGN KEY (`account_id`) REFERENCES `users_tbl` (`account_id`);

--
-- Constraints for table `classhour_tbl`
--
ALTER TABLE `classhour_tbl`
  ADD CONSTRAINT `classhour_tbl_ibfk_1` FOREIGN KEY (`Timeofday_ID`) REFERENCES `classtimeofday_tbl` (`timeofday_id`);

--
-- Constraints for table `confirmedrequests_tbl`
--
ALTER TABLE `confirmedrequests_tbl`
  ADD CONSTRAINT `confirmedrequests_request_id_fk` FOREIGN KEY (`request_id`) REFERENCES `request_tbl` (`request_id`),
  ADD CONSTRAINT `confirmedrequests_reviwed_by_admin_id_fk` FOREIGN KEY (`reviewed_by`) REFERENCES `admin_tbl` (`account_id`),
  ADD CONSTRAINT `confirmedrequests_status_id_fk` FOREIGN KEY (`status_id`) REFERENCES `requeststatus_tbl` (`status_id`);

--
-- Constraints for table `instructor_tbl`
--
ALTER TABLE `instructor_tbl`
  ADD CONSTRAINT `instructor_user_fk` FOREIGN KEY (`account_id`) REFERENCES `users_tbl` (`account_id`);

--
-- Constraints for table `request_tbl`
--
ALTER TABLE `request_tbl`
  ADD CONSTRAINT `request_tbl_account_id` FOREIGN KEY (`account_id`) REFERENCES `instructor_tbl` (`account_id`),
  ADD CONSTRAINT `request_tbl_ibfk_2` FOREIGN KEY (`schedule_id`) REFERENCES `schedule_tbl` (`Schedule_ID`),
  ADD CONSTRAINT `request_tbl_ibfk_3` FOREIGN KEY (`section_id`) REFERENCES `section_tbl` (`section_id`),
  ADD CONSTRAINT `request_tbl_ibfk_4` FOREIGN KEY (`purpose_id`) REFERENCES `purpose_tbl` (`purpose_id`),
  ADD CONSTRAINT `request_tbl_ibfk_5` FOREIGN KEY (`status_id`) REFERENCES `requeststatus_tbl` (`status_id`);

--
-- Constraints for table `room_tbl`
--
ALTER TABLE `room_tbl`
  ADD CONSTRAINT `room_tbl_building_id` FOREIGN KEY (`building_id`) REFERENCES `building_tbl` (`building_id`),
  ADD CONSTRAINT `room_tbl_floor_id` FOREIGN KEY (`floor_id`) REFERENCES `floor_tbl` (`floor_id`),
  ADD CONSTRAINT `room_tbl_roomtype_id` FOREIGN KEY (`roomtype_id`) REFERENCES `roomtype_tbl` (`roomtype_id`);

--
-- Constraints for table `schedule_tbl`
--
ALTER TABLE `schedule_tbl`
  ADD CONSTRAINT `schedule_tbl_availabilitystatus_id` FOREIGN KEY (`availabilitystatus_id`) REFERENCES `availabilitystatus_tbl` (`availabilitystatus_id`),
  ADD CONSTRAINT `schedule_tbl_classday_id` FOREIGN KEY (`classday_id`) REFERENCES `classday_tbl` (`classday_id`),
  ADD CONSTRAINT `schedule_tbl_classhour_id` FOREIGN KEY (`classhour_id`) REFERENCES `classhour_tbl` (`classhour_id`),
  ADD CONSTRAINT `schedule_tbl_reservation_id` FOREIGN KEY (`reservation_id`) REFERENCES `confirmedrequests_tbl` (`reservation_id`),
  ADD CONSTRAINT `schedule_tbl_room_id` FOREIGN KEY (`room_id`) REFERENCES `room_tbl` (`room_id`);

--
-- Constraints for table `section_tbl`
--
ALTER TABLE `section_tbl`
  ADD CONSTRAINT `section_tbl_ibfk_1` FOREIGN KEY (`schoolyear_id`) REFERENCES `schoolyear_tbl` (`schoolyear_id`),
  ADD CONSTRAINT `section_tbl_ibfk_2` FOREIGN KEY (`semester_id`) REFERENCES `semester_tbl` (`semester_id`),
  ADD CONSTRAINT `section_tbl_ibfk_3` FOREIGN KEY (`course_id`) REFERENCES `course_tbl` (`course_id`);

--
-- Constraints for table `users_tbl`
--
ALTER TABLE `users_tbl`
  ADD CONSTRAINT `user_tbl_role_id_fk` FOREIGN KEY (`role_id`) REFERENCES `role_tbl` (`role_id`),
  ADD CONSTRAINT `user_tbl_user_active_id` FOREIGN KEY (`is_active`) REFERENCES `userstatus_tbl` (`userstatus_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
