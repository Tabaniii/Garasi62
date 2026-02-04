-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 03, 2026 at 11:27 AM
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
-- Database: `garasi62`
--
CREATE DATABASE IF NOT EXISTS `garasi62` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `garasi62`;

-- --------------------------------------------------------

--
-- Table structure for table `about`
--

CREATE TABLE `about` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tentang` text NOT NULL,
  `visi` varchar(150) NOT NULL,
  `misi` varchar(150) NOT NULL,
  `nilai` text NOT NULL,
  `layanan` text NOT NULL,
  `harga` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `approvals`
--

CREATE TABLE `approvals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fund_request_id` bigint(20) UNSIGNED NOT NULL,
  `approver_id` bigint(20) UNSIGNED NOT NULL,
  `level` enum('1','2') NOT NULL,
  `decision` enum('approve','reject','revise') NOT NULL,
  `notes` text DEFAULT NULL,
  `decided_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `author` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `excerpt` text DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `tags` text DEFAULT NULL,
  `comment_count` int(11) NOT NULL DEFAULT 0,
  `published_at` timestamp NULL DEFAULT NULL,
  `status` enum('published','draft') NOT NULL DEFAULT 'draft',
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`id`, `title`, `slug`, `image`, `author`, `content`, `excerpt`, `category`, `tags`, `comment_count`, `published_at`, `status`, `user_id`, `created_at`, `updated_at`) VALUES
(8, 'Evolusi Livery Hitam-Hijau: Mobil F1 Modern dengan Aura Stealth', 'evolusi-livery-hitam-hijau-mobil-f1-modern-dengan-aura-stealth', 'blogs/35Caz3DnFEFTz8M7FcIYPKe6g3AsCITlnyUFhD7x.jpg', 'Garasi62 Editorial', '<h3>Aerodinamika yang Lebih Bersih</h3>\r\n<p>Dari sisi bentuk, mobil ini tampak mengutamakan aliran udara yang bersih di sepanjang bodi. Garis-garis halus di bagian hidung dan sidepod membuat aliran udara mengalir mulus ke arah diffuser belakang. Desain ini tidak hanya terlihat futuristik, tetapi juga mengisyaratkan fokus tim pada efisiensi downforce di era regulasi baru.</p>\r\n\r\n<h3>Detail Sponsor yang Elegan</h3>\r\n<p>Menariknya, penempatan logo sponsor tidak terasa berlebihan. Warna logo dibiarkan kontras terhadap bodi hitam, namun tetap selaras dengan nuansa hijau elektrik yang menjadi identitas utama. Hasilnya adalah tampilan yang bersih, premium, dan mudah dikenali saat melaju kencang di lintasan malam.</p>\r\n\r\n<h3>Identitas Baru, Ambisi Baru</h3>\r\n<p>Livery ini seperti pernyataan tegas bahwa tim memasuki musim baru dengan ambisi yang lebih besar. Kombinasi warna gelap, aksen neon, dan komposisi logo yang rapi menjadikan mobil ini salah satu kandidat terkuat untuk menyabet predikat mobil dengan tampilan paling keren musim ini.</p>', 'Mobil F1 hitam-hijau ini memadukan desain stealth dengan aksen turquoise yang tajam, menonjolkan identitas modern dan agresif di lintasan.', 'F1 2026', '[\"formula 1\",\"mobil f1\",\"livery\",\"mercedes\",\"mobil balap\"]', 1, '2026-01-23 02:08:52', 'published', 2, '2026-01-23 02:05:57', '2026-01-30 01:32:29'),
(9, 'Sentuhan Perak dan Oranye: Livery Futuristik di Era Baru F1', 'sentuhan-perak-dan-oranye-livery-futuristik-di-era-baru-f1', 'blogs/NTIzXD3aVdjouiDR6qpXOvxIwfLAMhHKyn4FnuaE.jpg', 'Garasi62 Editorial', '<p>Mobil F1 dengan livery perak-oranye ini menampilkan perpaduan yang sangat khas: elegan namun tetap agresif. Warna perak metalik memberikan kesan teknologi tinggi, sementara oranye menyala di bagian samping dan engine cover menambah nuansa dinamis yang kuat.</p>\r\n\r\n<h3>Kontras Warna yang Kuat</h3>\r\n<p>Pemilihan warna oranye pada area samping bodi bukan hanya estetika semata. Saat mobil melaju kencang, blok warna terang tersebut membantu mobil terlihat jelas dari berbagai sudut kamera, baik di siang maupun malam hari. Ini penting untuk aspek branding sekaligus pengalaman penonton.</p>\r\n\r\n<h3>Silhouette yang Bersih dan Modern</h3>\r\n<p>Dengan latar belakang hitam pekat, garis bodi mobil terlihat sangat jelas. Hidung yang rendah, sidepod yang rapat, dan area belakang yang menyempit menonjolkan fokus tim pada efisiensi aerodinamika dan stabilitas kecepatan tinggi.</p>\r\n\r\n<h3>Siap Menjadi Ikon Baru</h3>\r\n<p>Livery perak-oranye ini berpotensi menjadi salah satu ikon visual baru di grid F1. Kombinasi warnanya unik, mudah dikaitkan dengan identitas tim, dan memiliki karakter kuat yang membedakannya dari kompetitor lain.</p>', 'Perpaduan perak metalik dan oranye menyala membuat mobil F1 ini terlihat tajam, futuristik, dan sangat mudah dikenali di lintasan.', 'F1 2026', '[\"formula 1\",\"livery\",\"audi\",\"f1 2026\",\"desain mobil\"]', 0, '2026-01-23 02:10:02', 'published', 2, '2026-01-23 02:05:57', '2026-01-23 02:10:02'),
(10, 'Warna-Warni Agresif: Livery Biru-Kuning Merah yang Ikonik di Grid F1', 'warna-warni-agresif-livery-biru-kuning-merah-yang-ikonik-di-grid-f1', 'blogs/hXpvLq3DqYxuaRhqJs7j6UrCBDq12nONHXg65gKQ.jpg', 'Garasi62 Editorial', '<h3>Logo Besar di Lantai Mobil</h3>\r\n<p>Salah satu elemen paling mencolok adalah penggunaan logo berukuran besar di lantai mobil. Saat difoto dari sudut rendah atau saat mobil berada di pitlane, elemen ini langsung menarik perhatian dan memperkuat identitas visual tim.</p>\r\n\r\n<h3>Detail Kuning yang Kontras</h3>\r\n<p>Aksen kuning di bagian hidung, airbox, dan beberapa area kecil di bodi menambah kedalaman visual tanpa terasa berlebihan. Saat mobil melaju, kombinasi biru tua, merah, dan kuning menciptakan efek <em>motion blur</em> yang sangat menarik di layar.</p>\r\n\r\n<h3>Perpaduan Desain dan Performa</h3>\r\n<p>Di balik tampilan mencolok ini, bentuk bodinya tetap menunjukkan fokus tinggi pada performa: intake yang rapat, profil sidepod yang turun tajam, dan area belakang yang bersih. Livery ini bukan hanya soal estetika, tetapi juga cara tim menonjol di lintasan sekaligus di hati para penggemar.</p>', 'Dominasi biru gelap dengan aksen kuning dan merah menciptakan livery F1 yang sangat ikonik, penuh energi, dan langsung dikenali di layar TV.', 'F1 2026', '[\"formula 1\",\"red bull\",\"livery\",\"mobil balap\",\"desain\"]', 0, '2026-01-23 02:10:35', 'published', 2, '2026-01-23 02:05:57', '2026-01-23 02:10:35');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `car`
--

