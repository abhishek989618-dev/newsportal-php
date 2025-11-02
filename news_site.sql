-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 02, 2025 at 06:32 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `news_site`
--

-- --------------------------------------------------------

--
-- Table structure for table `advertisements`
--

CREATE TABLE `advertisements` (
  `id` int(11) NOT NULL,
  `website_id` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`website_id`)),
  `title` varchar(150) DEFAULT NULL,
  `media_path` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `position_id` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`position_id`)),
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `ad_type` varchar(20) DEFAULT 'image',
  `youtube_url` text DEFAULT NULL,
  `external_url` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `advertisements`
--

INSERT INTO `advertisements` (`id`, `website_id`, `title`, `media_path`, `link`, `position_id`, `status`, `created_at`, `ad_type`, `youtube_url`, `external_url`) VALUES
(1, '[\"1\"]', 'Movie Promotion', 'ad_686e1be638105.gif', 'https://www.google.com', '[\"4\"]', 'active', '2025-07-09 13:06:06', 'gif', NULL, NULL),
(3, '[\"1\"]', 'Promo Ads', 'ad_686e2561e49d2.gif', 'https://www.google.com', '[\"3\"]', 'active', '2025-07-09 13:46:33', 'gif', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `api_keys`
--

CREATE TABLE `api_keys` (
  `id` int(11) NOT NULL,
  `api_key` varchar(255) NOT NULL,
  `website_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `api_keys`
--

INSERT INTO `api_keys` (`id`, `api_key`, `website_id`) VALUES
(3, 'daeba99193c05be6d27793e7b7800e9d', 3),
(13, '9298c96e7189d554aeb452455b1b034b', 2);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(6, 'Astronomy & Astrophysics'),
(8, 'Space Technology'),
(9, 'Space Science'),
(10, 'Planetary Science'),
(12, 'Human Spaceflight'),
(13, 'Space Agencies & Organizations'),
(14, 'Around The World'),
(15, 'Politics'),
(16, 'Sport News'),
(17, 'Travel'),
(18, 'Technology'),
(19, 'Lifestyle'),
(20, 'Headlines'),
(21, 'Environment'),
(22, 'Breaking News'),
(23, 'Soccer News'),
(24, 'Health'),
(25, 'World News'),
(26, 'News'),
(27, 'Science'),
(28, 'other news');

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

CREATE TABLE `devices` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `devices`
--

INSERT INTO `devices` (`id`, `name`) VALUES
(1, 'Desktop'),
(2, 'Smart Phone'),
(3, 'Advertising Screen');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `website_id` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`website_id`)),
  `title` varchar(150) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `notes` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `website_id`, `title`, `description`, `event_date`, `location`, `image`, `notes`, `created_at`) VALUES
(2, '[\"2\"]', 'xcz', 'zxc', '2025-07-14', 'czc', 'event_68691734940ee_Dr. Geeta Mehra.jpg', 'czcz', '2025-07-05 17:44:44'),
(3, '[\"2\"]', 'xc', 'cvx', '2025-07-18', 'Ambala', '', 'Abhishek', '2025-07-05 17:48:44');

-- --------------------------------------------------------

--
-- Table structure for table `forms`
--

CREATE TABLE `forms` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `form_name` varchar(255) DEFAULT NULL,
  `form_data` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `public_link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `forms`
--

INSERT INTO `forms` (`id`, `user_id`, `form_name`, `form_data`, `created_at`, `public_link`) VALUES
(1, 1, 'New Form', '<input type=\"text\" name=\"text[]\" placeholder=\"text\"><input type=\"email\" name=\"email[]\" placeholder=\"email\"><input type=\"checkbox\" name=\"checkbox[]\" placeholder=\"checkbox\"><input type=\"checkbox\" name=\"checkbox[]\" placeholder=\"checkbox\"><input type=\"checkbox\" name=\"checkbox[]\" placeholder=\"checkbox\"><input type=\"checkbox\" name=\"checkbox[]\" placeholder=\"checkbox\"><input type=\"checkbox\" name=\"checkbox[]\" placeholder=\"checkbox\"><input type=\"checkbox\" name=\"checkbox[]\" placeholder=\"checkbox\">', '2025-04-01 17:14:23', 'd9733615d6ca0ebc'),
(2, 1, 'New Form', '<input type=\"text\" name=\"text[]\" placeholder=\"text\"><input type=\"email\" name=\"email[]\" placeholder=\"email\"><input type=\"checkbox\" name=\"checkbox[]\" placeholder=\"checkbox\"><input type=\"checkbox\" name=\"checkbox[]\" placeholder=\"checkbox\"><input type=\"checkbox\" name=\"checkbox[]\" placeholder=\"checkbox\"><input type=\"checkbox\" name=\"checkbox[]\" placeholder=\"checkbox\"><input type=\"checkbox\" name=\"checkbox[]\" placeholder=\"checkbox\"><input type=\"checkbox\" name=\"checkbox[]\" placeholder=\"checkbox\">', '2025-04-01 17:14:29', '9891cf11557692bc'),
(3, 1, NULL, '\n        <p class=\"text-muted\" draggable=\"false\">Drag &amp; Drop Fields Here</p>\n    <div class=\"form-field\" draggable=\"false\" style=\"\">\n            <label>Name: </label>\n            <input type=\"text\" name=\"text[]\" class=\"form-control\" placeholder=\"Enter Name\" required=\"\">\n            <span class=\"remove-field\">❌</span>\n        </div><div class=\"form-field\" draggable=\"false\" style=\"\">\n            <label>Email: </label>\n            <input type=\"email\" name=\"email[]\" class=\"form-control\" placeholder=\"Enter your Email\" required=\"\">\n            <span class=\"remove-field\">❌</span>\n        </div><div class=\"form-field\" draggable=\"false\">\n            <label>Phone No.: </label>\n            <input type=\"number\" name=\"number[]\" class=\"form-control\" placeholder=\"Enter your Number\" required=\"\">\n            <span class=\"remove-field\">❌</span>\n        </div><div class=\"form-field\" draggable=\"false\" style=\"\">\n            <label>Female: </label>\n            <input type=\"checkbox\" name=\"checkbox[]\" class=\"form-control\" placeholder=\"Female\">\n            <span class=\"remove-field\">❌</span>\n        </div><div class=\"form-field\" draggable=\"false\" style=\"\">\n            <label>Male: </label>\n            <input type=\"checkbox\" name=\"checkbox[]\" class=\"form-control\" placeholder=\"male\" required=\"\">\n            <span class=\"remove-field\">❌</span>\n        </div>', '2025-04-01 18:04:21', '9bd6bfd8540e3234'),
(5, 1, 'namef', '<h2></h2>\n    <p class=\"text-muted\">Drag and drop form fields here</p>\n  <div class=\"form-field\">\n        <label>Text:</label>\n        <input type=\"text\" class=\"form-control\" placeholder=\"text\">\n        <span class=\"remove-field\">×</span>\n      </div>', '2025-04-13 06:29:56', '41cbb32ddb127598'),
(6, 1, 'name', '<h2>hrllo</h2>\n    <p class=\"text-muted\" draggable=\"false\">Drag and drop form fields here</p>\n  <div class=\"form-field\" draggable=\"false\">\n        <label>Text:</label>\n        <input type=\"text\" class=\"form-control\" placeholder=\"text\">\n        <span class=\"remove-field\">×</span>\n      </div>', '2025-04-13 06:30:10', 'af82ea5e0c2b58bc'),
(8, 1, '', '<h2></h2>\r\n    <p class=\"text-muted\">Drag and drop form fields here</p>\r\n  <div class=\"form-field resizable\" style=\"background-color:#ffffff;\"><label>File:</label>\r\n        <input type=\"file\" class=\"form-control resizable\" placeholder=\"file\"><span class=\"remove-field\">×</span></div><div class=\"form-field resizable\" style=\"background-color:#ffffff;\"><label>Email:</label>\r\n        <input type=\"email\" class=\"form-control resizable\" placeholder=\"email\"><span class=\"remove-field\">×</span></div><div class=\"form-field resizable\" style=\"background-color:#ffffff;\"><label>Text:</label>\r\n        <input type=\"text\" class=\"form-control resizable\" placeholder=\"text\"><span class=\"remove-field\">×</span></div>', '2025-04-13 07:29:31', '2b7c45989649a64b'),
(9, 1, '', '<h2></h2>\r\n    <p class=\"text-muted\">Drag and drop form fields here</p>\r\n  <div class=\"form-field resizable\" style=\"background-color: rgb(255, 255, 255); width: 308px; height: 97px;\" draggable=\"false\"><label>Email:</label>\r\n        <input type=\"email\" class=\"form-control resizable\" placeholder=\"email\"><span class=\"remove-field\">×</span></div><div class=\"form-field resizable\" style=\"background-color: rgb(255, 255, 255); width: 309px; height: 91px;\" draggable=\"false\"><label>Text:</label>\r\n        <input type=\"text\" class=\"form-control resizable\" placeholder=\"text\"><span class=\"remove-field\">×</span></div>', '2025-04-13 07:32:15', '84736cad5dfeeef3'),
(10, 1, '', '<h2></h2>\r\n    <p class=\"text-muted\">Drag and drop form fields here</p>\r\n  <div class=\"form-field resizable\" style=\"background-color:#ffffff;\"><label>File:</label>\r\n        <input type=\"file\" class=\"form-control resizable\" placeholder=\"file\"><span class=\"remove-field\">×</span></div><div class=\"form-field resizable\" style=\"background-color:#ffffff;\"><label>Email:</label>\r\n        <input type=\"email\" class=\"form-control resizable\" placeholder=\"email\"><span class=\"remove-field\">×</span></div><div class=\"form-field resizable\" style=\"background-color:#ffffff;\"><label>Number:</label>\r\n        <input type=\"number\" class=\"form-control resizable\" placeholder=\"number\"><span class=\"remove-field\">×</span></div>', '2025-04-13 07:58:55', 'fa0dc2df83bcdfca'),
(12, 1, 'rtree', '<h2>rtree</h2>\r\n    <p class=\"text-muted\">Drag and drop form fields here</p>\r\n  <div class=\"form-field resizable\" style=\"background-color:#ffffff;\"><label>Email:</label>\r\n      <input type=\"email\" class=\"form-control resizable\" placeholder=\"email\"><span class=\"remove-field\">×</span></div><div class=\"form-field resizable\" style=\"background-color:#ffffff;\"><label>Number:</label>\r\n      <input type=\"number\" class=\"form-control resizable\" placeholder=\"number\"><span class=\"remove-field\">×</span></div>', '2025-04-13 09:53:29', '3d9820d79220466a'),
(13, 1, 'yuj', '<h2>yuj</h2>\r\n    <p class=\"text-muted\">Drag and drop form fields here</p>\r\n  <div class=\"form-field resizable\" style=\"background-color:#ffffff;\"><label>Email:</label>\r\n      <input type=\"email\" class=\"form-control resizable\" placeholder=\"email\"><span class=\"remove-field\">×</span></div><div class=\"form-field resizable\" style=\"background-color:#ffffff;\"><label>Number:</label>\r\n      <input type=\"number\" class=\"form-control resizable\" placeholder=\"number\"><span class=\"remove-field\">×</span></div><div class=\"form-field resizable\" style=\"background-color:#ffffff;\"><label>Password:</label>\r\n      <input type=\"password\" class=\"form-control resizable\" placeholder=\"password\"><span class=\"remove-field\">×</span></div><div class=\"form-field resizable\" style=\"background-color:#ffffff;\"><label>Date:</label>\r\n      <input type=\"date\" class=\"form-control resizable\" placeholder=\"date\"><span class=\"remove-field\">×</span></div><div class=\"form-field resizable\" style=\"background-color:#ffffff;\"><label>File Upload:</label>\r\n      <input type=\"file\" name=\"resume\" class=\"form-control\"><span class=\"remove-field\">×</span></div>', '2025-04-13 10:06:38', '288c6a23ba734a3a'),
(15, 1, 'res', '<h2></h2>\r\n    <p class=\"text-muted\">Drag and drop form fields here</p>\r\n  <div class=\"form-field resizable\" style=\"background-color:#ffffff;\"><label>Text:</label>\r\n      <input type=\"text\" class=\"form-control resizable\" placeholder=\"text\"><span class=\"remove-field\">×</span></div><div class=\"form-field resizable\" style=\"background-color:#ffffff;\"><label>Email:</label>\r\n      <input type=\"email\" class=\"form-control resizable\" placeholder=\"email\"><span class=\"remove-field\">×</span></div><div class=\"form-field resizable\" style=\"background-color:#ffffff;\"><label>Number:</label>\r\n      <input type=\"number\" class=\"form-control resizable\" placeholder=\"number\"><span class=\"remove-field\">×</span></div><div class=\"form-field resizable\" style=\"background-color:#ffffff;\"><label>Password:</label>\r\n      <input type=\"password\" class=\"form-control resizable\" placeholder=\"password\"><span class=\"remove-field\">×</span></div><div class=\"form-field resizable\" style=\"background-color:#ffffff;\"><label>File Upload:</label>\r\n      <input type=\"file\" name=\"resume\" class=\"form-control\"><span class=\"remove-field\">×</span></div>', '2025-04-13 11:19:13', 'a0b12c001dd59eff'),
(16, NULL, '', '<h2></h2>\r\n    <p class=\"text-muted\">Drag and drop form fields here</p>\r\n  <div class=\"form-field resizable\" style=\"background-color: rgb(255, 255, 255); width: 1065px; height: 108px;\" draggable=\"false\"><label>Text:</label>\r\n      <input type=\"text\" class=\"form-control resizable\" placeholder=\"text\"><span class=\"remove-field\">×</span></div><div class=\"form-field resizable\" style=\"background-color:#ffffff;\"><label>Email:</label>\r\n      <input type=\"email\" class=\"form-control resizable\" placeholder=\"email\"><span class=\"remove-field\">×</span></div><div class=\"form-field resizable\" style=\"background-color:#ffffff;\"><label>Number:</label>\r\n      <input type=\"number\" class=\"form-control resizable\" placeholder=\"number\"><span class=\"remove-field\">×</span></div><div class=\"form-field resizable\" style=\"background-color:#ffffff;\"><label>Number:</label>\r\n      <input type=\"number\" class=\"form-control resizable\" placeholder=\"number\"><span class=\"remove-field\">×</span></div><div class=\"form-field resizable\" style=\"background-color:#ffffff;\"><label>Password:</label>\r\n      <input type=\"password\" class=\"form-control resizable\" placeholder=\"password\"><span class=\"remove-field\">×</span></div><div class=\"form-field resizable\" style=\"background-color:#ffffff;\"><label>Date:</label>\r\n      <input type=\"date\" class=\"form-control resizable\" placeholder=\"date\"><span class=\"remove-field\">×</span></div><div class=\"form-field resizable\" style=\"background-color:#ffffff;\"><label>File Upload:</label>\r\n      <input type=\"file\" name=\"resume\" class=\"form-control\"><span class=\"remove-field\">×</span></div><div class=\"form-field resizable\" style=\"background-color:#ffffff;\"><label>Checkbox:</label>\r\n      <input type=\"checkbox\" class=\"form-check-input\"><span class=\"remove-field\">×</span></div>', '2025-07-07 18:10:59', NULL),
(17, NULL, '', '<h2></h2>\r\n    <p class=\"text-muted\">Drag and drop form fields here</p>\r\n  <div class=\"form-field resizable\" style=\"background-color:#ffffff;\"><label>Text:</label>\r\n      <input type=\"text\" class=\"form-control resizable\" placeholder=\"text\"><span class=\"remove-field\">×</span></div><div class=\"form-field resizable\" style=\"background-color:#ffffff;\"><label>Email:</label>\r\n      <input type=\"email\" class=\"form-control resizable\" placeholder=\"email\"><span class=\"remove-field\">×</span></div><div class=\"form-field resizable\" style=\"background-color:#ffffff;\"><label>Number:</label>\r\n      <input type=\"number\" class=\"form-control resizable\" placeholder=\"number\"><span class=\"remove-field\">×</span></div>', '2025-07-07 18:13:23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `form_responses`
--

CREATE TABLE `form_responses` (
  `id` int(11) NOT NULL,
  `form_id` int(11) DEFAULT NULL,
  `response_data` text DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `form_responses`
--

INSERT INTO `form_responses` (`id`, `form_id`, `response_data`, `submitted_at`) VALUES
(1, NULL, '{\"text\":[\"a\"],\"email\":[\"aaegdf@gmail.com\"],\"checkbox\":[\"on\"]}', '2025-04-01 18:24:46'),
(5, 8, '[]', '2025-04-13 07:30:19'),
(6, 10, '[]', '2025-04-13 07:59:45'),
(9, 15, '{\"resume\":\"uploads\\/1744543203_RAM.jpg\"}', '2025-04-13 11:20:03');

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` int(11) NOT NULL,
  `website_id` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`website_id`)),
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`id`, `website_id`, `title`, `description`, `images`, `status`, `created_at`) VALUES
(1, NULL, 'gfhg', 'ghgfh', '[\"gallery_68655f2b01278_Screenshot (4).png\",\"gallery_68655f2b0176d_Screenshot (5).png\",\"gallery_68655f2b01d57_Screenshot (6).png\"]', 'active', '2025-07-02 22:02:43'),
(1, NULL, 'gfhg', 'ghgfh', '[\"gallery_68655f2b01278_Screenshot (4).png\",\"gallery_68655f2b0176d_Screenshot (5).png\",\"gallery_68655f2b01d57_Screenshot (6).png\"]', 'active', '2025-07-02 22:02:43');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `replied_by` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `ticket_id`, `sender_id`, `replied_by`, `message`, `created_at`) VALUES
(1, 1, 8, NULL, 'helo', '2025-07-04 04:03:06'),
(2, 1, 8, NULL, 'hiow', '2025-07-04 04:03:12'),
(3, 2, 9, 9, 'hello', '2025-07-04 04:10:14'),
(4, 2, 9, 9, 'hr', '2025-07-04 04:12:13'),
(5, 2, 9, 9, 'hfjh', '2025-07-04 04:12:17'),
(6, 2, 9, 9, 'nhfj', '2025-07-04 04:12:19'),
(7, 2, 8, 8, 'jdxvcnx', '2025-07-04 04:21:15'),
(8, 2, 8, 8, 'awddf', '2025-07-04 04:21:20'),
(9, 2, 8, 8, 'ggg', '2025-07-04 04:22:28'),
(10, 2, 9, 9, 'gyy', '2025-07-04 04:22:41'),
(11, 2, 8, 8, 'uggy', '2025-07-04 04:22:49'),
(12, 2, 8, 8, 'hello', '2025-07-04 22:06:58'),
(13, 2, 10, 10, 'fdgffd', '2025-07-04 23:12:31'),
(14, 1, 10, 10, 'ddd', '2025-07-04 23:12:38'),
(15, 2, 9, 9, 'sdsgdf', '2025-07-05 08:36:43');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `unique_news_id` varchar(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `device_id` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `position_id` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `author_id` int(11) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `highlights` text DEFAULT NULL,
  `points` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `news_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `tag_id` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tag_id`)),
  `website_id` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`website_id`)),
  `status` enum('draft','pending_editor','pending_verification','verified','rejected','published') DEFAULT 'draft',
  `media` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`media`)),
  `requested_action` enum('create','delete') DEFAULT NULL,
  `requested_by` int(11) DEFAULT NULL,
  `approved_by_editor` int(11) DEFAULT NULL,
  `verified_by` int(11) DEFAULT NULL,
  `publish_at` datetime DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  `latitude` decimal(10,6) DEFAULT NULL,
  `longitude` decimal(10,6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `unique_news_id`, `title`, `slug`, `category_id`, `device_id`, `position_id`, `author_id`, `content`, `location`, `highlights`, `points`, `notes`, `news_date`, `created_at`, `updated_at`, `tag_id`, `website_id`, `status`, `media`, `requested_action`, `requested_by`, `approved_by_editor`, `verified_by`, `publish_at`, `type_id`, `latitude`, `longitude`) VALUES
(16, 'NEWS-20250707123853', 'Abhishek', 'abhishek', NULL, '', '', 8, 'hghg vf fvffsddd koiii', 'ghhytdfdf eff koiii', 'gbhbgthb wefef fef gfg gfgf', '[\"gjirtgfhjg fg gfgf\"]', 'gfrg fgfg', '2025-07-08', '2025-07-07 16:08:53', '2025-07-07 19:19:57', '0', '[\"1\"]', 'published', NULL, NULL, NULL, NULL, 8, '2025-07-07 13:06:41', 8, NULL, NULL),
(18, 'NEWS-20250707154146', 'hello i am phale bhi ', 'hello-i-am-phale-bhi-', NULL, '', '', 8, 'h ja k akk kaf kjka jf', 'maj aj aja ja ', 'halk lj k ka jk', '[\"fdsdaf\"]', 'olkkj,m', '2025-07-01', '2025-07-07 19:11:46', '2025-07-07 19:17:58', '[\"9\"]', '[\"1\"]', 'published', NULL, NULL, NULL, NULL, 8, '2025-07-07 15:47:58', 8, NULL, NULL),
(20, 'NEWS-20250710053918', 'hellopp yuo are a good hellopp yuo are a good hellopp yuo are a good', 'hellopp-yuo-are-a-good-hellopp-yuo-are-a-good-hellopp-yuo-are-a-good', 6, '[\"1\"]', '[\"11\"]', 8, 'hellopp yuo are a good hellopp yuo are a good hellopp yuo are a good ', 'kurukshetra, university', 'hellopp yuo are a good hellopp yuo are a good hellopp yuo are a good hellopp yuo are a good hellopp yuo are a good hellopp yuo are a good', '[\"hellopp yuo are a good\",\"hellopp yuo are a good\"]', 'sddgrfg', '2025-07-14', '2025-07-10 09:09:18', '2025-07-10 11:43:47', '[\"11\"]', '[\"1\"]', 'published', NULL, NULL, NULL, NULL, 8, '2025-07-10 05:39:24', 8, 36.701463, -118.755997),
(22, 'NEWS-20250710075338', 'hfvdjsh hfsfhisiddfssf hfsfhisiddfssf', 'hfvdjsh-hfsfhisiddfssf-hfsfhisiddfssf', 19, '[\"1\",\"2\"]', '[\"13\",\"14\"]', 8, 'hfvdjsh hfsfhisiddfssf hfsfhisiddfssf hfvdjsh hfsfhisiddfssf hfsfhisiddfssf hfvdjsh hfsfhisiddfssf hfsfhisiddfssf', 'Ludhiana, , India', 'hfvdjsh hfsfhisiddfssf hfsfhisiddfssf hfvdjsh hfsfhisiddfssf hfsfhisiddfssf v v hfvdjsh hfsfhisiddfssf hfsfhisiddfssf hfvdjsh hfsfhisiddfssf hfsfhisiddfssf', '[\"gfhfg\"]', 'dfdsg', '2025-07-16', '2025-07-10 11:23:38', '2025-07-11 00:50:00', '[\"10\"]', '[\"1\"]', 'published', '[\"media_687012609768f.png\"]', NULL, NULL, NULL, 8, '2025-07-10 07:53:43', 11, 30.909016, 75.851601),
(30, 'NEWS-20250710155830', 'hello from youtube', 'hello-from-youtube', 27, '[\"1\",\"2\"]', '[\"11\"]', 8, 'hello from youtube hello from youtube hello from youtube hello from youtube hello from youtube', 'Patna, , India', 'hello from youtube hello from youtube v', '[\"hello from youtube hello from youtubevv hello from youtube v\"]', 'ffdgfnhggjn', '2025-07-25', '2025-07-10 19:28:30', '2025-07-10 23:08:13', '[\"10\",\"11\"]', '[\"1\"]', 'published', '[\"media_686ffa8537bfb.png\"]', NULL, NULL, NULL, 8, '2025-07-10 15:58:33', 8, 25.609324, 85.123525),
(32, 'NEWS-20250710182428', 'Multiple Features In this news CMS.', 'jiodfgidfg', 28, '[\"1\"]', '[\"12\"]', 8, 'This news portal have all those control which a organization wants.', 'Kurukshetra University, Kurukshetra, India', 'User Management System', '[\"Assign different role to user.\"]', 'User Management System', '2025-07-18', '2025-07-10 21:54:28', '2025-11-02 13:46:03', '[\"10\"]', '[\"1\"]', 'published', '[]', NULL, NULL, NULL, 8, '2025-07-10 18:24:32', 11, 29.958263, 76.815630),
(33, 'NEWS-20250710193549', 'My First web CMS', 'sfsdgvfd', 28, '[\"1\"]', '[\"2\"]', 8, 'This is my first web CMS. this CMS is news CMS.', 'Kurukshetra University, Kurukshetra, India', 'Role based CMS.', '[\"Multiple Role Management\"]', 'This is Our first Role base News Portal. yes', '2025-07-11', '2025-07-10 23:05:49', '2025-11-02 23:00:32', '[\"10\"]', '[\"1\"]', 'verified', '[\"media_686ffa4c6cfbb.png\",\"media_686ffa4c6db99.png\"]', NULL, NULL, NULL, 8, '2025-07-10 19:35:53', 8, 29.958263, 76.815630);

-- --------------------------------------------------------

--
-- Table structure for table `news_comments`
--

CREATE TABLE `news_comments` (
  `id` int(11) NOT NULL,
  `news_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `news_images`
--

CREATE TABLE `news_images` (
  `id` int(11) NOT NULL,
  `news_id` int(11) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `news_tags`
--

CREATE TABLE `news_tags` (
  `id` int(11) NOT NULL,
  `news_id` int(11) DEFAULT NULL,
  `tag_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `news_websites`
--

CREATE TABLE `news_websites` (
  `id` int(11) NOT NULL,
  `news_id` int(11) DEFAULT NULL,
  `website_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `type` int(11) NOT NULL,
  `message` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `category_id` int(11) DEFAULT NULL,
  `website_id` int(11) DEFAULT NULL,
  `tag_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tag_ids`)),
  `audience_type` enum('public','dashboard','private') DEFAULT 'private',
  `target_users` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`target_users`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `title`, `type`, `message`, `user_id`, `role_id`, `is_read`, `created_at`, `category_id`, `website_id`, `tag_ids`, `audience_type`, `target_users`) VALUES
(2, 'efdf', 0, 'fdsfad', 10, 4, 0, '2025-07-06 13:12:22', 0, 0, '[\"Space\",\"Cosmic\"]', 'private', NULL),
(3, 'ref', 0, 'erfe', 13, 7, 1, '2025-07-06 13:14:09', 0, 0, '[\"Space\"]', 'private', NULL),
(7, 'frgerfrffre', 0, 'ffe', 10, 4, 0, '2025-07-06 13:18:58', 3, 1, '[\"Space\"]', 'private', NULL),
(10, 'qwer', 0, 'heloo', NULL, NULL, 1, '2025-07-06 14:24:12', 3, 3, NULL, 'private', '[\"8\",\"14\",\"11\",\"10\",\"12\",\"9\",\"13\",\"15\",\"17\"]'),
(11, 'i havew a new project', 0, 'i havew a new pro i havew a new project i havew a new project i havew a new project i havew a new project i havew a new projectject', NULL, NULL, 1, '2025-07-06 14:25:10', 2, 1, NULL, 'private', '[\"14\",\"11\",\"10\",\"12\",\"13\",\"15\",\"17\"]'),
(13, 'got  got jig ihgfjg', 0, 'gfkndkjg', NULL, NULL, 1, '2025-07-06 14:34:01', 2, 1, NULL, 'private', '[\"8\",\"14\",\"11\",\"10\",\"12\",\"13\",\"15\",\"17\"]'),
(15, 'xzc', 0, 'zsc', NULL, NULL, 1, '2025-07-06 15:04:19', 2, 1, NULL, 'private', '[\"8\",\"14\",\"11\",\"10\",\"12\",\"13\",\"15\",\"17\"]'),
(16, '🆕 New Reporter Registration', 1, 'Navpreet (nav@gmail.com) has registered and is awaiting verification.', NULL, NULL, 0, '2025-07-06 15:22:29', NULL, NULL, NULL, 'private', '[8,14]'),
(17, '🆕 New Reporter Registration', 1, 'alllllluuu (alllu@kachalu.com) has registered and is awaiting verification.', NULL, NULL, 0, '2025-07-06 15:23:26', NULL, NULL, NULL, 'private', '[8,14]'),
(18, '🆕 New Reporter Registration', 1, 'uioio (giop@gmail.com) has registered and is awaiting verification.', 1, NULL, 0, '2025-07-06 15:34:46', NULL, NULL, NULL, 'private', NULL),
(19, '🆕 New Reporter Registration', 1, 'opp (op@gmail.com) has registered and is awaiting verification.', 23, 1, 1, '2025-07-06 15:48:41', NULL, NULL, NULL, 'private', '0'),
(22, '🔑 New Role Assigned', 2, 'You have been assigned the role: Chief Editor. Permissions: No permissions assigned.', 14, NULL, 0, '2025-07-06 17:49:29', NULL, NULL, NULL, 'private', NULL),
(23, '🔑 New Role Assigned', 2, 'You have been assigned the role: End User. Permissions: products: create,read,update,delete,request_approval,approve,deny,pending,comment,reject,assign,accept,decline\norders: read', 13, NULL, 1, '2025-07-06 17:49:47', NULL, NULL, NULL, 'private', NULL),
(24, '🔑 New Role Assigned', 2, 'You have been assigned the role: Reporter. Permissions: news: create,read,delete,request_approval\nproducts: create,read,update,delete,request_approval,approve,deny,pending,comment,reject,assign,accept,decline\norders: create,read,update,delete,request_approval,approve,deny,pending,comment,reject,assign,accept,decline', 13, NULL, 1, '2025-07-06 17:50:03', NULL, NULL, NULL, 'private', NULL),
(25, '🆕 New Reporter Registration', 1, 'E-commerce (jo@gmail.com) has registered and is awaiting verification.', 24, 1, 1, '2025-11-02 22:36:08', NULL, NULL, NULL, 'private', '0'),
(26, '🔑 New Role Assigned', 2, 'You have been assigned the role: Admin. Permissions: No permissions assigned.', 24, NULL, 0, '2025-11-02 22:43:23', NULL, NULL, NULL, 'private', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','paid','shipped','delivered','cancelled') DEFAULT 'pending',
  `razorpay_order_id` varchar(100) DEFAULT NULL,
  `razorpay_payment_id` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `amount`, `status`, `razorpay_order_id`, `razorpay_payment_id`, `created_at`) VALUES
(27, 9, 565.00, 'delivered', 'order_Qp1gpJ4bztHNBV', NULL, '2025-07-04 20:09:08'),
(28, 9, 565.00, 'delivered', 'order_Qp1gwaLLjyQlSx', NULL, '2025-07-04 20:09:15'),
(29, 9, 565.00, 'delivered', 'order_Qp1pDu2RHdSm6S', NULL, '2025-07-04 20:17:05'),
(30, 9, 2332.00, 'delivered', 'order_Qp2DE66x44Kben', NULL, '2025-07-04 20:39:48'),
(31, 9, 2332.00, 'delivered', 'order_Qp2QAjPQwrclpU', NULL, '2025-07-04 20:52:04'),
(32, 9, 2332.00, 'delivered', 'order_Qp2XDNvdGA8zjz', NULL, '2025-07-04 20:58:44'),
(33, 9, 6767.00, 'delivered', 'order_Qp2ZH17dd2B8is', 'pay_Qp2ZRVW2VabyUj', '2025-07-04 21:00:41'),
(34, 9, 2332.00, 'delivered', 'order_QpLJ3MbQdTzt8y', 'pay_QpLKI2J6OcDTIu', '2025-07-05 15:20:28'),
(35, 13, 565.00, 'paid', 'order_QpOi8pRd05JxlF', 'pay_QpOjilvoTw3sAn', '2025-07-05 18:40:17'),
(36, 13, 2332.00, 'pending', 'order_RawQWNgymU0JTE', NULL, '2025-11-02 22:12:19'),
(37, 13, 2332.00, 'pending', 'order_RawRJtRVMCtRbg', NULL, '2025-11-02 22:13:04'),
(38, 13, 2332.00, 'pending', 'order_RawSYgiNEktT5z', NULL, '2025-11-02 22:14:15'),
(39, 13, 2332.00, 'pending', 'order_RawWJsCl4hbNuP', NULL, '2025-11-02 22:17:48'),
(40, 13, 2332.00, 'pending', 'order_RawXeQavSaVkGb', NULL, '2025-11-02 22:19:04'),
(41, 13, 2332.00, 'pending', 'order_RawYTGoeMWhiHS', NULL, '2025-11-02 22:19:50'),
(42, 13, 2332.00, 'pending', 'order_RawZ2k2rr5VBxC', NULL, '2025-11-02 22:20:23'),
(43, 13, 2332.00, 'paid', 'order_RawcGj5jiCMAvr', 'pay_RawhzkFnDjprHD', '2025-11-02 22:23:26'),
(44, 13, 9099.00, 'pending', 'order_RawkwHZD0hHGC0', NULL, '2025-11-02 22:31:39'),
(45, 13, 9664.00, 'pending', 'order_Rawnm6IKMrKn3Y', NULL, '2025-11-02 22:34:20');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 27, 1, 1, 565.00),
(2, 28, 1, 1, 565.00),
(3, 29, 1, 1, 565.00),
(4, 30, 3, 1, 2332.00),
(5, 31, 3, 1, 2332.00),
(6, 32, 3, 1, 2332.00),
(7, 33, 2, 1, 6767.00),
(8, 34, 3, 1, 2332.00),
(9, 35, 1, 1, 565.00),
(10, 36, 3, 1, 2332.00),
(11, 37, 3, 1, 2332.00),
(12, 38, 3, 1, 2332.00),
(13, 39, 3, 1, 2332.00),
(14, 40, 3, 1, 2332.00),
(15, 41, 3, 1, 2332.00),
(16, 42, 3, 1, 2332.00),
(17, 43, 3, 1, 2332.00),
(18, 44, 3, 1, 2332.00),
(19, 44, 2, 1, 6767.00),
(20, 45, 3, 1, 2332.00),
(21, 45, 2, 1, 6767.00),
(22, 45, 1, 1, 565.00);

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `table_name` varchar(100) NOT NULL,
  `operations` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `table_name`, `operations`) VALUES
(6, 'Categories', 'create, read, update, delete'),
(7, 'api_keys', 'create, read, update, delete'),
(8, 'api_keys', 'create, read, write, delete'),
(9, 'News', 'create,request'),
(10, 'news', 'create,read,request'),
(11, 'news', 'read,update,approve'),
(12, 'news', 'read, verify, schedule'),
(13, 'news', 'create,read,update,delete,request_approval,approve,deny,pending,comment,reject,assign,accept,decline'),
(14, 'news,user', 'create,read,update,delete,request_approval,approve,deny,pending,comment,reject,assign,accept,decline'),
(15, 'news', 'deny'),
(16, 'news', 'create,read,delete,request_approval'),
(17, 'products', 'read'),
(18, 'products', 'create,read,update,delete,request_approval,approve,deny,pending,comment,reject,assign,accept,decline'),
(19, 'orders', 'create,read,update,delete,request_approval,approve,deny,pending,comment,reject,assign,accept,decline'),
(20, 'tickets', 'create,read,update,delete,request_approval,approve,deny,pending,comment,reject,assign,accept,decline'),
(21, 'Programs', 'create,read,update,request_approval'),
(22, 'permissions', 'create,read,update,delete'),
(23, 'orders', 'read'),
(24, 'notifications', 'read');

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

CREATE TABLE `positions` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `positions`
--

INSERT INTO `positions` (`id`, `name`) VALUES
(1, 'toper'),
(2, 'Breaking Ribbon'),
(3, 'parallax'),
(4, 'desktop-add-place'),
(5, 'sidebar-add-place'),
(6, 'sidebar-add-place2'),
(7, 'parallax-section1'),
(8, 'add-place'),
(9, 'bottom-add-place'),
(10, 'Home Page'),
(11, 'first'),
(12, 'second'),
(13, 'third'),
(14, 'fourth');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `title` varchar(150) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `tag_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tag_ids`)),
  `price` decimal(10,2) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `title`, `description`, `category_id`, `tag_ids`, `price`, `stock`, `image`, `status`, `created_by`, `created_at`) VALUES
