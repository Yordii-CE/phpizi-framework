@echo off
setlocal enabledelayedexpansion

set controller=%1

IF "%controller%"=="" (
  echo Error: Missing controller name.
  exit /b
)
IF NOT "%~2"=="" (
  echo Error: Too many parameters, 1 given, expect 0.
  exit /b
)

cd "app/"
set "pattern=App::controllers(["
set "controller_class=    %controller%::class,"

(for /f "delims=" %%a in ('type "main.php" ^& echo.') do (
    set "line=%%a"
    echo !line!
     if "!line!"== "<?php" (
        echo use App\Controllers\%controller%;
    )
    if "!line!"== "%pattern%" (
        echo %controller_class%
    )
)) > "tempfile.php"

move /y "tempfile.php" "main.php" > nul

echo Update Main.php.

endlocal
