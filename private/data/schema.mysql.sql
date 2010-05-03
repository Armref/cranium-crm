/*
SQLyog Ultimate - MySQL GUI v8.21 
MySQL - 5.1.37-1ubuntu5.1 : Database - cranium
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `account` */

DROP TABLE IF EXISTS `account`;

CREATE TABLE `account` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `account_name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `company_id` bigint(20) unsigned NOT NULL,
  `lft` bigint(20) unsigned NOT NULL,
  `rgt` bigint(20) unsigned NOT NULL,
  `company_num` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_account_company` (`company_id`),
  CONSTRAINT `FK_account_company` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `account_contact` */

DROP TABLE IF EXISTS `account_contact`;

CREATE TABLE `account_contact` (
  `account_id` bigint(20) unsigned NOT NULL,
  `company_employee_id` bigint(20) unsigned NOT NULL,
  `primary` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`account_id`,`company_employee_id`),
  KEY `FK_contact_acct` (`company_employee_id`),
  CONSTRAINT `FK_acct_contact` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_contact_acct` FOREIGN KEY (`company_employee_id`) REFERENCES `company_employee` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `address` */

DROP TABLE IF EXISTS `address`;

CREATE TABLE `address` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `address_type_id` bigint(20) unsigned NOT NULL,
  `street_num` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `street` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `street2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country_id` bigint(20) unsigned NOT NULL,
  `state_id` bigint(20) unsigned NOT NULL,
  `county_id` bigint(20) unsigned NOT NULL,
  `city_id` bigint(20) unsigned NOT NULL,
  `postal_code_id` bigint(20) unsigned NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_address_type` (`address_type_id`),
  KEY `FK_address_state` (`state_id`),
  KEY `FK_address_county` (`county_id`),
  KEY `FK_address_city` (`city_id`),
  KEY `FK_address_postal_code` (`postal_code_id`),
  KEY `FK_address_country` (`country_id`),
  CONSTRAINT `FK_address_country` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `FK_address_city` FOREIGN KEY (`city_id`) REFERENCES `city` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `FK_address_county` FOREIGN KEY (`county_id`) REFERENCES `county` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `FK_address_postal_code` FOREIGN KEY (`postal_code_id`) REFERENCES `postal_code` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `FK_address_state` FOREIGN KEY (`state_id`) REFERENCES `state` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `FK_address_type` FOREIGN KEY (`address_type_id`) REFERENCES `address_type` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `address_type` */

DROP TABLE IF EXISTS `address_type`;

CREATE TABLE `address_type` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `address_type` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `city` */

DROP TABLE IF EXISTS `city`;

CREATE TABLE `city` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `country_id` bigint(20) unsigned NOT NULL,
  `state_id` bigint(20) unsigned NOT NULL,
  `county_id` bigint(20) unsigned NOT NULL,
  `city` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_city_county` (`county_id`),
  KEY `FK_city_country` (`country_id`),
  KEY `FK_city_state` (`state_id`),
  CONSTRAINT `FK_city_state` FOREIGN KEY (`state_id`) REFERENCES `state` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_city_country` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_city_county` FOREIGN KEY (`county_id`) REFERENCES `county` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `company` */

DROP TABLE IF EXISTS `company`;

CREATE TABLE `company` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `company_name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `company_code` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `company_address` */

DROP TABLE IF EXISTS `company_address`;

CREATE TABLE `company_address` (
  `company_id` bigint(20) unsigned NOT NULL,
  `address_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`company_id`,`address_id`),
  KEY `FK_address_company` (`address_id`),
  CONSTRAINT `FK_address_company` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_company_address` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `company_employee` */

DROP TABLE IF EXISTS `company_employee`;

CREATE TABLE `company_employee` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `person_id` bigint(20) unsigned NOT NULL,
  `company_id` bigint(20) unsigned NOT NULL,
  `position` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `lft` bigint(20) unsigned NOT NULL,
  `rgt` bigint(20) unsigned NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `person_company` (`person_id`,`company_id`),
  KEY `FK_employee_company` (`company_id`),
  CONSTRAINT `FK_company_employee` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_employee_company` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `company_phone` */

DROP TABLE IF EXISTS `company_phone`;

CREATE TABLE `company_phone` (
  `company_id` bigint(20) unsigned NOT NULL,
  `phone_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`company_id`,`phone_id`),
  KEY `FK_phone_company` (`phone_id`),
  CONSTRAINT `FK_phone_company` FOREIGN KEY (`phone_id`) REFERENCES `phone` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_company_phone` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `company_website` */

DROP TABLE IF EXISTS `company_website`;

CREATE TABLE `company_website` (
  `company_id` bigint(20) unsigned NOT NULL,
  `web_address_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`company_id`,`web_address_id`),
  KEY `FK_website_company` (`web_address_id`),
  CONSTRAINT `FK_company_website` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_website_company` FOREIGN KEY (`web_address_id`) REFERENCES `web_address` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `country` */

DROP TABLE IF EXISTS `country`;

CREATE TABLE `country` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `country` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `country_abbr` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `county` */

DROP TABLE IF EXISTS `county`;

CREATE TABLE `county` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `country_id` bigint(20) unsigned NOT NULL,
  `state_id` bigint(20) unsigned NOT NULL,
  `county` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_county_country` (`country_id`),
  KEY `FK_county_state` (`state_id`),
  CONSTRAINT `FK_county_state` FOREIGN KEY (`state_id`) REFERENCES `state` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_county_country` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `person` */

DROP TABLE IF EXISTS `person`;

CREATE TABLE `person` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `middle_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `title` enum('Mr.','Ms.','Mrs.') COLLATE utf8_unicode_ci DEFAULT NULL,
  `gender` enum('male','female') COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `person_address` */

