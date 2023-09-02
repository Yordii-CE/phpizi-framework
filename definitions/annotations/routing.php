<?php

namespace Core\Annotations\Routing;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class Prefix
{
    public function __construct(public string $prefix)
    {
    }
}
