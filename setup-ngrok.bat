@echo off
echo Configuration de Ngrok pour la plateforme E-books
echo =============================================

echo.
echo 1. Votre serveur Laravel est demarre sur le port 8000
echo 2. Ngrok est installe mais necessite un authtoken
echo.
echo Pour obtenir votre authtoken gratuit:
echo    - Allez sur https://dashboard.ngrok.com/signup
echo    - Creez un compte gratuit
echo    - Allez sur https://dashboard.ngrok.com/get-started/your-authtoken
echo    - Copiez votre authtoken
echo.
pause

set /p authtoken="Entrez votre authtoken ngrok: "

echo.
echo Configuration de l'authtoken...
ngrok config add-authtoken %authtoken%

echo.
echo Demarrage de ngrok pour exposer votre application...
echo Votre application sera accessible publiquement via l'URL qui s'affichera ci-dessous
echo.
echo Appuyez sur Ctrl+C pour arreter ngrok
echo.
ngrok http 8000
