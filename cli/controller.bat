@echo off
setlocal

SET name=%1
SET model=false
SET view=false

IF "%name%"=="" (
  echo Error: Missing controller name.
  exit /b
)


IF "%~2"=="-true" (
    SET model=true
)

IF "%~3"=="-true" (
    SET view=true
)

IF NOT "%~4"=="" (
  echo Error: Too many parameters, 3 given, expect 2.
  exit /b
)


IF "%~2" NEQ "-true" IF "%~2" NEQ "-false" IF NOT "%~2"=="" (
    echo Error: Wrong model option, valid: [^-true^, -false^].
    exit /b
)

IF "%~3" NEQ "-true" IF "%~3" NEQ "-false" IF NOT "%~3"=="" (
    echo Error: Wrong view option, valid: [^-true^, -false^].
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
  REM Creating controller WITH MODEL
  echo ^<?php > %name%.php
  echo. >> %name%.php
  echo namespace App\Controllers; >> %name%.php
  echo. >> %name%.php
  echo use Framework\Definitions\Abstracts\Controller; >> %name%.php
  echo use App\Models\%name% as %name%Model; >> %name%.php
  echo. >> %name%.php
  echo class %name% extends Controller >> %name%.php
  echo ^{ >> %name%.php
  echo    public $%lowerName%Model; >> %name%.php
  echo    function __construct^(%name%Model $%lowerName%Model^)>> %name%.php
  echo    { >> %name%.php
  echo      $this-^>%lowerName%Model = $%lowerName%Model; >> %name%.php
  echo    } >> %name%.php
  echo    function index^(^)>> %name%.php
  echo    { >> %name%.php
  IF "%view%"=="true" (
    echo        return view^(false, ['data'=^>$this-^>%lowerName%Model-^>getAll^(^)]^); >> %name%.php
  )

) ELSE (
  REM Creating controller
  echo ^<?php > %name%.php
  echo. >> %name%.php
  echo namespace App\Controllers; >> %name%.php    
  echo. >> %name%.php
  echo use Framework\Definitions\Abstracts\Controller; >> %name%.php
  echo. >> %name%.php
  echo class %name% extends Controller >> %name%.php
  echo ^{ >> %name%.php
  echo    function index^(^)>> %name%.php
  echo    { >> %name%.php
  IF "%view%"=="true" (
    echo        return view^(false^); >> %name%.php
  )
)

echo    } >> %name%.php
echo ^} >> %name%.php
echo Successfully created controller.

IF "%model%"=="true" (
  REM Creating model
  cd "../models"
  call %~dp0model.bat %name%
)

cd "../views"
IF "%view%"=="true" (
  REM Creating view
  call %~dp0view.bat %name%

)
endlocal