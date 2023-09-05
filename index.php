<?php
//GLOBALS FUNCTIONS FOR APP
require_once __DIR__ . '/global_funcs/json.php';
require_once __DIR__ . '/global_funcs/redirectToAction.php';
require_once __DIR__ . '/global_funcs/redirectToUrl.php';
require_once __DIR__ . '/global_funcs/render.php';
require_once __DIR__ . '/global_funcs/to.php';
require_once __DIR__ . '/global_funcs/view.php';


require_once 'app/main.php';

use Framework\Core\Program;

Program::start();
