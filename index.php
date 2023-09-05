<?php
require_once 'autoload.php';

//GLOBALS FUNCTIONS FOR APP
require_once 'framework/global_funcs/json.php';
require_once 'framework/global_funcs/redirectToAction.php';
require_once 'framework/global_funcs/redirectToUrl.php';
require_once 'framework/global_funcs/render.php';
require_once 'framework/global_funcs/to.php';
require_once 'framework/global_funcs/view.php';


require_once 'app/main.php';

use Framework\Core\Program;

Program::start();
