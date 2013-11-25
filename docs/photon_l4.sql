-- phpMyAdmin SQL Dump
-- version 4.0.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 25, 2013 at 04:35 PM
-- Server version: 5.5.33
-- PHP Version: 5.5.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `photon_l4`
--

-- --------------------------------------------------------

--
-- Table structure for table `fields`
--

DROP TABLE IF EXISTS `fields`;
CREATE TABLE `fields` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `relation_table` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `column_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `column_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tooltip_text` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `module_id` int(10) unsigned NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `lft` int(11) DEFAULT NULL,
  `rgt` int(11) DEFAULT NULL,
  `depth` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `fields_module_id_foreign` (`module_id`),
  KEY `fields_parent_id_index` (`parent_id`),
  KEY `fields_lft_index` (`lft`),
  KEY `fields_rgt_index` (`rgt`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=185 ;

--
-- Dumping data for table `fields`
--

INSERT INTO `fields` (`id`, `name`, `type`, `relation_table`, `column_name`, `column_type`, `tooltip_text`, `module_id`, `parent_id`, `lft`, `rgt`, `depth`, `created_at`, `updated_at`) VALUES
(1, 'Name', 'input-text', NULL, 'name', 'string', 'Enter Name', 1, NULL, 1, 2, 0, '2013-08-05 09:10:24', '2013-09-20 08:47:29'),
(60, 'Description', 'rich-text', NULL, 'description', 'text', 'Describe a style', 1, NULL, 127, 128, 0, '2013-08-11 11:22:18', '2013-09-20 08:47:29'),
(88, 'Active', 'boolean', NULL, 'active', 'smallInteger', 'Is ti active or not?', 1, NULL, 185, 186, 0, '2013-08-15 15:37:41', '2013-09-20 08:49:04'),
(89, 'Picture', 'image', NULL, 'picture', 'string', 'Upload a picture', 1, NULL, 183, 184, 0, '2013-08-15 15:37:41', '2013-09-20 08:49:04'),
(130, 'Date', 'calendar', NULL, 'date', 'timestamp', 'Set a date', 1, NULL, 187, 188, 0, '2013-09-20 08:17:53', '2013-09-20 08:49:04'),
(131, 'Weight', 'weight', NULL, 'weight', 'integer', 'set weight to reorder', 1, NULL, 189, 190, 0, '2013-09-20 08:50:32', '2013-09-20 08:50:32'),
(132, 'Hidden Fields', 'hidden', NULL, 'hidden_fields', 'string', 'This field will not be visible', 1, NULL, 191, 192, 0, '2013-09-20 08:50:32', '2013-09-20 08:50:32'),
(137, 'First Name', 'input-text', NULL, 'first_name', 'string', '', 46, NULL, 193, 194, 0, '2013-11-08 11:25:36', '2013-11-17 21:33:15'),
(138, 'Last Name', 'input-text', NULL, 'last_name', 'string', 'This is superhero''s last name', 46, NULL, 195, 196, 0, '2013-11-08 11:25:36', '2013-11-17 21:33:15'),
(140, 'Name', 'input-text', NULL, 'name', 'string', '', 48, NULL, 197, 198, 0, '2013-11-12 11:17:42', '2013-11-12 11:17:42'),
(149, 'Mugshot', 'image', NULL, 'mugshot', 'string', '', 46, NULL, 203, 204, 0, '2013-11-18 09:22:21', '2013-11-18 09:22:21'),
(154, 'Superhero Id', 'one-to-many', 'superheroes', 'superhero_id', 'integer', '', 48, NULL, 205, 206, 0, '2013-11-18 11:54:33', '2013-11-18 11:54:33'),
(170, 'Saviors', 'many-to-many', 'superheroes', 'saviors', '', '', 48, NULL, 211, 212, 0, '2013-11-19 13:11:50', '2013-11-19 13:11:50'),
(174, 'name', 'input-text', '', 'name', 'string', '', 50, NULL, 213, 214, 0, '2013-11-20 12:15:48', '2013-11-20 12:15:48'),
(175, 'Name', 'input-text', '', 'name', 'string', '', 52, NULL, 215, 216, 0, '2013-11-20 13:25:54', '2013-11-20 13:25:54'),
(176, 'Bonus Points', 'boolean', NULL, 'bonus_points', 'smallInteger', '', 52, NULL, 217, 218, 0, '2013-11-20 13:44:15', '2013-11-20 13:44:15'),
(177, 'Name', 'input-text', '', 'name', 'string', 'My Name', 53, NULL, 219, 220, 0, '2013-11-20 14:03:10', '2013-11-20 14:03:10'),
(178, 'Avatar', 'image', NULL, 'avatar', 'string', '', 53, NULL, 223, 224, 0, '2013-11-20 14:05:39', '2013-11-20 14:38:21'),
(179, 'Hero Id', 'one-to-many', 'superheroes', 'hero_id', 'integer', '', 53, NULL, 221, 222, 0, '2013-11-20 14:05:39', '2013-11-20 14:38:21'),
(180, 'Saviors', 'many-to-many', 'superheroes', 'saviors', '', '', 53, NULL, 225, 226, 0, '2013-11-20 14:28:35', '2013-11-20 14:28:35'),
(181, 'tester_name', 'input-text', '', 'tester_name', 'string', '', 54, NULL, 227, 228, 0, '2013-11-21 10:58:40', '2013-11-21 10:58:40'),
(182, 'Double Name', 'input-text', '', 'double_name', 'string', '', 55, NULL, 229, 230, 0, '2013-11-21 11:12:19', '2013-11-21 11:12:19'),
(183, 'Name', 'input-text', '', 'name', 'string', '', 56, NULL, 231, 232, 0, '2013-11-22 13:41:27', '2013-11-22 13:41:27'),
(184, 'Field Tester Id', 'one-to-many', 'superheroes', 'field_tester_id', 'integer', '', 55, NULL, 233, 234, 0, '2013-11-25 13:41:46', '2013-11-25 13:41:47');

-- --------------------------------------------------------

--
-- Table structure for table `field_testers`
--

DROP TABLE IF EXISTS `field_testers`;
CREATE TABLE `field_testers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `depth` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `tester_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `field_validation_rules`
--

DROP TABLE IF EXISTS `field_validation_rules`;
CREATE TABLE `field_validation_rules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `folders`
--

