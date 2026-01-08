-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 03, 2026 at 12:42 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sistem_laundry_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id_customer` bigint UNSIGNED NOT NULL,
  `nama_lengkap` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_wa` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `email` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id_customer`, `nama_lengkap`, `no_wa`, `alamat`, `email`, `password`, `description`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Pandu Firmansyah', '0702 1485 5298', 'Ki. Kebonjati No. 907, Bukittinggi 71722, NTT', 'sadina83@example.org', '$2y$12$i1fSAG0MGNfEiz9FviFwxeIFdltGBUcDKkp/rxgnRd9bgEjIlIhDu', 'Pelanggan setia', NULL, '2026-01-03 05:42:23', '2026-01-03 05:42:23'),
(2, 'Pranawa Natsir S.Kom', '(+62) 978 0274 6588', 'Jln. Baja Raya No. 929, Cimahi 73440, Aceh', 'hutagalung.titin@example.net', '$2y$12$N9lUY9ryMUD7Kzdn4CU5SuBRmunxpQzjYZxtYfrAneMLhDHiAuF.m', 'Pelanggan setia', NULL, '2026-01-03 05:42:24', '2026-01-03 05:42:24'),
(3, 'Agus Maryadi', '(+62) 292 3179 2105', 'Dk. Ekonomi No. 861, Surakarta 33236, Papua', 'fwahyuni@example.org', '$2y$12$IMJF1yuyi37ft9K/2wLEGuCmIoTXdPV4mnW77vBpve9H97LVTSCW.', 'Pelanggan setia', NULL, '2026-01-03 05:42:24', '2026-01-03 05:42:24'),
(4, 'Ganep Gunawan', '(+62) 266 9998 728', 'Gg. Ki Hajar Dewantara No. 77, Banjarbaru 77813, Sulteng', 'pfujiati@example.com', '$2y$12$xVEEBcG//U7st8YuPoWP7.kV1t.v4B1XthkoN2ej0KDOwFBWC3Tx.', 'Pelanggan setia', NULL, '2026-01-03 05:42:24', '2026-01-03 05:42:24'),
(5, 'Maya Restu Pratiwi S.Sos', '022 8113 570', 'Gg. Villa No. 547, Pangkal Pinang 68519, Pabar', 'gyolanda@example.net', '$2y$12$9waHJf0gPzLEpZgvQeJAXObWRtNKectbugZ.LeVs5MCug6Z5IWeAG', 'Pelanggan setia', NULL, '2026-01-03 05:42:24', '2026-01-03 05:42:24'),
(6, 'Humaira Wulandari', '(+62) 389 0331 2851', 'Ds. Imam Bonjol No. 583, Bekasi 78038, Kaltara', 'elma92@example.org', '$2y$12$x0obTfnHJLpNwSKXKGonCeFddeu2hXDxxnjGsfH4L4m00tcQ4AC6i', 'Pelanggan setia', NULL, '2026-01-03 05:42:24', '2026-01-03 05:42:24'),
(7, 'Victoria Anggraini', '(+62) 810 4958 5702', 'Kpg. Jambu No. 429, Padang 76323, Jambi', 'yunita.pratama@example.net', '$2y$12$25xzZNiona1s6uUp0qfxS.6DeMzamsdtEUHPxVJ9yWx/GElEe6GM.', 'Pelanggan setia', NULL, '2026-01-03 05:42:25', '2026-01-03 05:42:25'),
(8, 'Darimin Latupono', '(+62) 649 1483 593', 'Kpg. Salak No. 589, Tangerang Selatan 11501, Jateng', 'snuraini@example.net', '$2y$12$WNRMYt81R4bkyQ0BHun29O2Q/umM0yKetaE/LhOqmf3oMCOgG5iX6', 'Pelanggan setia', NULL, '2026-01-03 05:42:25', '2026-01-03 05:42:25'),
(9, 'Ganda Aditya Simanjuntak', '027 3655 447', 'Kpg. Yosodipuro No. 948, Tidore Kepulauan 26556, Aceh', 'hhandayani@example.net', '$2y$12$a70qHO9y6ODE.i5FQSpwU.49x537bXb8YOtFzstUwkei6JBg8q/rS', 'Pelanggan setia', NULL, '2026-01-03 05:42:25', '2026-01-03 05:42:25'),
(10, 'Farah Susanti', '026 8096 8616', 'Kpg. K.H. Maskur No. 1, Surabaya 81401, Kaltim', 'bwidiastuti@example.net', '$2y$12$e35ZVMzZWLKvqujd7ZSEa.hxX8.G2od3YLVTLB4vhVRUdvYsgahfi', 'Pelanggan setia', NULL, '2026-01-03 05:42:25', '2026-01-03 05:42:25'),
(11, 'Udin', '081234567890', 'Jl. Contoh No. 123', 'uramazingdev@gmail.com', '$2y$12$YbWZgQ2r/smsevYMaYnp9OBYQt3v0RkkilopqAIY7N8bz.yW9.GQe', 'Pelanggan', NULL, '2026-01-03 05:42:25', '2026-01-03 05:42:25');

-- --------------------------------------------------------

--
-- Table structure for table `exports`
--

CREATE TABLE `exports` (
  `id` bigint UNSIGNED NOT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `file_disk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `exporter` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `processed_rows` int UNSIGNED NOT NULL DEFAULT '0',
  `total_rows` int UNSIGNED NOT NULL,
  `successful_rows` int UNSIGNED NOT NULL DEFAULT '0',
  `user_id_user` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_import_rows`
--

CREATE TABLE `failed_import_rows` (
  `id` bigint UNSIGNED NOT NULL,
  `data` json NOT NULL,
  `import_id` bigint UNSIGNED NOT NULL,
  `validation_error` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `imports`
--

CREATE TABLE `imports` (
  `id` bigint UNSIGNED NOT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `importer` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `processed_rows` int UNSIGNED NOT NULL DEFAULT '0',
  `total_rows` int UNSIGNED NOT NULL,
  `successful_rows` int UNSIGNED NOT NULL DEFAULT '0',
  `user_id_user` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_12_16_033519_customers', 1),
(5, '2025_12_16_033629_services', 1),
(6, '2025_12_16_033734_orders', 1),
(7, '2025_12_16_033901_order_details', 1),
(8, '2025_12_16_034006_order_tracking', 1),
(9, '2025_12_25_042548_create_imports_table', 1),
(10, '2025_12_25_042549_create_exports_table', 1),
(11, '2025_12_25_042550_create_failed_import_rows_table', 1),
(12, '2025_12_25_044819_create_notifications_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id_orders` bigint UNSIGNED NOT NULL,
  `customer_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `tgl_masuk` datetime NOT NULL,
  `tgl_selesai_estimasi` datetime DEFAULT NULL,
  `total_harga` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status_pembayaran` enum('Pending','Lunas') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `is_pickup` tinyint(1) NOT NULL DEFAULT '0',
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `alamat_jemput` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id_orders`, `customer_id`, `user_id`, `tgl_masuk`, `tgl_selesai_estimasi`, `total_harga`, `status_pembayaran`, `is_pickup`, `catatan`, `alamat_jemput`, `created_at`, `updated_at`) VALUES
(1, 8, 2, '2026-01-01 12:42:25', '2026-01-05 12:42:25', 18000.00, 'Lunas', 1, 'Aut culpa eum amet perspiciatis. Qui modi cupiditate consequatur reprehenderit enim et.', 'Ds. Cihampelas No. 574, Lhokseumawe 88882, Sulut', '2026-01-03 05:42:25', '2026-01-03 05:42:25'),
(2, 9, 2, '2025-12-30 12:42:25', '2026-01-05 12:42:25', 7000.00, 'Pending', 0, 'Explicabo praesentium magni ut in fugiat et. Occaecati quia delectus maiores debitis alias nobis dolor. Rem dolorum tenetur corrupti sed aspernatur dicta.', 'Ki. Suharso No. 363, Probolinggo 48029, Sulteng', '2026-01-03 05:42:25', '2026-01-03 05:42:25'),
(3, 5, 2, '2026-01-03 12:42:25', '2026-01-05 12:42:25', 102000.00, 'Pending', 1, 'Qui facilis ipsum quidem ipsa nisi et in. Enim omnis porro aut ut et vero et incidunt.', 'Kpg. Ciwastra No. 552, Kediri 60974, Sulsel', '2026-01-03 05:42:25', '2026-01-03 05:42:26'),
(4, 7, 3, '2026-01-01 12:42:26', '2026-01-05 12:42:26', 255000.00, 'Pending', 0, 'A dignissimos totam voluptates qui. Esse mollitia impedit totam non ex nostrum ullam. Delectus quia blanditiis ad rerum aperiam.', 'Gg. Bata Putih No. 68, Tegal 88767, Aceh', '2026-01-03 05:42:26', '2026-01-03 05:42:26'),
(5, 9, 3, '2025-12-29 12:42:26', '2026-01-05 12:42:26', 47000.00, 'Lunas', 1, 'Quidem sequi totam animi ducimus perferendis quis pariatur. Qui sit tenetur exercitationem magni quis ea.', 'Psr. Flora No. 536, Payakumbuh 50115, Banten', '2026-01-03 05:42:26', '2026-01-03 05:42:26'),
(6, 2, 2, '2026-01-01 12:42:26', '2026-01-05 12:42:26', 86000.00, 'Pending', 1, 'Eius magni et eos nemo voluptatem. Quisquam sit dignissimos dicta nobis laudantium voluptate. Ea omnis et distinctio ut animi animi vero.', 'Jr. Labu No. 533, Administrasi Jakarta Timur 78802, Kalsel', '2026-01-03 05:42:26', '2026-01-03 05:42:26'),
(7, 11, 2, '2025-12-31 12:42:26', '2026-01-05 12:42:26', 54000.00, 'Lunas', 1, 'Reprehenderit illum quis aut repellendus labore ducimus. Autem magni aliquam autem temporibus.', 'Jr. Wahidin Sudirohusodo No. 302, Denpasar 34933, Sumsel', '2026-01-03 05:42:26', '2026-01-03 05:42:26'),
(8, 11, 3, '2026-01-01 12:42:26', '2026-01-05 12:42:26', 60000.00, 'Pending', 0, 'Quisquam consequatur fuga et ut aliquid. Facere ut nemo voluptas qui quam dolorem ad. Maxime est et voluptate qui ut perspiciatis saepe.', 'Jln. Orang No. 758, Serang 32325, Banten', '2026-01-03 05:42:26', '2026-01-03 05:42:26'),
(9, 3, 2, '2026-01-03 12:42:26', '2026-01-05 12:42:26', 100000.00, 'Pending', 1, 'Ipsam error animi eligendi quo. Incidunt esse sit aspernatur consequatur sunt id occaecati itaque. Vero vel eum et quae ipsum.', 'Ds. Setia Budi No. 349, Pariaman 39565, Papua', '2026-01-03 05:42:26', '2026-01-03 05:42:26'),
(10, 9, 2, '2025-12-30 12:42:26', '2026-01-05 12:42:26', 24000.00, 'Pending', 0, 'Nihil sit unde consequatur autem optio. Itaque eos itaque architecto tenetur et. Ea tempora qui excepturi voluptatibus voluptatem nostrum commodi.', 'Kpg. Pahlawan No. 82, Bitung 70123, DIY', '2026-01-03 05:42:26', '2026-01-03 05:42:26'),
(11, 2, 3, '2025-12-30 12:42:26', '2026-01-05 12:42:26', 10000.00, 'Pending', 1, 'Temporibus reprehenderit numquam nostrum. Facere ea quis ullam eum.', 'Psr. Wahidin No. 11, Sibolga 98722, Sulsel', '2026-01-03 05:42:26', '2026-01-03 05:42:26'),
(12, 11, 2, '2026-01-02 12:42:26', '2026-01-05 12:42:26', 118000.00, 'Pending', 1, 'Qui ut recusandae voluptatem at possimus. Ab sit odio consequuntur ipsum.', 'Ki. Untung Suropati No. 583, Tanjung Pinang 18118, Kepri', '2026-01-03 05:42:26', '2026-01-03 05:42:26'),
(13, 6, 3, '2025-12-31 12:42:26', '2026-01-05 12:42:26', 100000.00, 'Lunas', 0, 'Inventore quo error dolor mollitia in quia qui. Et qui ipsum voluptas corrupti tempora. Quaerat rerum dolore omnis sint fugiat voluptatem natus.', 'Kpg. Abdullah No. 449, Banjarmasin 92051, Jambi', '2026-01-03 05:42:26', '2026-01-03 05:42:26'),
(14, 2, 3, '2025-12-29 12:42:26', '2026-01-05 12:42:26', 36000.00, 'Pending', 0, 'Et doloribus est corrupti. Nihil dolores aliquam aliquam aut animi sint. Sunt dolores voluptas qui odit dolorem cumque.', 'Dk. Balikpapan No. 718, Pontianak 87640, Kaltara', '2026-01-03 05:42:26', '2026-01-03 05:42:26'),
(15, 4, 2, '2025-12-30 12:42:26', '2026-01-05 12:42:26', 54000.00, 'Lunas', 0, 'Dignissimos ut eligendi voluptatum occaecati qui officia. Vitae qui omnis qui id sequi asperiores. Voluptatum voluptatem aspernatur nobis voluptas iste perspiciatis.', 'Dk. Tangkuban Perahu No. 311, Ambon 23088, Kaltara', '2026-01-03 05:42:26', '2026-01-03 05:42:26');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id_order_details` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `service_id` bigint UNSIGNED DEFAULT NULL,
  `qty` double NOT NULL,
  `harga_saat_ini` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id_order_details`, `order_id`, `service_id`, `qty`, `harga_saat_ini`, `subtotal`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 1, 18000.00, 18000.00, '2026-01-03 05:42:25', '2026-01-03 05:42:25'),
(2, 2, 1, 1, 7000.00, 7000.00, '2026-01-03 05:42:25', '2026-01-03 05:42:25'),
(3, 3, 8, 4, 15000.00, 60000.00, '2026-01-03 05:42:25', '2026-01-03 05:42:25'),
(4, 3, 1, 1, 7000.00, 7000.00, '2026-01-03 05:42:25', '2026-01-03 05:42:25'),
(5, 3, 10, 1, 35000.00, 35000.00, '2026-01-03 05:42:25', '2026-01-03 05:42:25'),
(6, 4, 9, 4, 10000.00, 40000.00, '2026-01-03 05:42:26', '2026-01-03 05:42:26'),
(7, 4, 10, 4, 35000.00, 140000.00, '2026-01-03 05:42:26', '2026-01-03 05:42:26'),
(8, 4, 8, 5, 15000.00, 75000.00, '2026-01-03 05:42:26', '2026-01-03 05:42:26'),
(9, 5, 2, 1, 12000.00, 12000.00, '2026-01-03 05:42:26', '2026-01-03 05:42:26'),
(10, 5, 10, 1, 35000.00, 35000.00, '2026-01-03 05:42:26', '2026-01-03 05:42:26'),
(11, 6, 6, 2, 20000.00, 40000.00, '2026-01-03 05:42:26', '2026-01-03 05:42:26'),
(12, 6, 3, 1, 18000.00, 18000.00, '2026-01-03 05:42:26', '2026-01-03 05:42:26'),
(13, 6, 1, 4, 7000.00, 28000.00, '2026-01-03 05:42:26', '2026-01-03 05:42:26'),
(14, 7, 3, 3, 18000.00, 54000.00, '2026-01-03 05:42:26', '2026-01-03 05:42:26'),
(15, 8, 6, 3, 20000.00, 60000.00, '2026-01-03 05:42:26', '2026-01-03 05:42:26'),
(16, 9, 6, 5, 20000.00, 100000.00, '2026-01-03 05:42:26', '2026-01-03 05:42:26'),
(17, 10, 2, 2, 12000.00, 24000.00, '2026-01-03 05:42:26', '2026-01-03 05:42:26'),
(18, 11, 9, 1, 10000.00, 10000.00, '2026-01-03 05:42:26', '2026-01-03 05:42:26'),
(19, 12, 1, 4, 7000.00, 28000.00, '2026-01-03 05:42:26', '2026-01-03 05:42:26'),
(20, 12, 11, 2, 45000.00, 90000.00, '2026-01-03 05:42:26', '2026-01-03 05:42:26'),
(21, 13, 6, 5, 20000.00, 100000.00, '2026-01-03 05:42:26', '2026-01-03 05:42:26'),
(22, 14, 3, 2, 18000.00, 36000.00, '2026-01-03 05:42:26', '2026-01-03 05:42:26'),
(23, 15, 3, 3, 18000.00, 54000.00, '2026-01-03 05:42:26', '2026-01-03 05:42:26');

-- --------------------------------------------------------

--
-- Table structure for table `order_tracking`
--

CREATE TABLE `order_tracking` (
  `id_order_tracking` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `status` enum('Perlu Dijemput','Dicuci','Dijemur','Disetrika','Siap') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_tracking`
