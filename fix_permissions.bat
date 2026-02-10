@echo off
REM Script untuk memperbaiki permission folder uploads di Windows 11
REM Jalankan sebagai Administrator

echo Memperbaiki permission folder uploads...
echo.

REM Set permissions untuk public/uploads dan semua subfolder
icacls "c:\apache\htdocs\photobooth-app\public\uploads" /grant Users:(OI)(CI)F /T
icacls "c:\apache\htdocs\photobooth-app\public\uploads" /grant IUSR:(OI)(CI)F /T
icacls "c:\apache\htdocs\photobooth-app\public\uploads" /grant IIS_IUSRS:(OI)(CI)F /T

echo.
echo Permission sudah diperbaiki!
echo Folder uploads sekarang bisa diakses tanpa admin.
echo.
pause
