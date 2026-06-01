-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql307.infinityfree.com
-- Generation Time: Jun 01, 2026 at 10:51 AM
-- Server version: 11.4.12-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_41897365_shortener_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bio_links`
--

CREATE TABLE `bio_links` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `url` text DEFAULT NULL,
  `clicks` int(11) DEFAULT 0,
  `icon` varchar(50) DEFAULT NULL,
  `button_style` varchar(50) DEFAULT 'default',
  `sort_order` int(11) DEFAULT 0,
  `thumbnail` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `bio_links`
--

INSERT INTO `bio_links` (`id`, `user_id`, `title`, `url`, `clicks`, `icon`, `button_style`, `sort_order`, `thumbnail`) VALUES
(1, 1, 'YouTube', 'https://youtube.com', 0, NULL, 'default', 0, NULL),
(4, 1, 'Instagram', 'https://instagram.com', 0, NULL, 'default', 0, 'uploads/link_thumbnails/1778995934_1915.png'),
(5, 15, 'Facebook', 'https://facebook.com', 0, NULL, 'default', 0, NULL),
(6, 15, 'Youtube', 'https://youtube.com', 0, NULL, 'default', 0, NULL),
(8, 1, 'Facebook', 'https://facebook.com', 4, NULL, 'default', 0, 'https://www.google.com/s2/favicons?sz=128&domain=facebook.com'),
(12, 1, 'Lorence', 'https://lorencelaudeniodigital.com', 1, NULL, 'default', 0, 'https://www.google.com/s2/favicons?sz=128&domain=lorencelaudeniodigital.com'),
(16, 1, 'ðŸ”¥ Website hosting on a budget ðŸ‘‡', 'https://my.web.z.com/aff.php?aff=129', 0, NULL, 'default', 0, 'https://www.google.com/s2/favicons?sz=128&domain=my.web.z.com');

-- --------------------------------------------------------

--
-- Table structure for table `links`
--

CREATE TABLE `links` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `original_url` text NOT NULL,
  `short_code` varchar(20) DEFAULT NULL,
  `clicks` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `links`
--

INSERT INTO `links` (`id`, `user_id`, `original_url`, `short_code`, `clicks`, `created_at`) VALUES
(1, NULL, 'https://lorencelaudeniodigital.com/', 'ae2242', 1, '2026-05-12 15:45:10'),
(2, 1, 'https://lorencelaudeniodigitala.com/', '2d8286', 2, '2026-05-12 15:58:32'),
(4, 2, 'https://lorencelaudeniodigital.com/', 'efd942', 2, '2026-05-12 16:23:23'),
(5, NULL, 'https://lorencelaudeniodigital.com/', '6c90a0', 1, '2026-05-12 16:25:11'),
(6, NULL, 'https://ldltravelstories.com', 'd1bb2b', 1, '2026-05-12 16:29:05'),
(7, NULL, 'https://docs.google.com/spreadsheets/d/1dxKD-bM1s9eAOF_u8uTtoZMXaXauXEEgJxs8J6xdCgY/edit?gid=16746515#gid=16746515&fvid=1627067697', '43964b', 1, '2026-05-12 16:49:40'),
(8, NULL, 'https://google.com', '1fdd41', 0, '2026-05-13 04:06:57'),
(10, NULL, 'https://lorencelaudeniodigital.com/', 'd9509a', 1, '2026-05-13 06:24:17'),
(11, NULL, 'https://lorencelaudeniodigital.com', 'dffd0c', 0, '2026-05-13 06:28:50'),
(12, NULL, 'https://google.com', 'bc58b0', 0, '2026-05-13 06:30:06'),
(13, NULL, 'https://ldltravelstories.com', '595aab', 0, '2026-05-13 06:34:07'),
(14, NULL, 'https://lorencelaudeniodigital.com/feed', 'da948a', 0, '2026-05-13 06:34:31'),
(15, NULL, 'https://lorencelaudeniodigital.com', '97a8f4', 0, '2026-05-13 06:37:44'),
(16, NULL, 'https://lorencelaudeniodigital.com', '1061ec', 0, '2026-05-13 06:39:55'),
(17, NULL, 'https://lorencelaudeniodigital.com/feed', '6d0300', 0, '2026-05-13 06:41:28'),
(18, NULL, 'https://lorencelaudeniodigital.com', '462b7d', 0, '2026-05-13 06:46:45'),
(19, NULL, 'https://lorencelaudeniodigital.com/feed', 'e05bf8', 0, '2026-05-13 06:47:20'),
(20, NULL, 'https://google.com', 'de09ea', 1, '2026-05-13 06:47:53'),
(21, NULL, 'https://lorencelaudeniodigital.com/feed', 'b8921a', 0, '2026-05-13 06:58:35'),
(22, NULL, 'https://lorencelaudeniodigital.com/', '7188d1', 0, '2026-05-13 06:59:46'),
(23, NULL, 'https://lorencelaudeniodigital.com', 'e8d622', 0, '2026-05-13 07:00:31'),
(24, NULL, 'https://lorencelaudeniodigital.com/', '6b3060', 0, '2026-05-13 07:06:53'),
(25, NULL, 'https://ldltravelstories.com', '965ade', 0, '2026-05-13 07:18:10'),
(26, NULL, 'https://lorencelaudeniodigital.com', '405a69', 0, '2026-05-13 07:19:52'),
(27, NULL, 'https://lorencelaudeniodigital.com', '4d241c', 0, '2026-05-13 07:20:42'),
(28, NULL, 'https://lorencelaudeniodigital.com/feed', '59ca89', 0, '2026-05-13 07:21:35'),
(34, NULL, 'https://lorencelaudeniodigital.com/', 'ca8d07', 1, '2026-05-13 07:46:12'),
(41, 1, 'https://google.com', '9661e6', 7, '2026-05-13 09:16:11'),
(31, NULL, 'https://lorencelaudeniodigital.com', '78d4ed', 0, '2026-05-13 07:30:05'),
(32, NULL, 'https://lorencelaudeniodigital.com/feed', '80307a', 1, '2026-05-13 07:33:03'),
(52, NULL, 'https://lorencelaudeniodigital.com/', 'a08715', 0, '2026-05-13 12:43:23'),
(45, 2, 'https://facebook.com', '924e2f', 0, '2026-05-13 09:31:29'),
(46, 2, 'https://instagram.com', '008707', 2, '2026-05-13 09:34:41'),
(53, NULL, 'https://haskellforall.com/2026/03/a-sufficiently-detailed-spec-is-code', '5ec8dc', 1, '2026-05-13 12:53:07'),
(54, 10, 'https://lorencelaudeniodigital.com/', 'ad0924', 0, '2026-05-14 01:50:20'),
(55, NULL, 'https://lorencelaudeniodigital.com/', '0b2b5c', 0, '2026-05-14 03:36:14'),
(56, NULL, 'https://facebook.com', 'c62e32', 0, '2026-05-14 07:57:27'),
(57, NULL, 'https://lorencelaudeniodigital.com/', 'c04657', 1, '2026-05-14 14:44:59'),
(58, NULL, 'https://lorencelaudeniodigital.com/', '47e512', 0, '2026-05-15 01:49:08'),
(59, NULL, 'https://lorencelaudeniodigital.com/', '7ccad2', 0, '2026-05-15 02:04:35'),
(60, NULL, 'https://google.com', '26adca', 0, '2026-05-15 02:14:55'),
(61, NULL, 'https://lorencelaudeniodigital.com/feed', '084b12', 0, '2026-05-15 02:17:42'),
(62, NULL, 'https://lorencelaudeniodigital.com/feed', '1d7d80', 0, '2026-05-15 02:19:57'),
(63, NULL, 'https://lorencelaudeniodigital.com/feed', '669ee4', 0, '2026-05-15 02:20:29'),
(64, NULL, 'https://lorencelaudeniodigital.com/feed', '63b395', 0, '2026-05-15 02:20:42'),
(65, NULL, 'https://lorencelaudeniodigital.com/feed', '48c594', 0, '2026-05-15 02:21:49'),
(66, NULL, 'https://lorencelaudeniodigital.com/feed', '7d7370', 0, '2026-05-15 02:22:40'),
(67, NULL, 'https://lorencelaudeniodigital.com/feed', 'e7eaf7', 0, '2026-05-15 02:26:05'),
(68, NULL, 'https://google.com', '82d666', 0, '2026-05-15 02:26:26'),
(69, NULL, 'https://google.com', 'cfacd4', 0, '2026-05-15 02:27:06'),
(70, NULL, 'https://google.com', '04304b', 0, '2026-05-15 02:28:10'),
(71, NULL, 'https://google.com', '00d47e', 0, '2026-05-15 02:28:22'),
(72, NULL, 'https://lorencelaudeniodigital.com', 'f1c979', 0, '2026-05-15 02:28:27'),
(73, NULL, 'https://lorencelaudeniodigital.com', 'b25134', 0, '2026-05-15 02:30:29'),
(74, NULL, 'https://lorencelaudeniodigital.com', '7c88a3', 0, '2026-05-15 02:31:53'),
(75, NULL, 'https://lorencelaudeniodigital.com', '4c4781', 0, '2026-05-15 02:32:38'),
(76, NULL, 'https://lorencelaudeniodigital.com', 'c7fcd6', 1, '2026-05-15 02:36:28'),
(77, NULL, 'https://lorencelaudeniodigital.com', 'dbacd1', 0, '2026-05-15 02:37:52'),
(78, NULL, 'https://lorencelaudeniodigital.com/', '308959', 1, '2026-05-15 02:40:15'),
(79, NULL, 'https://lorencelaudeniodigital.com/', '064c53', 0, '2026-05-15 02:40:42'),
(80, NULL, 'https://youtube.com/shorts/KbAn7ckHEa8?si=qdlC46hlAgxCIzsw', '5a0f9b', 1, '2026-05-15 02:40:49'),
(81, NULL, 'https://lorencelaudeniodigital.com/', 'bcfcc8', 0, '2026-05-15 03:09:01'),
(82, NULL, 'https://ldltravelstories.com', '2c87a1', 0, '2026-05-15 03:47:19'),
(83, 1, 'https://lorencelaudeniodigital.com/', 'f0f1db', 0, '2026-05-15 03:47:36'),
(84, NULL, 'https://lorencelaudeniodigital.com/', 'c6e53e', 0, '2026-05-15 04:01:58'),
(85, 15, 'https://lorencelaudeniodigital.com/', '214eab', 2, '2026-05-15 04:03:46'),
(86, 15, 'https://google.com', '000d1e', 0, '2026-05-15 04:15:13'),
(87, 15, 'https://ldltravelstories.com', 'd36b26', 0, '2026-05-15 04:42:03'),
(92, 15, 'https://facebook.com', 'fbko', 0, '2026-05-15 05:01:15'),
(93, NULL, 'https://facebook.com', '507fae', 1, '2026-05-15 06:04:44'),
(95, 1, 'https://drive.google.com/drive/folders/1VuR57CzQzeEQ46P-alLjX4pTWhVnYx1C?usp=drive_link', '96637c', 21, '2026-05-15 10:36:31'),
(96, 1, 'https://drive.google.com/drive/folders/1o8fBxSqf2sBwJx_xKW-UPs-7F6p4oIWj?usp=drive_link', '8f1b24', 6, '2026-05-16 06:17:39'),
(97, 1, 'https://drive.google.com/drive/folders/175aNzLlGU_C3ujBQ338c3kIuXtp2gtMp?usp=sharing', '6c5316', 2, '2026-05-16 07:28:47'),
(98, 1, 'https://facebook.com', 'renz-fb', 1, '2026-05-16 08:15:42'),
(99, NULL, 'https://lorencelaudeniodigital.com/', 'ae7d13', 0, '2026-05-16 14:27:34'),
(100, NULL, 'https://lorencelaudeniodigital.com', 'ddd7c4', 0, '2026-05-16 15:09:55'),
(101, NULL, 'https://lorencelaudeniodigital.com/', '0515cf', 1, '2026-05-18 15:16:22'),
(102, NULL, 'https://lorencelaudeniodigital.com/', 'c1588e', 1, '2026-05-18 15:21:27'),
(103, NULL, 'https://lorencelaudeniodigital.com/', '34204e', 0, '2026-05-18 15:28:59'),
(104, NULL, 'https://lorencelaudeniodigital.com/', '7b2847', 1, '2026-05-18 15:31:10'),
(121, 1, 'https://my.web.z.com/aff.php?aff=129', 'e2e032', 33, '2026-05-20 00:22:06'),
(122, 1, 'https://lorencelaudeniodigital.com/step/store-checkout-thank-you-04/?wcf-key=wc_order_ei6uVGD19x6Qu&wcf-order=8174', 'thank-you', 0, '2026-05-20 07:25:39'),
(123, 1, 'https://dash.cloudflare.com/4ec0d1faafa0d44e71bb1c501cffe7e6/lorencelaudeniodigital.com/caching/configuration', '31e36b', 0, '2026-05-20 07:25:53'),
(124, 1, 'https://docs.google.com/spreadsheets/d/1dxKD-bM1s9eAOF_u8uTtoZMXaXauXEEgJxs8J6xdCgY/edit?gid=16746515#gid=16746515&fvid=1627067697', 'spending', 0, '2026-05-20 07:26:09'),
(125, 1, 'https://app.brevo.com/ecommerce', '8069fd', 0, '2026-05-20 07:26:34'),
(126, 1, 'https://formatter.org/', '2c92f7', 1, '2026-05-20 07:41:06'),
(127, 1, 'https://famelack.com/', '0e7112', 0, '2026-05-20 10:18:56'),
(129, 1, 'https://lorencelaudeniodigital.com/tools-v2/', '128776', 0, '2026-05-20 12:24:27'),
(130, 1, 'https://www.toolsoverflow.com/youtube/youtube-title-description-extractor', 'cc013d', 1, '2026-05-24 02:26:08');

-- --------------------------------------------------------

--
-- Table structure for table `payment_approvals`
--

CREATE TABLE `payment_approvals` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `used` tinyint(4) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `payment_approvals`
--

INSERT INTO `payment_approvals` (`id`, `user_id`, `token`, `created_at`, `used`) VALUES
(1, 1, 'f5eeeae4983bce43bc9ad2350768df2504a46559728a12b3bc6dc93c99a79ee8', '2026-05-24 08:02:15', 1),
(2, 1, 'cf15ce7a9ef6f9a47961e7191d8317e5d57c8c82c34587f88e858bdd8f1f014b', '2026-05-24 08:08:22', 1),
(3, 1, 'cc93f12d983f4ae181bf92ec990ac669b31f39f711e5a5a2316960eea26aeaff', '2026-05-24 08:20:47', 0),
(4, 1, '46921f92c1d0ece59edb3ab1222931cc40516ac7ec0245724095669b876dd158', '2026-05-24 08:24:26', 0);

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `url` text NOT NULL,
  `reason` varchar(100) NOT NULL,
  `details` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `email`, `url`, `reason`, `details`, `created_at`) VALUES
(1, 'laudeniolorence@gmail.com', 'https://lynk.page.gd/abc123', 'phishing', '', '2026-05-25 07:00:00'),
(2, 'laudeniolorence@gmail.com', 'https://lynk.page.gd/abc123', 'phishing', '', '2026-05-25 07:07:11');

-- --------------------------------------------------------

--
-- Table structure for table `report_pending`
--

CREATE TABLE `report_pending` (
  `id` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `url` text DEFAULT NULL,
  `reason` varchar(50) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `token` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `report_pending`
--

INSERT INTO `report_pending` (`id`, `email`, `url`, `reason`, `details`, `token`, `created_at`, `expires_at`) VALUES
(1, 'laudeniolorence@gmail.com', 'https://lynk.page.gd/abc123', 'phishing', 'sample details', 'fda22863d374714684d249915c7d9af04d078aa89df31726012c85ade1366f54', '2026-05-25 03:00:03', '2026-05-26 06:00:04'),
(2, 'laudeniolorence@gmail.com', 'https://lynk.page.gd/abc123', 'phishing', 'fdfdf', '974dd96b082fc225012c262b6c7894ec4ea3c323dad883512b6e8d1802aee339', '2026-05-25 03:03:55', '2026-05-26 06:03:54'),
(3, 'laudeniolorence@gmail.com', 'https://lynk.page.gd/abc123', 'phishing', 'fdfdf', '5f5aa206a16d8394d2e978b5ca8a2f76670983545acba525810d2f3d339f9720', '2026-05-25 03:03:58', '2026-05-26 06:03:58'),
(4, 'laudeniolorence@gmail.com', 'https://lynk.page.gd/abc123', 'phishing', 'fdfdf', 'e0d0b2a3764fc77bebc71da11eddf31370f5d06d94fb0709d66dd7eefa1d91a1', '2026-05-25 03:08:58', '2026-05-26 06:08:58'),
(5, 'laudeniolorence@gmail.com', 'https://lynk.page.gd/abc123', 'phishing', 'fdfdfd', '9dd49bdc75fe722480e2800494e2f610b8d0c8345f17247dbc0f8817a475c2be', '2026-05-25 03:09:11', '2026-05-26 06:09:11'),
(6, 'laudeniolorence@gmail.com', 'https://lynk.page.gd/abc123', 'phishing', 'fdfdfd', '6a74a081a298ff560f63d19d0bee28b37d0eafa07ab857645730a7bbfebb8928', '2026-05-25 03:10:58', '2026-05-26 06:10:57'),
(7, 'laudeniolorence@gmail.com', 'https://lynk.page.gd/abc123', 'phishing', 'fdfdfd', '70ad956607c9eb50449da5fa0094c24e94491618eb63143a595db2e5b8a4558a', '2026-05-25 03:15:01', '2026-05-26 06:15:00'),
(8, 'laudeniolorence@gmail.com', 'https://lynk.page.gd/abc123', 'phishing', '', '8e7798a215bd3ca0bcd6246d8d0fffbec07c8543314b246e9334ea516fbdee12', '2026-05-25 03:20:06', '2026-05-26 06:20:06');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `theme` varchar(50) DEFAULT 'default',
  `verified` tinyint(1) DEFAULT 0,
  `pending_email` varchar(255) DEFAULT NULL,
  `email_verify_token` varchar(255) DEFAULT NULL,
  `token_expires` datetime DEFAULT NULL,
  `recovery_token` varchar(255) DEFAULT NULL,
  `recovery_expires` datetime DEFAULT NULL,
  `previous_email` varchar(255) DEFAULT NULL,
  `url_limit` int(11) DEFAULT 1000,
  `urls_used` int(11) DEFAULT 0,
  `plan` varchar(20) DEFAULT 'free'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `reset_token`, `reset_expires`, `bio`, `avatar`, `theme`, `verified`, `pending_email`, `email_verify_token`, `token_expires`, `recovery_token`, `recovery_expires`, `previous_email`, `url_limit`, `urls_used`, `plan`) VALUES
(1, 'lorence', 'laudeniolorence@gmail.com', '$2y$10$20b9KH.KK/oia/H4H2ixdOFBAmGwFOjjIvKe.sCeertNUqfAY92hq', '2026-05-12 15:47:51', NULL, NULL, 'Just living, learning, and leveling up..', 'uploads/avatars/1779003113_8962.png', 'purple', 0, 'leelee@fivueriti.com', '42ae6c9b7f40cf2e1dbd556df909180f5392bd344fcaac1a3309fc52d5671742', '2026-05-24 02:58:33', NULL, NULL, NULL, 1000, 1, 'free'),
(2, 'tolits', 'laudeniolorence23@gmail.com', '$2y$10$0BaeAX0SXMzGFj8YLUeHRO2vIN4sMXrRMpZFVR12w4uSxsCJrIFqC', '2026-05-12 16:22:54', NULL, NULL, NULL, NULL, 'default', 0, NULL, NULL, NULL, NULL, NULL, NULL, 1000, 0, 'free'),
(3, 'lorence2', 'javonna@graphicser.com', '$2y$10$5lEapyThU.HaDuwA.1qn8uzavlKm2qSokvSQSkldzcwSNPl6OhJNK', '2026-05-14 01:24:01', NULL, NULL, NULL, NULL, 'default', 0, NULL, NULL, NULL, NULL, NULL, NULL, 1000, 0, 'free'),
(4, 'lorence3', 'kacy@graphicser.com', '$2y$10$9CZ.jerXi3RTNBhSop6Jq.xZZxnXdLsutuoWx1aXjlB78Usk4Cq8C', '2026-05-14 01:30:12', NULL, NULL, NULL, NULL, 'default', 0, NULL, NULL, NULL, NULL, NULL, NULL, 1000, 0, 'free'),
(5, 'user5', 'brookec30@graphicser.com', '$2y$10$Ku9l0IEiq0DKn2X9KauR1OIrva1CHUgjeutPzaCF3yDMMUF/glHxO', '2026-05-14 01:33:15', NULL, NULL, NULL, NULL, 'default', 0, NULL, NULL, NULL, NULL, NULL, NULL, 1000, 0, 'free'),
(6, 'user6', 'yumi45@graphicser.com', '$2y$10$md0tAnhesZla4Tx6IZaZLOOFamxE9HXSUr8SkGxmyCePqn.XOepeS', '2026-05-14 01:35:18', NULL, NULL, NULL, NULL, 'default', 0, NULL, NULL, NULL, NULL, NULL, NULL, 1000, 0, 'free'),
(7, 'user8', 'zuriel545@graphicser.com', '$2y$10$/GEnF8oHG7854NgA1yRbxOE1d1vMEMT4A2zlo3PPkBLl1qMs6xZiq', '2026-05-14 01:36:07', NULL, NULL, NULL, NULL, 'default', 0, NULL, NULL, NULL, NULL, NULL, NULL, 1000, 0, 'free'),
(8, 'user9', 'helaynac1@graphicser.com', '$2y$10$JSt0PZEyTM7whj7dCAYJUOrJJ2/MUHN6f2LyO1bqojg0R7Yzy9WAK', '2026-05-14 01:37:14', NULL, NULL, NULL, NULL, 'default', 0, NULL, NULL, NULL, NULL, NULL, NULL, 1000, 0, 'free'),
(9, 'user10', 'deer@graphicser.com', '$2y$10$mXQR8L0w5g0tLgifqEJFVuVWYnYDQ8mYRJ.A.zbIse2W/w2bnNiZG', '2026-05-14 01:38:49', NULL, NULL, NULL, NULL, 'default', 0, NULL, NULL, NULL, NULL, NULL, NULL, 1000, 0, 'free'),
(10, 'user11', 'lurenea2@graphicser.com', '$2y$10$mKNkcBNmi0it7gi7uL5vO.OR4yoi.bO98G1iqCrlHp1ViF.JlZq/q', '2026-05-14 01:40:48', NULL, NULL, NULL, NULL, 'default', 0, NULL, NULL, NULL, NULL, NULL, NULL, 1000, 0, 'free'),
(11, 'user12', 'waylon60@graphicser.com', '$2y$10$Hn0p7t4wkGSNUJf2EE5yDOYppqFQSOb/k8svJbNrv1BRx8OYanyFO', '2026-05-14 05:52:19', NULL, NULL, NULL, NULL, 'default', 0, NULL, NULL, NULL, NULL, NULL, NULL, 1000, 0, 'free'),
(12, 'user13', 'kaneisha33@graphicser.com', '$2y$10$xl7XulSD5zLj8WWrppd4WOoYr4oFK.MBSVbwTLWQzj58rJrLqB1Aq', '2026-05-14 05:57:23', NULL, NULL, NULL, NULL, 'default', 0, NULL, NULL, NULL, NULL, NULL, NULL, 1000, 0, 'free'),
(13, 'user14', 'brailyn@graphicser.com', '$2y$10$jVi8DClsmr4287UtMU1aheZkyF4Wm5PoTrXENEISdunTBqXOtEhhi', '2026-05-14 06:00:27', NULL, NULL, NULL, NULL, 'default', 0, NULL, NULL, NULL, NULL, NULL, NULL, 1000, 0, 'free'),
(14, 'user15', 'murray@graphicser.com', '$2y$10$Ciu.UEYZCPKuIItGcxvYAOO6YroGTQrO57xmplhdyr2ZhYuM7.LzO', '2026-05-14 06:13:46', NULL, NULL, NULL, NULL, 'default', 0, NULL, NULL, NULL, NULL, NULL, NULL, 1000, 0, 'free'),
(15, 'terrance5', 'terrance5@graphicser.com', '$2y$10$Pe3nrhHb4CaVpbSwOb9UTOx91i3PhbsI3wR.fJc/NKSO/KQIA6UqK', '2026-05-15 03:54:07', NULL, NULL, 'hello', 'uploads/avatars/1778823224_5835.png', 'default', 0, NULL, NULL, NULL, NULL, NULL, NULL, 1000, 0, 'free'),
(16, 'lorencelaudeniodigital', 'lorencelaudeniodigital@gmail.com', '$2y$10$OdapJ7fu6HO4xNKCdtKze.I1QsYjl8GKVsXVsjTc0cQHif1ZSkeUm', '2026-05-19 08:41:16', NULL, NULL, NULL, NULL, 'default', 0, NULL, NULL, NULL, NULL, NULL, NULL, 1000, 0, 'free');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bio_links`
--
ALTER TABLE `bio_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `links`
--
ALTER TABLE `links`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short_code` (`short_code`);

--
-- Indexes for table `payment_approvals`
--
ALTER TABLE `payment_approvals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `report_pending`
--
ALTER TABLE `report_pending`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bio_links`
--
ALTER TABLE `bio_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `links`
--
ALTER TABLE `links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;

--
-- AUTO_INCREMENT for table `payment_approvals`
--
ALTER TABLE `payment_approvals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `report_pending`
--
ALTER TABLE `report_pending`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