CREATE TABLE `car` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `seller_id` bigint(20) UNSIGNED DEFAULT NULL,
  `image` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `tipe` enum('rent','buy') NOT NULL DEFAULT 'buy',
  `tahun` varchar(4) NOT NULL,
  `brand` varchar(20) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `kilometer` varchar(6) NOT NULL,
  `transmisi` varchar(20) NOT NULL,
  `harga` varchar(20) NOT NULL,
  `metode` varchar(10) NOT NULL,
  `kapasitasmesin` varchar(50) NOT NULL,
  `stock` varchar(50) DEFAULT NULL,
  `vin` varchar(50) DEFAULT NULL,
  `msrp` varchar(15) DEFAULT NULL,
  `dealer_discounts` varchar(15) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `interior_features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `safety_features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `extra_features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `technical_specs` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `car`
--

INSERT INTO `car` (`id`, `seller_id`, `image`, `tipe`, `tahun`, `brand`, `nama`, `kilometer`, `transmisi`, `harga`, `metode`, `kapasitasmesin`, `stock`, `vin`, `msrp`, `dealer_discounts`, `description`, `interior_features`, `safety_features`, `extra_features`, `technical_specs`, `location`, `status`, `created_at`, `updated_at`) VALUES
(7, 7, '[\"cars\\/WfQu3oPbsf72NLzoPlToYA04NHJkSesKewojmSmY.jpg\"]', 'buy', '2023', 'Ferrari', 'SF-25', '10000', 'Automatic', '1000000000', 'Cash', '2000cc', 'K34849032HK', 'K34849032HK', '1000000000', NULL, 'mobil khusus balapan resmi', '[]', '[]', '[]', NULL, 'itali', 'approved', '2026-01-14 08:58:02', '2026-01-18 22:57:15'),
(8, 7, '[\"cars\\/MigcKxXl3gCMgp754t2Qq0woczBomHO3utsUp2t8.jpg\"]', 'buy', '2024', 'Haas', 'VF-24', '0', 'Manual', '25000000', 'Cash', '1.6L V6 Turbo Hybrid', 'Tersedia', 'HAAS-VF24-2024-001', '25000000', NULL, 'Haas VF-24 Formula 1 racing car dengan livery hitam, putih, dan merah. Mobil ini menampilkan sponsor MoneyGram, Chipotle, Palm Angels, dan MERCARI. Dilengkapi dengan teknologi F1 terbaru dan aerodinamika yang sangat canggih.', '[\"F1 Racing Seat\",\"Steering Wheel with Paddle Shifters\",\"Halo Safety Device\",\"Advanced Telemetry System\"]', '[\"Halo Protection System\",\"Monocoque Carbon Fiber Chassis\",\"FIA Safety Standards\",\"Fire Suppression System\"]', '[\"Pirelli P Zero Tires\",\"Advanced Aerodynamics\",\"DRS System\",\"Energy Recovery System\"]', 'Engine: 1.6L V6 Turbo Hybrid, Power: 1000+ HP, Transmission: 8-speed Sequential, Weight: 798 kg (minimum), Top Speed: 350+ km/h, Aerodynamics: Advanced F1 aero package', 'Jakarta, Indonesia', 'approved', '2026-01-18 22:41:04', '2026-01-18 22:44:44'),
(9, 7, '[\"cars\\/VHpKt3uJwXPkxHP5b5D4XmAGVwvM6yrfvED6Eqnz.jpg\"]', 'buy', '2024', 'Alpine', 'A524', '0', 'Automatic', '28000000', 'Cash', '1.6L V6 Turbo Hybrid', 'Tersedia', 'ALPINE-A524-2024-001', '28000000', NULL, 'Alpine A524 Formula 1 dengan livery hitam matte yang mencolok dengan aksen pink dan biru cerah. Mobil ini menampilkan sponsor utama BWT dan branding Alpine yang elegan. Desain aerodinamis yang sangat canggih untuk performa maksimal.', '[\"F1 Racing Seat\",\"Steering Wheel with Paddle Shifters\",\"Halo Safety Device\",\"Advanced Telemetry System\"]', '[\"Halo Protection System\",\"Monocoque Carbon Fiber Chassis\",\"FIA Safety Standards\",\"Fire Suppression System\"]', '[\"Pirelli P Zero Tires\",\"BWT Water Technology Integration\",\"Advanced Aerodynamics\",\"DRS System\"]', 'Engine: 1.6L V6 Turbo Hybrid, Power: 1000+ HP, Transmission: 8-speed Sequential, Weight: 798 kg (minimum), Top Speed: 350+ km/h, Aerodynamics: Advanced F1 aero package with BWT branding', 'Bandung, Indonesia', 'approved', '2026-01-18 22:41:04', '2026-01-18 22:46:15'),
(10, 7, '[\"cars\\/5Erb6kXPgMJLVxkQzUe13l3wibULFEIHBrbqyShJ.jpg\"]', 'buy', '2024', 'McLaren', 'MCL38', '0', 'Automatic', '32000000', 'Cash', '1.6L V6 Turbo Hybrid', 'Tersedia', 'MCLAREN-MCL38-2024-001', '32000000', NULL, 'McLaren MCL38 Formula 1 dengan livery hitam yang mencolok dengan aksen orange dan biru muda. Mobil ini menampilkan sponsor VELO, Android, Richard Mille, Tezos, dan Darktrace. Desain yang sangat aerodinamis dengan teknologi F1 terdepan.', '[\"F1 Racing Seat\",\"Steering Wheel with Paddle Shifters\",\"Halo Safety Device\",\"Advanced Telemetry System\",\"Richard Mille Timepiece Integration\"]', '[\"Halo Protection System\",\"Monocoque Carbon Fiber Chassis\",\"FIA Safety Standards\",\"Fire Suppression System\"]', '[\"Pirelli P Zero Tires\",\"VELO Branding\",\"Android Integration\",\"Advanced Aerodynamics\",\"DRS System\"]', 'Engine: 1.6L V6 Turbo Hybrid, Power: 1000+ HP, Transmission: 8-speed Sequential, Weight: 798 kg (minimum), Top Speed: 350+ km/h, Aerodynamics: Advanced F1 aero package', 'Surabaya, Indonesia', 'approved', '2026-01-18 22:41:04', '2026-01-18 22:46:40'),
(11, 7, '[\"cars\\/ow3349lM5HDTOPusYNyp7CTrxZKzsKc2SoBO9uTu.jpg\"]', 'buy', '2024', 'Mercedes-AMG', 'W15', '0', 'Automatic', '35000000', 'Cash', '1.6L V6 Turbo Hybrid', 'Tersedia', 'MERCEDES-W15-2024-063', '35000000', NULL, 'Mercedes-AMG Petronas W15 Formula 1 dengan livery hitam matte yang elegan dengan aksen teal/biru kehijauan. Mobil nomor 63 ini menampilkan sponsor Petronas, TeamViewer, AMD, CrowdStrike, IWC, dan INEOS. Teknologi F1 terdepan dengan performa maksimal.', '[\"F1 Racing Seat\",\"Steering Wheel with Paddle Shifters\",\"Halo Safety Device\",\"Advanced Telemetry System\",\"IWC Timepiece Integration\"]', '[\"Halo Protection System\",\"Monocoque Carbon Fiber Chassis\",\"FIA Safety Standards\",\"Fire Suppression System\"]', '[\"Pirelli P Zero Tires\",\"Petronas Fuel Technology\",\"TeamViewer Integration\",\"Advanced Aerodynamics\",\"DRS System\"]', 'Engine: 1.6L V6 Turbo Hybrid, Power: 1000+ HP, Transmission: 8-speed Sequential, Weight: 798 kg (minimum), Top Speed: 350+ km/h, Aerodynamics: Advanced F1 aero package with Petronas technology', 'Jakarta, Indonesia', 'approved', '2026-01-18 22:41:04', '2026-01-18 22:49:41'),
(12, 7, '[\"cars\\/Ra0giyY5fyFZZ7c7jqBP3Y0f22rfqVMwjx4SG50P.jpg\"]', 'rent', '2024', 'Stake F1', 'C44', '0', 'Automatic', '24000000', 'Cash', '1.6L V6 Turbo Hybrid', 'Tersedia', 'STAKE-C44-2024-001', '24000000', NULL, 'Stake F1 Team C44 dengan livery hitam matte dan hijau neon yang sangat mencolok. Mobil ini menampilkan sponsor Stake, Kick, Sensetime.ai, Cielo, dan berbagai sponsor lainnya. Desain yang sangat agresif dan modern dengan teknologi F1 terkini.', '[\"F1 Racing Seat\",\"Steering Wheel with Paddle Shifters\",\"Halo Safety Device\",\"Advanced Telemetry System\"]', '[\"Halo Protection System\",\"Monocoque Carbon Fiber Chassis\",\"FIA Safety Standards\",\"Fire Suppression System\"]', '[\"Pirelli P Zero Tires\",\"Stake Branding\",\"Kick Integration\",\"Advanced Aerodynamics\",\"DRS System\"]', 'Engine: 1.6L V6 Turbo Hybrid, Power: 1000+ HP, Transmission: 8-speed Sequential, Weight: 798 kg (minimum), Top Speed: 350+ km/h, Aerodynamics: Advanced F1 aero package', 'Yogyakarta, Indonesia', 'approved', '2026-01-18 22:41:04', '2026-01-18 22:52:26'),
(13, 7, '[\"cars\\/RaTahXoa4iKJSJQKDrTvLTG8Ki9v2S94RDhdJWUP.jpg\"]', 'rent', '2024', 'Red Bull Racing', 'RB20', '0', 'Automatic', '38000000', 'Cash', '1.6L V6 Turbo Hybrid', 'Tersedia', 'REDBULL-RB20-2024-001', '38000000', NULL, 'Red Bull Racing RB20 Formula 1 dengan livery biru gelap yang ikonik dengan aksen merah dan kuning. Mobil ini menampilkan sponsor Oracle, Mobil, AT&T, Honda, dan berbagai sponsor premium lainnya. Juara dunia dengan teknologi F1 terdepan.', '[\"F1 Racing Seat\",\"Steering Wheel with Paddle Shifters\",\"Halo Safety Device\",\"Advanced Telemetry System\",\"Oracle Cloud Integration\"]', '[\"Halo Protection System\",\"Monocoque Carbon Fiber Chassis\",\"FIA Safety Standards\",\"Fire Suppression System\"]', '[\"Pirelli P Zero Tires\",\"Oracle Technology\",\"Mobil 1 Lubricants\",\"Honda Power Unit\",\"Advanced Aerodynamics\",\"DRS System\"]', 'Engine: 1.6L V6 Turbo Hybrid (Honda), Power: 1000+ HP, Transmission: 8-speed Sequential, Weight: 798 kg (minimum), Top Speed: 350+ km/h, Aerodynamics: Advanced F1 aero package', 'Jakarta, Indonesia', 'approved', '2026-01-18 22:41:04', '2026-01-18 22:54:38'),
(14, 7, '[\"cars\\/SYC48qQaOGKaWytMnrYYHJlsIu351W0wYgEvqfRN.jpg\"]', 'rent', '2024', 'Aston Martin', 'AMR24', '0', 'Automatic', '30000000', 'Cash', '1.6L V6 Turbo Hybrid', 'Tersedia', 'ASTON-AMR24-2024-001', '30000000', NULL, 'Aston Martin AMR24 Formula 1 dengan livery British Racing Green yang elegan dengan aksen kuning-hijau. Mobil ini menampilkan sponsor Aramco, BOSS, Cognizant, Pepperstone, dan berbagai sponsor premium. Kombinasi elegan antara tradisi Inggris dan teknologi F1 modern.', '[\"F1 Racing Seat\",\"Steering Wheel with Paddle Shifters\",\"Halo Safety Device\",\"Advanced Telemetry System\",\"BOSS Branding\"]', '[\"Halo Protection System\",\"Monocoque Carbon Fiber Chassis\",\"FIA Safety Standards\",\"Fire Suppression System\"]', '[\"Pirelli P Zero Tires\",\"Aramco Technology\",\"Cognizant Integration\",\"Advanced Aerodynamics\",\"DRS System\"]', 'Engine: 1.6L V6 Turbo Hybrid, Power: 1000+ HP, Transmission: 8-speed Sequential, Weight: 798 kg (minimum), Top Speed: 350+ km/h, Aerodynamics: Advanced F1 aero package', 'Bali, Indonesia', 'approved', '2026-01-18 22:41:04', '2026-01-18 22:54:27'),
(15, 7, '[\"cars\\/rMGCa3RKmSSqaCGVd83VwVLj59nw5P11lyAM4KMd.jpg\"]', 'rent', '2024', 'Williams', 'FW46', '0', 'Automatic', '22000000', 'Cash', '1.6L V6 Turbo Hybrid', 'Tersedia', 'WILLIAMS-FW46-2024-055', '22000000', NULL, 'Williams FW46 Formula 1 dengan livery biru gelap yang klasik. Mobil nomor 55 ini menampilkan sponsor Atlassian, Komatsu, Gulf, MyProtein, Kraken, dan Duracell. Warisan balap yang kaya dengan teknologi F1 modern.', '[\"F1 Racing Seat\",\"Steering Wheel with Paddle Shifters\",\"Halo Safety Device\",\"Advanced Telemetry System\"]', '[\"Halo Protection System\",\"Monocoque Carbon Fiber Chassis\",\"FIA Safety Standards\",\"Fire Suppression System\"]', '[\"Pirelli P Zero Tires\",\"Atlassian Integration\",\"Komatsu Technology\",\"Gulf Branding\",\"Advanced Aerodynamics\",\"DRS System\"]', 'Engine: 1.6L V6 Turbo Hybrid, Power: 1000+ HP, Transmission: 8-speed Sequential, Weight: 798 kg (minimum), Top Speed: 350+ km/h, Aerodynamics: Advanced F1 aero package', 'Medan, Indonesia', 'approved', '2026-01-18 22:41:04', '2026-01-18 22:57:27'),
(16, 7, '[\"cars\\/oGA1G49ayAr9cHJ5ZzhOw5yY8i6IrS8JnA5Xg937.jpg\"]', 'buy', '2025', 'Red Bull', 'RB21', '50000', 'Automatic', '1000000000', 'Cash', '1.6L V6 Turbo Hybrid', 'Tersedia', 'REDBULL-RB21-2024-001', NULL, NULL, 'Mobil Formula 1 pemenang kejuaraan dunia dengan aerodinamika \'aggressive cooling\' terbaru.', '[\"5-point safety harness.\",\"Custom F1 steering wheel with LED display\",\"Carbon fiber bucket seat\"]', '[\"Halo Protection System\",\"Carbon fiber survival cell\",\"HANS device compatibility\",\"Fire suppression system\"]', '[\"DRS Drag Reduction System\",\"ERS Energy Recovery System\",\"Pirelli P Zero Tires\"]', 'Engine: 1.6L V6 Turbo Hybrid, Power: 1000+ HP, Transmission: 8-speed Sequential, Weight: 798 kg (minimum), Top Speed: 350+ km/h, Aerodynamics: Advanced F1 aero package', 'Jakarta, Indonesia', 'approved', '2026-01-18 23:05:40', '2026-01-18 23:20:30'),
(17, 7, '[\"cars\\/WfQu3oPbsf72NLzoPlToYA04NHJkSesKewojmSmY.jpg\"]', 'rent', '2023', 'ferrari', 'SF-25', '10000', 'Automatic', '50000000', 'Cash', '1.6L V6 Turbo Hybrid', NULL, NULL, NULL, NULL, NULL, '[]', '[]', '[]', NULL, NULL, 'approved', '2026-01-19 22:05:05', '2026-01-19 22:05:52'),
(19, 7, '[\"cars\\/WfQu3oPbsf72NLzoPlToYA04NHJkSesKewojmSmY.jpg\",\"cars\\/Ra0giyY5fyFZZ7c7jqBP3Y0f22rfqVMwjx4SG50P.jpg\",\"cars\\/rMGCa3RKmSSqaCGVd83VwVLj59nw5P11lyAM4KMd.jpg\",\"cars\\/MigcKxXl3gCMgp754t2Qq0woczBomHO3utsUp2t8.jpg\",\"cars\\/VHpKt3uJwXPkxHP5b5D4XmAGVwvM6yrfvED6Eqnz.jpg\",\"cars\\/RaTahXoa4iKJSJQKDrTvLTG8Ki9v2S94RDhdJWUP.jpg\"]', 'rent', '2025', 'Collaboration', 'F1', '0', 'Automatic', '1000000000', 'Kredit', '1.6L V6 Turbo Hybrid', NULL, NULL, NULL, NULL, NULL, '[]', '[]', '[]', NULL, NULL, 'approved', '2026-01-23 01:29:47', '2026-01-23 01:30:28');

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `buyer_id` bigint(20) UNSIGNED NOT NULL,
  `car_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `car_approvals`
