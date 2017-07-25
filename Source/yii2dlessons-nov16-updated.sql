-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 16, 2016 at 03:36 PM
-- Server version: 5.6.14
-- PHP Version: 5.5.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `yii2dlessons`
--

-- --------------------------------------------------------

--
-- Table structure for table `db_ads`
--

CREATE TABLE IF NOT EXISTS `db_ads` (
  `ads_id` int(11) NOT NULL AUTO_INCREMENT,
  `lesson_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `content` longtext,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`ads_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `db_ads`
--

INSERT INTO `db_ads` (`ads_id`, `lesson_id`, `admin_id`, `image`, `url`, `content`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 8, 1, 'uploads/ads/image_1.jpg', NULL, 'Test Content', '2016-11-16 07:49:15', '0000-00-00 00:00:00', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `db_schedules`
--

CREATE TABLE IF NOT EXISTS `db_schedules` (
  `schedule_id` int(11) NOT NULL AUTO_INCREMENT,
  `lesson_id` int(11) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `schedule_date` date NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `zip` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`schedule_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `db_schedules`
--

INSERT INTO `db_schedules` (`schedule_id`, `lesson_id`, `instructor_id`, `schedule_date`, `start_time`, `end_time`, `city`, `state`, `zip`, `country`, `status`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 9, 1, '2016-11-15', '10:00:00', '18:00:00', 'Madurai', 'Tamilnadu', '123456', 'India', '1', '2016-11-14 13:48:31', '2016-11-14 14:40:44', 1, 1),
(2, 10, 3, '2016-11-30', '06:30:00', '16:00:00', 'Losangeles', 'California', '90001', 'US', '1', '2016-11-16 12:23:20', '2016-11-16 12:26:10', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `dl_admin`
--

CREATE TABLE IF NOT EXISTS `dl_admin` (
  `admin_id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) DEFAULT '0',
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email` varchar(75) DEFAULT NULL,
  `address1` varchar(200) DEFAULT NULL,
  `address2` varchar(200) DEFAULT NULL,
  `website` varchar(200) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `company_code` varchar(11) DEFAULT NULL,
  `city` varchar(55) DEFAULT NULL,
  `state` varchar(55) DEFAULT NULL,
  `zip` varchar(55) DEFAULT NULL,
  `work_phone` varchar(55) DEFAULT NULL,
  `cell_phone` varchar(55) DEFAULT NULL,
  `notes` text,
  `status` int(5) DEFAULT '0',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `auth_key` varchar(255) DEFAULT NULL,
  `updated_at` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `dl_admin`
--

INSERT INTO `dl_admin` (`admin_id`, `parent_id`, `username`, `password`, `first_name`, `last_name`, `email`, `address1`, `address2`, `website`, `company_name`, `company_code`, `city`, `state`, `zip`, `work_phone`, `cell_phone`, `notes`, `status`, `remember_token`, `created_at`, `modified_at`, `auth_key`, `updated_at`) VALUES
(1, 0, 'admin', 'YWRtaW4xMjM=', 'Admin', 'User', 'admin@driving.com', '', '', '', 'admin123', 'ADM1', '', '', '', '', '232424234', '', 1, '', '2016-07-01 12:41:22', '2016-09-27 11:02:47', 'KkyMAspRraKoSd56UQjtv9t2LjRHD79q', '1476940884'),
(15, 0, 'testman77', 'YWRtaW4xMjM=', '', '', 'testman77@gmail.com', '', '', 'testman77', 'testman77', 'testman77', '', '', '', '', '3453545', '', 1, NULL, '0000-00-00 00:00:00', '2016-10-26 12:55:40', NULL, '1477490067'),
(17, 0, 'testman66', 'dGVzdG1hbjY2', '', '', 'testman66@gmail.com', '', '', '', 'testman66', 'testman66', '', '', '', '', '3453453', '', 1, NULL, '2016-10-26 16:02:26', '2016-10-26 14:02:26', NULL, '2016-10-26 16:08:21');

-- --------------------------------------------------------

--
-- Table structure for table `dl_admin_lessons`
--

CREATE TABLE IF NOT EXISTS `dl_admin_lessons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `disclaimer` longtext NOT NULL,
  `privacy` longtext NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` varchar(255) NOT NULL,
  `updated_at` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `dl_admin_lessons`
--

INSERT INTO `dl_admin_lessons` (`id`, `admin_id`, `lesson_id`, `price`, `disclaimer`, `privacy`, `status`, `created_at`, `updated_at`) VALUES
(8, 1, 6, '45.00', '', '', 1, '2016-10-26 16:08:21', '2016-10-26 16:08:21'),
(9, 1, 7, '22.00', '', '', 1, '2016-10-26 16:08:22', '2016-10-26 16:08:22'),
(10, 1, 8, '17.00', 'Disclaimar : Sun Driving School made its foray into the service industry imparting institutional learning on Car Driving, to start with. ', 'Privacy : Sun Driving School made its foray into the service industry imparting institutional learning on Car Driving, to start with. ', 1, '2016-10-26 16:08:22', '2016-10-28 08:24:25');

-- --------------------------------------------------------

--
-- Table structure for table `dl_instructors`
--

CREATE TABLE IF NOT EXISTS `dl_instructors` (
  `instructor_id` int(10) NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email` varchar(75) DEFAULT NULL,
  `address1` varchar(200) DEFAULT NULL,
  `address2` varchar(200) DEFAULT NULL,
  `website` varchar(200) DEFAULT NULL,
  `city` varchar(55) DEFAULT NULL,
  `state` varchar(55) DEFAULT NULL,
  `zip` varchar(55) DEFAULT NULL,
  `work_phone` varchar(55) DEFAULT NULL,
  `cell_phone` varchar(55) DEFAULT NULL,
  `notes` text,
  `status` int(5) DEFAULT '0',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `auth_key` varchar(255) DEFAULT NULL,
  `updated_at` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`instructor_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `dl_instructors`
--

INSERT INTO `dl_instructors` (`instructor_id`, `admin_id`, `username`, `password`, `first_name`, `last_name`, `email`, `address1`, `address2`, `website`, `city`, `state`, `zip`, `work_phone`, `cell_phone`, `notes`, `status`, `remember_token`, `created_at`, `modified_at`, `auth_key`, `updated_at`) VALUES
(1, 1, 'instructor', 'TVRJek5EVTI=', 'Arivu 1', 'Ajay', 'arivazhagan.pandi@arkinfotec.com', '', '', '', '', '', '', '', '123456', '', 1, NULL, '2016-11-10 12:25:04', '2016-11-10 11:25:04', NULL, '2016-11-16 13:24:30'),
(2, 1, 'instructor2', 'MTIzNDU2', 'Instructor', '2', 'test@test.com', '', '', '', '', '', '', '', '2312312', '', 1, NULL, '2016-11-10 15:24:19', '2016-11-10 14:24:19', NULL, '2016-11-10 15:24:19'),
(3, 1, 'testins1', 'dGVzdGluczE=', 'testins1 FN', 'testins1 LN', 'testins1@gmail.com', '123 wallet street', '', '', '', '', '', '', '24234234234', '', 1, NULL, '2016-11-16 12:14:19', '2016-11-16 11:14:19', NULL, '2016-11-16 12:28:25');

-- --------------------------------------------------------

--
-- Table structure for table `dl_lessons`
--

CREATE TABLE IF NOT EXISTS `dl_lessons` (
  `lesson_id` int(10) NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) NOT NULL,
  `lesson_name` varchar(55) NOT NULL,
  `lesson_desc` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`lesson_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `dl_lessons`
--

INSERT INTO `dl_lessons` (`lesson_id`, `admin_id`, `lesson_name`, `lesson_desc`, `created_at`, `updated_at`) VALUES
(6, 1, '4-Hour Florida Traffic School', '4 hours course', '2016-10-15 10:08:04', '2016-10-15 10:08:04'),
(7, 1, '12 hours course', '12-Hour Advanced Driver Improvement (ADI)', '2016-10-19 14:53:26', '2016-10-19 14:53:26'),
(8, 1, 'New York 6-Hour Point & Insurance Reduction course', 'New York 6-Hour Point & Insurance Reduction course', '2016-10-19 14:55:12', '2016-10-19 14:55:12'),
(9, 1, 'New Jersey Defensive Driving', 'New Jersey Defensive Driving', '2016-10-19 14:55:22', '2016-10-19 14:55:22'),
(10, 1, 'DMV Practice/Prep Test', 'DMV Practice/Prep Test', '2016-10-19 14:55:34', '2016-10-19 14:55:34'),
(11, 1, '4-Hour First Time Driver', '4-Hour First Time Driver', '2016-10-19 14:55:45', '2016-10-19 14:55:45');

-- --------------------------------------------------------

--
-- Table structure for table `dl_payment`
--

CREATE TABLE IF NOT EXISTS `dl_payment` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `scr_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `payment_amount` double(10,2) NOT NULL,
  `payment_type` char(2) NOT NULL,
  `payment_trans_id` varchar(50) NOT NULL,
  `payment_status` int(5) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`payment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dl_student`
--

CREATE TABLE IF NOT EXISTS `dl_student` (
  `student_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(55) NOT NULL,
  `email` varchar(55) NOT NULL,
  `first_name` varchar(55) NOT NULL,
  `middle_name` varchar(55) NOT NULL,
  `last_name` varchar(55) NOT NULL,
  `status` int(5) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dl_student_course`
--

CREATE TABLE IF NOT EXISTS `dl_student_course` (
  `scr_id` int(11) NOT NULL AUTO_INCREMENT,
  `ads_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `scr_disclaimer_status` int(11) NOT NULL,
  `scr_paid_status` int(11) NOT NULL,
  `scr_skpststus` int(11) NOT NULL,
  `scr_skip_url` varchar(255) NOT NULL,
  `scr_registerdate` date NOT NULL,
  `scr_completed_date` date NOT NULL,
  `scr_completed_status` int(11) NOT NULL,
  `scr_certificate_serialno` varchar(255) NOT NULL,
  `scr_certificate_status` int(11) NOT NULL,
  `scr_certificate_send_date` date NOT NULL,
  `scr_status` int(11) NOT NULL,
  `preferred_days` varchar(255) NOT NULL,
  `preferred_time` varchar(255) NOT NULL,
  `additional_infos` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`scr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dl_student_profile`
--

CREATE TABLE IF NOT EXISTS `dl_student_profile` (
  `std_prof_id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `gender` char(1) NOT NULL,
  `address1` varchar(255) NOT NULL,
  `address2` varchar(255) NOT NULL,
  `city` varchar(55) NOT NULL,
  `state` varchar(55) NOT NULL,
  `zip` varchar(55) NOT NULL,
  `phone` varchar(55) NOT NULL,
  `dob` date NOT NULL,
  `permit_num` varchar(255) NOT NULL,
  `language` varchar(10) NOT NULL,
  `hear_about_this` varchar(255) NOT NULL,
  `referred_by` varchar(255) NOT NULL,
  `payer_firstname` varchar(55) NOT NULL,
  `payer_lastname` varchar(55) NOT NULL,
  `payer_address1` varchar(255) NOT NULL,
  `payer_address2` varchar(255) NOT NULL,
  `payer_city` varchar(50) NOT NULL,
  `payer_state` varchar(50) NOT NULL,
  `payer_zip` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`std_prof_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dl_super_admin`
--

CREATE TABLE IF NOT EXISTS `dl_super_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `modified_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` int(5) DEFAULT '0',
  `auth_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updated_at` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dmv_super_admin_email_unique` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `dl_super_admin`
--

INSERT INTO `dl_super_admin` (`id`, `username`, `email`, `password`, `modified_at`, `remember_token`, `status`, `auth_key`, `updated_at`) VALUES
(1, 'webadmin', 'bartjr@americansafetyinstitute.com', 'YWRtaW4xMjM=', '2016-06-07 17:18:03', '', 1, 'KkyMAspRraKoSd56UQjtv9t2LjRHDe9q', NULL),
(2, 'subadmin1', 'vasanth@arkinfotec.com', 'YWRtaW4xMjM=', '2016-07-27 05:49:24', '', 1, '1wNT8Aqv7Lqsh0KIhdkzPdhP8wwaDn9Z', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migration`
--

CREATE TABLE IF NOT EXISTS `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1474952874),
('m130524_201442_init', 1474952879);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `password_reset_token` (`password_reset_token`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `status`, `created_at`, `updated_at`) VALUES
(1, 'testman1', 'KkyMAspRraKoSd56UQjtv9t2LjRHDe9q', '$2y$13$CYDxVI3RO3wfJh4lugYwPOCPrqChv35ii8qwjxg5yX9Qvr9L7kE3e', NULL, 'testman1@gmail.com', 10, 1474954589, 1474954589),
(2, 'testman2', '1wNT8Aqv7Lqsh0KIhdkzPdhP8wwaDn9Z', '$2y$13$gv1VOCkS1pGgpRXOJqIqceIOJLb8rhGKzo7BOkUZePcaddq9BeaBC', NULL, 'testman2@gmail.com', 10, 1474958908, 1474958908);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
