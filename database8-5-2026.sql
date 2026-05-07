-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               12.1.2-MariaDB - MariaDB Server
-- Server OS:                    Win64
-- HeidiSQL Version:             12.14.0.7165
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for photobooth_db
CREATE DATABASE IF NOT EXISTS `photobooth_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `photobooth_db`;

-- Dumping structure for table photobooth_db.admins
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table photobooth_db.admins: ~1 rows (approximately)
INSERT INTO `admins` (`id`, `username`, `password`, `created_at`) VALUES
	(1, 'admin', '$2y$12$Oth6k7Dj.vBklsPGoeYcReNOkdwTE.9fCxpKcN5ujYck/Et9GZI3u', '2026-01-02 18:01:46');

-- Dumping structure for table photobooth_db.assets
CREATE TABLE IF NOT EXISTS `assets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) DEFAULT NULL,
  `type` enum('frame','sticker','filter') NOT NULL,
  `path` varchar(255) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `slot_count` int(11) NOT NULL DEFAULT 0,
  `slot_coordinates` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `event_id` (`event_id`),
  CONSTRAINT `assets_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table photobooth_db.assets: ~20 rows (approximately)
INSERT INTO `assets` (`id`, `event_id`, `type`, `path`, `name`, `slot_count`, `slot_coordinates`, `created_at`) VALUES
	(11, NULL, 'sticker', '/assets/stickers/sticker_698abc94ecc607.32174681.png', 'asdsa', 0, NULL, '2026-02-10 05:05:25'),
	(14, NULL, 'frame', '/assets/frames/frame_698ae323efc7f9.61784940.png', 'transparant', 3, '[{"top":5,"left":8,"width":84.88541666666667,"height":21.20486111111111},{"top":28.444444444444443,"left":7.333333333333333,"width":85.88541666666667,"height":21.09375},{"top":51.77777777777778,"left":7.666666666666666,"width":85.88541666666667,"height":21.538194444444443}]', '2026-02-10 07:49:56'),
	(15, NULL, 'frame', '/assets/frames/frame_698b016f905f30.78721750.png', 'Transparant 2', 4, '[{"top":3.2755913991912666,"left":9.15928867293366,"width":82.64593737897407,"height":17.96751441434647},{"top":24.387515740894965,"left":9.826754184960542,"width":82.64207675649929,"height":18.190001215324592},{"top":45.27695158613423,"left":9.15928867293366,"width":82.97774236697968,"height":18.00481002686193},{"top":66.38887592783793,"left":9.826754184960542,"width":82.3102768549528,"height":17.79518179411208}]', '2026-02-10 09:59:11'),
	(17, NULL, 'frame', '/assets/frames/frame_698b0c5f9b53d3.56381123.png', 'New Year', 3, '[{"top":22.11111111111111,"left":5.333333333333334,"width":89.55208333333333,"height":17.760416666666668},{"top":40.666666666666664,"left":5.333333333333334,"width":89.73958333333333,"height":17.96875},{"top":59.22222222222222,"left":5.333333333333334,"width":89.73958333333333,"height":17.74652777777778}]', '2026-02-10 10:45:51'),
	(18, NULL, 'frame', '/assets/frames/frame_698b0e7d4a4930.22873215.png', 'Summers', 4, '[{"top":6.207407212496798,"left":4.955553186487509,"width":90.39999487304739,"height":17.092592563707644},{"top":25.31851872297372,"left":4.955553186487509,"width":90.55415948952643,"height":17.315740470666473},{"top":44.31851743445384,"left":4.955553186487509,"width":90.39999487304739,"height":17.092592563707644},{"top":63.31851614593397,"left":4.955553186487509,"width":90.2208261901015,"height":17.093518263514657}]', '2026-02-10 10:54:53'),
	(34, NULL, 'filter', 'brightness(115%) contrast(90%) saturate(95%) blur(0.2px)', 'Milky White', 0, NULL, '2026-02-16 13:02:05'),
	(35, NULL, 'filter', 'brightness(110%) saturate(85%) contrast(95%) sepia(10%)', 'Clean Face', 0, NULL, '2026-02-16 13:02:05'),
	(36, NULL, 'filter', 'contrast(90%) brightness(105%) hue-rotate(-5deg) opacity(95%)', 'Soft Pink', 0, NULL, '2026-02-16 13:02:05'),
	(37, NULL, 'filter', 'saturate(140%) contrast(110%) sepia(20%) brightness(105%)', 'Summer Glow', 0, NULL, '2026-02-16 13:02:05'),
	(38, NULL, 'filter', 'brightness(110%) contrast(115%) saturate(130%) hue-rotate(-10deg)', 'Bali Sunset', 0, NULL, '2026-02-16 13:02:05'),
	(39, NULL, 'filter', 'sepia(40%) contrast(110%) brightness(110%) saturate(150%)', 'Golden Hour', 0, NULL, '2026-02-16 13:02:05'),
	(40, NULL, 'filter', 'sepia(20%) hue-rotate(50deg) saturate(110%) contrast(95%)', 'Matcha Latte', 0, NULL, '2026-02-16 13:02:05'),
	(41, NULL, 'filter', 'brightness(105%) contrast(110%) hue-rotate(300deg) saturate(90%)', 'Coquette', 0, NULL, '2026-02-16 13:02:05'),
	(42, NULL, 'filter', 'saturate(50%) contrast(110%) brightness(105%) hue-rotate(180deg)', 'Cool Tone', 0, NULL, '2026-02-16 13:02:05'),
	(43, NULL, 'filter', 'contrast(130%) saturate(120%) brightness(90%) sepia(15%)', 'Kodak Portra', 0, NULL, '2026-02-16 13:02:05'),
	(44, NULL, 'filter', 'grayscale(100%) contrast(130%) brightness(95%)', 'Deep B&W', 0, NULL, '2026-02-16 13:02:05'),
	(45, NULL, 'filter', 'hue-rotate(-20deg) contrast(90%) brightness(110%) saturate(80%)', 'Fuji Soft', 0, NULL, '2026-02-16 13:02:05'),
	(46, NULL, 'filter', 'contrast(160%) saturate(140%) brightness(110%)', 'Flash Party', 0, NULL, '2026-02-16 13:02:05'),
	(47, NULL, 'filter', 'brightness(85%) contrast(150%) saturate(110%)', 'Low Key', 0, NULL, '2026-02-16 13:02:05'),
	(48, NULL, 'filter', 'invert(10%) hue-rotate(190deg) contrast(130%)', 'Night Blue', 0, NULL, '2026-02-16 13:02:05');

-- Dumping structure for table photobooth_db.email_queue
CREATE TABLE IF NOT EXISTS `email_queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `subject` varchar(500) NOT NULL,
  `body` text NOT NULL,
  `attachments` text DEFAULT NULL COMMENT 'JSON array of attachment file paths',
  `priority` int(11) NOT NULL DEFAULT 1 COMMENT 'Higher number = higher priority',
  `status` enum('pending','processing','completed','failed') NOT NULL DEFAULT 'pending',
  `retries` int(11) NOT NULL DEFAULT 0,
  `error_message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `completed_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `priority` (`priority`),
  KEY `created_at` (`created_at`),
  KEY `idx_email_queue_status_priority` (`status`,`priority`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table photobooth_db.email_queue: ~0 rows (approximately)

-- Dumping structure for table photobooth_db.events
CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_name` varchar(255) NOT NULL,
  `event_date` date NOT NULL,
  `qris_image_path` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive','archived') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table photobooth_db.events: ~0 rows (approximately)

-- Dumping structure for table photobooth_db.packages
CREATE TABLE IF NOT EXISTS `packages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `photo_limit` int(11) NOT NULL DEFAULT 1 COMMENT 'Jumlah photostrip yang akan dicetak',
  `photo_slots` int(11) NOT NULL DEFAULT 4 COMMENT 'Jumlah foto per photostrip',
  `retake_limit` int(11) NOT NULL DEFAULT 0 COMMENT 'Jumlah retake yang diizinkan',
  `frame_limit` int(11) NOT NULL DEFAULT 1 COMMENT 'Jumlah frame yang bisa dipilih',
  `session_duration` int(11) NOT NULL DEFAULT 300 COMMENT 'Durasi sesi foto dalam detik',
  `max_save_photos` int(11) NOT NULL DEFAULT 20 COMMENT 'Maksimal foto yang bisa disimpan',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table photobooth_db.packages: ~3 rows (approximately)
INSERT INTO `packages` (`id`, `name`, `description`, `price`, `photo_limit`, `photo_slots`, `retake_limit`, `frame_limit`, `session_duration`, `max_save_photos`, `created_at`) VALUES
	(1, 'Paket Ceria', 'Paket ideal untuk bersenang-senang dengan teman-teman!', 1000.00, 2, 4, 3, 2, 300, 20, '2026-01-02 18:01:46'),
	(2, 'Paket Seru', 'Lebih banyak foto, lebih banyak kenangan!', 1000.00, 3, 4, 5, 3, 450, 30, '2026-01-02 18:01:46'),
	(3, 'Paket Pro', 'Paket lengkap untuk acara spesial!', 1000.00, 4, 6, 10, 4, 600, 50, '2026-01-02 18:01:46');

-- Dumping structure for table photobooth_db.payment_sessions
CREATE TABLE IF NOT EXISTS `payment_sessions` (
  `id` varchar(255) NOT NULL,
  `event_id` int(11) NOT NULL,
  `status` enum('pending','paid','expired') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `paid_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_id` (`event_id`),
  CONSTRAINT `payment_sessions_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table photobooth_db.payment_sessions: ~0 rows (approximately)

-- Dumping structure for table photobooth_db.photo_session_photos
CREATE TABLE IF NOT EXISTS `photo_session_photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `is_saved` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Whether user chose to save this photo',
  `taken_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `session_id` (`session_id`),
  CONSTRAINT `photo_session_photos_ibfk_1` FOREIGN KEY (`session_id`) REFERENCES `photo_sessions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=733 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table photobooth_db.photo_session_photos: ~0 rows (approximately)

-- Dumping structure for table photobooth_db.photo_sessions
CREATE TABLE IF NOT EXISTS `photo_sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` int(11) NOT NULL,
  `selected_frames` text DEFAULT NULL COMMENT 'JSON array of selected frame IDs',
  `session_status` enum('started','in_progress','completed','expired') NOT NULL DEFAULT 'started',
  `photos_taken` int(11) NOT NULL DEFAULT 0,
  `photos_saved` int(11) NOT NULL DEFAULT 0,
  `session_start_time` timestamp NULL DEFAULT NULL,
  `session_end_time` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `transaction_id` (`transaction_id`),
  KEY `idx_photo_sessions_status` (`session_status`),
  KEY `idx_photo_sessions_created_at` (`created_at`),
  CONSTRAINT `photo_sessions_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=122 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table photobooth_db.photo_sessions: ~0 rows (approximately)

-- Dumping structure for table photobooth_db.photos
CREATE TABLE IF NOT EXISTS `photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `emailed_to` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `transaction_id` (`transaction_id`),
  CONSTRAINT `photos_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table photobooth_db.photos: ~0 rows (approximately)

-- Dumping structure for table photobooth_db.photostrips
CREATE TABLE IF NOT EXISTS `photostrips` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` int(11) NOT NULL,
  `frame_id` int(11) NOT NULL,
  `layout_data` text DEFAULT NULL COMMENT 'JSON data of photo positions in photostrip',
  `decoration_data` text DEFAULT NULL COMMENT 'JSON data of stickers and decorations',
  `final_image_path` varchar(255) DEFAULT NULL COMMENT 'Path to final composed photostrip image',
  `is_printed` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `session_id` (`session_id`),
  KEY `frame_id` (`frame_id`),
  KEY `idx_photostrips_created_at` (`created_at`),
  CONSTRAINT `photostrips_ibfk_1` FOREIGN KEY (`session_id`) REFERENCES `photo_sessions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `photostrips_ibfk_2` FOREIGN KEY (`frame_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table photobooth_db.photostrips: ~0 rows (approximately)

-- Dumping structure for table photobooth_db.print_queue
CREATE TABLE IF NOT EXISTS `print_queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `photostrip_id` int(11) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `copies` int(11) NOT NULL DEFAULT 1,
  `priority` int(11) NOT NULL DEFAULT 1 COMMENT 'Higher number = higher priority',
  `status` enum('pending','processing','completed','failed') NOT NULL DEFAULT 'pending',
  `retries` int(11) NOT NULL DEFAULT 0,
  `error_message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `completed_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `photostrip_id` (`photostrip_id`),
  KEY `status` (`status`),
  KEY `priority` (`priority`),
  KEY `created_at` (`created_at`),
  KEY `idx_print_queue_status_priority` (`status`,`priority`),
  CONSTRAINT `1` FOREIGN KEY (`photostrip_id`) REFERENCES `photostrips` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table photobooth_db.print_queue: ~0 rows (approximately)

-- Dumping structure for table photobooth_db.transactions
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_status` enum('pending','success','failed') NOT NULL DEFAULT 'pending',
  `order_id` varchar(255) NOT NULL,
  `payment_token` varchar(255) DEFAULT NULL,
  `payment_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_id` (`order_id`),
  KEY `package_id` (`package_id`),
  KEY `idx_transactions_created_at` (`created_at`),
  CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=202 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table photobooth_db.transactions: ~0 rows (approximately)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
