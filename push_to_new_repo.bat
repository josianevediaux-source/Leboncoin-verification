@echo off
setlocal enabledelayedexpansion

echo ============================================================
echo Push du repo vers le nouveau compte GitHub
echo ============================================================
echo.

set /p TOKEN="Entre ton PAT GitHub (ghp_...): "

if "%TOKEN%"=="" (
    echo ERREUR: Token vide
    pause
    exit /b 1
)

echo Changement du remote...
git remote set-url origin https://%TOKEN%@github.com/jeanlucduchamps76-lang/leboncoin-com.git

if !ERRORLEVEL! neq 0 (
    echo ERREUR: Impossible de changer le remote
    pause
    exit /b 1
)

echo.
echo Push du code vers le nouveau compte...
git push -u origin main --force

if !ERRORLEVEL! equ 0 (
    echo.
    echo ============================================================
    echo OK - Code pousse avec succes!
    echo Refresh Railway et il devrait compiler correctement
    echo ============================================================
) else (
    echo ERREUR: Echec du push
)

pause
