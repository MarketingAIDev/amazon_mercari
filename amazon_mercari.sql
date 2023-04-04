/*
SQLyog Community v13.1.5  (64 bit)
MySQL - 10.4.17-MariaDB : Database - amazon_mercari
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`amazon_mercari` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `amazon_mercari`;

/*Table structure for table `amazon_products` */

DROP TABLE IF EXISTS `amazon_products`;

CREATE TABLE `amazon_products` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `user_id` int(20) DEFAULT NULL,
  `image` text DEFAULT NULL,
  `ASIN` varchar(255) DEFAULT NULL,
  `product` text DEFAULT NULL,
  `prime` varchar(10) DEFAULT NULL,
  `price` int(20) DEFAULT 0,
  `r_price` int(20) DEFAULT 0,
  `attribute` varchar(255) DEFAULT 'データなし',
  `feature_1` text DEFAULT NULL,
  `feature_2` text DEFAULT NULL,
  `feature_3` text DEFAULT NULL,
  `feature_4` text DEFAULT NULL,
  `feature_5` text DEFAULT NULL,
  `feature` text DEFAULT NULL,
  `rank` varchar(255) DEFAULT 'データなし',
  `p_length` varchar(255) DEFAULT 'データなし',
  `p_width` varchar(255) DEFAULT 'データなし',
  `p_height` varchar(255) DEFAULT 'データなし',
  `a_c_root` varchar(255) DEFAULT 'データなし',
  `a_c_sub` varchar(255) DEFAULT 'データなし',
  `a_c_tree` varchar(255) DEFAULT 'データなし',
  `m_code` varchar(20) DEFAULT NULL,
  `inventory` int(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `amazon_products` */

/*Table structure for table `categories` */

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(20) DEFAULT NULL,
  `a_c_root` varchar(255) DEFAULT NULL,
  `a_c_sub` varchar(255) DEFAULT NULL,
  `m_category` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `categories` */

/*Table structure for table `category_ids` */

DROP TABLE IF EXISTS `category_ids`;

CREATE TABLE `category_ids` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `user_id` int(20) DEFAULT NULL,
  `category_id` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `all_category` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `category_ids` */

/*Table structure for table `errors` */

DROP TABLE IF EXISTS `errors`;

CREATE TABLE `errors` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;

/*Data for the table `errors` */

insert  into `errors`(`id`,`code`,`created_at`,`updated_at`) values 
(7,'11','2022-12-29 08:46:11','2022-12-29 08:46:58'),
(8,'212','2022-12-29 08:46:15','2022-12-29 08:47:21'),
(9,'3','2022-12-29 08:46:26','2022-12-29 08:46:26'),
(10,'4','2022-12-29 08:46:27','2022-12-29 08:46:27'),
(11,'5','2022-12-29 08:46:29','2022-12-29 08:46:29');

/*Table structure for table `exhibitions` */

DROP TABLE IF EXISTS `exhibitions`;

CREATE TABLE `exhibitions` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `user_id` int(20) DEFAULT NULL,
  `amazon_id` int(20) DEFAULT NULL,
  `m_code` varchar(15) DEFAULT NULL,
  `ASIN` varchar(15) DEFAULT NULL,
  `image` text DEFAULT NULL,
  `product` text DEFAULT NULL,
  `prime` varchar(10) DEFAULT NULL,
  `feature` text DEFAULT NULL,
  `price` int(20) DEFAULT 0,
  `e_price` int(20) DEFAULT 0,
  `a_category` varchar(255) DEFAULT NULL,
  `m_category` varchar(255) DEFAULT NULL,
  `m_category_id` varchar(255) DEFAULT NULL,
  `postage` int(10) DEFAULT 0,
  `etc` int(10) DEFAULT 100,
  `exclusion` varchar(255) DEFAULT NULL,
  `condition_n_u` tinyint(1) DEFAULT NULL,
  `inventory` int(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `exhibitions` */

/*Table structure for table `mercari_products` */

DROP TABLE IF EXISTS `mercari_products`;

CREATE TABLE `mercari_products` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `user_id` int(255) DEFAULT NULL,
  `ASIN` varchar(20) DEFAULT NULL,
  `image_1` varchar(255) DEFAULT NULL,
  `image_2` varchar(255) DEFAULT NULL,
  `image_3` varchar(255) DEFAULT NULL,
  `image_4` varchar(255) DEFAULT NULL,
  `image_5` varchar(255) DEFAULT NULL,
  `image_6` varchar(255) DEFAULT NULL,
  `image_7` varchar(255) DEFAULT NULL,
  `image_8` varchar(255) DEFAULT NULL,
  `image_9` varchar(255) DEFAULT NULL,
  `image_10` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `product` text DEFAULT NULL,
  `feature` text DEFAULT NULL,
  `SKU1_type` varchar(255) DEFAULT NULL,
  `SKU1_inventory` varchar(255) DEFAULT NULL,
  `SKU1_management` varchar(255) DEFAULT NULL,
  `SKU1_jan_code` varchar(255) DEFAULT NULL,
  `SKU2_type` varchar(255) DEFAULT NULL,
  `SKU2_inventory` varchar(255) DEFAULT NULL,
  `SKU2_management` varchar(255) DEFAULT NULL,
  `SKU2_jan_code` varchar(255) DEFAULT NULL,
  `SKU3_type` varchar(255) DEFAULT NULL,
  `SKU4_type` varchar(255) DEFAULT NULL,
  `SKU5_type` varchar(255) DEFAULT NULL,
  `SKU6_type` varchar(255) DEFAULT NULL,
  `SKU7_type` varchar(255) DEFAULT NULL,
  `SKU8_type` varchar(255) DEFAULT NULL,
  `SKU9_type` varchar(255) DEFAULT NULL,
  `SKU10_type` varchar(255) DEFAULT NULL,
  `SKU3_inventory` int(255) DEFAULT NULL,
  `SKU4_inventory` int(255) DEFAULT NULL,
  `SKU5_inventory` int(255) DEFAULT NULL,
  `SKU6_inventory` int(255) DEFAULT NULL,
  `SKU7_inventory` int(255) DEFAULT NULL,
  `SKU8_inventory` int(255) DEFAULT NULL,
  `SKU9_inventory` int(255) DEFAULT NULL,
  `SKU10_inventory` int(255) DEFAULT NULL,
  `SKU3_management` varchar(255) DEFAULT NULL,
  `SKU4_management` varchar(255) DEFAULT NULL,
  `SKU5_management` varchar(255) DEFAULT NULL,
  `SKU6_management` varchar(255) DEFAULT NULL,
  `SKU7_management` varchar(255) DEFAULT NULL,
  `SKU8_management` varchar(255) DEFAULT NULL,
  `SKU9_management` varchar(255) DEFAULT NULL,
  `SKU10_management` varchar(255) DEFAULT NULL,
  `SKU3_jan_code` varchar(255) DEFAULT NULL,
  `SKU4_jan_code` varchar(255) DEFAULT NULL,
  `SKU5_jan_code` varchar(255) DEFAULT NULL,
  `SKU6_jan_code` varchar(255) DEFAULT NULL,
  `SKU7_jan_code` varchar(255) DEFAULT NULL,
  `SKU8_jan_code` varchar(255) DEFAULT NULL,
  `SKU9_jan_code` varchar(255) DEFAULT NULL,
  `SKU10_jan_code` varchar(255) DEFAULT NULL,
  `brand_id` int(20) DEFAULT NULL,
  `selling_price` int(50) DEFAULT NULL,
  `category_id` varchar(255) DEFAULT NULL,
  `commodity` int(255) DEFAULT NULL,
  `shipping_method` int(1) DEFAULT NULL,
  `region_origin` varchar(255) DEFAULT NULL,
  `day_ship` int(1) DEFAULT NULL,
  `product_status` int(1) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `mercari_products` */

/*Table structure for table `mercari_updates` */

DROP TABLE IF EXISTS `mercari_updates`;

CREATE TABLE `mercari_updates` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `user_id` int(20) DEFAULT NULL,
  `product_id` varchar(255) DEFAULT NULL,
  `snapshot_id` varchar(255) DEFAULT NULL,
  `image_n_1` varchar(100) DEFAULT NULL COMMENT 'image_name_1',
  `image_u_1` tinyint(1) DEFAULT NULL COMMENT 'image_update_1',
  `image_r_1` tinyint(1) DEFAULT NULL COMMENT 'image_registration_1',
  `image_n_2` varchar(100) DEFAULT NULL,
  `image_u_2` tinyint(1) DEFAULT NULL,
  `image_r_2` tinyint(1) DEFAULT NULL,
  `image_n_3` varchar(100) DEFAULT NULL,
  `image_u_3` tinyint(1) DEFAULT NULL,
  `image_r_3` tinyint(1) DEFAULT NULL,
  `image_n_4` varchar(100) DEFAULT NULL,
  `image_u_4` tinyint(1) DEFAULT NULL,
  `image_r_4` tinyint(1) DEFAULT NULL,
  `image_n_5` varchar(100) DEFAULT NULL,
  `image_u_5` tinyint(1) DEFAULT NULL,
  `image_r_5` tinyint(1) DEFAULT NULL,
  `image_n_6` varchar(100) DEFAULT NULL,
  `image_u_6` tinyint(1) DEFAULT NULL,
  `image_r_6` tinyint(1) DEFAULT NULL,
  `image_n_7` varchar(100) DEFAULT NULL,
  `image_u_7` tinyint(1) DEFAULT NULL,
  `image_r_7` tinyint(1) DEFAULT NULL,
  `image_n_8` varchar(100) DEFAULT NULL,
  `image_u_8` tinyint(1) DEFAULT NULL,
  `image_r_8` tinyint(1) DEFAULT NULL,
  `image_n_9` varchar(100) DEFAULT NULL,
  `image_u_9` tinyint(1) DEFAULT NULL,
  `image_r_9` tinyint(1) DEFAULT NULL,
  `image_n_10` varchar(100) DEFAULT NULL,
  `image_u_10` tinyint(1) DEFAULT NULL,
  `image_r_10` tinyint(1) DEFAULT NULL,
  `product_name` text DEFAULT NULL,
  `feature` text DEFAULT NULL,
  `SKU1_id` varchar(255) DEFAULT NULL,
  `SKU1_Snapshot_id` varchar(255) DEFAULT NULL,
  `SKU1_Type` varchar(20) DEFAULT NULL,
  `SKU1_current_inventory` int(10) DEFAULT NULL,
  `SKU1_increase` int(10) DEFAULT NULL,
  `SKU1_stock_increase` int(10) DEFAULT NULL,
  `SKU1_product_management_code` varchar(100) DEFAULT NULL,
  `SKU1_JAN_code` varchar(100) DEFAULT NULL,
  `SKU2_id` varchar(255) DEFAULT NULL,
  `SKU3_id` varchar(255) DEFAULT NULL,
  `SKU4_id` varchar(255) DEFAULT NULL,
  `SKU5_id` varchar(255) DEFAULT NULL,
  `SKU6_id` varchar(255) DEFAULT NULL,
  `SKU7_id` varchar(255) DEFAULT NULL,
  `SKU8_id` varchar(255) DEFAULT NULL,
  `SKU9_id` varchar(255) DEFAULT NULL,
  `SKU10_id` varchar(255) DEFAULT NULL,
  `SKU2_Snapshot_id` varchar(255) DEFAULT NULL,
  `SKU3_Snapshot_id` varchar(255) DEFAULT NULL,
  `SKU4_Snapshot_id` varchar(255) DEFAULT NULL,
  `SKU5_Snapshot_id` varchar(255) DEFAULT NULL,
  `SKU6_Snapshot_id` varchar(255) DEFAULT NULL,
  `SKU7_Snapshot_id` varchar(255) DEFAULT NULL,
  `SKU8_Snapshot_id` varchar(255) DEFAULT NULL,
  `SKU9_Snapshot_id` varchar(255) DEFAULT NULL,
  `SKU10_Snapshot_id` varchar(255) DEFAULT NULL,
  `SKU2_Type` varchar(20) DEFAULT NULL,
  `SKU3_Type` varchar(20) DEFAULT NULL,
  `SKU4_Type` varchar(20) DEFAULT NULL,
  `SKU5_Type` varchar(20) DEFAULT NULL,
  `SKU6_Type` varchar(20) DEFAULT NULL,
  `SKU7_Type` varchar(20) DEFAULT NULL,
  `SKU8_Type` varchar(20) DEFAULT NULL,
  `SKU9_Type` varchar(20) DEFAULT NULL,
  `SKU10_Type` varchar(20) DEFAULT NULL,
  `SKU2_current_inventory` int(10) DEFAULT NULL,
  `SKU3_current_inventory` int(10) DEFAULT NULL,
  `SKU4_current_inventory` int(10) DEFAULT NULL,
  `SKU5_current_inventory` int(10) DEFAULT NULL,
  `SKU6_current_inventory` int(10) DEFAULT NULL,
  `SKU7_current_inventory` int(10) DEFAULT NULL,
  `SKU8_current_inventory` int(10) DEFAULT NULL,
  `SKU9_current_inventory` int(10) DEFAULT NULL,
  `SKU10_current_inventory` int(10) DEFAULT NULL,
  `SKU2_increase` int(10) DEFAULT NULL,
  `SKU3_increase` int(10) DEFAULT NULL,
  `SKU4_increase` int(10) DEFAULT NULL,
  `SKU5_increase` int(10) DEFAULT NULL,
  `SKU6_increase` int(10) DEFAULT NULL,
  `SKU7_increase` int(10) DEFAULT NULL,
  `SKU8_increase` int(10) DEFAULT NULL,
  `SKU9_increase` int(10) DEFAULT NULL,
  `SKU10_increase` int(10) DEFAULT NULL,
  `SKU2_stock_increase` int(10) DEFAULT NULL,
  `SKU3_stock_increase` int(10) DEFAULT NULL,
  `SKU4_stock_increase` int(10) DEFAULT NULL,
  `SKU5_stock_increase` int(10) DEFAULT NULL,
  `SKU6_stock_increase` int(10) DEFAULT NULL,
  `SKU7_stock_increase` int(10) DEFAULT NULL,
  `SKU8_stock_increase` int(10) DEFAULT NULL,
  `SKU9_stock_increase` int(10) DEFAULT NULL,
  `SKU10_stock_increase` int(10) DEFAULT NULL,
  `SKU2_product_management_code` varchar(100) DEFAULT NULL,
  `SKU3_product_management_code` varchar(100) DEFAULT NULL,
  `SKU4_product_management_code` varchar(100) DEFAULT NULL,
  `SKU5_product_management_code` varchar(100) DEFAULT NULL,
  `SKU6_product_management_code` varchar(100) DEFAULT NULL,
  `SKU7_product_management_code` varchar(100) DEFAULT NULL,
  `SKU8_product_management_code` varchar(100) DEFAULT NULL,
  `SKU9_product_management_code` varchar(100) DEFAULT NULL,
  `SKU10_product_management_code` varchar(100) DEFAULT NULL,
  `SKU2_JAN_code` varchar(100) DEFAULT NULL,
  `SKU3_JAN_code` varchar(100) DEFAULT NULL,
  `SKU4_JAN_code` varchar(100) DEFAULT NULL,
  `SKU5_JAN_code` varchar(100) DEFAULT NULL,
  `SKU6_JAN_code` varchar(100) DEFAULT NULL,
  `SKU7_JAN_code` varchar(100) DEFAULT NULL,
  `SKU8_JAN_code` varchar(100) DEFAULT NULL,
  `SKU9_JAN_code` varchar(100) DEFAULT NULL,
  `SKU10_JAN_code` varchar(100) DEFAULT NULL,
  `brand_id` varchar(100) DEFAULT NULL,
  `Selling_price` int(20) DEFAULT NULL,
  `category_id` varchar(100) DEFAULT NULL,
  `commodity` int(1) DEFAULT NULL,
  `Shipping_method` int(1) DEFAULT NULL,
  `region_origin` varchar(10) DEFAULT NULL,
  `days_ship` int(1) DEFAULT NULL,
  `product_status` int(1) DEFAULT NULL,
  `product_registration_time` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `hash` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `mercari_updates` */

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

/*Table structure for table `ng_categories` */

DROP TABLE IF EXISTS `ng_categories`;

CREATE TABLE `ng_categories` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `user_id` int(20) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8;

/*Data for the table `ng_categories` */

/*Table structure for table `ng_products` */

DROP TABLE IF EXISTS `ng_products`;

CREATE TABLE `ng_products` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `user_id` int(20) DEFAULT NULL,
  `product` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;

/*Data for the table `ng_products` */

/*Table structure for table `ng_words` */

DROP TABLE IF EXISTS `ng_words`;

CREATE TABLE `ng_words` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `user_id` int(20) DEFAULT NULL,
  `word` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8;

/*Data for the table `ng_words` */

insert  into `ng_words`(`id`,`user_id`,`word`,`created_at`,`updated_at`) values 
(69,1,'15cm 6220-006012 847940','2023-04-02 02:28:28','2023-04-02 02:28:28'),
(70,1,'Ribitek テント','2023-04-02 02:28:28','2023-04-02 02:28:28'),
(71,1,'(YAMAZEN)','2023-04-02 02:28:28','2023-04-02 02:28:28');

/*Table structure for table `personal_access_tokens` */

DROP TABLE IF EXISTS `personal_access_tokens`;

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `personal_access_tokens` */

/*Table structure for table `postages` */

DROP TABLE IF EXISTS `postages`;

CREATE TABLE `postages` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `user_id` int(20) DEFAULT NULL,
  `size` varchar(100) DEFAULT NULL,
  `width` int(20) DEFAULT NULL,
  `length` int(20) DEFAULT NULL,
  `height` int(20) DEFAULT NULL,
  `final` int(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

/*Data for the table `postages` */

insert  into `postages`(`id`,`user_id`,`size`,`width`,`length`,`height`,`final`,`created_at`,`updated_at`) values 
(1,1,'SS',292,205,17,448,'2023-03-17 15:52:52','2023-03-17 15:57:45'),
(2,1,'60',211,151,75,847,'2023-03-17 15:58:07','2023-03-17 17:27:30'),
(3,1,'60',265,175,125,847,'2023-03-17 15:58:33','2023-03-17 16:01:23'),
(4,1,'60',265,225,75,847,'2023-03-17 15:58:54','2023-03-17 17:28:02'),
(5,1,'80',306,226,240,968,'2023-03-17 15:59:13','2023-03-17 17:28:42'),
(6,1,'80',366,256,143,968,'2023-03-17 15:59:35','2023-03-17 17:34:39'),
(7,1,'80',381,268,94,968,'2023-03-17 15:59:55','2023-03-17 17:34:35'),
(8,1,'100',451,331,181,1089,'2023-03-17 16:00:16','2023-03-17 17:34:26'),
(10,1,'100',495,325,95,1089,'2023-03-17 17:30:36','2023-03-17 17:34:17'),
(11,1,'100',340,295,295,1089,'2023-03-17 17:31:14','2023-03-17 17:34:11'),
(12,1,'120',451,331,274,1186,'2023-03-17 17:31:32','2023-03-17 17:34:07'),
(13,1,'120',585,385,195,1186,'2023-03-17 17:31:54','2023-03-17 17:34:02'),
(14,1,'140',596,396,244,1477,'2023-03-17 17:32:18','2023-03-17 17:33:55'),
(15,1,'140',605,405,355,1477,'2023-03-17 17:32:42','2023-03-17 17:33:49'),
(16,1,'140',646,406,254,1477,'2023-03-17 17:33:09','2023-03-17 17:33:46');

/*Table structure for table `prices` */

DROP TABLE IF EXISTS `prices`;

CREATE TABLE `prices` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `user_id` int(20) DEFAULT NULL,
  `up` int(20) DEFAULT NULL,
  `down` int(20) DEFAULT NULL,
  `profit` int(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Data for the table `prices` */

insert  into `prices`(`id`,`user_id`,`up`,`down`,`profit`,`created_at`,`updated_at`) values 
(1,33,2000,1,800,'2023-03-27 18:11:56','2023-03-27 18:11:56'),
(2,33,4000,2001,900,'2023-03-27 18:11:56','2023-03-27 18:11:56'),
(3,33,8000,4001,1000,'2023-03-27 18:11:56','2023-03-27 18:11:56'),
(4,33,12000,8001,2000,'2023-03-27 18:11:56','2023-03-27 18:11:56'),
(5,1,2000,1,800,'2023-03-30 04:22:10','2023-03-30 04:22:10'),
(6,1,4000,2001,900,'2023-03-30 04:22:10','2023-03-30 04:22:10'),
(7,1,8000,4001,1000,'2023-03-30 04:22:10','2023-03-30 04:22:10'),
(8,1,12000,8001,2000,'2023-03-30 04:22:10','2023-03-30 04:22:10');

/*Table structure for table `sessions` */

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `sessions` */

insert  into `sessions`(`id`,`user_id`,`ip_address`,`user_agent`,`payload`,`last_activity`) values 
('aElqhcCxVUNhyO9V9rZGK6bRkp3JCX92ONTNpL3y',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36','YTozOntzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoyNzoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo2OiJfdG9rZW4iO3M6NDA6InFLMmFuVk51VEZxeEJFZlgyR0pDVmlOazV0Q2FxR1FzZ3dzaFZOa2ciO30=',1680479526),
('cx0oJqhP4Nc3Cd0hAFQnRvmD8NZKC4PcLymmWUIz',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36','YTo2OntzOjY6Il90b2tlbiI7czo0MDoiY3hjNmR5TGFIUXdFZTVYNzhXeEFWbjlQd016ck80aDZReUc1WXdYNCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMjoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2VudHJ5X2RhdGEiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozMToiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2Jhc2VfZGF0YSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjA6IiQyeSQxMCRoQzVEcTZJLjMvMGtRMVguSXFyQi4uUUFtSmRidUQ3TnYzREhrQi8zS0FCTGZqcmNjRFRHeSI7fQ==',1680513760),
('kKN9WSbkReX1oTJTQn1wvFpsaZu1vowxmRqao94v',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36','YTo1OntzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozMjoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2VudHJ5X2RhdGEiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjY6Il90b2tlbiI7czo0MDoiOHdzV1M1d0F0MnVFSDJJcTVlNzU2RmFZS0lGR1NtS2tHWTRCZ1ZSUSI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2MDoiJDJ5JDEwJGhDNURxNkkuMy8wa1ExWC5JcXJCLi5RQW1KZGJ1RDdOdjNESGtCLzNLQUJMZmpyY2NEVEd5Ijt9',1680483679);

/*Table structure for table `settings` */

DROP TABLE IF EXISTS `settings`;

CREATE TABLE `settings` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `user_id` int(20) DEFAULT NULL,
  `prime` tinyint(1) DEFAULT NULL,
  `mark` tinyint(1) DEFAULT NULL,
  `sentence` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `settings` */

insert  into `settings`(`id`,`user_id`,`prime`,`mark`,`sentence`,`created_at`,`updated_at`) values 
(2,1,0,1,NULL,'2023-03-17 15:28:58','2023-04-01 22:03:45'),
(3,30,1,1,NULL,'2023-03-19 18:32:20','2023-03-20 01:54:13'),
(4,31,1,1,NULL,'2023-03-26 11:07:59','2023-03-26 11:07:59'),
(5,32,1,1,NULL,'2023-03-26 11:10:18','2023-03-26 11:10:18'),
(6,33,1,1,'全国送料無料配送 !!!','2023-03-27 15:13:36','2023-04-01 10:29:44'),
(7,34,1,1,NULL,'2023-04-03 09:12:25','2023-04-03 09:12:25');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('user','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `family_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_permitted` tinyint(1) NOT NULL DEFAULT 0,
  `secretkey` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `accesskey` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `partnertag` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`_token`,`email`,`email_verified_at`,`password`,`role`,`family_name`,`is_permitted`,`secretkey`,`accesskey`,`partnertag`,`created_at`,`updated_at`) values 
(1,'SsSNUgGtxJDHDIzT1v8paW8kV5c29KzvlKgSzpTx','nonaka@gmail.com',NULL,'$2y$10$hC5Dq6I.3/0kQ1X.IqrB..QAmJdbuD7Nv3DHkB/3KABLfjrccDTGy','admin','のなかけいた',1,'vckvzNFD5Oqu1FpWVerwPelLEPkbcPb1qeJrIEsN','AKIAISP56OZ77IPJFU4Q','gnem03010a-22','2022-12-12 18:30:07','2023-03-21 14:50:49');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
