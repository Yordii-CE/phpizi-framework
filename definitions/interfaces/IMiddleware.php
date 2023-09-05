<?php

namespace Framework\Definitions\Interfaces;

interface IMiddleware
{
    public function handle($body);
}
