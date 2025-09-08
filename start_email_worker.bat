@echo off
echo Starting Email Worker for Photobooth App...
echo.
echo This will process email queue in background.
echo Press Ctrl+C to stop.
echo.

cd /d "%~dp0"
php scripts\email_worker.php

pause