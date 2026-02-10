@echo off
REM Script lengkap untuk memperbaiki permission agar Apache/PHP bisa membaca dan menulis file
REM Jalankan sebagai Administrator

echo ========================================
echo Fix Permission Photobooth App - Lengkap
echo ========================================
echo.

echo [1/7] Memperbaiki permission folder app (kode PHP)...
icacls "c:\apache\htdocs\photobooth-app\app" /grant Everyone:(OI)(CI)RX /T
icacls "c:\apache\htdocs\photobooth-app\app" /grant IUSR:(OI)(CI)RX /T
icacls "c:\apache\htdocs\photobooth-app\app" /grant IIS_IUSRS:(OI)(CI)RX /T

echo.
echo [2/7] Memperbaiki permission folder public (akses web)...
icacls "c:\apache\htdocs\photobooth-app\public" /grant Everyone:(OI)(CI)RX /T
icacls "c:\apache\htdocs\photobooth-app\public" /grant IUSR:(OI)(CI)RX /T
icacls "c:\apache\htdocs\photobooth-app\public" /grant IIS_IUSRS:(OI)(CI)RX /T

echo.
echo [3/7] Memperbaiki permission folder assets (stiker, frame)...
icacls "c:\apache\htdocs\photobooth-app\public\assets" /grant Everyone:(OI)(CI)F /T
icacls "c:\apache\htdocs\photobooth-app\public\assets" /grant IUSR:(OI)(CI)F /T
icacls "c:\apache\htdocs\photobooth-app\public\assets" /grant IIS_IUSRS:(OI)(CI)F /T
icacls "c:\apache\htdocs\photobooth-app\public\assets" /grant Users:(OI)(CI)F /T

echo.
echo [4/7] Memperbaiki permission folder uploads (semua subfolder)...
icacls "c:\apache\htdocs\photobooth-app\public\uploads" /grant Everyone:(OI)(CI)F /T
icacls "c:\apache\htdocs\photobooth-app\public\uploads" /grant IUSR:(OI)(CI)F /T
icacls "c:\apache\htdocs\photobooth-app\public\uploads" /grant IIS_IUSRS:(OI)(CI)F /T
icacls "c:\apache\htdocs\photobooth-app\public\uploads" /grant Users:(OI)(CI)F /T

echo.
echo [5/7] Memperbaiki permission folder vendor (library)...
icacls "c:\apache\htdocs\photobooth-app\vendor" /grant Everyone:(OI)(CI)RX /T
icacls "c:\apache\htdocs\photobooth-app\vendor" /grant IUSR:(OI)(CI)RX /T
icacls "c:\apache\htdocs\photobooth-app\vendor" /grant IIS_IUSRS:(OI)(CI)RX /T

echo.
echo [6/7] Memperbaiki permission root folder...
icacls "c:\apache\htdocs\photobooth-app" /grant Everyone:(OI)(CI)RX /T
icacls "c:\apache\htdocs\photobooth-app" /grant IUSR:(OI)(CI)RX /T
icacls "c:\apache\htdocs\photobooth-app" /grant IIS_IUSRS:(OI)(CI)RX /T

echo.
echo [7/7] Memperbaiki permission folder scripts (Python scripts)...
icacls "c:\apache\htdocs\photobooth-app\scripts" /grant Everyone:(OI)(CI)RX /T
icacls "c:\apache\htdocs\photobooth-app\scripts" /grant IUSR:(OI)(CI)RX /T
icacls "c:\apache\htdocs\photobooth-app\scripts" /grant IIS_IUSRS:(OI)(CI)RX /T

echo.
echo ========================================
echo SELESAI! Semua permission sudah diperbaiki.
echo ========================================
echo.
echo Summary:
echo - app/        : Read/Execute untuk Everyone, IUSR, IIS_IUSRS
echo - public/     : Read/Execute untuk Everyone, IUSR, IIS_IUSRS
echo - assets/     : Full Access untuk Everyone, IUSR, IIS_IUSRS, Users
echo - uploads/    : Full Access untuk Everyone, IUSR, IIS_IUSRS, Users
echo   - captures/ : Full Access (untuk foto dari kamera DSLR)
echo   - photo/    : Full Access (untuk foto dari kamera web)
echo   - session_photos/    : Full Access
echo   - final_photostrips/ : Full Access
echo   - temp/             : Full Access
echo - vendor/     : Read/Execute untuk Everyone, IUSR, IIS_IUSRS
echo - scripts/    : Read/Execute untuk Everyone, IUSR, IIS_IUSRS
echo.
echo Fitur yang sekarang berjalan TANPA admin:
echo [v] Upload stiker (admin panel)
echo [v] Upload frame (admin panel)
echo [v] Lihat stiker (user panel - broken image fixed)
echo [v] Simpan foto session (user panel)
echo [v] Capture kamera DSLR (user panel)
echo [v] Capture kamera web (user panel)
echo [v] Generate photostrip (user panel)
echo [v] Download/print photostrip (user panel)
echo.
pause