DROP TABLE IF EXISTS `folders`;
CREATE TABLE `folders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `depth` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `folders`
--

INSERT INTO `folders` (`id`, `lft`, `rgt`, `parent_id`, `depth`, `created_at`, `updated_at`, `name`) VALUES
(1, 1, 2, NULL, 0, '2013-11-22 13:42:34', '2013-11-22 13:42:41', 'Advanced Concepts'),
(2, 3, 4, NULL, 0, '2013-11-22 13:42:53', '2013-11-22 13:42:53', 'Custom Models'),
(3, 5, 6, NULL, 0, '2013-11-22 13:42:53', '2013-11-22 13:42:53', 'Empty Folder');

-- --------------------------------------------------------

--
-- Table structure for table `high_mountains`
--

DROP TABLE IF EXISTS `high_mountains`;
CREATE TABLE `high_mountains` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `depth` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `high_mountains`
--

INSERT INTO `high_mountains` (`id`, `lft`, `rgt`, `parent_id`, `depth`, `created_at`, `updated_at`, `name`) VALUES
(2, 1, 2, NULL, 0, '2013-11-20 14:39:09', '2013-11-20 14:39:09', 'ivan'),
(3, 3, 4, NULL, 0, '2013-11-20 14:39:11', '2013-11-20 14:39:11', 'ivan'),
(4, 5, 6, NULL, 0, '2013-11-20 14:39:24', '2013-11-20 14:39:24', '');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2013_11_19_132923_add_to_world_wonders_table', 1),
('2013_11_19_132923_pivot_superhero_world_wonder_table', 1),
('2013_11_19_132956_remove_from_world_wonders_table', 2),
('2013_11_19_133200_add_to_world_wonders_table', 3),
('2013_11_19_133200_pivot_superhero_world_wonder_table', 3),
('2013_11_19_133243_remove_from_world_wonders_table', 4),
('2013_11_19_133447_remove_from_world_wonders_table', 5),
('2013_11_19_133536_add_to_world_wonders_table', 6),
('2013_11_19_133536_pivot_superhero_world_wonder_table', 6),
('2013_11_19_133842_add_to_world_wonders_table', 7),
('2013_11_19_133843_pivot_superhero_world_wonder_table', 7),
('2013_11_19_133912_destroy_superhero_world_wonder_table', 8),
('2013_11_19_133912_remove_from_world_wonders_table', 8),
('2013_11_19_140149_create_mountains_table', 9),
('2013_11_19_140150_add_name_and_image_to_mountains_table', 9),
('2013_11_19_141150_add_to_world_wonders_table', 10),
('2013_11_19_141150_pivot_superhero_world_wonder_table', 10),
('2013_11_19_145813_add_photo_to_mountains_table', 11),
('2013_11_19_145932_remove_photo_from_mountains_table', 12),
('2013_11_19_150015_add_photo_to_mountains_table', 13),
('2013_11_20_103951_remove_name_and_photo_from_mountains_table', 14),
('2013_11_20_104305_add_to_mountains_table', 15),
('2013_11_20_120634_create_high_mountains_table', 16),
('2013_11_20_131548_add_name_to_high_mountains_table', 17),
('2013_11_20_142429_create_superclouds_table', 18),
('2013_11_20_142554_add_name_to_superclouds_table', 19),
('2013_11_20_144415_add_bonus_points_to_superclouds_table', 20),
('2013_11_20_145841_create_spaces_table', 21),
('2013_11_20_150310_add_name_to_spaces_table', 22),
('2013_11_20_150539_add_avatar_and_hero_id_to_spaces_table', 23),
('2013_11_20_152835_add_to_spaces_table', 24),
('2013_11_20_152835_pivot_space_superhero_table', 24),
('2013_11_21_115839_create_field_tester_table', 25),
('2013_11_21_115840_add_tester_name_to_field_tester_table', 25),
('2013_11_21_121217_create_second_field_testers_table', 26),
('2013_11_21_121218_add_double_name_to_second_field_testers_table', 26),
('2013_11_22_144126_create_folders_table', 27),
('2013_11_22_144127_add_name_to_folders_table', 27),
('2013_11_25_144146_add_field_tester_id_to_second_field_testers_table', 28);

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

DROP TABLE IF EXISTS `modules`;
CREATE TABLE `modules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `table_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `folder_id` int(10) unsigned DEFAULT NULL,
  `nestable` tinyint(1) NOT NULL DEFAULT '0',
  `icon` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `lft` int(10) unsigned DEFAULT NULL,
  `rgt` int(10) unsigned DEFAULT NULL,
  `depth` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_folder` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `table_name` (`table_name`),
  UNIQUE KEY `module_name` (`name`),
  KEY `modules_parent_module_index` (`folder_id`),
  KEY `modules_parent_id_index` (`parent_id`),
  KEY `modules_lft_index` (`lft`),
  KEY `modules_rgt_index` (`rgt`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=57 ;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `name`, `table_name`, `folder_id`, `nestable`, `icon`, `parent_id`, `lft`, `rgt`, `depth`, `created_at`, `updated_at`, `is_folder`) VALUES