--

CREATE TABLE `car_approvals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `car_id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `action` enum('approved','rejected') NOT NULL,
  `notes` text DEFAULT NULL,
  `approved_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `car_approvals`
--

INSERT INTO `car_approvals` (`id`, `car_id`, `admin_id`, `action`, `notes`, `approved_at`, `created_at`, `updated_at`) VALUES
(2, 7, 2, 'approved', NULL, '2026-01-14 08:59:35', '2026-01-14 08:59:35', '2026-01-14 08:59:35'),
(7, 8, 2, 'approved', 'Dummy data F1 car - Auto approved by seeder', '2026-01-18 22:41:04', '2026-01-18 22:41:04', '2026-01-18 22:41:04'),
(8, 9, 2, 'approved', 'Dummy data F1 car - Auto approved by seeder', '2026-01-18 22:41:04', '2026-01-18 22:41:04', '2026-01-18 22:41:04'),
(9, 10, 2, 'approved', 'Dummy data F1 car - Auto approved by seeder', '2026-01-18 22:41:04', '2026-01-18 22:41:04', '2026-01-18 22:41:04'),
(10, 11, 2, 'approved', 'Dummy data F1 car - Auto approved by seeder', '2026-01-18 22:41:04', '2026-01-18 22:41:04', '2026-01-18 22:41:04'),
(11, 12, 2, 'approved', 'Dummy data F1 car - Auto approved by seeder', '2026-01-18 22:41:04', '2026-01-18 22:41:04', '2026-01-18 22:41:04'),
(12, 13, 2, 'approved', 'Dummy data F1 car - Auto approved by seeder', '2026-01-18 22:41:04', '2026-01-18 22:41:04', '2026-01-18 22:41:04'),
(13, 14, 2, 'approved', 'Dummy data F1 car - Auto approved by seeder', '2026-01-18 22:41:04', '2026-01-18 22:41:04', '2026-01-18 22:41:04'),
(14, 15, 2, 'approved', 'Dummy data F1 car - Auto approved by seeder', '2026-01-18 22:41:04', '2026-01-18 22:41:04', '2026-01-18 22:41:04'),
(15, 16, 2, 'approved', NULL, '2026-01-18 23:09:31', '2026-01-18 23:09:31', '2026-01-18 23:09:31'),
(16, 17, 2, 'approved', NULL, '2026-01-19 22:05:52', '2026-01-19 22:05:52', '2026-01-19 22:05:52'),
(18, 19, 2, 'approved', NULL, '2026-01-23 01:30:28', '2026-01-23 01:30:28', '2026-01-23 01:30:28');

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `buyer_id` bigint(20) UNSIGNED NOT NULL,
  `seller_id` bigint(20) UNSIGNED NOT NULL,
  `car_id` bigint(20) UNSIGNED DEFAULT NULL,
  `last_message_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chats`
--

INSERT INTO `chats` (`id`, `buyer_id`, `seller_id`, `car_id`, `last_message_at`, `created_at`, `updated_at`) VALUES
(4, 14, 7, 17, '2026-01-25 09:08:51', '2026-01-25 08:50:19', '2026-01-25 09:08:51'),
(5, 7, 14, 17, '2026-01-28 23:26:18', '2026-01-25 21:17:09', '2026-01-28 23:26:18'),
(6, 7, 14, 8, '2026-01-25 21:41:17', '2026-01-25 21:41:17', '2026-01-25 21:41:17'),
(7, 7, 14, 11, '2026-01-26 02:43:50', '2026-01-26 00:33:55', '2026-01-26 02:43:50'),
(8, 7, 14, 13, '2026-02-01 22:39:04', '2026-01-26 07:20:20', '2026-02-01 22:39:04'),
(9, 7, 14, 9, '2026-01-28 22:14:18', '2026-01-27 09:38:52', '2026-01-28 22:14:18');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `blog_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `comment` text NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `blog_id`, `user_id`, `parent_id`, `name`, `email`, `comment`, `status`, `created_at`, `updated_at`) VALUES
(8, 8, 14, NULL, NULL, NULL, 'hai', 'approved', '2026-01-30 01:32:05', '2026-01-30 01:32:29');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `digital_signatures`
--

