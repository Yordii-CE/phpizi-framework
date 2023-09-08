@echo off
setlocal

SET name=%1

IF "%name%"=="" (
  echo Error: Missing model name.
  exit /b
)

IF NOT "%~2"=="" (
  echo Error: Too many parameters, 1 given, expect 0.
  exit /b
)

REM Capitalize
SET name=%name%
cd "app/models/"
For /f %%A in ('
  Powershell -NoP -C "$Env:name.Substring(0,1).ToUpper()+$Env:name.Substring(1)"
') do set name=%%A

REM Creating model

echo ^<?php > %name%.php
echo. >> %name%.php
echo namespace App\Models; >> %name%.php
echo. >> %name%.php
echo class %name% >> %name%.php
echo ^{ >> %name%.php
echo    function getAll^(^)>> %name%.php
echo    { >> %name%.php
echo        return [1,2,3]; >> %name%.php
echo    } >> %name%.php
echo ^} >> %name%.php

echo Successfully created model.

endlocal