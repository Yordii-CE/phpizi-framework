<?php
require_once 'core/framework.php';
//Core
Framework::requireFolder("definitions/abstracts");
Framework::requireFolder("definitions/annotations");
Framework::requireFolder("definitions/interfaces");
Framework::requireFolder("utils/reflection");
Framework::requireFolder("utils/routing");
Framework::requireFolder("utils/url");
Framework::requireFolder("cors");
Framework::requireFolder("database");
Framework::requireFolder("globals");
Framework::requireFolder("middleware");
Framework::requireFolder("response");
Framework::requireFolder("exceptions/");
Framework::requireFolder("dev/");

//App
require_once 'core/app.php';
App::requireControllers();
App::requireFolder('libs');

require_once 'core/program.php';
require_once 'app/main.php';
Program::start();