DROP TABLE IF EXISTS `person_address`;

CREATE TABLE `person_address` (
  `person_id` bigint(20) unsigned NOT NULL,
  `address_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`person_id`,`address_id`),
  KEY `FK_address_person` (`address_id`),
  CONSTRAINT `FK_address_person` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_person_address` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `person_phone` */

DROP TABLE IF EXISTS `person_phone`;

CREATE TABLE `person_phone` (
  `person_id` bigint(20) unsigned NOT NULL,
  `phone_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`person_id`,`phone_id`),
  KEY `FK_phone_person` (`phone_id`),
  CONSTRAINT `FK_phone_person` FOREIGN KEY (`phone_id`) REFERENCES `phone` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_person_phone` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `phone` */

DROP TABLE IF EXISTS `phone`;

CREATE TABLE `phone` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `phone_type_id` bigint(20) unsigned NOT NULL,
  `area_code` int(3) unsigned NOT NULL,
  `phone_number` bigint(7) unsigned NOT NULL,
  `extension` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_phone_type` (`phone_type_id`),
  CONSTRAINT `FK_phone_type` FOREIGN KEY (`phone_type_id`) REFERENCES `phone_type` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `phone_type` */

DROP TABLE IF EXISTS `phone_type`;

CREATE TABLE `phone_type` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `phone_type` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `postal_code` */

DROP TABLE IF EXISTS `postal_code`;

CREATE TABLE `postal_code` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `country_id` bigint(20) unsigned NOT NULL,
  `state_id` bigint(20) unsigned NOT NULL,
  `county_id` bigint(20) unsigned NOT NULL,
  `city_id` bigint(20) unsigned NOT NULL,
  `postal_code` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `latitude` decimal(18,12) DEFAULT NULL,
  `longitude` decimal(18,12) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_postal_code_country` (`country_id`),
  KEY `FK_postal_code_state` (`state_id`),
  KEY `FK_postal_code_county` (`county_id`),
  KEY `FK_postal_code_city` (`city_id`),
  CONSTRAINT `FK_postal_code_city` FOREIGN KEY (`city_id`) REFERENCES `city` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_postal_code_country` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_postal_code_county` FOREIGN KEY (`county_id`) REFERENCES `county` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_postal_code_state` FOREIGN KEY (`state_id`) REFERENCES `state` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `state` */

DROP TABLE IF EXISTS `state`;

CREATE TABLE `state` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `country_id` bigint(20) unsigned NOT NULL,
  `state` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `state_abbr` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_state_country` (`country_id`),
  CONSTRAINT `FK_state_country` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `system_controller` */

DROP TABLE IF EXISTS `system_controller`;

CREATE TABLE `system_controller` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `controller` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `system_log` */

DROP TABLE IF EXISTS `system_log`;

CREATE TABLE `system_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `controller_id` bigint(20) unsigned NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `model_pk_id` bigint(20) unsigned NOT NULL,
  `event` enum('create','update','delete') COLLATE utf8_unicode_ci NOT NULL,
  `source` enum('web','cli') COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_system_log_user` (`user_id`),
  KEY `FK_system_log_controller` (`controller_id`),
  KEY `FK_system_log_model` (`model_id`),
  CONSTRAINT `FK_system_log_model` FOREIGN KEY (`model_id`) REFERENCES `system_model` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `FK_system_log_controller` FOREIGN KEY (`controller_id`) REFERENCES `system_controller` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `FK_system_log_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1256 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `system_log_change` */

DROP TABLE IF EXISTS `system_log_change`;

CREATE TABLE `system_log_change` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `system_log_id` bigint(20) unsigned NOT NULL,
  `column` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `value` longblob,
  PRIMARY KEY (`id`),
  KEY `FK_system_log_change` (`system_log_id`),
  CONSTRAINT `FK_system_log_change` FOREIGN KEY (`system_log_id`) REFERENCES `system_log` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `system_model` */

DROP TABLE IF EXISTS `system_model`;

CREATE TABLE `system_model` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `model` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `person_id` bigint(20) unsigned NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_user_person` (`person_id`),
  CONSTRAINT `FK_user_person` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `web_address` */

DROP TABLE IF EXISTS `web_address`;

CREATE TABLE `web_address` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
