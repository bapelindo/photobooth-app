@echo off
echo ========================================================
echo Photobooth Hardware Controller - Windows Host
echo ========================================================
echo.

REM Check if Python is installed
python --version >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] Python is not installed or not in PATH.
    echo Please install Python 3.10+ and add it to PATH.
    pause
    exit /b
)

echo [INFO] Installing required Python packages...
pip install -r requirements.txt
if %errorlevel% neq 0 (
    echo [ERROR] Failed to install Python dependencies.
    pause
    exit /b
)

echo.
echo [INFO] Starting Live View Server...
echo [INFO] Please make sure your Sony camera is connected via USB/WiFi.
echo.

REM Start the live view server in the background or new window
start "Photobooth Live View Server" cmd /c "python scripts\liveview_server.py"

echo [INFO] Live View Server is running in a new window.
echo [INFO] To handle printing, please run the Print Worker in a new terminal using:
echo set DB_HOST=127.0.0.1;port=3307
echo php scripts\print_worker.php
echo.
echo Press any key to exit this installer...
pause >nul