(1, 'tttyh', 'hytyhb', 4, '[\"4\",\"5\"]', 565.00, 3, '1751618377_Screenshot (5).png', 'active', 8, '2025-07-04 14:09:37'),
(2, 'dsgfd', 'hgfhg', 3, '[\"4\",\"5\"]', 6767.00, 2, '1751625523_Screenshot (5).png', 'active', 9, '2025-07-04 16:08:43'),
(3, 'gorr', 'dfdd', 27, '[\"11\"]', 2332.00, NULL, '1751641743_Screenshot (6).png', 'active', 8, '2025-07-04 20:39:03');

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `tag_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tag_ids`)),
  `website_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`website_ids`)),
  `status` enum('active','inactive') DEFAULT 'active',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`id`, `title`, `description`, `image`, `category_id`, `tag_ids`, `website_ids`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(2, 'fefgre', 'fgrgrfrfg', 'program_6868341e094c7.png', NULL, NULL, '[\"1\"]', 'active', NULL, '2025-07-05 01:35:50', '2025-07-05 01:35:53'),
(3, 'qwfewa', 'efewdf', 'program_68683453b072c.png', NULL, NULL, '[\"1\"]', 'active', NULL, '2025-07-05 01:36:43', '2025-07-05 17:54:10'),
(4, 'jhbgsdjhb', 'nerii', NULL, NULL, NULL, '[\"2\"]', 'inactive', NULL, '2025-07-05 17:54:30', '2025-07-05 17:54:30');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`) VALUES
(4, ' Editor'),
(2, 'Admin'),
(5, 'Auditor'),
(3, 'Chief Editor'),
(7, 'End User'),
(9, 'Program Manager'),
(6, 'Reporter'),
(1, 'Super Admin');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id` int(11) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `permission_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`id`, `role_id`, `permission_id`) VALUES
(113, 4, 11),
(114, 4, 20),
(116, 9, 21),
(130, 5, 13),
(131, 5, 20),
(134, 7, 18),
(135, 7, 23),
(136, 6, 16),
(137, 6, 17),
(138, 6, 19),
(139, 1, 6),
(140, 1, 7),
(141, 1, 8),
(142, 1, 9),
(143, 1, 12),
(144, 1, 13),
(145, 1, 14),
(146, 1, 17),
(147, 1, 22);

