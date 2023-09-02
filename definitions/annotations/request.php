<?php

namespace Core\Annotations\Request;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class Middlewares
{
    public function __construct(public array $middlewares)
    {
    }
}
