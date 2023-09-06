@echo off
setlocal

SET name=%1
SET type=MySql

IF "%name%"=="" (
  echo Error: Missing database name.
  exit /b
)

IF NOT "%~3"=="" (
  echo Error: Too many parameters, 2 given, expect 1.
  exit /b
)

IF "%~2"=="-MySql" (
    SET type=MySql
)
IF "%~2"=="-SqlSvr" (
    SET type=SqlSvr
)

IF "%~2" NEQ "-MySql" IF "%~2" NEQ "-SqlSvr" IF NOT "%~2"=="" (
    echo Error: Wrong database type, valid: [^-MySql^, -SqlSvr^].
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
echo Successfully created database.

endlocal