<?php

namespace Framework\Response;

use Framework\Utils\Routing\Path;
use Framework\Utils\Url\DefaultUrl;

class View
{
    public $name;
    public $model;
    public $useTemplate;
    public static $vars = [];

    public function __construct($name, $model, $useTemplate)
    {
        $this->name = $name;
        $this->model = $model;
        $this->useTemplate = $useTemplate;
    }

    public function render()
    {
        $viewFound = $this->findView();
        $this->renderView($viewFound);
    }

    private function findView()
    {
        $viewPath = Path::getPathView($this->name);
        $viewFound = null;

        if (pathinfo($viewPath, PATHINFO_EXTENSION) == "") {
            $viewPath .= '.{php,html}';
            $sameViews = glob($viewPath, GLOB_BRACE);

            if (!empty($sameViews)) {
                if (count($sameViews) == 1) $viewFound = $sameViews[0];
                else throw new \Exception("Multiple views found: <b>" . implode(', ', $sameViews) . "</b>");
            }
        } else {
            if (file_exists($viewPath)) $viewFound = $viewPath;
        }

        if ($viewFound == null) throw new \Exception("'$this->name' View not found");

        return $viewFound;
    }

    private function renderView($viewPath)
    {
        // Data for the view        
        $PUBLIC = Path::getProjectPath() . "/" . Path::$folderPublic;
        $THIS = strtolower(explode("/", $this->name)[0]);
        $ACTION = explode('.', basename($this->name))[0]; // Obtiene el nombre de la acción
        $BASE_URL = Path::getProjectPath() . DefaultUrl::$defaultPrefix;

        require_once 'app/views/shared/globals.php';

        //Necesito las variables para RENDER()
        $vars = compact('PUBLIC', 'THIS', 'ACTION', 'BASE_URL');
        if (is_array($this->model)) {
            // Combinarlos con los valores de $this->model
            $vars = array_merge($vars, $this->model);
        }

        View::$vars = $vars;

        if ($this->useTemplate) require_once Path::getPathView('shared') . '/template.php';
        else {
            extract(View::$vars);
            require_once $viewPath;
        }
    }
}