(1, 'Styles', 'styles', NULL, 0, '', NULL, 3, 4, 0, '2013-08-05 09:10:24', '2013-11-22 13:41:39', 1),
(46, 'Superheroes', 'superheroes', 1, 1, '', NULL, 5, 6, 0, '2013-11-08 11:25:36', '2013-11-22 13:41:39', 1),
(48, 'World Wonders', 'world_wonders', 1, 1, '', NULL, 7, 8, 0, '2013-11-12 11:17:42', '2013-11-22 13:41:39', 1),
(50, 'High Mountains', 'high_mountains', NULL, 1, '', NULL, 9, 10, 0, '2013-11-20 11:06:35', '2013-11-22 13:41:39', 1),
(52, 'Superclouds', 'superclouds', 1, 0, '', NULL, 11, 12, 0, '2013-11-20 13:24:30', '2013-11-22 13:41:39', 1),
(53, 'Spaces', 'spaces', 2, 0, '', NULL, 13, 14, 0, '2013-11-20 13:58:43', '2013-11-22 13:41:39', 1),
(54, 'Field Tester', 'field_testers', NULL, 0, '', NULL, 15, 16, 0, '2013-11-21 10:58:40', '2013-11-22 13:41:39', 0),
(55, 'Second Field Testers', 'second_field_testers', 2, 0, '', NULL, 17, 18, 0, '2013-11-21 11:12:18', '2013-11-22 13:41:39', 1),
(56, 'Folders', 'folders', 2, 1, '', NULL, 1, 2, 0, '2013-11-22 13:41:27', '2013-11-22 13:41:39', 1);

-- --------------------------------------------------------

--
-- Table structure for table `second_field_testers`
--

