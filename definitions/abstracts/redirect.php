<?php

namespace Framework\Definitions\Abstracts;

abstract class Redirect
{
    public $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function redirect()
    {
        header("Location: $this->url");
    }
}
