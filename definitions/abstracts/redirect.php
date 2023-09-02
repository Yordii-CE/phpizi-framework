<?php
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