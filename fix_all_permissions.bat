@echo off
REM Script lengkap untuk memperbaiki semua permission photobooth-app
REM Jalankan sebagai Administrator

echo ========================================
echo Memperbaiki Permission Photobooth App
echo ========================================
echo.

echo [1/4] Memperbaiki permission folder app (kode PHP)...
icacls "c:\apache\htdocs\photobooth-app\app" /grant IUSR:(OI)(CI)RX /T
icacls "c:\apache\htdocs\photobooth-app\app" /grant IIS_IUSRS:(OI)(CI)RX /T
icacls "c:\apache\htdocs\photobooth-app\app" /grant Everyone:(OI)(CI)RX /T

echo.
echo [2/4] Memperbaiki permission folder public (assets)...
icacls "c:\apache\htdocs\photobooth-app\public" /grant IUSR:(OI)(CI)RX /T
icacls "c:\apache\htdocs\photobooth-app\public" /grant IIS_IUSRS:(OI)(CI)RX /T
icacls "c:\apache\htdocs\photobooth-app\public" /grant Everyone:(OI)(CI)RX /T

echo.
echo [3/4] Memperbaiki permission folder uploads (write access)...
icacls "c:\apache\htdocs\photobooth-app\public\uploads" /grant IUSR:(OI)(CI)F /T
icacls "c:\apache\htdocs\photobooth-app\public\uploads" /grant IIS_IUSRS:(OI)(CI)F /T
icacls "c:\apache\htdocs\photobooth-app\public\uploads" /grant Users:(OI)(CI)F /T
icacls "c:\apache\htdocs\photobooth-app\public\uploads" /grant Everyone:(OI)(CI)F /T

echo.
echo [4/4] Memperbaiki permission root folder...
icacls "c:\apache\htdocs\photobooth-app" /grant IUSR:(OI)(CI)RX /T
icacls "c:\apache\htdocs\photobooth-app" /grant IIS_IUSRS:(OI)(CI)RX /T
icacls "c:\apache\htdocs\photobooth-app" /grant Everyone:(OI)(CI)RX /T

echo.
echo ========================================
echo SELESAI! Semua permission sudah diperbaiki.
echo ========================================
echo.
echo IUSR dan IIS_IUSRS sekarang memiliki:
echo - RX (Read/Execute) untuk folder app, public, root
echo - F (Full) untuk folder uploads
echo.
echo Stiker dan frame sekarang seharusnya bisa ditampilkan.
echo File upload juga bisa disimpan tanpa admin.
echo.
pause
