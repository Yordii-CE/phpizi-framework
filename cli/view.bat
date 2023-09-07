@echo off
setlocal

SET name=%1

IF "%name%"=="" (
  echo Error: Missing view name.
  exit /b
)

IF NOT "%~2"=="" (
  echo Error: Too many parameters, 1 given, expect 0.
  exit /b
)
REM Creating view
cd "app/views/"
mkdir %name%
cd %name%
echo  ^<h4^> %name%  ^</h4^> >> Index.php

echo Successfully created view.

endlocal