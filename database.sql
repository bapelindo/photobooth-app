CREATE DATABASE IF NOT EXISTS `photobooth_db`;
USE `photobooth_db`;

--
-- Struktur dari tabel `admins`
--
CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admins`
--
INSERT INTO `admins` (`username`, `password`) VALUES ('admin', '$2y$12$Oth6k7Dj.vBklsPGoeYcReNOkdwTE.9fCxpKcN5ujYck/Et9GZI3u'); -- password: password123

-- --------------------------------------------------------

--
-- Struktur dari tabel `events`
--
CREATE TABLE `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_name` varchar(255) NOT NULL,
  `event_date` date NOT NULL,
  `qris_image_path` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive','archived') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `assets`
--
CREATE TABLE `assets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NULL DEFAULT NULL,
  `type` enum('frame','sticker','filter') NOT NULL,
  `path` varchar(255) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `slot_count` int(11) NOT NULL DEFAULT 0,
  `slot_coordinates` TEXT NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `event_id` (`event_id`),
  CONSTRAINT `assets_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `packages` (DIREVISI)
--
CREATE TABLE `packages` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `price` DECIMAL(10, 2) NOT NULL,
  `photo_limit` INT(11) NOT NULL DEFAULT 6, -- Total foto di semua photostrip (misal: 6 untuk 2 frame)
  `frame_count` INT(11) NOT NULL DEFAULT 2, -- REVISI: Jumlah frame/photostrip yang didapat
  `session_time_limit` INT(11) NOT NULL DEFAULT 300, -- REVISI: Batas waktu sesi dalam detik (misal: 300 untuk 5 menit)
  `photo_shot_limit` INT(11) NOT NULL DEFAULT 20, -- REVISI: Maksimal foto yang bisa diambil
  `retake_limit` INT(11) NOT NULL DEFAULT 0, -- Tetap ada jika ingin digunakan untuk retake per foto
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Struktur dari tabel `transactions`
--
CREATE TABLE `transactions` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `package_id` INT(11) NOT NULL,
  `amount` DECIMAL(10, 2) NOT NULL,
  `payment_status` ENUM('pending', 'success', 'failed') NOT NULL DEFAULT 'pending',
  `order_id` VARCHAR(255) NOT NULL UNIQUE,
  `payment_token` VARCHAR(255) NULL,
  `payment_url` VARCHAR(255) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `package_id` (`package_id`),
  CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `photos` (DIREVISI)
--
CREATE TABLE `photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `type` ENUM('raw', 'final') NOT NULL DEFAULT 'raw', -- REVISI: Menandai jenis foto
  `emailed_to` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `transaction_id` (`transaction_id`),
  CONSTRAINT `photos_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `payment_sessions`
--
CREATE TABLE `payment_sessions` (
  `id` varchar(255) NOT NULL,
  `event_id` int(11) NOT NULL,
  `status` enum('pending','paid','expired') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `paid_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_id` (`event_id`),
  CONSTRAINT `payment_sessions_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;