--

INSERT INTO `order_tracking` (`id_order_tracking`, `order_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Perlu Dijemput', '2026-01-01 05:42:25', '2026-01-01 05:42:25'),
(2, 1, 'Dicuci', '2026-01-01 09:42:25', '2026-01-01 09:42:25'),
(3, 1, 'Dijemur', '2026-01-01 13:42:25', '2026-01-01 13:42:25'),
(4, 1, 'Disetrika', '2026-01-01 17:42:25', '2026-01-01 17:42:25'),
(5, 2, 'Perlu Dijemput', '2025-12-30 05:42:25', '2025-12-30 05:42:25'),
(6, 2, 'Dicuci', '2025-12-30 09:42:25', '2025-12-30 09:42:25'),
(7, 3, 'Perlu Dijemput', '2026-01-03 05:42:25', '2026-01-03 05:42:25'),
(8, 3, 'Dicuci', '2026-01-03 09:42:25', '2026-01-03 09:42:25'),
(9, 3, 'Dijemur', '2026-01-03 13:42:25', '2026-01-03 13:42:25'),
(10, 3, 'Disetrika', '2026-01-03 17:42:25', '2026-01-03 17:42:25'),
(11, 4, 'Perlu Dijemput', '2026-01-01 05:42:26', '2026-01-01 05:42:26'),
(12, 4, 'Dicuci', '2026-01-01 09:42:26', '2026-01-01 09:42:26'),
(13, 4, 'Dijemur', '2026-01-01 13:42:26', '2026-01-01 13:42:26'),
(14, 4, 'Disetrika', '2026-01-01 17:42:26', '2026-01-01 17:42:26'),
(15, 5, 'Perlu Dijemput', '2025-12-29 05:42:26', '2025-12-29 05:42:26'),
(16, 5, 'Dicuci', '2025-12-29 09:42:26', '2025-12-29 09:42:26'),
(17, 5, 'Dijemur', '2025-12-29 13:42:26', '2025-12-29 13:42:26'),
(18, 5, 'Disetrika', '2025-12-29 17:42:26', '2025-12-29 17:42:26'),
(19, 6, 'Perlu Dijemput', '2026-01-01 05:42:26', '2026-01-01 05:42:26'),
(20, 6, 'Dicuci', '2026-01-01 09:42:26', '2026-01-01 09:42:26'),
(21, 6, 'Dijemur', '2026-01-01 13:42:26', '2026-01-01 13:42:26'),
(22, 7, 'Perlu Dijemput', '2025-12-31 05:42:26', '2025-12-31 05:42:26'),
(23, 7, 'Dicuci', '2025-12-31 09:42:26', '2025-12-31 09:42:26'),
(24, 7, 'Dijemur', '2025-12-31 13:42:26', '2025-12-31 13:42:26'),
(25, 7, 'Disetrika', '2025-12-31 17:42:26', '2025-12-31 17:42:26'),
(26, 8, 'Perlu Dijemput', '2026-01-01 05:42:26', '2026-01-01 05:42:26'),
(27, 9, 'Perlu Dijemput', '2026-01-03 05:42:26', '2026-01-03 05:42:26'),
(28, 10, 'Perlu Dijemput', '2025-12-30 05:42:26', '2025-12-30 05:42:26'),
(29, 10, 'Dicuci', '2025-12-30 09:42:26', '2025-12-30 09:42:26'),
(30, 10, 'Dijemur', '2025-12-30 13:42:26', '2025-12-30 13:42:26'),
(31, 10, 'Disetrika', '2025-12-30 17:42:26', '2025-12-30 17:42:26'),
(32, 11, 'Perlu Dijemput', '2025-12-30 05:42:26', '2025-12-30 05:42:26'),
(33, 12, 'Perlu Dijemput', '2026-01-02 05:42:26', '2026-01-02 05:42:26'),
(34, 12, 'Dicuci', '2026-01-02 09:42:26', '2026-01-02 09:42:26'),
(35, 12, 'Dijemur', '2026-01-02 13:42:26', '2026-01-02 13:42:26'),
(36, 13, 'Perlu Dijemput', '2025-12-31 05:42:26', '2025-12-31 05:42:26'),
(37, 13, 'Dicuci', '2025-12-31 09:42:26', '2025-12-31 09:42:26'),
(38, 13, 'Dijemur', '2025-12-31 13:42:26', '2025-12-31 13:42:26'),
(39, 13, 'Disetrika', '2025-12-31 17:42:26', '2025-12-31 17:42:26'),
(40, 14, 'Perlu Dijemput', '2025-12-29 05:42:26', '2025-12-29 05:42:26'),
(41, 14, 'Dicuci', '2025-12-29 09:42:26', '2025-12-29 09:42:26'),
(42, 14, 'Dijemur', '2025-12-29 13:42:26', '2025-12-29 13:42:26'),
(43, 15, 'Perlu Dijemput', '2025-12-30 05:42:26', '2025-12-30 05:42:26'),
(44, 15, 'Dicuci', '2025-12-30 09:42:26', '2025-12-30 09:42:26'),
(45, 15, 'Dijemur', '2025-12-30 13:42:26', '2025-12-30 13:42:26');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id_services` bigint UNSIGNED NOT NULL,
  `nama_paket` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `satuan` enum('Kg','Pcs','m2','Pasang','Set') COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `estimasi_durasi` int NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id_services`, `nama_paket`, `deskripsi`, `satuan`, `harga`, `estimasi_durasi`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Cuci Komplit Regular', 'Layanan cuci, kering, dan setrika standar. Pengerjaan 3 hari.', 'Kg', 7000.00, 72, 1, '2026-01-03 05:42:25', '2026-01-03 05:42:25'),
(2, 'Cuci Komplit Express', 'Layanan prioritas cuci, kering, dan setrika. Selesai dalam 1 hari.', 'Kg', 12000.00, 24, 1, '2026-01-03 05:42:25', '2026-01-03 05:42:25'),
(3, 'Cuci Komplit Kilat', 'Layanan super cepat, selesai dalam 6 jam (tunggu di tempat/ditunggu).', 'Kg', 18000.00, 6, 1, '2026-01-03 05:42:25', '2026-01-03 05:42:25'),
(4, 'Cuci Kering Lipat', 'Cuci dan kering saja tanpa setrika. Pakaian dilipat rapi.', 'Kg', 5000.00, 48, 1, '2026-01-03 05:42:25', '2026-01-03 05:42:25'),
(5, 'Setrika Saja', 'Hanya jasa setrika dan pewangi pakaian.', 'Kg', 5000.00, 24, 1, '2026-01-03 05:42:25', '2026-01-03 05:42:25'),
(6, 'Cuci Bedcover Kecil/Single', 'Pencucian khusus bedcover ukuran single.', 'Pcs', 20000.00, 72, 1, '2026-01-03 05:42:25', '2026-01-03 05:42:25'),
(7, 'Cuci Bedcover Besar/Double', 'Pencucian khusus bedcover ukuran king/queen.', 'Pcs', 30000.00, 72, 1, '2026-01-03 05:42:25', '2026-01-03 05:42:25'),
(8, 'Cuci Karpet', 'Deep cleaning untuk karpet (harga per meter persegi).', 'm2', 15000.00, 96, 1, '2026-01-03 05:42:25', '2026-01-03 05:42:25'),
(9, 'Cuci Boneka (Small)', 'Cuci boneka ukuran kecil (< 30cm).', 'Pcs', 10000.00, 48, 1, '2026-01-03 05:42:25', '2026-01-03 05:42:25'),
(10, 'Cuci Sepatu Deep Clean', 'Perawatan detail untuk sepatu (Canvas/Suede/Leather).', 'Pasang', 35000.00, 72, 1, '2026-01-03 05:42:25', '2026-01-03 05:42:25'),
(11, 'Dry Clean Jas/Suit', 'Pencucian kering khusus bahan jas formal.', 'Set', 45000.00, 72, 1, '2026-01-03 05:42:25', '2026-01-03 05:42:25');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` bigint UNSIGNED NOT NULL,
  `nama_lengkap` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','kasir') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'kasir',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `nama_lengkap`, `email`, `password`, `role`, `email_verified_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Intan Nurlistiyani', 'intan9el@gmail.com', '$2y$12$8TAaM83bDuu4ccxTjXhv0O3oXwhQKSPAHYA5o5AAhG340mKs81Q5K', 'admin', NULL, NULL, '2026-01-03 05:42:23', '2026-01-03 05:42:23'),
