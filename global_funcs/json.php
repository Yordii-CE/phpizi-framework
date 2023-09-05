<?php

use Framework\Response\Json;
use Framework\Utils\Reflection\Meta;

function json($data): Json
{
    $inContext = Meta::validateFunctionContext(["Controller", "Api"], "action");

    if ($inContext !== true)  throw new \Exception($inContext);

    return new Json($data);
}
