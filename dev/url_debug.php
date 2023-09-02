<?php
//Development
function url_debug()
{
    $defaultUrl = DefaultUrl::getUrl();
    $inputUrl = InputUrl::getUrl();
    $url = InputUrl::parseUrl($inputUrl, $defaultUrl);

    $data = [$defaultUrl, $inputUrl, $url];
    echo "<pre>";
    $i = 0;
    foreach ($data as $arr) {
        if ($i == 0) echo "<h3> Default</h3><br>";
        if ($i == 1) echo "<h3> Input</h3><br>";
        if ($i == 2) echo "<h3> Using</h3><br>";
        echo "<b>  appPrefix</b>       : " . $arr->appPrefix . "<br>";
        echo "<b>  controllerPrefix</b>: " . $arr->controllerPrefix . "<br>";
        echo "<b>  controller</b>      : " . $arr->controller . "<br>";
        echo "<b>  actionPrefix</b>    : " . $arr->actionPrefix . "<br>";
        echo "<b>  action</b>          : " . $arr->action . "<br>";
        echo "<b>  params</b>          : ";
        if (empty($arr->params)) echo "";
        else print_r($arr->params);

        $i += 1;
        echo "<br><br>";
    }
}
