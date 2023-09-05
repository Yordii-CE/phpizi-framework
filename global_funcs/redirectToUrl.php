<?php

use Framework\Definitions\Abstracts\Redirect;
use Framework\Response\RedirectToUrl;
use Framework\Utils\Reflection\Meta;

function redirectToUrl($url): Redirect
{
    $inContext = Meta::validateFunctionContext(["Controller", "Api"], "action");

    if ($inContext !== true)  throw new \Exception($inContext);

    return new RedirectToUrl($url);
}
