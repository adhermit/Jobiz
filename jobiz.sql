-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 09, 2025 at 11:22 PM
-- Server version: 5.7.40
-- PHP Version: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `joby`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `type`) VALUES
(1, 'Full_Time'),
(2, 'Part_Time'),
(3, 'Internship'),
(4, 'Contract'),
(5, 'Temporary');

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

DROP TABLE IF EXISTS `company`;
CREATE TABLE IF NOT EXISTS `company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`id`, `name`, `description`, `address`, `city`, `country`) VALUES
(1, 'TechSolutions Inc.', 'A leading software development company specializing in AI-powered solutions.', '123 Main Street', 'San Francisco', 'USA'),
(2, 'GreenWorld Ltd.', 'An innovative company focused on sustainable energy and environmental solutions.', '45 Green Lane', 'London', 'UK'),
(3, 'HealthFirst Corp.', 'A healthcare provider offering cutting-edge medical services and technologies.', '78 Health Avenue', 'Berlin', 'Germany'),
(4, 'EduGrow Partners', 'An educational consultancy dedicated to fostering growth through innovative learning.', '22 Sakura Road', 'Tokyo', 'Japan'),
(5, 'FinanceSphere PLC', 'A multinational firm providing comprehensive financial services and products.', '89 Rue de Paris', 'Paris', 'France');

-- --------------------------------------------------------

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20250405212159', '2025-04-05 21:22:41', 103),
('DoctrineMigrations\\Version20250405212728', '2025-04-05 21:27:48', 45),
('DoctrineMigrations\\Version20250405213026', '2025-04-05 21:30:42', 71),
('DoctrineMigrations\\Version20250405213648', '2025-04-05 21:37:07', 264),
('DoctrineMigrations\\Version20250405214031', '2025-04-05 21:40:58', 241),
('DoctrineMigrations\\Version20250405220707', '2025-04-05 22:07:14', 117),
('DoctrineMigrations\\Version20250407002752', '2025-04-07 00:27:59', 160);

-- --------------------------------------------------------

--
-- Table structure for table `job`
--

DROP TABLE IF EXISTS `job`;
CREATE TABLE IF NOT EXISTS `job` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `min_salary` int(11) NOT NULL,
  `max_salary` int(11) NOT NULL,
  `companies_id` int(11) DEFAULT NULL,
  `type` tinyint(1) NOT NULL,
  `post_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `IDX_FBD8E0F86AE4741E` (`companies_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job`
--

INSERT INTO `job` (`id`, `title`, `description`, `city`, `country`, `min_salary`, `max_salary`, `companies_id`, `type`, `post_date`) VALUES
(1, 'Software Engineer', 'Responsible for developing and maintaining software applications.', 'San Francisco', 'USA', 70000, 120000, 2, 2, '2025-04-07 02:32:46'),
(2, 'Graphic Designer', 'Designs visual content for marketing and branding purposes.', 'London', 'UK', 30000, 50000, 4, 1, '2025-04-07 02:32:46'),
(3, 'Data Analyst', 'Analyzes data to provide actionable insights to businesses.', 'Berlin', 'Germany', 45000, 75000, 3, 5, '2025-04-07 02:32:46'),
(4, 'Marketing Manager', 'Plans and executes marketing strategies to boost product visibility.', 'Tokyo', 'Japan', 60000, 90000, 4, 4, '2025-04-07 02:32:46'),
(5, 'Human Resources Manager', 'Manages recruitment processes and employee relations.', 'Paris', 'France', 55000, 80000, 1, 3, '2025-04-07 02:32:46');

-- --------------------------------------------------------

--
-- Table structure for table `job_category`
--

DROP TABLE IF EXISTS `job_category`;
CREATE TABLE IF NOT EXISTS `job_category` (
  `job_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`job_id`,`category_id`),
  KEY `IDX_610BBCBABE04EA9` (`job_id`),
  KEY `IDX_610BBCBA12469DE2` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_category`
--

INSERT INTO `job_category` (`job_id`, `category_id`) VALUES
(1, 2),
(2, 1),
(3, 5),
(4, 4),
(5, 3);

-- --------------------------------------------------------

--
-- Table structure for table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `first_name`, `last_name`, `email`, `password`, `username`, `roles`) VALUES
(1, 'John', 'Doe', 'john.doe@example.com', '$2y$13$ci9tvpyQj6puuo9Nx13.bOkxz/UtmN8n2pdCBHOF1WeNYzQeN9pH6', 'John', '[\"ROLE_ADMIN\"]'),
(2, 'Jane', 'Smith', 'jane.smith@example.com', '$2y$13$RIm8kHlr3DBs4iRUEoebU.yDrUMOnJkSikhKFAxRBJTVIqUwxLPJC', 'Jane', 'null'),
(3, 'Alice', 'Johnson', 'alice.johnson@example.com', '$2y$13$7BH/8qvshprSvSUzyHbg0e4DEnb6khCeL9/uMBuXLaazoOM5XZFSG', 'Alice', 'null'),
(4, 'Bob', 'Brown', 'bob.brown@example.com', '$2y$13$xv9yrgE4iIjJtODzDCmCkep4TfPm0NlsArip84Kwt4Ys2aML5VMuW', 'Bob', 'null'),
(5, 'Charlie', 'Wilson', 'charlie.wilson@example.com', '$2y$13$h9L/EBo7iey0LN.lxNUyduMieg23iInTlc3RpHepYhkDBGGit1CsK', 'Charlie', 'null');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `job`
--
ALTER TABLE `job`
  ADD CONSTRAINT `FK_FBD8E0F86AE4741E` FOREIGN KEY (`companies_id`) REFERENCES `company` (`id`);

--
-- Constraints for table `job_category`
--
ALTER TABLE `job_category`
  ADD CONSTRAINT `FK_610BBCBA12469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_610BBCBABE04EA9` FOREIGN KEY (`job_id`) REFERENCES `job` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