DROP TABLE IF EXISTS `second_field_testers`;
CREATE TABLE `second_field_testers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `depth` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `double_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `field_tester_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `second_field_testers`
--

INSERT INTO `second_field_testers` (`id`, `lft`, `rgt`, `parent_id`, `depth`, `created_at`, `updated_at`, `double_name`, `field_tester_id`) VALUES
(1, 1, 2, NULL, 0, '2013-11-21 11:12:51', '2013-11-21 11:12:51', 'Superman', 0);

-- --------------------------------------------------------

--
-- Table structure for table `spaces`
--

DROP TABLE IF EXISTS `spaces`;
CREATE TABLE `spaces` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `depth` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hero_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `spaces`
--

INSERT INTO `spaces` (`id`, `lft`, `rgt`, `parent_id`, `depth`, `created_at`, `updated_at`, `name`, `avatar`, `hero_id`) VALUES
(1, 1, 2, NULL, 0, '2013-11-20 14:03:33', '2013-11-20 14:03:33', 'Ivan', '', 0),
(2, 3, 4, NULL, 0, '2013-11-20 14:05:57', '2013-11-20 14:05:57', 'Helper', 'media/images/spaces/avatar/2_165cececa7ea5cd8c70214d90e33e6.jpg', 2),
(3, 5, 6, NULL, 0, '2013-11-20 14:06:38', '2013-11-20 14:28:47', 'Avatar', 'media/images/spaces/avatar/3_165cececa7ea5cd8c70214d90e33e6.jpg', 2);

-- --------------------------------------------------------

--
-- Table structure for table `space_superhero`
--

DROP TABLE IF EXISTS `space_superhero`;
CREATE TABLE `space_superhero` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `space_id` int(10) unsigned NOT NULL,
  `superhero_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `space_superhero_space_id_index` (`space_id`),
  KEY `space_superhero_superhero_id_index` (`superhero_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `styles`
--

DROP TABLE IF EXISTS `styles`;
CREATE TABLE `styles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `lft` int(11) DEFAULT NULL,
  `rgt` int(11) DEFAULT NULL,
  `depth` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `picture` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `active` smallint(6) NOT NULL,
  `date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `weight` int(11) NOT NULL,
  `hidden_fields` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `styles_parent_id_index` (`parent_id`),
  KEY `styles_lft_index` (`lft`),
  KEY `styles_rgt_index` (`rgt`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

--
-- Dumping data for table `styles`
--

INSERT INTO `styles` (`id`, `name`, `parent_id`, `lft`, `rgt`, `depth`, `created_at`, `updated_at`, `description`, `picture`, `active`, `date`, `weight`, `hidden_fields`) VALUES
(1, 'Mens Goggles', NULL, 3, 4, 0, '2013-08-05 11:02:07', '2013-09-25 09:49:55', 'Some descriptive text.', 'pc-891.jpg', 1, '2013-09-16 22:00:00', -7, ''),
(2, 'Womens Goggles', NULL, 5, 6, 0, '2013-08-05 11:11:41', '2013-09-25 09:49:55', '', '', 0, '0000-00-00 00:00:00', 0, ''),
(3, 'Youth Goggles', NULL, 1, 2, 0, '2013-08-05 11:11:52', '2013-09-25 09:49:55', '', '', 0, '0000-00-00 00:00:00', 0, ''),
(4, 'Pro Models', NULL, 11, 12, 0, '2013-08-05 11:12:05', '2013-09-25 09:49:49', '', '', 0, '0000-00-00 00:00:00', 0, ''),
(5, 'Helmets Mens', NULL, 7, 8, 0, '2013-08-05 11:12:14', '2013-09-25 09:49:49', '', '', 0, '0000-00-00 00:00:00', 0, ''),
(6, 'Helmets Womens', NULL, 9, 10, 0, '2013-08-05 11:13:50', '2013-11-08 09:41:07', '', '', 0, '0000-00-00 00:00:00', 0, ''),
(7, 'Helmets Youth', NULL, 13, 14, 0, '2013-08-05 11:14:01', '2013-09-20 08:45:28', '', '', 0, '0000-00-00 00:00:00', 0, ''),
(8, 'Mens Lenses', NULL, 15, 16, 0, '2013-08-05 11:14:51', '2013-09-20 08:45:28', '', '', 0, '0000-00-00 00:00:00', 0, ''),
(9, 'Beanies All Styles', NULL, 21, 22, 0, '2013-08-05 11:15:07', '2013-09-20 08:45:28', '', '', 0, '0000-00-00 00:00:00', 0, ''),
(10, 'Womens Lenses', NULL, 17, 18, 0, '2013-08-13 17:19:10', '2013-09-20 08:45:28', '', '', 0, '0000-00-00 00:00:00', 0, ''),
(11, 'Youth Lenses', NULL, 19, 20, 0, '2013-08-13 17:19:18', '2013-09-20 08:45:28', '', '', 0, '0000-00-00 00:00:00', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `superclouds`
--

DROP TABLE IF EXISTS `superclouds`;
CREATE TABLE `superclouds` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `depth` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `bonus_points` smallint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `superclouds`
--

INSERT INTO `superclouds` (`id`, `lft`, `rgt`, `parent_id`, `depth`, `created_at`, `updated_at`, `name`, `bonus_points`) VALUES
(1, 1, 2, NULL, 0, '2013-11-20 13:26:08', '2013-11-20 13:26:08', 'Zion', 0),
(2, 3, 4, NULL, 0, '2013-11-20 13:28:00', '2013-11-20 13:28:00', 'Zion', 0);

-- --------------------------------------------------------

--
-- Table structure for table `superheroes`
--

DROP TABLE IF EXISTS `superheroes`;
CREATE TABLE `superheroes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `depth` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mugshot` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

--
-- Dumping data for table `superheroes`
--

INSERT INTO `superheroes` (`id`, `lft`, `rgt`, `parent_id`, `depth`, `created_at`, `updated_at`, `first_name`, `last_name`, `mugshot`) VALUES
(8, 13, 14, NULL, 0, '2013-11-21 10:02:50', '2013-11-22 13:23:51', 'Thomas', 'Edison', ''),
(9, 15, 16, NULL, 0, '2013-11-21 10:03:26', '2013-11-21 10:03:26', 'Jack', 'Dawson', ''),
(10, 17, 18, NULL, 0, '2013-11-21 10:05:34', '2013-11-21 10:05:34', 'Abraham', 'Lincoln', ''),
(11, 19, 20, NULL, 0, '2013-11-21 10:06:08', '2013-11-21 10:06:08', 'Nick', 'Slaughter', '');

-- --------------------------------------------------------

--
-- Table structure for table `superhero_world_wonder`
--

DROP TABLE IF EXISTS `superhero_world_wonder`;
CREATE TABLE `superhero_world_wonder` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `superhero_id` int(10) unsigned NOT NULL,
  `world_wonder_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `superhero_world_wonder_superhero_id_index` (`superhero_id`),
  KEY `superhero_world_wonder_world_wonder_id_index` (`world_wonder_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `world_wonders`
--

DROP TABLE IF EXISTS `world_wonders`;
CREATE TABLE `world_wonders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `depth` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `founder_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hero_id` int(11) NOT NULL,
  `superhero_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `world_wonders`
--

INSERT INTO `world_wonders` (`id`, `lft`, `rgt`, `parent_id`, `depth`, `created_at`, `updated_at`, `name`, `founder_name`, `hero_id`, `superhero_id`) VALUES
(1, 3, 4, NULL, 0, '2013-11-12 11:18:00', '2013-11-20 14:38:32', 'The Great Canyon', 'Alexander the Great', 0, 3),
(5, 1, 2, NULL, 0, '2013-11-19 13:12:25', '2013-11-20 14:38:32', 'The Great Wall', '', 0, 2);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `fields`
--
ALTER TABLE `fields`
  ADD CONSTRAINT `fields_ibfk_1` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `space_superhero`
--
ALTER TABLE `space_superhero`
  ADD CONSTRAINT `space_superhero_space_id_foreign` FOREIGN KEY (`space_id`) REFERENCES `spaces` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `space_superhero_superhero_id_foreign` FOREIGN KEY (`superhero_id`) REFERENCES `superheroes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `superhero_world_wonder`
--
ALTER TABLE `superhero_world_wonder`
  ADD CONSTRAINT `superhero_world_wonder_superhero_id_foreign` FOREIGN KEY (`superhero_id`) REFERENCES `superheroes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `superhero_world_wonder_world_wonder_id_foreign` FOREIGN KEY (`world_wonder_id`) REFERENCES `world_wonders` (`id`) ON DELETE CASCADE;