CREATE TABLE `digital_signatures` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fund_request_id` bigint(20) UNSIGNED NOT NULL,
  `signed_by` bigint(20) UNSIGNED NOT NULL,
  `signature_path` varchar(255) NOT NULL,
  `signed_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fund_requests`
--

CREATE TABLE `fund_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `program_name` varchar(255) NOT NULL,
  `program_type` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `amount_requested` decimal(15,2) NOT NULL,
  `amount_approved` decimal(15,2) DEFAULT NULL,
  `bank_name` varchar(255) NOT NULL,
  `account_number` varchar(255) NOT NULL,
  `account_holder` varchar(255) NOT NULL,
  `status` enum('draft','submitted','revision','approved_level_1','approved_level_2','rejected','signed','archived') NOT NULL DEFAULT 'draft',
  `submitted_at` timestamp NULL DEFAULT NULL,
  `archived_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fund_request_documents`
--

CREATE TABLE `fund_request_documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fund_request_id` bigint(20) UNSIGNED NOT NULL,
  `document_type` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `file_size` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `chat_id` bigint(20) UNSIGNED NOT NULL,
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `receiver_id` bigint(20) UNSIGNED DEFAULT NULL,
  `reply_to` bigint(20) UNSIGNED DEFAULT NULL,
  `message` text NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `edited_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `is_deleted_for_sender` tinyint(1) NOT NULL DEFAULT 0,
  `reply_to_message_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `chat_id`, `sender_id`, `receiver_id`, `reply_to`, `message`, `is_deleted`, `is_read`, `created_at`, `updated_at`, `edited_at`, `deleted_at`, `is_deleted_for_sender`, `reply_to_message_id`) VALUES
