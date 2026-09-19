@echo off
setlocal enabledelayedexpansion

echo ============================================================
echo Configuration de Railway pour leboncoin
echo ============================================================
echo.

REM Clé APP_KEY valide (base64)
set APP_KEY=base64:DVA6dRRyXh6h3ZVSzSXJO+xwUXX8dAvQKlBsGyPVUvQ=

echo Configuration des variables d'environnement Railway...
echo.
echo Commandes à exécuter:
echo.
echo railway variables set APP_KEY="%APP_KEY%"
echo railway variables set APP_ENV=production
echo railway variables set APP_DEBUG=false
echo railway variables set DB_CONNECTION=sqlite
echo railway variables set DB_DATABASE=/app/database/database.sqlite
echo railway variables set SESSION_DRIVER=database
echo railway variables set CACHE_STORE=database
echo railway variables set LOG_CHANNEL=stack
echo.

echo Exécution...
railway variables set APP_KEY="%APP_KEY%"
railway variables set APP_ENV=production
railway variables set APP_DEBUG=false
railway variables set DB_CONNECTION=sqlite
railway variables set DB_DATABASE=/app/database/database.sqlite
railway variables set SESSION_DRIVER=database
railway variables set CACHE_STORE=database
railway variables set LOG_CHANNEL=stack

if !ERRORLEVEL! equ 0 (
    echo.
    echo ============================================================
    echo OK - Variables configurees!
    echo Redemarrage du service en cours...
    echo ============================================================
    railway up
) else (
    echo ERREUR: Impossible de configurer les variables
    echo Assurez-vous que railway CLI est connecte au bon projet
    pause
)

pause
