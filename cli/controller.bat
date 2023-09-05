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
SET model=true
SET view=true

IF "%name%"=="" (
  echo Error: Missing name
  exit /b
)


IF "%~2"=="-false" (
    SET model= false
)

IF "%~3"=="-false" (
    SET view= false
)

IF "%~2" NEQ "-true" IF "%~2" NEQ "-false" IF NOT "%~2"=="" (
    echo Error: Wrong model option
    exit /b
)

IF "%~3" NEQ "-true" IF "%~3" NEQ "-false" IF NOT "%~3"=="" (
    echo Error: Wrong view option
    exit /b
)

REM Capitalize
SET name=%name%
cd "app\controllers\"
For /f %%A in ('
  Powershell -NoP -C "$Env:name.Substring(0,1).ToUpper()+$Env:name.Substring(1)"
') do set name=%%A

IF "%view%"=="true" (
  IF "%model%"=="true" (
    REM Creating controller VIEW WITH MODEL
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
    echo Successfully created controller
  ) ELSE (
    REM Creating controller VIEW
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
    echo Successfully created controller
  )

) ELSE (
    IF "%model%"=="true" (
      REM Creating controller API  WITH MODEL
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
      echo    public $model; >> %name%.php
      echo    function __construct^(%name%Model $model^)>> %name%.php
      echo    { >> %name%.php
      echo      $this-^>model = $model; >> %name%.php
      echo    } >> %name%.php
      echo    #[Get] >> %name%.php
      echo    function index^(^)>> %name%.php
      echo    { >> %name%.php
      echo        return json^($this-^>model-^>getAll^(^)^); >> %name%.php
      echo    } >> %name%.php
      echo ^} >> %name%.php
      echo Successfully created api controller
    ) ELSE (
      REM Creating controller API
      echo ^<?php > %name%.ph
      echo. >> %name%.phpp
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
      echo Successfully created api controller
    )

)


IF "%model%"=="true" (
  REM Creating model file
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

  echo Successfully created model
)

IF "%view%"=="true" (
  REM Creating view file
  cd ".."
  cd "views/"
  mkdir %name%
  cd %name%
  echo %name% > Index.php
  echo ^<br^> >> index.php
  echo Data : ^<?php print_r^($data^)?^> >> index.php

  echo Successfully created view

)
endlocal