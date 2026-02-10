@echo off
REM Script untuk memperbaiki permission folder assets di Windows 11
REM Jalankan sebagai Administrator

echo Memperbaiki permission folder assets (frames dan stickers)...
echo.

REM Set permissions untuk public/assets dan semua subfolder
icacls "c:\apache\htdocs\photobooth-app\public\assets" /grant Users:(OI)(CI)RX /T
icacls "c:\apache\htdocs\photobooth-app\public\assets" /grant IUSR:(OI)(CI)RX /T
icacls "c:\apache\htdocs\photobooth-app\public\assets" /grant IIS_IUSRS:(OI)(CI)RX /T

REM Also grant read access to Everyone for web access
icacls "c:\apache\htdocs\photobooth-app\public\assets" /grant Everyone:(OI)(CI)RX /T

echo.
echo Permission folder assets sudah diperbaiki!
echo Stiker dan frame sekarang bisa diakses user biasa.
echo.
pause
