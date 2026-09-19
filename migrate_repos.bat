@echo off
setlocal enabledelayedexpansion

REM Configuration
set OLD_USER=jacksparoy8-cloud
set NEW_USER=%1
set TOKEN=%2

if "%NEW_USER%"=="" (
    echo Usage: migrate_repos.bat ^<nouveau_compte^> ^<token_github^>
    exit /b 1
)

if "%TOKEN%"=="" (
    echo Usage: migrate_repos.bat ^<nouveau_compte^> ^<token_github^>
    exit /b 1
)

setlocal
set REPOS=leboncoin-com Leboncoin.com lydia lydia-authentification vinted wero-eu-es wero-eu-wallet wero-eu-wallet-v2 wero-project85

mkdir migration
cd migration

echo.
echo ============================================================
echo Migration des repos de %OLD_USER% vers %NEW_USER%
echo ============================================================
echo.

for %%R in (%REPOS%) do (
    echo.
    echo ============================================================
    echo Traitement de: %%R
    echo ============================================================
    
    echo Creation du repo sur le nouveau compte...
    curl -s -X POST ^
        -H "Authorization: token %TOKEN%" ^
        -H "Accept: application/vnd.github.v3+json" ^
        -d "{\"name\":\"%%R\",\"private\":false}" ^
        https://api.github.com/user/repos > nul
    
    if !ERRORLEVEL! equ 0 (
        echo OK - Repo cree ou existe deja
    ) else (
        echo Attention: Erreur lors de la creation du repo
    )
    
    echo.
    echo Clone du repo (mirror)...
    git clone --mirror https://github.com/%OLD_USER%/%%R.git
    
    if !ERRORLEVEL! equ 0 (
        cd %%R.git
        
        echo Push vers le nouveau compte...
        git push --mirror https://%TOKEN%@github.com/%NEW_USER%/%%R.git
        
        if !ERRORLEVEL! equ 0 (
            echo OK - %%R migre avec succes
        ) else (
            echo ERREUR - Echec du push pour %%R
        )
        
        cd ..
        rmdir /s /q %%R.git
    ) else (
        echo ERREUR - Impossible de cloner %%R
    )
)

cd ..
echo.
echo ============================================================
echo Migration terminee!
echo ============================================================
pause