(6, 4, 14, 7, NULL, 'halo', 0, 1, '2026-01-25 08:54:20', '2026-01-25 08:54:30', NULL, NULL, 0, NULL),
(7, 4, 7, 14, NULL, 'sdasdasd', 0, 1, '2026-01-25 09:08:51', '2026-01-25 09:09:19', NULL, NULL, 0, NULL),
(8, 5, 7, NULL, NULL, 'halo', 0, 0, '2026-01-25 21:17:09', '2026-01-25 21:17:09', NULL, NULL, 0, NULL),
(9, 6, 14, NULL, NULL, 'halo', 0, 0, '2026-01-25 21:41:17', '2026-01-25 21:41:17', NULL, NULL, 0, NULL),
(10, 7, 14, NULL, NULL, 'halo', 0, 0, '2026-01-26 00:33:55', '2026-01-26 00:33:55', NULL, NULL, 0, NULL),
(11, 7, 7, NULL, NULL, 'ada yg bisa di bantu?', 0, 0, '2026-01-26 00:35:03', '2026-01-26 00:35:03', NULL, NULL, 0, NULL),
(12, 7, 14, NULL, NULL, 'apkah saya boleh test drive?', 0, 0, '2026-01-26 00:38:07', '2026-01-26 00:38:07', NULL, NULL, 0, NULL),
(13, 7, 7, NULL, NULL, 'tentu', 0, 0, '2026-01-26 01:13:32', '2026-01-26 01:13:32', NULL, NULL, 0, NULL),
(14, 7, 14, NULL, NULL, 'oke saya otw', 0, 0, '2026-01-26 01:16:26', '2026-01-26 01:16:26', NULL, NULL, 0, NULL),
(15, 7, 7, NULL, NULL, 'apa kah anda jadi?', 0, 0, '2026-01-26 02:43:20', '2026-01-26 02:43:20', NULL, NULL, 0, NULL),
(16, 7, 14, NULL, NULL, 'yaa, saya sudah di depan', 0, 0, '2026-01-26 02:43:35', '2026-01-26 02:43:35', NULL, NULL, 0, NULL),
(17, 7, 14, NULL, NULL, 'apakah saya bisa cobain sekarang?', 0, 0, '2026-01-26 02:43:51', '2026-01-26 02:43:51', NULL, NULL, 0, NULL),
(18, 8, 14, NULL, NULL, 'halo', 0, 1, '2026-01-26 07:20:20', '2026-01-26 07:47:09', NULL, NULL, 0, NULL),
(19, 8, 7, NULL, NULL, 'hallo', 0, 1, '2026-01-26 07:20:32', '2026-01-26 07:47:28', NULL, NULL, 0, NULL),
(20, 8, 7, NULL, NULL, 'apa boleh saya lihat mobil nya langsung?', 0, 1, '2026-01-26 07:57:59', '2026-01-26 07:58:15', NULL, NULL, 0, NULL),
(21, 8, 14, NULL, NULL, 'bolehhh', 0, 1, '2026-01-26 08:16:40', '2026-01-26 08:21:34', NULL, NULL, 0, NULL),
(22, 8, 14, NULL, NULL, 'halo', 0, 1, '2026-01-26 08:16:48', '2026-01-26 08:21:34', NULL, NULL, 0, 20),
(23, 8, 7, NULL, NULL, 'halo', 0, 1, '2026-01-26 08:46:29', '2026-01-26 08:46:36', NULL, NULL, 0, NULL),
(24, 8, 14, NULL, NULL, 'p', 0, 1, '2026-01-26 08:46:42', '2026-01-26 09:10:02', NULL, NULL, 0, NULL),
(25, 8, 14, NULL, NULL, 'p', 0, 1, '2026-01-26 08:46:45', '2026-01-26 09:10:02', NULL, NULL, 0, NULL),
(26, 8, 14, NULL, NULL, 'p', 0, 1, '2026-01-26 08:46:49', '2026-01-26 09:10:02', NULL, NULL, 0, NULL),
(27, 8, 7, NULL, NULL, 'iya?', 0, 1, '2026-01-26 08:56:00', '2026-01-26 09:08:48', NULL, NULL, 0, NULL),
(28, 8, 14, NULL, NULL, 'hahahahahah', 0, 1, '2026-01-26 09:11:05', '2026-01-26 09:12:06', NULL, NULL, 0, NULL),
(29, 8, 7, NULL, NULL, 'iya?', 0, 1, '2026-01-26 09:11:33', '2026-01-26 09:12:03', NULL, NULL, 0, NULL),
(30, 8, 14, NULL, NULL, 'p', 0, 1, '2026-01-26 09:20:57', '2026-01-26 09:24:22', NULL, NULL, 0, NULL),
(31, 8, 7, NULL, NULL, 'halo', 0, 1, '2026-01-26 09:54:34', '2026-01-26 10:05:03', NULL, NULL, 0, NULL),
(32, 8, 7, NULL, NULL, 'ada yg bisa di bantu?', 0, 1, '2026-01-26 09:54:48', '2026-01-26 10:05:03', NULL, NULL, 0, NULL),
(33, 8, 7, NULL, NULL, 'p', 0, 1, '2026-01-26 09:57:27', '2026-01-26 10:05:03', NULL, NULL, 0, NULL),
(34, 8, 7, NULL, NULL, 'pp', 0, 1, '2026-01-26 09:57:28', '2026-01-26 10:05:03', NULL, NULL, 0, NULL),
(35, 8, 7, NULL, NULL, 'p', 0, 1, '2026-01-26 09:57:29', '2026-01-26 10:05:03', NULL, NULL, 0, NULL),
(36, 8, 7, NULL, NULL, 'p', 0, 1, '2026-01-26 09:57:32', '2026-01-26 10:05:03', NULL, NULL, 0, NULL),
(37, 8, 7, NULL, NULL, 'p', 0, 1, '2026-01-26 09:57:44', '2026-01-26 10:05:03', NULL, NULL, 0, NULL),
(38, 8, 7, NULL, NULL, 'p', 0, 1, '2026-01-26 09:57:53', '2026-01-26 10:05:03', NULL, NULL, 0, NULL),
(39, 8, 7, NULL, NULL, 'p', 0, 1, '2026-01-26 09:57:58', '2026-01-26 10:05:03', NULL, NULL, 0, NULL),
(42, 8, 14, NULL, NULL, 'halo', 0, 1, '2026-01-26 20:27:54', '2026-01-26 20:58:27', NULL, NULL, 0, 39),
(45, 8, 14, NULL, NULL, '', 1, 1, '2026-01-26 20:58:09', '2026-01-27 02:15:30', NULL, NULL, 0, NULL),
(47, 8, 7, NULL, NULL, 'oke', 0, 1, '2026-01-27 01:08:28', '2026-01-27 02:08:00', NULL, NULL, 0, NULL),
(54, 8, 7, NULL, NULL, '', 1, 1, '2026-01-27 02:02:46', '2026-01-27 02:08:00', NULL, NULL, 0, NULL),
(55, 8, 14, NULL, NULL, '', 1, 1, '2026-01-27 02:47:40', '2026-01-27 08:40:28', NULL, NULL, 0, NULL),
(56, 8, 7, NULL, NULL, '', 1, 1, '2026-01-27 02:47:52', '2026-01-27 09:10:41', NULL, NULL, 0, NULL),
(57, 8, 7, NULL, NULL, '', 1, 1, '2026-01-27 02:48:14', '2026-01-27 09:10:41', NULL, NULL, 0, NULL),
(58, 8, 14, NULL, NULL, '', 1, 1, '2026-01-27 02:48:17', '2026-01-27 08:40:28', NULL, NULL, 0, NULL),
(59, 8, 7, NULL, NULL, 'hai', 0, 1, '2026-01-27 09:08:46', '2026-01-27 09:10:41', NULL, NULL, 0, NULL),
(60, 8, 7, NULL, NULL, 'halo', 0, 1, '2026-01-27 09:09:14', '2026-01-27 09:10:41', NULL, NULL, 0, NULL),
(61, 9, 14, NULL, NULL, 'halo', 0, 1, '2026-01-27 09:38:52', '2026-01-27 09:39:09', NULL, NULL, 0, NULL),
(62, 9, 14, NULL, NULL, 'hai', 0, 1, '2026-01-27 09:39:28', '2026-01-27 09:39:29', NULL, NULL, 0, NULL),
(63, 8, 14, NULL, NULL, 'p', 0, 1, '2026-01-27 21:48:33', '2026-01-27 21:51:25', NULL, NULL, 0, NULL),
(64, 9, 14, NULL, NULL, 'halo', 0, 1, '2026-01-27 21:49:04', '2026-01-27 21:53:36', NULL, NULL, 0, NULL),
(65, 9, 14, NULL, NULL, 'halo', 0, 1, '2026-01-27 21:55:38', '2026-01-27 21:55:43', NULL, NULL, 0, NULL),
(66, 9, 14, NULL, NULL, 'p', 0, 1, '2026-01-27 21:55:52', '2026-01-27 21:55:56', NULL, NULL, 0, NULL),
(67, 8, 14, NULL, NULL, 'lalala', 0, 1, '2026-01-27 21:57:48', '2026-01-27 21:57:55', NULL, NULL, 0, NULL),
(68, 9, 7, NULL, NULL, 'halo', 0, 1, '2026-01-27 22:04:17', '2026-01-27 22:04:20', NULL, NULL, 0, NULL),
(69, 9, 14, NULL, NULL, 'iya', 0, 1, '2026-01-27 22:08:38', '2026-01-28 02:26:06', NULL, NULL, 0, NULL),
(70, 8, 14, NULL, NULL, 'halo', 0, 1, '2026-01-27 22:08:57', '2026-01-27 22:18:53', NULL, NULL, 0, NULL),
(71, 8, 7, NULL, NULL, 'p', 0, 1, '2026-01-27 22:18:56', '2026-01-27 22:19:22', NULL, NULL, 0, NULL),
(72, 8, 14, NULL, NULL, 'hohoho', 0, 1, '2026-01-27 22:19:25', '2026-01-27 22:19:29', NULL, NULL, 0, NULL),
(73, 8, 7, NULL, NULL, 'hahaha', 0, 1, '2026-01-27 22:19:34', '2026-01-27 22:19:35', NULL, NULL, 0, NULL),
(74, 8, 7, NULL, NULL, 'halo?', 0, 1, '2026-01-28 02:22:58', '2026-01-29 00:07:38', NULL, NULL, 0, NULL),
(75, 8, 7, NULL, NULL, 'sore', 0, 1, '2026-01-28 02:23:16', '2026-01-29 00:07:38', NULL, NULL, 0, NULL),
(76, 9, 7, NULL, NULL, 'halo', 0, 1, '2026-01-28 02:26:14', '2026-01-28 22:13:51', NULL, NULL, 0, NULL),
(77, 9, 7, NULL, NULL, 'p', 0, 1, '2026-01-28 22:11:48', '2026-01-28 22:13:51', NULL, NULL, 0, NULL),
(78, 9, 7, NULL, NULL, 'halo', 0, 1, '2026-01-28 22:12:52', '2026-01-28 22:13:51', NULL, NULL, 0, NULL),
(79, 9, 7, NULL, NULL, 'halo', 0, 1, '2026-01-28 22:13:25', '2026-01-28 22:13:51', NULL, NULL, 0, NULL),
(80, 9, 14, NULL, NULL, 'p', 0, 1, '2026-01-28 22:14:18', '2026-01-28 23:53:54', NULL, NULL, 0, NULL),
(81, 5, 7, NULL, NULL, 'halo', 0, 0, '2026-01-28 23:26:18', '2026-01-28 23:26:18', NULL, NULL, 0, NULL),
(82, 8, 7, NULL, NULL, 'halo', 0, 1, '2026-01-28 23:54:17', '2026-01-29 00:07:38', NULL, NULL, 0, NULL),
(83, 8, 14, NULL, NULL, 'woi', 0, 1, '2026-01-29 00:31:40', '2026-01-29 00:31:48', NULL, NULL, 0, NULL),
(84, 8, 7, NULL, NULL, 'apa', 0, 1, '2026-01-29 00:31:50', '2026-01-29 00:31:53', NULL, NULL, 0, NULL),
(85, 8, 14, NULL, NULL, 'brng masi ada ga', 0, 1, '2026-01-29 00:32:04', '2026-01-29 00:32:12', NULL, NULL, 0, NULL),
(86, 8, 7, NULL, NULL, 'msh', 0, 1, '2026-01-29 00:33:59', '2026-01-29 00:34:10', NULL, NULL, 0, NULL),
(87, 8, 14, NULL, NULL, 'oke', 0, 1, '2026-01-29 00:38:29', '2026-01-29 00:38:35', NULL, NULL, 0, NULL),
(88, 8, 7, NULL, NULL, 'halo', 0, 1, '2026-01-29 01:07:20', '2026-01-30 00:21:34', NULL, NULL, 0, NULL),
(89, 8, 14, NULL, NULL, 'p', 0, 1, '2026-01-30 00:21:59', '2026-01-30 00:27:27', NULL, NULL, 0, NULL),
(90, 8, 7, NULL, NULL, '', 1, 1, '2026-01-30 00:27:38', '2026-01-30 01:38:58', NULL, NULL, 0, NULL),
(91, 8, 14, NULL, NULL, 'halo', 0, 1, '2026-01-30 00:29:31', '2026-01-30 00:29:35', NULL, NULL, 0, NULL),
(92, 8, 7, NULL, NULL, 'iya?', 0, 1, '2026-01-30 00:29:41', '2026-01-30 00:29:44', NULL, NULL, 0, NULL),
(93, 8, 7, NULL, NULL, 'halo', 0, 1, '2026-02-01 22:38:44', '2026-02-01 22:52:53', NULL, NULL, 0, NULL),
(94, 8, 7, NULL, NULL, 'tes', 0, 1, '2026-02-01 22:39:04', '2026-02-01 22:52:53', NULL, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `message_hashes`
--

CREATE TABLE `message_hashes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `chat_id` varchar(100) NOT NULL,
  `message_hash` varchar(64) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `message_rate_limits`
--

CREATE TABLE `message_rate_limits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `chat_id` varchar(100) NOT NULL,
  `last_message_at` timestamp NULL DEFAULT NULL,
  `message_count` int(11) NOT NULL DEFAULT 1,
  `window_start` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000001_create_cache_table', 1),
(2, '0001_01_01_000002_create_jobs_table', 1),
(3, '2014_10_12_100000_create_password_resets_table', 1),
(4, '2025_03_05_151551_create_users_table', 1),
(5, '2025_03_09_135603_create_sessions_table', 1),
(6, '2025_03_11_070324_create_car_table', 1),
(7, '2025_03_11_072739_create_about_table', 1),
(8, '2026_01_01_000000_create_fund_request_system_tables', 2),
(9, '2026_01_07_021147_add_missing_auth_columns_to_users_table', 3),
(11, '2026_01_07_090000_drop_role_from_users_table', 4),
(12, '2026_01_08_043600_update_car_table_add_tipe_and_multiple_images', 4),
(13, '2026_01_08_050503_add_detailed_fields_to_car_table', 5),
(14, '2026_01_09_065857_add_nama_to_car_table', 6),
(15, '2026_01_12_071754_create_blogs_table', 7),
(16, '2026_01_13_052000_create_comments_table', 8),
(17, '2026_01_12_050223_add_role_to_users_table', 9),
(18, '2026_01_12_050616_remove_institution_from_users_table', 9),
(19, '2026_01_14_000001_create_testimonials_table', 9),
(20, '2026_01_14_055808_create_contacts_table', 10),
(21, '2026_01_14_061343_update_users_table_role_to_admin_buyer_seller', 10),
(22, '2026_01_14_151520_add_seller_and_status_to_car_table', 11),
(23, '2026_01_14_151752_create_car_approvals_table', 11),
(24, '2026_01_14_165613_create_wishlists_table', 12),
(25, '2026_01_14_170904_create_reports_table', 13),
(26, '2026_01_15_010506_create_carts_table', 14),
(27, '2026_01_15_015448_add_indexes_to_car_table_for_performance', 14),
(28, '2026_01_15_200000_create_chats_table', 14),
(29, '2026_01_15_200001_create_messages_table', 14),
(30, '2026_01_21_015957_create_password_reset_tokens_table', 15),
(31, '2026_01_21_121353_add_verification_code_to_users_table', 16),
(32, '2026_01_22_152355_add_reply_edit_delete_to_messages_table', 17),
(33, '2026_01_23_000000_add_receiver_id_and_indexes_to_messages_table', 18),
(35, '2026_01_26_052656_spam', 19),
(36, '2026_01_26_052708_create_anti_spam_tables', 19),
(37, '2026_01_26_000001_add_reply_to_message_id_to_messages_table', 20),
(38, '2026_01_27_000001_add_is_deleted_to_messages_table', 21),
(39, '2026_02_02_000001_create_site_settings_table', 22);

-- --------------------------------------------------------

--
-- Table structure for table `notification_logs`
--

CREATE TABLE `notification_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `car_id` bigint(20) UNSIGNED NOT NULL,
  `reporter_id` bigint(20) UNSIGNED NOT NULL,
  `seller_id` bigint(20) UNSIGNED NOT NULL,
  `reason` enum('false_information','inappropriate_content','spam','duplicate','scam','other') NOT NULL,
  `message` text NOT NULL,
  `status` enum('pending','reviewed','resolved','dismissed') NOT NULL DEFAULT 'pending',
  `admin_notes` text DEFAULT NULL,
  `reviewed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `car_id`, `reporter_id`, `seller_id`, `reason`, `message`, `status`, `admin_notes`, `reviewed_by`, `reviewed_at`, `created_at`, `updated_at`) VALUES
