@echo off
setlocal

SET controllerName=%1

IF "%controllerName%"=="" (
  echo Error: Missing controller name
  exit /b
)

set "controllerPath=%controllerName%.php"
set "modelPath=%controllerName%.php"
set "viewPath=%controllerName%"


REM Removing controller
cd "app\controllers\"
if exist "%controllerPath%" (
    del "%controllerPath%"
    echo Successfully removed controller    
) else (
    echo Error: Controller not found.
)

REM Removing model
cd "..\models\"
if exist "%modelPath%" (
    del "%modelPath%"
    echo Successfully removed model    
) else (
    echo Error: Model not found.
)

REM Removing view
cd "..\views\"
if exist "%viewPath%" (
    rmdir /s /q "%viewPath%"
    echo Successfully removed view
) else (
    echo Error: View not found.
)

endlocal
