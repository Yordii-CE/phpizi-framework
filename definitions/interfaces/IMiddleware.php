<?php

namespace Framework\Definitions\Interfaces;

use Framework\Request\Body;

interface IMiddleware
{
    public function handle(Body $body);
}
