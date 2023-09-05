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
SET type=Mysql

IF "%name%"=="" (
  echo Error: Missing database name
  exit /b
)

IF "%~2"=="-Mysql" (
    SET type=Mysql
)
IF "%~2"=="-SqlSvr" (
    SET type=SqlSvr
)

IF "%~2" NEQ "-Mysql" IF "%~2" NEQ "-SqlSvr" IF NOT "%~2"=="" (
    echo Error: Wrong database type
    exit /b
)

REM Capitalize
SET name=%name%
REM cd "app\%database_or_middleware%s\"
cd "app\databases\"
For /f %%A in ('
  Powershell -NoP -C "$Env:name.Substring(0,1).ToUpper()+$Env:name.Substring(1).ToLower()"
') do set name=%%A

REM Creating database
echo ^<?php > %name%.php
echo. >> %name%.php
echo namespace App\Databases; >> %name%.php
echo. >> %name%.php
echo use Framework\Databases\%type%Database; >> %name%.php
echo. >> %name%.php
echo class %name% extends %type%Database >> %name%.php
echo ^{ >> %name%.php
echo    function __construct^(^)>> %name%.php
echo    { >> %name%.php
echo        $this-^>host = 'localhost';>> %name%.php
echo        $this-^>db_name = '%name%';>> %name%.php
echo        $this-^>user = 'root';>> %name%.php
echo        $this-^>password = '';>> %name%.php
echo        $this-^>charset = 'utf8';>> %name%.php
echo    } >> %name%.php
echo ^} >> %name%.php
echo Successfully created database