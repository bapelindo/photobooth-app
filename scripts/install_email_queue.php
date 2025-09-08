<?php
/**
 * Install Email Queue Table
 * Run this once to create the email_queue table
 */

require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\Core\Database;

echo "Installing Email Queue table...\n";

try {
    $db = new Database();
    
    $sql = "
    CREATE TABLE IF NOT EXISTS email_queue (
        id INT AUTO_INCREMENT PRIMARY KEY,
        recipient_email VARCHAR(255) NOT NULL,
        recipient_name VARCHAR(255) DEFAULT NULL,
        subject VARCHAR(500) NOT NULL,
        body TEXT NOT NULL,
        attachments JSON,
        status ENUM('pending', 'processing', 'sent', 'failed') DEFAULT 'pending',
        attempts INT DEFAULT 0,
        max_attempts INT DEFAULT 3,
        error_message TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        processed_at TIMESTAMP NULL,
        INDEX idx_status (status),
        INDEX idx_created (created_at)
    )";
    
    $db->query($sql);
    $result = $db->execute();
    
    if ($result) {
        echo "✓ Email queue table created successfully!\n";
        
        // Test insert a dummy record to verify
        $db->query("
            INSERT INTO email_queue (recipient_email, subject, body) 
            VALUES ('test@example.com', 'Test Email', 'Test body')
        ");
        $db->execute();
        
        // Delete the test record
        $db->query("DELETE FROM email_queue WHERE recipient_email = 'test@example.com'");
        $db->execute();
        
        echo "✓ Table verification passed!\n";
        echo "\nEmail queue system is ready to use.\n";
        echo "You can now run: php scripts/email_worker.php\n";
        
    } else {
        echo "✗ Failed to create email queue table\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "\nPlease check your database configuration.\n";
}
?>