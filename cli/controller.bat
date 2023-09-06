@echo off
setlocal

SET name=%1
SET model=true
SET view=true

IF "%name%"=="" (
  echo Error: Missing controller name.
  exit /b
)


IF "%~2"=="-false" (
    SET model=false
)

IF "%~3"=="-false" (
    SET view=false
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
cd "app\controllers\"
For /f %%A in ('
  Powershell -NoP -C "$Env:name.Substring(0,1).ToUpper()+$Env:name.Substring(1)"
') do set name=%%A

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
  echo    public $model; >> %name%.php
  echo    function __construct^(%name%Model $model^)>> %name%.php
  echo    { >> %name%.php
  echo      $this-^>model = $model; >> %name%.php
  echo    } >> %name%.php
  echo    function index^(^)>> %name%.php
  echo    { >> %name%.php
  echo        return view^(false, ['data'=^>$this-^>model-^>getAll^(^)]^); >> %name%.php
  echo    } >> %name%.php
  echo ^} >> %name%.php
  echo Successfully created controller.
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
  echo        return view^(false^); >> %name%.php
  echo    } >> %name%.php
  echo ^} >> %name%.php
  echo Successfully created controller.
)

IF "%model%"=="true" (
  REM Creating model
  cd ".."
  cd "models/"
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
)

IF "%view%"=="true" (
  REM Creating view
  cd ".."
  cd "views/"
  mkdir %name%
  cd %name%
  echo %name% > Index.php
  echo ^<br^> >> index.php
  echo Data : ^<?php print_r^($data^)?^> >> index.php

  echo Successfully created view.

)
endlocal