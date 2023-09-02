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
SET model=true
SET view=true

IF "%fileName%"=="" (
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


SET className=%fileName%
cd "app\controllers\"
For /f %%A in ('
  Powershell -NoP -C "$Env:fileName.Substring(0,1).ToUpper()+$Env:fileName.Substring(1).ToLower()"
') do set className=%%A


IF "%view%"=="true" (
  REM Creating controller VIEW
  echo ^<?php > %fileName%.controller.php
  echo class %className%Controller extends Controller >> %fileName%.controller.php
  echo ^{ >> %fileName%.controller.php
  echo    function index^(^) : View>> %fileName%.controller.php
  echo    { >> %fileName%.controller.php
  echo        return view^(^); >> %fileName%.controller.php
  echo    } >> %fileName%.controller.php
  echo ^} >> %fileName%.controller.php
  echo Successfully created controller

) ELSE (
  REM Creating controller API
  echo ^<?php > %fileName%.controller.php
  echo use Core\Annotations\http\Get; >> %fileName%.controller.php
  echo class %className%Controller extends Api >> %fileName%.controller.php
  echo ^{ >> %fileName%.controller.php
  echo    #[Get] >> %fileName%.controller.php
  echo    function getAll^(^) : Json>> %fileName%.controller.php
  echo    { >> %fileName%.controller.php
  echo        return json^('Hello world!'^); >> %fileName%.controller.php
  echo    } >> %fileName%.controller.php
  echo ^} >> %fileName%.controller.php
  echo Successfully created api controller

)


IF "%model%"=="true" (
  REM Creating model file
  cd ".."
  cd "models/"
  echo ^<?php > %fileName%.model.php
  echo class %className%Model >> %fileName%.model.php
  echo ^{ >> %fileName%.model.php
  echo    function getData^(^)>> %fileName%.model.php
  echo    { >> %fileName%.model.php
  echo        return [1,2,3]; >> %fileName%.model.php
  echo    } >> %fileName%.model.php
  echo ^} >> %fileName%.model.php

  echo Successfully created model
)

IF "%view%"=="true" (
  REM Creating view file
  cd ".."
  cd "views/"
  mkdir %fileName%
  cd %fileName%
  echo %fileName% > index.php

  echo Successfully created view

)
endlocal