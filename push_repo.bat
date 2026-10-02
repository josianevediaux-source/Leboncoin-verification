@echo off
setlocal enabledelayedexpansion

set /p TOKEN="Entre ton PAT GitHub: "
set REPO_NAME=leboncoin-verification
set GITHUB_USER=jeanlucduchamps76-lang

echo.
echo ============================================================
echo Push automatique vers %GITHUB_USER%/%REPO_NAME%
echo ============================================================
echo.

cd "C:\Users\user\Desktop\leboncoin0210"

echo Configuration du remote...
git remote set-url origin https://%TOKEN%@github.com/%GITHUB_USER%/%REPO_NAME%.git

echo.
echo Push en cours...
git push -u origin main --force

if !ERRORLEVEL! equ 0 (
    echo.
    echo ============================================================
    echo OK - Code pousse vers %REPO_NAME%!
    echo Repo: https://github.com/%GITHUB_USER%/%REPO_NAME%
    echo ============================================================
) else (
    echo ERREUR - Echec du push
)

pause
