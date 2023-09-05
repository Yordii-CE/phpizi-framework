@ECHO OFF
REM BFCPEOPTIONSTART
REM Advanced BAT to EXE Converter www.BatToExeConverter.com
REM BFCPEEXE=C:\xampp\htdocs\easyflow\izi.exe
REM BFCPEICON=
REM BFCPEICONINDEX=-1
REM BFCPEEMBEDDISPLAY=0
REM BFCPEEMBEDDELETE=1
REM BFCPEADMINEXE=0
REM BFCPEINVISEXE=0
REM BFCPEVERINCLUDE=0
REM BFCPEVERVERSION=1.0.0.0
REM BFCPEVERPRODUCT=Product Name
REM BFCPEVERDESC=Product Description
REM BFCPEVERCOMPANY=Your Company
REM BFCPEVERCOPYRIGHT=Copyright Info
REM BFCPEWINDOWCENTER=1
REM BFCPEDISABLEQE=0
REM BFCPEWINDOWHEIGHT=25
REM BFCPEWINDOWWIDTH=80
REM BFCPEWTITLE=Window Title
REM BFCPEOPTIONEND
@echo off
setlocal

SET name=%1

IF "%name%"=="" (
  echo Error: Missing middleware name
  exit /b
)

REM Capitalize
SET name=%name%
cd "app\middlewares\"
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
echo Successfully created middleware