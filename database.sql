-- ============================================
-- PHOTOBOOTH DATABASE - COMPLETE SCHEMA
-- Updated: 2025-01-20
-- Includes: Core tables, Queue system, Enhanced features
-- ============================================

CREATE DATABASE IF NOT EXISTS `photobooth_db` 
DEFAULT CHARACTER SET utf8mb4 
DEFAULT COLLATE utf8mb4_general_ci;

USE `photobooth_db`;

-- ============================================
-- ADMIN MANAGEMENT
-- ============================================

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
INSERT INTO `admins` (`username`, `password`) VALUES ('admin',  '$2y$12$Oth6k7Dj.vBklsPGoeYcReNOkdwTE.9fCxpKcN5ujYck/Et9GZI3u'); -- password: password123

-- ============================================
-- EVENT MANAGEMENT
-- ============================================

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

-- ============================================
-- ASSET MANAGEMENT
-- ============================================

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

-- ============================================
-- PACKAGE & TRANSACTION MANAGEMENT
-- ============================================

--
-- Struktur dari tabel `packages` - Enhanced with new fields
--
CREATE TABLE `packages` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `price` DECIMAL(10, 2) NOT NULL,
  `photo_limit` INT(11) NOT NULL DEFAULT 1 COMMENT 'Jumlah photostrip yang akan dicetak',
  `photo_slots` INT(11) NOT NULL DEFAULT 4 COMMENT 'Jumlah foto per photostrip',
  `retake_limit` INT(11) NOT NULL DEFAULT 0 COMMENT 'Jumlah retake yang diizinkan',
  `frame_limit` INT(11) NOT NULL DEFAULT 1 COMMENT 'Jumlah frame yang bisa dipilih',
  `session_duration` INT(11) NOT NULL DEFAULT 300 COMMENT 'Durasi sesi foto dalam detik',
  `max_save_photos` INT(11) NOT NULL DEFAULT 20 COMMENT 'Maksimal foto yang bisa disimpan',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sample packages data
--
INSERT INTO `packages` (`name`, `description`, `price`, `photo_limit`, `photo_slots`, `retake_limit`,         `frame_limit`, `session_duration`, `max_save_photos`) VALUES
('Paket Ceria', 'Paket ideal untuk bersenang-senang dengan teman-teman!', 25000, 2, 4, 3, 2, 300, 20),       
('Paket Seru', 'Lebih banyak foto, lebih banyak kenangan!', 35000, 3, 4, 5, 3, 450, 30),
('Paket Spektakuler', 'Paket lengkap untuk acara spesial!', 50000, 4, 6, 10, 4, 600, 50);

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
  CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE          CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- PHOTO SESSION MANAGEMENT
-- ============================================