(3, 16, 2, 7, 'spam', 'samaan sama kyk yg lain', 'resolved', NULL, 2, '2026-01-19 22:06:02', '2026-01-19 02:59:58', '2026-01-19 22:06:02');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('R3gIWIfk3H1wEkg64SquwX8vP3Uyb6zRIoFzzA2Z', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiSUloRVBGckR2S0huSEw2dWVyZEUzSGZqVlQ3YmxHcnJzMmV0eHBUTyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jYXIiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjM6InVybCI7YTowOnt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mjt9', 1770114421);

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `key`, `value`) VALUES
(1, 'footer_contact_title', 'Contact Us Now!'),
(2, 'footer_phone', '(+62) 234 1234 1234'),
(3, 'footer_email', 'garasi62@gmail.com'),
(4, 'footer_about_text', 'Any questions? Let us know in store at Jl. Prof. Joko Sutono SH No.1 2A, RT.1/RW.2, Melawai, Kebayoran Baru, South Jakarta City, Jakarta 2112'),
(5, 'footer_facebook_url', ''),
(6, 'footer_twitter_url', ''),
(7, 'footer_google_url', 'https://www.youtube.com/'),
(8, 'footer_instagram_url', ''),
(9, 'footer_skype_url', ''),
(10, 'footer_info1_title', 'Information'),
(11, 'footer_info1_list', 'youtube|/about'),
(12, 'footer_info2_title', 'Information'),
(13, 'footer_info2_list', 'Listed Car|/car'),
(14, 'footer_brands_title', 'Top Brand'),
(15, 'footer_brands_list', 'Alpine\nAston Martin\nMcLaren\nStake F1'),
(16, 'site_name', 'GARASI MOBIL'),
(17, 'site_email', 'garasi62@gmail.com'),
(18, 'site_operational_hours', 'Sales: 08:00 to 18:00'),
(19, 'about_title', 'Wellcome To Garasi62'),
(20, 'about_subtitle', 'We Provide Everything You Need To A Car'),
(21, 'about_description', 'First I will explain what contextual advertising is. Contextual advertising means the advertising of products on a website according to the content the page is displaying. For example if the content of a website was information on a Ford truck then the advertisements'),
(22, 'about_feature_1_title', 'Quality Assurance System'),
(23, 'about_feature_1_text', 'It seems though that some of the biggest problems with the internet advertising trends are the lack of'),
(24, 'about_feature_2_title', 'Accurate Testing Processes'),
(25, 'about_feature_2_text', 'Where do you register your complaints? How can you protest in any form against companies whose'),
(26, 'about_feature_3_title', 'Infrastructure Integration Technology'),
(27, 'about_feature_3_text', 'So in final analysis: it’s true, I hate peeping Toms, but if I had to choose, I’d take one any day over an'),
(28, 'about_mission_title', 'Our Mission'),
(29, 'about_mission_text', 'Now, I’m not like Robin, that weirdo from my cultural anthropology class; I think that advertising is something that has its place in our society; which for better or worse is structured along a marketplace economy. But, simply because I feel advertising has a right to exist, doesn’t mean that I like or agree with it, in its'),
(30, 'about_vision_title', 'Our Vision'),
(31, 'about_vision_text', 'Where do you register your complaints? How can you protest in any form against companies whose advertising techniques you don’t agree with? You don’t. And on another point of difference between traditional products and their advertising and those of the internet nature, simply ignoring internet advertising is'),
(32, 'operational_hours', '[{\"day\":\"Weekday\",\"hours\":\"08:00 am to 18:00 pm\"},{\"day\":\"Saturday\",\"hours\":\"10:00 am to 16:00 pm\"},{\"day\":\"Sunday\",\"hours\":\"open\"}]'),
(33, 'showrooms', '[{\"title\":\"California Showroom\",\"address\":\"625 Gloria Union, California, United Stated\",\"phone\":\"(+12) 456 678 9100\"},{\"title\":\"New York Showroom\",\"address\":\"8235 South Ave. Jamestown, NewYork\",\"phone\":\"(+12) 456 678 9100\"},{\"title\":\"Florida Showroom\",\"address\":\"497 Beaver Ridge St. Daytona Beach, Florida\",\"phone\":\"(+12) 456 678 9100\"}]'),
(34, 'about_image', 'storage/about/lEX4s3UqPiVcacaVLviV8ArqbZ7mEnKdsYitSuPt.jpg'),
(35, 'footer_social_links', '[{\"platform\":\"Google\",\"icon\":\"google\",\"url\":\"https:\\/\\/www.youtube.com\\/\"},{\"platform\":\"Instagram\",\"icon\":\"instagram\",\"url\":\"https:\\/\\/www.instagram.com\\/manutd\\/\"},{\"platform\":\"WhatsApp\",\"icon\":\"whatsapp\",\"url\":\"https:\\/\\/www.whatsapp.com\\/?lang=en\"},{\"platform\":\"GitHub\",\"icon\":\"github\",\"url\":\"https:\\/\\/github.com\\/\"}]');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `position` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL DEFAULT 5,
  `image` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `name`, `position`, `company`, `rating`, `image`, `message`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'usep', 'CEO', 'pt. kerja males', 5, NULL, 'bagussss', 1, '2026-01-13 21:39:28', '2026-01-13 21:39:28'),
