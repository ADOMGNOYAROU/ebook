@echo off
echo Demarrage de la plateforme E-books avec Ngrok
echo ============================================

echo.
echo 1. Demarrage du serveur Laravel...
start "Laravel Server" cmd /k "cd /d %~dp0 && php artisan serve --host=0.0.0.0 --port=8000"

echo 2. Attente du demarrage du serveur (5 secondes)...
timeout /t 5 /nobreak >nul

echo.
echo 3. Configuration et demarrage de Ngrok...
echo Si vous n'avez pas de compte ngrok, creez-en un sur https://dashboard.ngrok.com/signup
echo.
call setup-ngrok.bat
