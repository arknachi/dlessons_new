/*
SQLyog Ultimate v8.55 
MySQL - 5.6.14 : Database - yii2dlessons
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`yii2dlessons` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `yii2dlessons`;

/*Table structure for table `db_ads` */

DROP TABLE IF EXISTS `db_ads`;

CREATE TABLE `db_ads` (
  `ads_id` int(11) NOT NULL AUTO_INCREMENT,
  `lesson_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `content` longtext,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `isDeleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ads_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

/*Data for the table `db_ads` */

insert  into `db_ads`(`ads_id`,`lesson_id`,`admin_id`,`file_name`,`image`,`url`,`content`,`created_at`,`updated_at`,`created_by`,`updated_by`,`isDeleted`) values (1,8,1,'image_1.jpg','uploads/ads/image_1.jpg',NULL,'Test Content','2016-11-21 11:23:15','0000-00-00 00:00:00',1,NULL,0),(2,7,1,'image_1.jpg','uploads/ads/image_1.jpg',NULL,'Test Content','2016-11-21 17:49:23','0000-00-00 00:00:00',1,NULL,1);

/*Table structure for table `db_schedules` */

DROP TABLE IF EXISTS `db_schedules`;

CREATE TABLE `db_schedules` (
  `schedule_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL,
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
  `isDeleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`schedule_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `db_schedules` */

insert  into `db_schedules`(`schedule_id`,`admin_id`,`lesson_id`,`instructor_id`,`schedule_date`,`start_time`,`end_time`,`city`,`state`,`zip`,`country`,`status`,`created_at`,`updated_at`,`created_by`,`updated_by`,`isDeleted`) values (1,1,9,1,'2016-11-15','10:00:00','18:00:00','Madurai','Tamilnadu','123456','India','1','2016-11-14 13:48:31','2016-11-14 14:40:44',1,1,0),(2,1,10,3,'2016-11-30','06:30:00','16:00:00','Losangeles','California','90001','US','1','2016-11-16 12:23:20','2016-11-16 12:26:10',1,1,0),(3,1,9,3,'2016-11-22','05:00:00','07:00:00','Losangeles','California','90001','US','1','2016-11-17 14:32:34','2016-11-17 14:32:34',1,NULL,0);

/*Table structure for table `dl_admin` */

DROP TABLE IF EXISTS `dl_admin`;

CREATE TABLE `dl_admin` (
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
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

/*Data for the table `dl_admin` */

insert  into `dl_admin`(`admin_id`,`parent_id`,`username`,`password`,`first_name`,`last_name`,`email`,`address1`,`address2`,`website`,`company_name`,`company_code`,`city`,`state`,`zip`,`work_phone`,`cell_phone`,`notes`,`status`,`remember_token`,`created_at`,`modified_at`,`auth_key`,`updated_at`) values (1,0,'admin','YWRtaW4xMjM=','Admin','User','admin@driving.com','','','','admin123','ADM1','','','','','232424234','',1,'','2016-07-01 12:41:22','2016-09-27 16:32:47','KkyMAspRraKoSd56UQjtv9t2LjRHD79q','1476940884'),(15,0,'testman77','YWRtaW4xMjM=','','','testman77@gmail.com','','','testman77','testman77','testman77','','','','','3453545','',1,NULL,'0000-00-00 00:00:00','2016-10-26 18:25:40',NULL,'1477490067'),(17,0,'testman66','dGVzdG1hbjY2','','','testman66@gmail.com','','','','testman66','testman66','','','','','3453453','',1,NULL,'2016-10-26 16:02:26','2016-10-26 19:32:26',NULL,'2016-10-26 16:08:21');

/*Table structure for table `dl_admin_lessons` */

DROP TABLE IF EXISTS `dl_admin_lessons`;

CREATE TABLE `dl_admin_lessons` (
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

/*Data for the table `dl_admin_lessons` */

insert  into `dl_admin_lessons`(`id`,`admin_id`,`lesson_id`,`price`,`disclaimer`,`privacy`,`status`,`created_at`,`updated_at`) values (8,1,6,'45.00','','',1,'2016-10-26 16:08:21','2016-10-26 16:08:21'),(9,1,7,'22.00','','',1,'2016-10-26 16:08:22','2016-10-26 16:08:22'),(10,1,8,'17.00','Disclaimar : Sun Driving School made its foray into the service industry imparting institutional learning on Car Driving, to start with. ','Privacy : Sun Driving School made its foray into the service industry imparting institutional learning on Car Driving, to start with. ',1,'2016-10-26 16:08:22','2016-10-28 08:24:25');

/*Table structure for table `dl_instructors` */

DROP TABLE IF EXISTS `dl_instructors`;

CREATE TABLE `dl_instructors` (
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
  `isDeleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`instructor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `dl_instructors` */

insert  into `dl_instructors`(`instructor_id`,`admin_id`,`username`,`password`,`first_name`,`last_name`,`email`,`address1`,`address2`,`website`,`city`,`state`,`zip`,`work_phone`,`cell_phone`,`notes`,`status`,`remember_token`,`created_at`,`modified_at`,`auth_key`,`updated_at`,`isDeleted`) values (1,1,'instructor','TVRJek5EVTI=','Arivu 1','Ajay','arivazhagan.pandi@arkinfotec.com','','','','','','','','123456','',1,NULL,'2016-11-10 12:25:04','2016-11-10 16:55:04',NULL,'2016-11-16 13:24:30',0),(2,1,'instructor2','MTIzNDU2','Instructor','2','test@test.com','','','','','','','','2312312','',1,NULL,'2016-11-10 15:24:19','2016-11-10 19:54:19',NULL,'2016-11-10 15:24:19',0),(3,1,'testins1','dGVzdGluczE=','testins1 FN','testins1 LN','testins1@gmail.com','123 wallet street','','','','','','','24234234234','',1,NULL,'2016-11-16 12:14:19','2016-11-16 16:44:19',NULL,'2016-11-16 12:28:25',0);

/*Table structure for table `dl_lessons` */

DROP TABLE IF EXISTS `dl_lessons`;

CREATE TABLE `dl_lessons` (
  `lesson_id` int(10) NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) NOT NULL,
  `lesson_name` varchar(55) NOT NULL,
  `lesson_desc` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`lesson_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

/*Data for the table `dl_lessons` */

insert  into `dl_lessons`(`lesson_id`,`admin_id`,`lesson_name`,`lesson_desc`,`created_at`,`updated_at`) values (6,1,'4-Hour Florida Traffic School','4 hours course','2016-10-15 10:08:04','2016-10-15 10:08:04'),(7,1,'12 hours course','12-Hour Advanced Driver Improvement (ADI)','2016-10-19 14:53:26','2016-10-19 14:53:26'),(8,1,'New York 6-Hour Point & Insurance Reduction course','New York 6-Hour Point & Insurance Reduction course','2016-10-19 14:55:12','2016-10-19 14:55:12'),(9,1,'New Jersey Defensive Driving','New Jersey Defensive Driving','2016-10-19 14:55:22','2016-10-19 14:55:22'),(10,1,'DMV Practice/Prep Test','DMV Practice/Prep Test','2016-10-19 14:55:34','2016-10-19 14:55:34'),(11,1,'4-Hour First Time Driver','4-Hour First Time Driver','2016-10-19 14:55:45','2016-10-19 14:55:45');

/*Table structure for table `dl_payment` */

DROP TABLE IF EXISTS `dl_payment`;

CREATE TABLE `dl_payment` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `scr_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `payment_amount` double(10,2) NOT NULL,
  `payment_type` char(2) NOT NULL,
  `payment_trans_id` varchar(50) NOT NULL,
  `payment_status` int(5) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`payment_id`),
  KEY `FK_dl_payment` (`scr_id`),
  KEY `FK_dl_payment_student_id` (`student_id`),
  CONSTRAINT `FK_dl_payment` FOREIGN KEY (`scr_id`) REFERENCES `dl_student_course` (`scr_id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_dl_payment_student_id` FOREIGN KEY (`student_id`) REFERENCES `dl_student` (`student_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `dl_payment` */

insert  into `dl_payment`(`payment_id`,`scr_id`,`student_id`,`payment_amount`,`payment_type`,`payment_trans_id`,`payment_status`,`created_at`,`updated_at`) values (1,3,3,120.00,'CC','123123123',1,'2016-11-17 00:00:00','2016-11-17 00:00:00');

/*Table structure for table `dl_student` */

DROP TABLE IF EXISTS `dl_student`;

CREATE TABLE `dl_student` (
  `student_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(55) NOT NULL,
  `email` varchar(55) NOT NULL,
  `first_name` varchar(55) NOT NULL,
  `middle_name` varchar(55) DEFAULT NULL,
  `last_name` varchar(55) NOT NULL,
  `status` int(5) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isDeleted` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`student_id`),
  KEY `FK_dl_student` (`admin_id`),
  CONSTRAINT `FK_dl_student` FOREIGN KEY (`admin_id`) REFERENCES `dl_admin` (`admin_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

/*Data for the table `dl_student` */

insert  into `dl_student`(`student_id`,`admin_id`,`username`,`password`,`email`,`first_name`,`middle_name`,`last_name`,`status`,`created_at`,`updated_at`,`isDeleted`) values (1,1,'testman1','testman1','testman1@gmail.com','testman1 FN','testman1 MN','testman1 LN',1,'2016-10-19 14:53:26','2016-10-19 14:53:26',0),(2,1,'testman2','testman2','testman2@gmail.com','testman2 Fn','testman2 Mn','testman2 LN',1,'2016-11-17 00:00:00','2016-11-17 16:06:11',0),(3,1,'testman3','testman3','testman3@gmail.com','testman3 Fn','testman3 Mn','testman3 Ln',1,'2016-11-17 00:00:00','2016-11-17 16:06:11',0),(4,15,'testman4','testman4','testman4@gmail.com','testman4 Fn','testman4 Mn','testman4 Ln',1,'2016-11-17 00:00:00','2016-11-17 16:06:11',0),(5,15,'testman5','testman5','testman5@gmail.com','testman5 Fn','testman5 Mn','testman5 Ln',1,'2016-11-17 00:00:00','2016-11-17 16:06:11',0),(6,1,'testman6','testman6','testman6@gmail.com','testman6 Fn','testman6 Mn','testman6 Ln',1,'2016-11-17 00:00:00','2016-11-17 16:06:11',0),(7,1,'testman7','testman7','testman7@gmail.com','testman7 Fn','testman7 Mn','testman7 Ln',1,'2016-11-17 00:00:00','2016-11-17 16:06:11',0);

/*Table structure for table `dl_student_course` */

DROP TABLE IF EXISTS `dl_student_course`;

CREATE TABLE `dl_student_course` (
  `scr_id` int(11) NOT NULL AUTO_INCREMENT,
  `ads_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `schedule_id` int(11) DEFAULT '0',
  `scr_disclaimer_status` int(11) NOT NULL DEFAULT '0',
  `scr_paid_status` int(11) NOT NULL DEFAULT '0',
  `scr_skpststus` int(11) NOT NULL DEFAULT '0',
  `scr_skip_url` varchar(255) DEFAULT NULL,
  `scr_registerdate` date NOT NULL,
  `scr_completed_date` date DEFAULT NULL,
  `scr_completed_status` int(11) DEFAULT '0',
  `scr_certificate_serialno` varchar(255) DEFAULT NULL,
  `scr_certificate_status` int(11) NOT NULL DEFAULT '0',
  `scr_certificate_send_date` date DEFAULT NULL,
  `scr_status` int(11) DEFAULT '0',
  `preferred_days` varchar(255) DEFAULT NULL,
  `preferred_time` varchar(255) DEFAULT NULL,
  `additional_infos` text,
  `created_at` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`scr_id`),
  KEY `FK_dl_student_course` (`lesson_id`),
  KEY `FK_dl_student_course_stud_id` (`student_id`),
  KEY `FK_dl_student_course_ads_id` (`ads_id`),
  KEY `FK_dl_student_course_admin_id` (`admin_id`),
  CONSTRAINT `FK_dl_student_course` FOREIGN KEY (`lesson_id`) REFERENCES `dl_lessons` (`lesson_id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_dl_student_course_admin_id` FOREIGN KEY (`admin_id`) REFERENCES `dl_admin` (`admin_id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_dl_student_course_ads_id` FOREIGN KEY (`ads_id`) REFERENCES `db_ads` (`ads_id`) ON UPDATE CASCADE,
  CONSTRAINT `FK_dl_student_course_stud_id` FOREIGN KEY (`student_id`) REFERENCES `dl_student` (`student_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

/*Data for the table `dl_student_course` */

insert  into `dl_student_course`(`scr_id`,`ads_id`,`lesson_id`,`admin_id`,`student_id`,`schedule_id`,`scr_disclaimer_status`,`scr_paid_status`,`scr_skpststus`,`scr_skip_url`,`scr_registerdate`,`scr_completed_date`,`scr_completed_status`,`scr_certificate_serialno`,`scr_certificate_status`,`scr_certificate_send_date`,`scr_status`,`preferred_days`,`preferred_time`,`additional_infos`,`created_at`,`updated_at`) values (1,1,10,1,1,0,0,0,0,NULL,'2016-11-17',NULL,0,NULL,0,NULL,0,'','',NULL,'2016-11-17 00:00:00','2016-11-17 16:01:54'),(2,1,10,1,2,0,0,0,0,NULL,'2016-11-17',NULL,0,NULL,0,NULL,0,'','',NULL,'2016-11-17 00:00:00','2016-11-17 16:02:13'),(3,1,10,1,3,2,0,1,0,NULL,'2016-11-17',NULL,0,NULL,0,NULL,0,'','',NULL,'2016-11-17 00:00:00','2016-11-17 16:02:13'),(4,2,10,15,4,0,0,0,0,NULL,'2016-11-17',NULL,0,NULL,0,NULL,0,'','',NULL,'2016-11-17 00:00:00','2016-11-17 16:02:13'),(5,2,10,15,5,0,0,0,0,NULL,'2016-11-17',NULL,0,NULL,0,NULL,0,'','',NULL,'2016-11-17 00:00:00','2016-11-17 16:02:13'),(6,1,10,1,6,0,0,0,0,NULL,'2016-11-17',NULL,0,NULL,0,NULL,0,'','',NULL,'2016-11-17 00:00:00','2016-11-17 16:02:13'),(7,1,10,1,7,0,0,0,0,NULL,'2016-11-17',NULL,0,NULL,0,NULL,0,'','',NULL,'2016-11-17 00:00:00','2016-11-17 16:02:13');

/*Table structure for table `dl_student_profile` */

DROP TABLE IF EXISTS `dl_student_profile`;

CREATE TABLE `dl_student_profile` (
  `std_prof_id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `gender` char(1) NOT NULL,
  `address1` varchar(255) NOT NULL,
  `address2` varchar(255) DEFAULT NULL,
  `city` varchar(55) NOT NULL,
  `state` varchar(55) NOT NULL,
  `zip` varchar(55) NOT NULL,
  `phone` varchar(55) NOT NULL,
  `dob` date NOT NULL,
  `permit_num` varchar(255) DEFAULT NULL,
  `language` varchar(10) DEFAULT NULL,
  `hear_about_this` varchar(255) DEFAULT NULL,
  `referred_by` varchar(255) DEFAULT NULL,
  `payer_firstname` varchar(55) DEFAULT NULL,
  `payer_lastname` varchar(55) DEFAULT NULL,
  `payer_address1` varchar(255) DEFAULT NULL,
  `payer_address2` varchar(255) DEFAULT NULL,
  `payer_city` varchar(50) DEFAULT NULL,
  `payer_state` varchar(50) DEFAULT NULL,
  `payer_zip` varchar(50) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`std_prof_id`),
  KEY `FK_dl_student_profile` (`student_id`),
  CONSTRAINT `FK_dl_student_profile` FOREIGN KEY (`student_id`) REFERENCES `dl_student` (`student_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `dl_student_profile` */

insert  into `dl_student_profile`(`std_prof_id`,`student_id`,`gender`,`address1`,`address2`,`city`,`state`,`zip`,`phone`,`dob`,`permit_num`,`language`,`hear_about_this`,`referred_by`,`payer_firstname`,`payer_lastname`,`payer_address1`,`payer_address2`,`payer_city`,`payer_state`,`payer_zip`,`created_at`,`updated_at`) values (1,3,'M','123 wallet tsreet','','Losangeles','california','90001','123-123-1234','2000-11-09','11223344','SP','Social media','Nachi','testman3 Pfn','testman3 Pln','testman3 PAd1','testman3 PAd2','testman3 Pc','testman3 Ps','900012','2016-11-18 00:00:00','2016-11-18 19:19:30'),(2,6,'M','123 wallet tsreet',NULL,'Losangeles','california','90001','7867876868768','2000-11-09',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-11-18 00:00:00','2016-11-18 19:19:30'),(3,7,'M','123 wallet tsreet',NULL,'Losangeles','california','90001','8888888','2000-11-09',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2016-11-18 00:00:00','2016-11-18 19:19:30');

/*Table structure for table `dl_super_admin` */

DROP TABLE IF EXISTS `dl_super_admin`;

CREATE TABLE `dl_super_admin` (
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `dl_super_admin` */

insert  into `dl_super_admin`(`id`,`username`,`email`,`password`,`modified_at`,`remember_token`,`status`,`auth_key`,`updated_at`) values (1,'webadmin','bartjr@americansafetyinstitute.com','YWRtaW4xMjM=','2016-06-07 22:48:03','',1,'KkyMAspRraKoSd56UQjtv9t2LjRHDe9q',NULL),(2,'subadmin1','vasanth@arkinfotec.com','YWRtaW4xMjM=','2016-07-27 11:19:24','',1,'1wNT8Aqv7Lqsh0KIhdkzPdhP8wwaDn9Z',NULL);

/*Table structure for table `migration` */

DROP TABLE IF EXISTS `migration`;

CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `migration` */

insert  into `migration`(`version`,`apply_time`) values ('m000000_000000_base',1474952874),('m130524_201442_init',1474952879);

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `user` */

insert  into `user`(`id`,`username`,`auth_key`,`password_hash`,`password_reset_token`,`email`,`status`,`created_at`,`updated_at`) values (1,'testman1','KkyMAspRraKoSd56UQjtv9t2LjRHDe9q','$2y$13$CYDxVI3RO3wfJh4lugYwPOCPrqChv35ii8qwjxg5yX9Qvr9L7kE3e',NULL,'testman1@gmail.com',10,1474954589,1474954589),(2,'testman2','1wNT8Aqv7Lqsh0KIhdkzPdhP8wwaDn9Z','$2y$13$gv1VOCkS1pGgpRXOJqIqceIOJLb8rhGKzo7BOkUZePcaddq9BeaBC',NULL,'testman2@gmail.com',10,1474958908,1474958908);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
