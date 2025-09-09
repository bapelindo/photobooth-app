@echo off
echo Starting Photobooth Queue Workers...

REM Start Email Worker
echo Starting Email Worker...
start "Email Worker" cmd /k "cd /d %~dp0 && php scripts\email_worker.php"

REM Wait a moment
timeout /t 2 /nobreak > nul

REM Start Print Worker  
echo Starting Print Worker...
start "Print Worker" cmd /k "cd /d %~dp0 && php scripts\print_worker.php"

echo Workers started in separate windows.
echo Close this window when you want to stop the workers.
pause