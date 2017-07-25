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

/*Table structure for table `dl_admin` */

DROP TABLE IF EXISTS `dl_admin`;

CREATE TABLE `dl_admin` (
  `admin_id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Data for the table `dl_admin` */

insert  into `dl_admin`(`admin_id`,`username`,`password`,`email`,`address1`,`address2`,`website`,`company_name`,`company_code`,`city`,`state`,`zip`,`work_phone`,`cell_phone`,`notes`,`status`,`remember_token`,`created_at`,`modified_at`,`auth_key`,`updated_at`) values (1,'admin','YWRtaW4xMjM=','admin@driving.com',NULL,'',NULL,'admin',NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'','2016-07-01 12:41:22','2016-09-27 16:32:47','KkyMAspRraKoSd56UQjtv9t2LjRHD79q','1475063865'),(2,'multiline','YWRtaW4xMjM=','multiline@driving.com',NULL,'',NULL,'multiline','MLC',NULL,NULL,NULL,NULL,NULL,NULL,1,'','2016-07-02 12:41:22','2016-09-27 16:32:55','KkyMAspRraKoSd56UQjtv9t2LjRTDe9q','1475063849'),(3,'testman1','testman1','testman1@gmail.com','','','','testman1','testman1','','','','','243423424',NULL,0,NULL,'0000-00-00 00:00:00','2016-09-29 13:59:40',NULL,'1475137780'),(4,'testman2','testman2','testman2@gmamil.com','','','','testman2','testman2','','','','','adsasdasd',NULL,0,NULL,'0000-00-00 00:00:00','2016-09-29 14:01:54',NULL,'1475137914'),(5,'testman3','testman3','testman3@gmail.com','123 wallet street,','','http://testman3.com','testman3 Company','TM3','Losangeles','California','90001','123-123-1234','2352345345','ths is test notes.',1,NULL,'0000-00-00 00:00:00','2016-09-30 13:26:34',NULL,'1475222638');

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
  PRIMARY KEY (`id`),
  UNIQUE KEY `dmv_super_admin_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `dl_super_admin` */

insert  into `dl_super_admin`(`id`,`username`,`email`,`password`,`modified_at`,`remember_token`,`status`,`auth_key`) values (1,'webadmin','bartjr@americansafetyinstitute.com','YWRtaW4xMjM=','2016-06-07 22:48:03','',1,'KkyMAspRraKoSd56UQjtv9t2LjRHDe9q'),(2,'subadmin1','vasanth@arkinfotec.com','YWRtaW4xMjM=','2016-07-27 11:19:24','',1,'1wNT8Aqv7Lqsh0KIhdkzPdhP8wwaDn9Z');

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
