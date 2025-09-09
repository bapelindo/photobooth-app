#!/bin/bash

echo "Starting Photobooth Queue Workers..."

# Get the directory where this script is located
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )"
cd "$DIR"

# Start Email Worker in background
echo "Starting Email Worker..."
php scripts/email_worker.php &
EMAIL_PID=$!
echo "Email Worker started with PID: $EMAIL_PID"

# Start Print Worker in background
echo "Starting Print Worker..."
php scripts/print_worker.php &
PRINT_PID=$!
echo "Print Worker started with PID: $PRINT_PID"

# Create PID file to track workers
echo "$EMAIL_PID" > workers.pid
echo "$PRINT_PID" >> workers.pid

echo "Workers started successfully!"
echo "To stop workers, run: kill $EMAIL_PID $PRINT_PID"
echo "Or use: pkill -f email_worker.php && pkill -f print_worker.php"