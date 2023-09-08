@echo off
setlocal

SET name=%1
SET model=false


IF "%name%"=="" (
  echo Error: Missing api controller name.
  exit /b
)

IF NOT "%~3"=="" (
  echo Error: Too many parameters, 2 given, expect 1.
  exit /b
)


IF "%~2"=="-false" (
    SET model=false
)

IF "%~2"=="-true" (
    SET model=true
)


IF "%~2" NEQ "-true" IF "%~2" NEQ "-false" IF NOT "%~2"=="" (
    echo Error: Wrong model option, valid: [^-true^, -false^].
    exit /b
)

REM Capitalize
SET name=%name%
cd "app/controllers/"
For /f %%A in ('
  Powershell -NoP -C "$Env:name.Substring(0,1).ToUpper()+$Env:name.Substring(1)"
') do set name=%%A

REM Lower
SET lowerName=%name%
For /f %%A in ('
  Powershell -NoP -C "$Env:lowerName.ToLower()"
') do set lowerName=%%A

IF "%model%"=="true" (
    REM Creating api WITH MODEL
    echo ^<?php > %name%.php
    echo. >> %name%.php
    echo namespace App\Controllers; >> %name%.php
    echo. >> %name%.php
    echo use Framework\Definitions\Abstracts\Api; >> %name%.php
    echo use Framework\Definitions\Annotations\HttpMethods\Get; >> %name%.php
    echo use App\Models\%name% as %name%Model; >> %name%.php      
    echo. >> %name%.php
    echo class %name% extends Api >> %name%.php
    echo ^{ >> %name%.php
    echo    public $%lowerName%Model; >> %name%.php
    echo    function __construct^(%name%Model $%lowerName%Model^)>> %name%.php
    echo    { >> %name%.php
    echo      $this-^>%lowerName%Model = $%lowerName%Model; >> %name%.php
    echo    } >> %name%.php
    echo    #[Get] >> %name%.php
    echo    function index^(^)>> %name%.php
    echo    { >> %name%.php
    echo        return json^($this-^>%lowerName%Model-^>getAll^(^)^); >> %name%.php
    echo    } >> %name%.php
    echo ^} >> %name%.php
    echo Successfully created api controller.
) ELSE (
    REM Creating api
    echo ^<?php > %name%.php
    echo. >> %name%.php
    echo namespace App\Controllers; >> %name%.php
    echo. >> %name%.php
    echo use Framework\Definitions\Abstracts\Api; >> %name%.php
    echo use Framework\Definitions\Annotations\HttpMethods\Get; >> %name%.php
    echo. >> %name%.php
    echo class %name% extends Api >> %name%.php
    echo ^{ >> %name%.php
    echo    #[Get] >> %name%.php
    echo    function index^(^)>> %name%.php
    echo    { >> %name%.php
    echo        return json^(['Hello world!']^); >> %name%.php
    echo    } >> %name%.php
    echo ^} >> %name%.php
    echo Successfully created api controller.
)

IF "%model%"=="true" (
  REM Creating model
  cd "../models"
  call %~dp0model.bat %name%
)

endlocal
