@echo off
title STEAM Site
cd /d "%~dp0"

echo ========================================
echo   STEAM - starting the site
echo ========================================
echo.

if exist "C:\xampp\php\php.exe" (
    set "PATH=C:\xampp\php;%PATH%"
)

where php >nul 2>&1
if errorlevel 1 (
    echo PHP was not found. Install XAMPP or add PHP to PATH.
    pause
    exit /b 1
)

if not exist "vendor\autoload.php" (
    echo Installing composer packages...
    call composer install --no-interaction
)

echo Applying database migrations...
php artisan migrate --force

echo.
echo Server: http://127.0.0.1:8000
echo Press CTRL+C in this window to stop the server.
echo.

start "" "http://127.0.0.1:8000"
php artisan serve --host=127.0.0.1 --port=8000

pause
