<?php
function json($data): Json
{
    $inContext = Meta::validateFunctionContext("Controller", "action");

    if ($inContext !== true)  throw new Exception($inContext);

    return new Json($data);
}
