# Queue System for Photobooth App

## Overview
The photobooth app now uses a queue system for email and print operations to prevent blocking the main application while processing these time-consuming tasks.

## Database Setup

1. **Import Queue Tables:**
   ```sql
   -- Import the SQL file
   mysql -u your_username -p your_database < queue_tables.sql
   ```

2. **Or run the SQL manually:**
   - Open `queue_tables.sql` and execute the CREATE TABLE statements

## Queue Workers

### Email Worker
- **File:** `scripts/email_worker.php`
- **Purpose:** Processes email queue in background
- **Features:**
  - Sends emails with photostrip attachments
  - Automatically cleans up temporary ZIP files
  - Retry mechanism (up to 3 attempts)
  - Auto-cleanup of old completed jobs

### Print Worker  
- **File:** `scripts/print_worker.php`
- **Purpose:** Processes print queue in background
- **Features:**
  - Executes print commands for photostrips
  - Support for multiple copies
  - Retry mechanism (up to 3 attempts)
  - Printer overload prevention with delays

## Starting the Workers

### Windows
```bat
# Run the batch file
start_workers.bat
```

### Linux/Mac
```bash
# Make executable and run
chmod +x start_workers.sh
./start_workers.sh
```

### Manual Start
```bash
# Email Worker
php scripts/email_worker.php &

# Print Worker  
php scripts/print_worker.php &
```

## Queue System Benefits

1. **Non-blocking Operations:**
   - Email and print requests return immediately
   - Users can continue using other parts of the app
   - Admin panel doesn't freeze during email sending

2. **Reliability:**
   - Retry mechanism for failed operations
   - Jobs persist across app restarts
   - Error logging and tracking

3. **Performance:**
   - Background processing
   - Controlled resource usage
   - Prevention of timeout issues

4. **Scalability:**
   - Can run multiple workers
   - Priority-based job processing
   - Easy to monitor and manage

## Queue Status

Jobs have the following statuses:
- `pending` - Waiting to be processed
- `processing` - Currently being processed
- `completed` - Successfully completed
- `failed` - Failed after maximum retries

## Monitoring

Workers output logs to console showing:
- Job processing status
- Success/failure messages
- Error details
- Cleanup activities

## Configuration

Key settings in the models:
- **Max Retries:** 3 attempts per job
- **Job Limits:** 5 email jobs, 3 print jobs processed per cycle
- **Cleanup:** Old completed jobs deleted after 7 days
- **Delays:** Configurable delays between jobs to prevent overload

## Troubleshooting

1. **Workers not processing:**
   - Check if workers are running: `ps aux | grep worker`
   - Restart workers if needed

2. **Jobs stuck in 'processing':**
   - Restart the appropriate worker
   - Check error logs

3. **Email attachments missing:**
   - Verify file paths in queue
   - Check file permissions

4. **Print jobs failing:**
   - Verify printer connectivity
   - Check print script permissions
   - Ensure Python is available in PATH