(2, 'Hadi', 'hadidpranoto@gmail.com', '$2y$12$C92Sp7P2Mqe.wEXEbc183.AJblNktdJnytKDQyJjmTFnpNUnSp212', 'kasir', NULL, NULL, '2026-01-03 05:42:23', '2026-01-03 05:42:23'),
(3, 'Akhdan Fadhil', 'itsnaakhdan25@gmail.com', '$2y$12$iTT9ZsBJEIBEfz9Ah93oAuhMJpp382r7Qf6diwYyaV5hXA/iY25Ia', 'kasir', NULL, NULL, '2026-01-03 05:42:23', '2026-01-03 05:42:23');

--
-- Indexes for dumped tables
--

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
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id_customer`),
  ADD UNIQUE KEY `customers_email_unique` (`email`);

--
-- Indexes for table `exports`
--
ALTER TABLE `exports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exports_user_id_user_foreign` (`user_id_user`);

--
-- Indexes for table `failed_import_rows`
--
ALTER TABLE `failed_import_rows`
  ADD PRIMARY KEY (`id`),
  ADD KEY `failed_import_rows_import_id_foreign` (`import_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `imports`
--
ALTER TABLE `imports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `imports_user_id_user_foreign` (`user_id_user`);

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
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id_orders`),
  ADD KEY `orders_customer_id_foreign` (`customer_id`),
  ADD KEY `orders_user_id_foreign` (`user_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id_order_details`),
  ADD KEY `order_details_order_id_foreign` (`order_id`),
  ADD KEY `order_details_service_id_foreign` (`service_id`);

--
-- Indexes for table `order_tracking`
--
ALTER TABLE `order_tracking`
  ADD PRIMARY KEY (`id_order_tracking`),
  ADD KEY `order_tracking_order_id_foreign` (`order_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id_services`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id_customer` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `exports`
--
ALTER TABLE `exports`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_import_rows`
--
ALTER TABLE `failed_import_rows`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `imports`
--
ALTER TABLE `imports`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id_orders` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id_order_details` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `order_tracking`
--
ALTER TABLE `order_tracking`
  MODIFY `id_order_tracking` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id_services` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `exports`
--
ALTER TABLE `exports`
  ADD CONSTRAINT `exports_user_id_user_foreign` FOREIGN KEY (`user_id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `failed_import_rows`
--
ALTER TABLE `failed_import_rows`
  ADD CONSTRAINT `failed_import_rows_import_id_foreign` FOREIGN KEY (`import_id`) REFERENCES `imports` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `imports`
--
ALTER TABLE `imports`
  ADD CONSTRAINT `imports_user_id_user_foreign` FOREIGN KEY (`user_id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id_customer`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id_orders`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_details_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id_services`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `order_tracking`
--
ALTER TABLE `order_tracking`
  ADD CONSTRAINT `order_tracking_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id_orders`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
