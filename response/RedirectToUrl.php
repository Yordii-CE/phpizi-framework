<?php

namespace Framework\Response;

use Framework\Definitions\Abstracts\Redirect;

class RedirectToUrl extends Redirect
{
    public function __construct($url)
    {
        parent::__construct($url);
    }
}
