-- MariaDB dump 10.19  Distrib 10.5.12-MariaDB, for debian-linux-gnu (aarch64)
--
-- Host: wp-browser_db    Database: wp-browser
-- ------------------------------------------------------
-- Server version	10.6.5-MariaDB-1:10.6.5+maria~focal

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wp_discotest_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `wp_disco_campaigns`
--

CREATE TABLE `wp_disco_campaigns` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `intent` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `status` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `priority` int(5) NOT NULL DEFAULT '0',
  `data` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `wp_disco_campaigns`
--
ALTER TABLE `wp_disco_campaigns`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `wp_disco_campaigns`
--
ALTER TABLE `wp_disco_campaigns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
