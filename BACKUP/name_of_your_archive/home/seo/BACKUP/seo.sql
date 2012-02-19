-- MySQL dump 10.13  Distrib 5.1.56, for redhat-linux-gnu (i386)
--
-- Host: localhost    Database: seo
-- ------------------------------------------------------
-- Server version	5.1.56-log

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
-- Table structure for table `cms_page`
--

DROP TABLE IF EXISTS `cms_page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cms_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `route` varchar(128) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `contents` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `route` (`route`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms_page`
--

LOCK TABLES `cms_page` WRITE;
/*!40000 ALTER TABLE `cms_page` DISABLE KEYS */;
/*!40000 ALTER TABLE `cms_page` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `confirmation`
--

DROP TABLE IF EXISTS `confirmation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `confirmation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` varchar(16) NOT NULL,
  `confirmation_type` varchar(100) NOT NULL,
  `entity_model` varchar(50) NOT NULL,
  `entity_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hash_idx` (`hash`),
  UNIQUE KEY `entity_idx` (`entity_model`,`entity_id`,`confirmation_type`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `confirmation`
--

LOCK TABLES `confirmation` WRITE;
/*!40000 ALTER TABLE `confirmation` DISABLE KEYS */;
INSERT INTO `confirmation` VALUES (1,'5d79207b3b5fec92','registration','User',3,'2011-10-10 10:48:48'),(3,'942edb2a38e5b587','registration','User',5,'2011-10-10 10:54:56');
/*!40000 ALTER TABLE `confirmation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `domain`
--

DROP TABLE IF EXISTS `domain`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `domain` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `domain` varchar(32) DEFAULT NULL,
  `country` varchar(2) DEFAULT NULL,
  `keyword` longtext,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `domain_idx` (`domain`),
  KEY `domain_user_id_idx` (`user_id`),
  CONSTRAINT `domain_user_id_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `domain`
--

LOCK TABLES `domain` WRITE;
/*!40000 ALTER TABLE `domain` DISABLE KEYS */;
INSERT INTO `domain` VALUES (7,7,'http://murrieta.olx.com','US','alegro','2011-10-10 10:58:29','2011-10-10 10:58:29'),(9,7,'http://www.cycladia.com','US','alegro','2011-10-10 11:11:35','2011-10-10 11:11:35'),(11,7,'http://eriwen.com','US','crontab','2011-10-10 11:13:11','2011-10-10 11:13:11'),(13,7,'http://service.futurequest.net','US','crontab','2011-10-10 11:14:46','2011-10-10 11:14:46'),(15,7,'http://google.com','US','google','2011-10-10 12:58:31','2011-10-10 12:58:31'),(17,9,'http://lenta.ru','RU','news','2011-10-11 10:40:52','2011-10-11 10:40:52'),(19,13,'http://finalview.com','US','android development company','2011-10-11 12:24:36','2011-10-11 12:24:36'),(21,11,'http://www.finalview.com','US','iphone 2d game development','2011-10-11 12:41:32','2011-10-11 12:41:32');
/*!40000 ALTER TABLE `domain` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `geo_country`
--

DROP TABLE IF EXISTS `geo_country`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `geo_country` (
  `code` char(2) NOT NULL DEFAULT '',
  `name` char(64) DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `geo_country`
--

LOCK TABLES `geo_country` WRITE;
/*!40000 ALTER TABLE `geo_country` DISABLE KEYS */;
INSERT INTO `geo_country` VALUES ('AD','Andorra'),('AE','United Arab Emirates'),('AG','Antigua and Barbuda'),('AI','Anguilla'),('AL','Albania'),('AM','Armenia'),('AN','Netherland Antilles'),('AO','Angola'),('AQ','Antarctica'),('AR','Argentina'),('AS','American Samoa'),('AT','Austria'),('AU','Australia'),('AW','Aruba'),('AZ','Azerbaijan'),('BA','Bosnia-Herzegovina'),('BB','Barbados'),('BD','Bangladesh'),('BE','Belgium'),('BF','Burkina Faso'),('BG','Bulgaria'),('BH','Bahrain'),('BI','Burundi'),('BJ','Benin'),('BM','Bermuda'),('BN','Brunei Darussalam'),('BO','Bolivia'),('BR','Brazil'),('BS','Bahamas'),('BT','Buthan'),('BV','Bouvet Island'),('BW','Botswana'),('BY','Belarus'),('BZ','Belize'),('CA','Canada'),('CC','Cocos (Keeling) Isl.'),('CF','Central African Rep.'),('CG','Congo'),('CH','Switzerland'),('CI','Ivory Coast'),('CK','Cook Islands'),('CL','Chile'),('CM','Cameroon'),('CN','China'),('CO','Colombia'),('CR','Costa Rica'),('CS','Czechoslovakia'),('CU','Cuba'),('CV','Cape Verde'),('CX','Christmas Island'),('CY','Cyprus'),('CZ','Czech Republic'),('DE','Germany'),('DJ','Djibouti'),('DK','Denmark'),('DM','Dominica'),('DO','Dominican Republic'),('DZ','Algeria'),('EC','Ecuador'),('EE','Estonia'),('EG','Egypt'),('EH','Western Sahara'),('ES','Spain'),('ET','Ethiopia'),('FI','Finland'),('FJ','Fiji'),('FK','Falkland Isl.(Malvinas)'),('FM','Micronesia'),('FO','Faroe Islands'),('FR','France'),('FX','France (European Ter.)'),('GA','Gabon'),('GB','United Kingdom (Great Britain)'),('GD','Grenada'),('GE','Georgia'),('GF','Guyana (Fr.)'),('GH','Ghana'),('GI','Gibraltar'),('GL','Greenland'),('GM','Gambia'),('GN','Guinea'),('GP','Guadeloupe (Fr.)'),('GQ','Equatorial Guinea'),('GR','Greece'),('GT','Guatemala'),('GU','Guam (US)'),('GW','Guinea Bissau'),('GY','Guyana'),('HK','Hong Kong'),('HM','Heard & McDonald Isl.'),('HN','Honduras'),('HR','Croatia'),('HT','Haiti'),('HU','Hungary'),('ID','Indonesia'),('IE','Ireland'),('IL','Israel'),('IN','India'),('IO','British Indian O. Terr.'),('IQ','Iraq'),('IR','Iran'),('IS','Iceland'),('IT','Italy'),('JM','Jamaica'),('JO','Jordan'),('JP','Japan'),('KE','Kenya'),('KG','Kirgistan'),('KH','Cambodia'),('KI','Kiribati'),('KM','Comoros'),('KN','St.Kitts Nevis Anguilla'),('KP','Korea (North)'),('KR','Korea (South)'),('KW','Kuwait'),('KY','Cayman Islands'),('KZ','Kazachstan'),('LA','Laos'),('LB','Lebanon'),('LC','Saint Lucia'),('LI','Liechtenstein'),('LK','Sri Lanka'),('LR','Liberia'),('LS','Lesotho'),('LT','Lithuania'),('LU','Luxembourg'),('LV','Latvia'),('LY','Libya'),('MA','Morocco'),('MC','Monaco'),('MD','Moldavia'),('MG','Madagascar'),('MH','Marshall Islands'),('ML','Mali'),('MM','Myanmar'),('MN','Mongolia'),('MO','Macau'),('MP','Northern Mariana Isl.'),('MQ','Martinique (Fr.)'),('MR','Mauritania'),('MS','Montserrat'),('MT','Malta'),('MU','Mauritius'),('MV','Maldives'),('MW','Malawi'),('MX','Mexico'),('MY','Malaysia'),('MZ','Mozambique'),('NA','Namibia'),('NC','New Caledonia (Fr.)'),('NE','Niger'),('NF','Norfolk Island'),('NG','Nigeria'),('NI','Nicaragua'),('NL','Netherlands'),('NO','Norway'),('NP','Nepal'),('NR','Nauru'),('NT','Neutral Zone'),('NU','Niue'),('NZ','New Zealand'),('OM','Oman'),('PA','Panama'),('PE','Peru'),('PF','Polynesia (Fr.)'),('PG','Papua New'),('PH','Philippines'),('PK','Pakistan'),('PL','Poland'),('PM','St. Pierre & Miquelon'),('PN','Pitcairn'),('PR','Puerto Rico'),('PT','Portugal'),('PW','Palau'),('PY','Paraguay'),('QA','Qatar'),('RE','Reunion (Fr.)'),('RO','Romania'),('RU','Russia'),('RW','Rwanda'),('SA','Saudi Arabia'),('SB','Solomon Islands'),('SC','Seychelles'),('SD','Sudan'),('SE','Sweden'),('SG','Singapore'),('SH','St. Helena'),('SI','Slovenia'),('SJ','Svalbard & Jan Mayen Is'),('SK','Slovak Republic'),('SL','Sierra Leone'),('SM','San Marino'),('SN','Senegal'),('SO','Somalia'),('SR','Suriname'),('ST','St. Tome and Principe'),('SV','El Salvador'),('SY','Syria'),('SZ','Swaziland'),('TC','Turks & Caicos Islands'),('TD','Chad'),('TF','French Southern Terr.'),('TG','Togo'),('TH','Thailand'),('TJ','Tadjikistan'),('TK','Tokelau'),('TM','Turkmenistan'),('TN','Tunisia'),('TO','Tonga'),('TP','East Timor'),('TR','Turkey'),('TT','Trinidad & Tobago'),('TV','Tuvalu'),('TW','Taiwan'),('TZ','Tanzania'),('UA','Ukraine'),('UG','Uganda'),('UM','US Minor outlying Isl.'),('US','United States'),('UY','Uruguay'),('UZ','Uzbekistan'),('VA','Vatican City State'),('VC','St.Vincent & Grenadines'),('VE','Venezuela'),('VG','Virgin Islands (British)'),('VI','Virgin Islands (US)'),('VN','Vietnam'),('VU','Vanuatu'),('WF','Wallis & Futuna Islands'),('WS','Samoa'),('YE','Yemen'),('YU','Yugoslavia'),('ZA','South Africa'),('ZM','Zambia'),('ZR','Zaire'),('ZW','Zimbabwe');
/*!40000 ALTER TABLE `geo_country` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `geo_state`
--

DROP TABLE IF EXISTS `geo_state`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `geo_state` (
  `code` char(2) NOT NULL DEFAULT '',
  `name` char(64) DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `geo_state`
--

LOCK TABLES `geo_state` WRITE;
/*!40000 ALTER TABLE `geo_state` DISABLE KEYS */;
/*!40000 ALTER TABLE `geo_state` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mail_templates`
--

DROP TABLE IF EXISTS `mail_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mail_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template` varchar(64) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `html` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mail_templates`
--

LOCK TABLES `mail_templates` WRITE;
/*!40000 ALTER TABLE `mail_templates` DISABLE KEYS */;
INSERT INTO `mail_templates` VALUES (1,'user/registration-confirmation','Registration confirmation','','<p>Hello, User !\r\n<br /><br />\r\nPlease confirm your account by&nbsp;clicking the link below or copy and paste it into your web browser:\r\n<br />\r\n{$BASE_PATH}confirmation/{$hash}/accept.html\r\n<br /><br />\r\nTo decline registration click the link below or copy and paste it into your web browser:\r\n{$BASE_PATH}confirmation/{$hash}/decline.html\r\n</p>');
/*!40000 ALTER TABLE `mail_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migration_version`
--

DROP TABLE IF EXISTS `migration_version`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migration_version` (
  `version` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migration_version`
--

LOCK TABLES `migration_version` WRITE;
/*!40000 ALTER TABLE `migration_version` DISABLE KEYS */;
INSERT INTO `migration_version` VALUES (2);
/*!40000 ALTER TABLE `migration_version` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `parsing`
--

DROP TABLE IF EXISTS `parsing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `parsing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL,
  `keyword` longtext,
  `position` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `url` longtext,
  `description` longtext,
  `country` varchar(2) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parsing_domain_id_idx` (`domain_id`),
  CONSTRAINT `parsing_domain_id_domain_id` FOREIGN KEY (`domain_id`) REFERENCES `domain` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parsing`
--

LOCK TABLES `parsing` WRITE;
/*!40000 ALTER TABLE `parsing` DISABLE KEYS */;
INSERT INTO `parsing` VALUES (1,17,'news',800,1,NULL,NULL,'RU','2011-10-06 10:43:04','2011-10-06 10:43:04'),(3,17,'news',700,1,NULL,NULL,'RU','2011-10-07 10:43:06','2011-10-07 10:43:06'),(5,17,'news',600,1,NULL,NULL,'RU','2011-10-08 10:54:17','2011-10-08 10:54:17'),(7,17,'news',100,1,NULL,NULL,'RU','2011-10-09 10:54:21','2011-10-09 10:54:21'),(9,17,'news',3,1,NULL,NULL,'RU','2011-10-10 10:54:26','2011-10-10 10:54:26'),(11,19,'android development company',38,1,'http://finalview.com/','FinalView - iPhone & Android Development Company, Professional ...','US','2011-10-11 12:27:05','2011-10-11 12:27:05'),(13,21,'iphone 2d game development',17,1,'http://finalview.com/iphone-development','iPhone development - web app projects, iphone 2d/3d game ...','US','2011-10-11 12:44:05','2011-10-11 12:44:05'),(15,17,'news',3000,1,'lenta.ru','lenta.ru','RU','2011-10-12 10:43:06','2011-10-12 10:43:06'),(17,19,'android development company',39,1,'http://finalview.com/','FinalView - iPhone & Android Development Company, Professional ...','US','2011-10-12 12:27:07','2011-10-12 12:27:07'),(19,21,'iphone 2d game development',18,1,'http://finalview.com/iphone-development','iPhone development - web app projects, iphone 2d/3d game ...','US','2011-10-12 12:44:04','2011-10-12 12:44:04'),(21,17,'news',3000,1,'lenta.ru','lenta.ru','RU','2011-10-13 10:43:08','2011-10-13 10:43:08'),(23,19,'android development company',39,1,'http://finalview.com/','FinalView - iPhone & Android Development Company, Professional ...','US','2011-10-13 12:27:08','2011-10-13 12:27:08'),(25,21,'iphone 2d game development',19,1,'http://finalview.com/iphone-development','iPhone development - web app projects, iphone 2d/3d game ...','US','2011-10-13 12:44:07','2011-10-13 12:44:07'),(27,17,'news',3000,1,'lenta.ru','lenta.ru','RU','2011-10-14 10:43:07','2011-10-14 10:43:07'),(29,19,'android development company',36,1,'http://finalview.com/','FinalView - iPhone & Android Development Company, Professional ...','US','2011-10-14 12:27:04','2011-10-14 12:27:04'),(31,21,'iphone 2d game development',21,1,'http://finalview.com/iphone-development','iPhone development - web app projects, iphone 2d/3d game ...','US','2011-10-14 12:44:03','2011-10-14 12:44:03');
/*!40000 ALTER TABLE `parsing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(128) NOT NULL,
  `password` varchar(32) NOT NULL,
  `role` tinyint(4) NOT NULL,
  `confirmed` tinyint(4) NOT NULL DEFAULT '0',
  `replied_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'sabre@tut.by','9c11094240230fb92c81778015c649da',3,1,NULL,'2011-10-04 13:57:06','2011-10-04 13:57:06'),(2,'admin@seo.com','25d55ad283aa400af464c76d713c07ad',5,1,'2011-10-04 16:59:16','2011-10-04 16:59:16','2011-10-04 16:59:16'),(5,'aristo.wm@gmail.com','9c11094240230fb92c81778015c649da',3,0,NULL,'2011-10-10 10:54:55','2011-10-10 10:54:55'),(7,'maxim.klimchuk@finalview.com','25d55ad283aa400af464c76d713c07ad',3,1,'2011-10-10 10:56:59','2011-10-10 10:56:45','2011-10-10 10:56:59'),(9,'maxim.klimchuk+seofvdev@finalview.com','25d55ad283aa400af464c76d713c07ad',3,1,'2011-10-11 10:39:39','2011-10-11 10:38:40','2011-10-11 10:39:39'),(11,'andrey@finalview.com','25d55ad283aa400af464c76d713c07ad',3,1,'2011-10-11 10:42:28','2011-10-11 10:41:55','2011-10-11 10:42:28'),(13,'elena.saltykova@finalview.com','efe2deb4012913e469be4ba21695d5ca',3,1,'2011-10-11 12:15:39','2011-10-11 11:32:10','2011-10-11 12:15:39');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-10-20 13:45:23
