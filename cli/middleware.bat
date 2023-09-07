@echo off
setlocal

SET name=%1

IF "%name%"=="" (
  echo Error: Missing middleware name.
  exit /b
)

IF NOT "%~2"=="" (
  echo Error: Too many parameters, 1 given, expect 0.
  exit /b
)

REM Capitalize
SET name=%name%
cd "app/middlewares/"
For /f %%A in ('
  Powershell -NoP -C "$Env:name.Substring(0,1).ToUpper()+$Env:name.Substring(1).ToLower()"
') do set name=%%A

REM Creating middleware
echo ^<?php > %name%.php
echo. >> %name%.php
echo namespace App\Middlewares; >> %name%.php
echo. >> %name%.php
echo use Framework\Definitions\Interfaces\IMiddleware; >> %name%.php
echo. >> %name%.php
echo class %name% implements IMiddleware >> %name%.php
echo ^{ >> %name%.php
echo    function handle^($body^)>> %name%.php
echo    { >> %name%.php
echo        return $body; >> %name%.php
echo    } >> %name%.php
echo ^} >> %name%.php
echo Successfully created middleware.

endlocal