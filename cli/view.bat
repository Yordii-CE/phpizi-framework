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

REM Capitalize
SET name=%name%
cd "app/views/"
For /f %%A in ('
  Powershell -NoP -C "$Env:name.Substring(0,1).ToUpper()+$Env:name.Substring(1)"
') do set name=%%A

REM Creating view
mkdir %name%
cd %name%
echo  ^<h4^> %name%  ^</h4^> >> Index.php

echo Successfully created view.

endlocal