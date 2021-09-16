-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: suitecrm_neo
-- ------------------------------------------------------
-- Server version	5.5.5-10.1.38-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `renewal_cibil_trigger`
--

DROP TABLE IF EXISTS `renewal_cibil_trigger`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `renewal_cibil_trigger` (
  `as_app_id` int(4) NOT NULL,
  `triggers_uid` varchar(25) CHARACTER SET utf8 DEFAULT NULL,
  `member_code` varchar(25) CHARACTER SET utf8 DEFAULT NULL,
  `file_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `group_name` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `group_member_reference` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `group_start_date` datetime DEFAULT NULL,
  `group_end_date` datetime DEFAULT NULL,
  `real_time_delivery` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `alert_report_frequency` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `trigger_type` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `trigger_p1` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `trigger_p2` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `trigger_p3` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `trigger_p4` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `trigger_p5` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `trigger_p6` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `addon_info` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `account_type` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `ownership_indicator` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `alert_generation_timestamp` datetime DEFAULT NULL,
  `acct_account_type` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `acct_account_ownership` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `name_1` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `name_2` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `name_3` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `name_4` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `name_5` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `gender` varchar(6) CHARACTER SET utf8 DEFAULT NULL,
  `dob` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `latest_address_1` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `latest_address_2` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `latest_address_3` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `latest_address_4` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `latest_address_5` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `latest_state_code` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `latest_pin_code` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
  `latest_address_category` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `latest_address_residence_code` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `second_address_1` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `second_address_2` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `second_address_3` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `second_address_4` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `second_address_5` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `second_state_code` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `second_pin_code` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
  `second_address_category` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `second_address_residence_code` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `latest_phone` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `latest_phone_extension` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
  `latest_phone_type` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
  `second_phone` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `second_phone_extension` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
  `second_phone_type` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
  `latest_id_no` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `latest_id_type` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `second_id_no` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `second_id_type` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `enquiry_type` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `enquiry_amt` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `processed_file_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `date_entered` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_updated` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_processed` char(1) CHARACTER SET utf8 DEFAULT NULL,
  KEY `idx_triggers_uid` (`triggers_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `renewal_users`
--

DROP TABLE IF EXISTS `renewal_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `renewal_users` (
  `user_id` char(36) NOT NULL,
  `user_name` varchar(2048) NOT NULL,
  `ticket_size` varchar(2048) NOT NULL,
  `city` varchar(2048) NOT NULL,
  `role` varchar(2048) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `renewalmonthlydata`
--

DROP TABLE IF EXISTS `renewalmonthlydata`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `renewalmonthlydata` (
  `customer_id` int(11) NOT NULL,
  `instant_renewal` varchar(3) DEFAULT NULL,
  `eligible_amount` int(20) DEFAULT NULL,
  `risk_grade` varchar(2) DEFAULT NULL,
  `blacklist` varchar(3) DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `modified_user_id` varchar(100) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `renewals_user_activity`
--

DROP TABLE IF EXISTS `renewals_user_activity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `renewals_user_activity` (
  `id` char(36) NOT NULL,
  `user_id` char(36) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `activity_key` varchar(45) DEFAULT NULL,
  `activity_value` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-09-03 19:20:03