-- --------------------------------------------------------

--
-- Table structure for table `scholarship`
--

CREATE TABLE `scholarship` (
  `id` int(11) NOT NULL,
  `website_id` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`website_id`)),
  `organization_name` varchar(150) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `apply_link` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scroller`
--

CREATE TABLE `scroller` (
  `id` int(11) NOT NULL,
  `website_id` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`website_id`)),
  `text` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `scroller`
--

INSERT INTO `scroller` (`id`, `website_id`, `text`, `status`, `created_at`) VALUES
(2, '[\"1\"]', 'spanish', 'active', '2025-07-03 09:44:52'),
(2, '[\"1\"]', 'spanish', 'active', '2025-07-03 09:44:52');

-- --------------------------------------------------------

--
-- Table structure for table `social_platforms`
--

CREATE TABLE `social_platforms` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `icon_class` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `social_platforms`
--

INSERT INTO `social_platforms` (`id`, `name`, `icon_class`) VALUES
(1, 'Facebook', 'fa-brands fa-facebook'),
(2, 'Twitter', 'fa-brands fa-x-twitter'),
(3, 'Instagram', 'fa-brands fa-instagram'),
(4, 'YouTube', 'fa-brands fa-youtube'),
(5, 'LinkedIn', 'fa-brands fa-linkedin');

-- --------------------------------------------------------

--
-- Table structure for table `sponsors`
--

CREATE TABLE `sponsors` (
  `id` int(11) NOT NULL,
  `website_id` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`website_id`)),
  `name` varchar(100) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `name`) VALUES
(9, 'NASA'),
(10, 'SpaceX'),
(11, 'Mars Rover'),
(12, 'James Webb Space Telescope (JWST)'),
(13, 'Lunar Exploration'),
(14, 'ISS (International Space Station)'),
(15, 'SpaceX Starship'),
(16, 'Exoplanets'),
(17, 'Black Hole'),
(18, 'Space Debris'),
(19, 'Astrobiology'),
(20, 'Rocket Launch'),
(21, 'Space Tourism'),
(22, 'Solar System'),
(23, 'Artificial Intelligence in Space'),
(24, 'Gravity Waves'),
(25, 'Planetary Exploration'),
(26, 'Space Science'),
(27, 'Astronomical Discoveries'),
(28, 'Asteroids');

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `id` int(11) NOT NULL,
  `website_id` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`website_id`)),
  `name` varchar(100) NOT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`id`, `website_id`, `name`, `designation`, `photo`, `status`, `created_at`) VALUES
(1, '[\"2\"]', 'Eathen', 'Chief Employ', 'team_69073dc8c09d2_testimonial-user-img-03.png', 'active', '2025-07-02 21:46:46'),
(2, '[\"2\"]', 'Json Charm', 'writer', 'team_69073d8944f9e_testimonial-user-img-02.png', 'active', '2025-07-03 09:45:16');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` int(11) NOT NULL,
  `website_id` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`website_id`)),
  `name` varchar(100) NOT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `message` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `website_id`, `name`, `designation`, `message`, `image`, `status`, `created_at`) VALUES