(3, 'joe', 'CEO', 'pt. kerja keras', 5, NULL, 'bagus bagus mobil nya', 1, '2026-01-14 00:42:55', '2026-01-14 00:42:55'),
(4, 'john', 'Manager', 'pt. kerja terus', 5, NULL, 'recomended beli di sini', 1, '2026-01-14 00:43:33', '2026-01-14 00:43:33'),
(5, 'michael', 'Manager', 'pt. kerja kerja', 5, NULL, 'semua mobil di sini bagus bagus', 1, '2026-01-14 09:37:25', '2026-01-14 09:37:25');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `gender` enum('Perempuan','Laki-laki') NOT NULL,
  `city` varchar(255) NOT NULL,
  `institution` enum('Perorangan','Dealer') NOT NULL,
  `role` enum('admin','buyer','seller') NOT NULL DEFAULT 'buyer',
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `verification_code` varchar(255) DEFAULT NULL,
  `code_expires_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `gender`, `city`, `institution`, `role`, `password`, `created_at`, `updated_at`, `email_verified_at`, `verification_code`, `code_expires_at`, `remember_token`) VALUES
(2, 'Administrator', 'admin@garasi62.com', '081234567890', 'Laki-laki', 'Jakarta', 'Perorangan', 'admin', '$2y$12$H0uu.WWnECX6KuspForcg.9P7DYZRVd.Ji0S5wj2tkkIDY/hUJgXi', '2026-01-06 19:23:13', '2026-01-21 05:28:03', '2026-01-21 05:28:03', NULL, NULL, NULL),
(7, 'Seller Account', 'seller@garasi62.com', '081234567891', 'Laki-laki', 'Jakarta', 'Dealer', 'seller', '$2y$12$OS/dODYdTc7yxtlNL.aqQeQJ3HgK8DBMjNgyMUzqfk6CuInD/aOi6', '2026-01-14 08:34:23', '2026-01-21 05:28:03', '2026-01-21 05:28:03', NULL, NULL, NULL),
(14, 'Buyer Account', 'buyer@garasi62.com', '081234567892', 'Laki-laki', 'Jakarta', 'Perorangan', 'buyer', '$2y$12$M7IZQbGQZ7MgvhGK8CU0ZOogd1NhbQQRptzT7b8nIngY2hsyk0bgi', '2026-01-21 05:27:28', '2026-01-21 05:28:03', '2026-01-21 05:28:03', NULL, NULL, NULL),
(17, 'Taqy Nabil Adriano', 'taqy.nabil.adriano@gmail.com', '08123456789', 'Laki-laki', 'Jakarta Selatan', 'Perorangan', 'buyer', '$2y$12$3LTiK18K8xRMymZ36jB9rOYNu8.HH5S7HhLzMM0M7RMMbiZFVya8C', '2026-01-21 19:46:00', '2026-01-21 19:46:00', '2026-01-21 19:46:00', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

CREATE TABLE `wishlists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `car_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about`
--
ALTER TABLE `about`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `approvals`
--
ALTER TABLE `approvals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `approvals_fund_request_id_foreign` (`fund_request_id`),
  ADD KEY `approvals_approver_id_foreign` (`approver_id`);

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `blogs_slug_unique` (`slug`),
  ADD KEY `blogs_user_id_foreign` (`user_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `car`