--
-- Create sessions table for photo session management
--
CREATE TABLE IF NOT EXISTS photo_sessions (
    id INT(11) NOT NULL AUTO_INCREMENT,
    transaction_id INT(11) NOT NULL,
    selected_frames TEXT NULL COMMENT 'JSON array of selected frame IDs',
    session_status ENUM('started', 'in_progress', 'completed', 'expired') NOT NULL DEFAULT 'started',        
    photos_taken INT(11) NOT NULL DEFAULT 0,
    photos_saved INT(11) NOT NULL DEFAULT 0,
    session_start_time TIMESTAMP NULL DEFAULT NULL,
    session_end_time TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY transaction_id (transaction_id),
    CONSTRAINT photo_sessions_ibfk_1 FOREIGN KEY (transaction_id) REFERENCES transactions (id) ON  DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Create photo_session_photos table to track individual photos in a session
--
CREATE TABLE IF NOT EXISTS photo_session_photos (
    id INT(11) NOT NULL AUTO_INCREMENT,
    session_id INT(11) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    is_saved BOOLEAN NOT NULL DEFAULT FALSE COMMENT 'Whether user chose to save this photo',
    taken_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY session_id (session_id),
    CONSTRAINT photo_session_photos_ibfk_1 FOREIGN KEY (session_id) REFERENCES photo_sessions (id) ON         DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Create photostrips table for final composed photostrips
--
CREATE TABLE IF NOT EXISTS photostrips (
    id INT(11) NOT NULL AUTO_INCREMENT,
    session_id INT(11) NOT NULL,
    frame_id INT(11) NOT NULL,
    layout_data TEXT NULL COMMENT 'JSON data of photo positions in photostrip',
    decoration_data TEXT NULL COMMENT 'JSON data of stickers and decorations',
    final_image_path VARCHAR(255) NULL COMMENT 'Path to final composed photostrip image',
    is_printed BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY session_id (session_id),
    KEY frame_id (frame_id),
    CONSTRAINT photostrips_ibfk_1 FOREIGN KEY (session_id) REFERENCES photo_sessions (id) ON DELETE  CASCADE,
    CONSTRAINT photostrips_ibfk_2 FOREIGN KEY (frame_id) REFERENCES assets (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- QUEUE SYSTEM TABLES
-- ============================================

--
-- Email queue table for background email processing
--
CREATE TABLE IF NOT EXISTS email_queue (
    id INT(11) NOT NULL AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL,
    subject VARCHAR(500) NOT NULL,
    body TEXT NOT NULL,
    attachments TEXT NULL COMMENT 'JSON array of attachment file paths',
    priority INT(11) NOT NULL DEFAULT 1 COMMENT 'Higher number = higher priority',
    status ENUM('pending', 'processing', 'completed', 'failed') NOT NULL DEFAULT 'pending',
    retries INT(11) NOT NULL DEFAULT 0,
    error_message TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL,
    PRIMARY KEY (id),
    KEY status (status),
    KEY priority (priority),
    KEY created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Print queue table for background print processing
--
CREATE TABLE IF NOT EXISTS print_queue (
    id INT(11) NOT NULL AUTO_INCREMENT,
    photostrip_id INT(11) NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    copies INT(11) NOT NULL DEFAULT 1,
    priority INT(11) NOT NULL DEFAULT 1 COMMENT 'Higher number = higher priority',
    status ENUM('pending', 'processing', 'completed', 'failed') NOT NULL DEFAULT 'pending',
    retries INT(11) NOT NULL DEFAULT 0,
    error_message TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL,
    PRIMARY KEY (id),
    KEY photostrip_id (photostrip_id),
    KEY status (status),
    KEY priority (priority),
    KEY created_at (created_at),
    FOREIGN KEY (photostrip_id) REFERENCES photostrips (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- LEGACY TABLES (For backwards compatibility)
-- ============================================

--
-- Legacy photos table - kept for compatibility
--
CREATE TABLE `photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `emailed_to` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `transaction_id` (`transaction_id`),
  CONSTRAINT `photos_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE       
CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Legacy payment sessions table - kept for compatibility
--
CREATE TABLE `payment_sessions` (
  `id` varchar(255) NOT NULL,
  `event_id` int(11) NOT NULL,
  `status` enum('pending','paid','expired') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `paid_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_id` (`event_id`),
  CONSTRAINT `payment_sessions_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE          CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- INDEXES FOR PERFORMANCE
-- ============================================

-- Additional indexes for better query performance
CREATE INDEX idx_transactions_created_at ON transactions(created_at);
CREATE INDEX idx_photo_sessions_status ON photo_sessions(session_status);
CREATE INDEX idx_photo_sessions_created_at ON photo_sessions(created_at);
CREATE INDEX idx_photostrips_created_at ON photostrips(created_at);
CREATE INDEX idx_email_queue_status_priority ON email_queue(status, priority);
CREATE INDEX idx_print_queue_status_priority ON print_queue(status, priority);

-- ============================================
-- DATABASE SETUP COMPLETE
-- ============================================