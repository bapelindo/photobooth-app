-- This migration script updates the email_queue table to the correct schema.
-- The original database.sql file is outdated.
-- The correct schema is found in scripts/install_email_queue.php.

ALTER TABLE `email_queue`
  CHANGE `email` `recipient_email` VARCHAR(255) NOT NULL,
  ADD `recipient_name` VARCHAR(255) DEFAULT NULL AFTER `recipient_email`,
  CHANGE `retries` `attempts` INT(11) NOT NULL DEFAULT 0,
  ADD `max_attempts` INT(11) NOT NULL DEFAULT 3 AFTER `attempts`,
  CHANGE `completed_at` `processed_at` TIMESTAMP NULL DEFAULT NULL,
  CHANGE `status` `status` ENUM('pending', 'processing', 'sent', 'failed') NOT NULL DEFAULT 'pending',
  DROP `priority`;