(1, '[\"2\"]', 'Jhon', 'CEO of R.K tech', 'This is Best CMS.', 'testimonial_6865575663e0e_Screenshot (4).png', 'active', '2025-07-02 21:28:37');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `issue_type` enum('technical','payment_related','functioning','account_related','other') DEFAULT 'technical',
  `status` enum('open','in_progress','resolved','closed') DEFAULT 'open',
  `assigned_to` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_hidden`
--

CREATE TABLE `ticket_hidden` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ticket_hidden`
--

INSERT INTO `ticket_hidden` (`id`, `ticket_id`, `user_id`, `created_at`) VALUES
(1, 5, 9, '2025-07-05 03:59:11'),
(2, 4, 9, '2025-07-05 03:59:19'),
(3, 5, 12, '2025-07-05 04:02:21'),
(4, 4, 12, '2025-07-05 04:02:22');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_messages`
--

CREATE TABLE `ticket_messages` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `reply` text DEFAULT NULL,
  `sender_id` int(11) NOT NULL,
  `message` text DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `sent_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ticket_messages`
--

INSERT INTO `ticket_messages` (`id`, `ticket_id`, `reply`, `sender_id`, `message`, `attachment`, `sent_at`) VALUES
(1, 1, NULL, 9, 'dfdfd', NULL, '2025-07-04 03:34:39'),
(2, 1, NULL, 8, 'hh', NULL, '2025-07-04 03:54:04'),
(3, 1, NULL, 8, 'hh', NULL, '2025-07-04 03:54:14'),
(4, 1, NULL, 8, 'fdf', NULL, '2025-07-04 03:55:56'),
(5, 1, NULL, 8, 'fdf', NULL, '2025-07-04 03:56:00'),
(6, 5, 'here is your', 12, NULL, NULL, '2025-07-05 09:09:56'),
(7, 5, 'here is your', 12, NULL, NULL, '2025-07-05 09:10:28'),
(8, 5, 'hudud', 9, NULL, NULL, '2025-07-05 09:16:49'),
(9, 5, 'gudud', 8, NULL, NULL, '2025-07-05 18:16:35');

-- --------------------------------------------------------

--
-- Table structure for table `types`
--

CREATE TABLE `types` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `types`
--

INSERT INTO `types` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(8, 'Breaking News', NULL, '2025-07-07 16:16:58', '2025-07-07 16:16:58'),
(9, 'Article', NULL, '2025-07-08 12:00:52', '2025-07-08 12:00:52'),
(10, 'Blog', NULL, '2025-07-08 12:01:02', '2025-07-08 12:01:10'),
(11, 'Press Release', NULL, '2025-07-08 12:01:44', '2025-07-08 12:01:44'),
(12, 'Space Agency Updates', NULL, '2025-07-08 12:05:10', '2025-07-08 12:05:10'),
(13, 'Space Exploration', NULL, '2025-07-08 12:05:29', '2025-07-08 12:05:29'),
(14, 'Space Technology', NULL, '2025-07-08 12:05:43', '2025-07-08 12:05:43'),
(15, 'Space Industry', NULL, '2025-07-08 12:05:57', '2025-07-08 12:05:57'),
(16, 'Human Space', NULL, '2025-07-08 12:08:40', '2025-07-08 12:08:40'),
(17, 'Astronomy and Science', NULL, '2025-07-08 12:08:44', '2025-07-08 12:08:44'),
(18, 'ISS News', NULL, '2025-07-08 12:09:06', '2025-07-08 12:09:06'),
(19, 'Commercial Spaceflight', NULL, '2025-07-08 12:09:09', '2025-07-08 12:09:09');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `reporter_id` varchar(50) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `is_blocked` tinyint(1) DEFAULT 0,
  `last_active` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role_id`, `created_at`, `reporter_id`, `is_verified`, `is_blocked`, `last_active`) VALUES