--
ALTER TABLE `car`
  ADD PRIMARY KEY (`id`),
  ADD KEY `car_created_at_index` (`created_at`),
  ADD KEY `car_status_index` (`status`),
  ADD KEY `car_tipe_index` (`tipe`),
  ADD KEY `car_seller_id_created_at_index` (`seller_id`,`created_at`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `carts_buyer_id_car_id_unique` (`buyer_id`,`car_id`),
  ADD KEY `carts_car_id_foreign` (`car_id`);

--
-- Indexes for table `car_approvals`
--
ALTER TABLE `car_approvals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `car_approvals_admin_id_foreign` (`admin_id`),
  ADD KEY `car_approvals_car_id_approved_at_index` (`car_id`,`approved_at`);

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chats_buyer_id_seller_id_car_id_unique` (`buyer_id`,`seller_id`,`car_id`),
  ADD KEY `chats_seller_id_foreign` (`seller_id`),
  ADD KEY `chats_car_id_foreign` (`car_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comments_blog_id_foreign` (`blog_id`),
  ADD KEY `comments_user_id_foreign` (`user_id`),
  ADD KEY `comments_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contacts_email_sent_at_index` (`email`,`sent_at`);

--
-- Indexes for table `digital_signatures`
--
ALTER TABLE `digital_signatures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `digital_signatures_fund_request_id_foreign` (`fund_request_id`),
  ADD KEY `digital_signatures_signed_by_foreign` (`signed_by`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `fund_requests`
--
ALTER TABLE `fund_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fund_requests_user_id_foreign` (`user_id`);

--
-- Indexes for table `fund_request_documents`
--
ALTER TABLE `fund_request_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fund_request_documents_fund_request_id_foreign` (`fund_request_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_sender_id_foreign` (`sender_id`),
  ADD KEY `messages_chat_id_created_at_index` (`chat_id`,`created_at`),
  ADD KEY `messages_reply_to_index` (`reply_to`),
  ADD KEY `messages_sender_id_index` (`sender_id`),
  ADD KEY `messages_receiver_id_index` (`receiver_id`),
  ADD KEY `messages_sender_id_receiver_id_index` (`sender_id`,`receiver_id`),
  ADD KEY `messages_receiver_id_is_read_index` (`receiver_id`,`is_read`),
  ADD KEY `messages_reply_to_message_id_foreign` (`reply_to_message_id`);

--
-- Indexes for table `message_hashes`
--
ALTER TABLE `message_hashes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `message_hashes_user_id_chat_id_message_hash_index` (`user_id`,`chat_id`,`message_hash`),
  ADD KEY `message_hashes_created_at_index` (`created_at`);

--
-- Indexes for table `message_rate_limits`
--
ALTER TABLE `message_rate_limits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `message_rate_limits_user_id_chat_id_index` (`user_id`,`chat_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification_logs`
--
ALTER TABLE `notification_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notification_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reports_reporter_id_foreign` (`reporter_id`),
  ADD KEY `reports_reviewed_by_foreign` (`reviewed_by`),
  ADD KEY `reports_car_id_status_index` (`car_id`,`status`),
  ADD KEY `reports_seller_id_status_index` (`seller_id`,`status`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `site_settings_key_unique` (`key`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `wishlists_user_id_car_id_unique` (`user_id`,`car_id`),
  ADD KEY `wishlists_car_id_foreign` (`car_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about`
--
ALTER TABLE `about`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `approvals`
--
ALTER TABLE `approvals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `car`
--
ALTER TABLE `car`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `car_approvals`
--
ALTER TABLE `car_approvals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `digital_signatures`
--
ALTER TABLE `digital_signatures`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fund_requests`
--
ALTER TABLE `fund_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fund_request_documents`
--
ALTER TABLE `fund_request_documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `message_hashes`
--
ALTER TABLE `message_hashes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `message_rate_limits`
--
ALTER TABLE `message_rate_limits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `notification_logs`
--
ALTER TABLE `notification_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `approvals`
--
ALTER TABLE `approvals`
  ADD CONSTRAINT `approvals_approver_id_foreign` FOREIGN KEY (`approver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `approvals_fund_request_id_foreign` FOREIGN KEY (`fund_request_id`) REFERENCES `fund_requests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `blogs`
--
ALTER TABLE `blogs`
  ADD CONSTRAINT `blogs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `car`
--
ALTER TABLE `car`
  ADD CONSTRAINT `car_seller_id_foreign` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_buyer_id_foreign` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_car_id_foreign` FOREIGN KEY (`car_id`) REFERENCES `car` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `car_approvals`
--
ALTER TABLE `car_approvals`
  ADD CONSTRAINT `car_approvals_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `car_approvals_car_id_foreign` FOREIGN KEY (`car_id`) REFERENCES `car` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `chats`
--
ALTER TABLE `chats`
  ADD CONSTRAINT `chats_buyer_id_foreign` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chats_car_id_foreign` FOREIGN KEY (`car_id`) REFERENCES `car` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `chats_seller_id_foreign` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_blog_id_foreign` FOREIGN KEY (`blog_id`) REFERENCES `blogs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `digital_signatures`
--
ALTER TABLE `digital_signatures`
  ADD CONSTRAINT `digital_signatures_fund_request_id_foreign` FOREIGN KEY (`fund_request_id`) REFERENCES `fund_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `digital_signatures_signed_by_foreign` FOREIGN KEY (`signed_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fund_requests`
--
ALTER TABLE `fund_requests`
  ADD CONSTRAINT `fund_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fund_request_documents`
--
ALTER TABLE `fund_request_documents`
  ADD CONSTRAINT `fund_request_documents_fund_request_id_foreign` FOREIGN KEY (`fund_request_id`) REFERENCES `fund_requests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_chat_id_foreign` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_reply_to_foreign` FOREIGN KEY (`reply_to`) REFERENCES `messages` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `messages_reply_to_message_id_foreign` FOREIGN KEY (`reply_to_message_id`) REFERENCES `messages` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `message_hashes`
--
ALTER TABLE `message_hashes`
  ADD CONSTRAINT `message_hashes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `message_rate_limits`
--
ALTER TABLE `message_rate_limits`
  ADD CONSTRAINT `message_rate_limits_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notification_logs`
--
ALTER TABLE `notification_logs`
  ADD CONSTRAINT `notification_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_car_id_foreign` FOREIGN KEY (`car_id`) REFERENCES `car` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reports_reporter_id_foreign` FOREIGN KEY (`reporter_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reports_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `reports_seller_id_foreign` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD CONSTRAINT `wishlists_car_id_foreign` FOREIGN KEY (`car_id`) REFERENCES `car` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
