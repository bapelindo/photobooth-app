-- Database schema updates for enhanced photobooth workflow

-- Update packages table to include new fields
ALTER TABLE packages 
ADD COLUMN IF NOT EXISTS frame_limit INT(11) NOT NULL DEFAULT 1 COMMENT 'Jumlah frame yang bisa dipilih',
ADD COLUMN IF NOT EXISTS session_duration INT(11) NOT NULL DEFAULT 300 COMMENT 'Durasi sesi foto dalam detik',
ADD COLUMN IF NOT EXISTS max_save_photos INT(11) NOT NULL DEFAULT 20 COMMENT 'Maksimal foto yang bisa disimpan';

-- Create sessions table for photo session management
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
    CONSTRAINT photo_sessions_ibfk_1 FOREIGN KEY (transaction_id) REFERENCES transactions (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create photo_session_photos table to track individual photos in a session
CREATE TABLE IF NOT EXISTS photo_session_photos (
    id INT(11) NOT NULL AUTO_INCREMENT,
    session_id INT(11) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    is_saved BOOLEAN NOT NULL DEFAULT FALSE COMMENT 'Whether user chose to save this photo',
    taken_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY session_id (session_id),
    CONSTRAINT photo_session_photos_ibfk_1 FOREIGN KEY (session_id) REFERENCES photo_sessions (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create photostrips table for final composed photostrips
CREATE TABLE IF NOT EXISTS photostrips (
    id INT(11) NOT NULL AUTO_INCREMENT,
    session_id INT(11) NOT NULL,
    frame_id INT(11) NOT NULL,
    layout_data TEXT NULL COMMENT 'JSON data of photo positions in photostrip',
    decoration_data TEXT NULL COMMENT 'JSON data of stickers and decorations',
    final_image_path VARCHAR(255) NULL COMMENT 'Path to final composed photostrip image',
    is_printed BOOLEAN NOT NULL DEFAULT FALSE,
    print_status ENUM('none', 'queued', 'printing', 'printed', 'failed') NOT NULL DEFAULT 'none',
    email_status ENUM('none', 'queued', 'sending', 'sent', 'failed') NOT NULL DEFAULT 'none',
    email_address VARCHAR(255) NULL COMMENT 'Email address for sending photostrip',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY session_id (session_id),
    KEY frame_id (frame_id),
    CONSTRAINT photostrips_ibfk_1 FOREIGN KEY (session_id) REFERENCES photo_sessions (id) ON DELETE CASCADE,
    CONSTRAINT photostrips_ibfk_2 FOREIGN KEY (frame_id) REFERENCES assets (id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Update existing photostrips table to add new columns if they don't exist
ALTER TABLE photostrips 
ADD COLUMN IF NOT EXISTS print_status ENUM('none', 'queued', 'printing', 'printed', 'failed') NOT NULL DEFAULT 'none',
ADD COLUMN IF NOT EXISTS email_status ENUM('none', 'queued', 'sending', 'sent', 'failed') NOT NULL DEFAULT 'none',
ADD COLUMN IF NOT EXISTS email_address VARCHAR(255) NULL COMMENT 'Email address for sending photostrip';

-- Insert sample packages with enhanced data
INSERT INTO packages (name, description, price, photo_limit, photo_slots, retake_limit, frame_limit, session_duration, max_save_photos) VALUES
('Paket Ceria', 'Paket ideal untuk bersenang-senang dengan teman-teman!', 25000, 2, 4, 3, 2, 300, 20),
('Paket Seru', 'Lebih banyak foto, lebih banyak kenangan!', 35000, 3, 4, 5, 3, 450, 30),
('Paket Super', 'Paket lengkap untuk momen spesial yang tak terlupakan!', 50000, 5, 4, 10, 5, 600, 50)
ON DUPLICATE KEY UPDATE
name = VALUES(name),
description = VALUES(description),
price = VALUES(price),
frame_limit = VALUES(frame_limit),
session_duration = VALUES(session_duration),
max_save_photos = VALUES(max_save_photos);