(8, 'SuperAdmin', 'superadmin@example.com', '$2y$10$cuBwODImnkqTTj2eWLbGkuUhDiEvMf8d3q7F1UDZ72vf4hcUrHTna', 1, '2025-07-03 23:10:46', 'reporter_8', 1, 0, '2025-11-02 22:59:17'),
(9, 'Sam', 'sam@gmail.com', '$2y$10$tv8MIO4cXkIKiyQ8uWujd.Y2APHYfREm6slJ8JqYIEik.w87G/UCm', 2, '2025-07-04 00:01:20', 'reporter_9', 1, 0, '2025-07-06 17:24:04'),
(10, 'Rose', 'rose@gmail.com', '$2y$10$oNxzdbzNa1p4EFBIKIewxe/zki4I8A1D8sd9Q9HNdzgc91X.V.Ooe', 4, '2025-07-04 01:09:10', 'reporter_10', 1, 0, '2025-11-02 15:27:34'),
(11, 'Eathen', 'eathen@gmail.com', '$2y$10$siBcmRIvsVtLw6e3yT4KfehZ11IcHX1weOS0w1GkFnZTKOPfi4Yh.', 3, '2025-07-04 01:38:38', 'reporter_11', 1, 0, NULL),
(12, 'Kyle', 'kyle@gmail.com', '$2y$10$sz/7bB1lXJRxoFh3TkVZHuwTFaMtaPvzrWkCWrDrp4qpzDAnqaHU.', 5, '2025-07-04 12:40:19', 'reporter_12', 1, 0, NULL),
(13, 'Jack', 'jack@gmail.com', '$2y$10$I2cGoZh4S8Ez5Q8JqN6nve8.kKE.it63Ug6PqhOogDQOrLedJsXfW', 6, '2025-07-05 12:58:55', 'reporter_13', 1, 0, '2025-11-02 22:34:04'),
(14, 'John', 'Jhon@gmail.com', '$2y$10$1iqnq3auxSj7PGeaKyPNauwF/2H1.dSptGz0469C5JHj/6T4yDdW.', 3, '2025-07-05 13:11:42', 'reporter_14', 1, 0, NULL),
(24, 'E-commerce', 'jo@gmail.com', '$2y$10$nX4nfPRYUb.VUS6GK.3VCukV.8DV4oadIoRtMv5nVqD.gDYEd1gmi', 2, '2025-11-02 22:36:08', 'reporter_24', 1, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `websites`
--

CREATE TABLE `websites` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `domain` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `websites`
--

INSERT INTO `websites` (`id`, `name`, `domain`, `logo`, `email`, `phone`, `address`, `status`, `created_at`) VALUES
(1, 'Farm Tools', 'http://localhost/', 'logo_69073c88a8276_lastest_blog_img_01.jpg', 'farmtools@example.com', '09034251426', '3rd floor Abc', 'active', '2025-06-16 21:56:59'),
(2, 'JsonShop', 'http://localhost/', 'logo_69073c2944a89_buying_item_img01.jpg', 'jhonshop@example.com', '09034251426', '3rd floor street 2 new bus stand', 'active', '2025-06-17 10:30:08'),
(3, 'E-commerce', 'http://localhost/', 'logo_69073bb7365e9_buying_item_img02.jpg', 'ecom@example.com', '1234567890', 'xyz', 'active', '2025-07-02 14:23:08');

-- --------------------------------------------------------

--
-- Table structure for table `website_social_links`
--

CREATE TABLE `website_social_links` (
  `id` int(11) NOT NULL,
  `website_id` int(11) DEFAULT NULL,
  `platform_id` int(11) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `website_social_links`
--

INSERT INTO `website_social_links` (`id`, `website_id`, `platform_id`, `url`) VALUES
(6, 1, 1, 'http://localhost/news-portal/websites/create.php'),
(7, 1, 2, 'http://localhost/news-portal/websites/create.php'),
(8, 1, 3, 'http://localhost/news-portal/websites/create.php'),
(9, 1, 4, 'http://localhost/news-portal/websites/create.php'),
(10, 1, 5, 'http://localhost/news-portal/websites/create.php');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `advertisements`
--
ALTER TABLE `advertisements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `api_keys`
--
ALTER TABLE `api_keys`
  ADD PRIMARY KEY (`id`),
  ADD KEY `website_id` (`website_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `forms`
--
ALTER TABLE `forms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `form_responses`
--
ALTER TABLE `form_responses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `form_id` (`form_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_news_id` (`unique_news_id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `author_id` (`author_id`),
  ADD KEY `news_type_fk` (`type_id`);

--
-- Indexes for table `news_comments`
--
ALTER TABLE `news_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news_images`
--
ALTER TABLE `news_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news_tags`
--
ALTER TABLE `news_tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news_websites`
--
ALTER TABLE `news_websites`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_category` (`category_id`),
  ADD KEY `fk_website` (`website_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- Indexes for table `scholarship`
--
ALTER TABLE `scholarship`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `social_platforms`
--
ALTER TABLE `social_platforms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sponsors`
--
ALTER TABLE `sponsors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket_hidden`
--
ALTER TABLE `ticket_hidden`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket_messages`
--
ALTER TABLE `ticket_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `websites`
--
ALTER TABLE `websites`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `website_social_links`
--
ALTER TABLE `website_social_links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `website_id` (`website_id`),
  ADD KEY `platform_id` (`platform_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `advertisements`
--
ALTER TABLE `advertisements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `api_keys`
--
ALTER TABLE `api_keys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `devices`
--
ALTER TABLE `devices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `forms`
--
ALTER TABLE `forms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `form_responses`
--
ALTER TABLE `form_responses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `news_comments`
--
ALTER TABLE `news_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `news_images`
--
ALTER TABLE `news_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `news_tags`
--
ALTER TABLE `news_tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `news_websites`
--
ALTER TABLE `news_websites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=148;

--
-- AUTO_INCREMENT for table `scholarship`
--
ALTER TABLE `scholarship`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `social_platforms`
--
ALTER TABLE `social_platforms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sponsors`
--
ALTER TABLE `sponsors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ticket_hidden`
--
ALTER TABLE `ticket_hidden`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ticket_messages`
--
ALTER TABLE `ticket_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `types`
--
ALTER TABLE `types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `websites`
--
ALTER TABLE `websites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `website_social_links`
--
ALTER TABLE `website_social_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `api_keys`
--
ALTER TABLE `api_keys`
  ADD CONSTRAINT `api_keys_ibfk_1` FOREIGN KEY (`website_id`) REFERENCES `websites` (`id`);

--
-- Constraints for table `form_responses`
--
ALTER TABLE `form_responses`
  ADD CONSTRAINT `form_responses_ibfk_1` FOREIGN KEY (`form_id`) REFERENCES `forms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `news_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `news_ibfk_3` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `news_type_fk` FOREIGN KEY (`type_id`) REFERENCES `types` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `website_social_links`
--
ALTER TABLE `website_social_links`
  ADD CONSTRAINT `website_social_links_ibfk_1` FOREIGN KEY (`website_id`) REFERENCES `websites` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `website_social_links_ibfk_2` FOREIGN KEY (`platform_id`) REFERENCES `social_platforms` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
