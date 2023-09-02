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

SET fileName=%1
SET database_or_middleware=""

IF "%fileName%"=="" (
  echo Error: Missing name
  exit /b
)

IF "%~2"=="" (
    echo Error: Missing type
    exit /b
)


IF "%~2"=="-database" (
    SET database_or_middleware="database"
)
IF "%~2"=="-middleware" (
    SET database_or_middleware="middleware"
)

IF "%~2" NEQ "-middleware" IF "%~2" NEQ "-database" (
    echo Error: Wrong option
    exit /b
)


SET className=%fileName%
cd "app\%database_or_middleware%s\"
For /f %%A in ('
  Powershell -NoP -C "$Env:fileName.Substring(0,1).ToUpper()+$Env:fileName.Substring(1).ToLower()"
') do set className=%%A

IF %database_or_middleware%=="middleware" (
  REM Creating middleware
  echo ^<?php > %fileName%.php
  echo class %className% implements IMiddleware >> %fileName%.php
  echo ^{ >> %fileName%.php
  echo    function handle^($body^)>> %fileName%.php
  echo    { >> %fileName%.php
  echo        return $body; >> %fileName%.php
  echo    } >> %fileName%.php
  echo ^} >> %fileName%.php
  echo Successfully created middleware

) ELSE (
  REM Creating database
  echo ^<?php > %fileName%.php
  echo class %className% extends MysqlDatabase >> %fileName%.php
  echo ^{ >> %fileName%.php
  echo    function __construct^(^)>> %fileName%.php
  echo    { >> %fileName%.php
  echo        $this-^>host = '';>> %fileName%.php
  echo        $this-^>db_name = '%fileName%';>> %fileName%.php
  echo        $this-^>user = '';>> %fileName%.php
  echo        $this-^>password = '';>> %fileName%.php
  echo        $this-^>charset = 'utf8';>> %fileName%.php
  echo    } >> %fileName%.php
  echo ^} >> %fileName%.php
  echo Successfully created database
)

endlocal