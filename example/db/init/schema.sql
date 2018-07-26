DROP DATABASE sampledb;
CREATE DATABASE sampledb;
USE sampledb;

-- MySQL dump 10.13  Distrib 5.7.17, for macos10.12 (x86_64)
--
-- Host: 127.0.0.1    Database: sampledb
-- ------------------------------------------------------
-- Server version	5.5.5-10.2.16-MariaDB-10.2.16+maria~jessie

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
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `departments` (
  `code` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '名前',
  `deleted` timestamp NULL DEFAULT NULL COMMENT '削除日',
  `created` timestamp NULL DEFAULT NULL COMMENT '作成日',
  `modified` timestamp NULL DEFAULT NULL COMMENT '更新日',
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='部';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `devisions`
--

DROP TABLE IF EXISTS `devisions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `devisions` (
  `code` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sub_code` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '名前',
  `deleted` timestamp NULL DEFAULT NULL COMMENT '削除日',
  `created` timestamp NULL DEFAULT NULL COMMENT '作成日',
  `modified` timestamp NULL DEFAULT NULL COMMENT '更新日',
  PRIMARY KEY (`code`,`sub_code`),
  CONSTRAINT `idx_devisions_code` FOREIGN KEY (`code`) REFERENCES `departments` (`code`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='課';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `profiles`
--

DROP TABLE IF EXISTS `profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `profiles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '名前',
  `code` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_code` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted` timestamp NULL DEFAULT NULL COMMENT '削除日',
  `created` timestamp NULL DEFAULT NULL COMMENT '作成日',
  `modified` timestamp NULL DEFAULT NULL COMMENT '更新日',
  PRIMARY KEY (`id`),
  KEY `idx_profiles_user_id_idx` (`user_id`),
  KEY `idx_devision_code_sub_code_idx` (`code`,`sub_code`),
  CONSTRAINT `idx_profiles_code_sub_code` FOREIGN KEY (`code`, `sub_code`) REFERENCES `devisions` (`code`, `sub_code`) ON DELETE SET NULL ON UPDATE NO ACTION,
  CONSTRAINT `idx_profiles_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='プロフィール';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Eメール',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'パスワード',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted` timestamp NULL DEFAULT NULL COMMENT '削除日',
  `created` timestamp NULL DEFAULT NULL COMMENT '作成日',
  `modified` timestamp NULL DEFAULT NULL COMMENT '更新日',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ユーザー';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping events for database 'sampledb'
--

--
-- Dumping routines for database 'sampledb'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-07-27  0:03:01
