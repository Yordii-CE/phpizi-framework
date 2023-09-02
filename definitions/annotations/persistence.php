<?php

namespace Core\Annotations\Persistence;

#[\Attribute(\Attribute::TARGET_CLASS)]
class Database
{
    public function __construct(public string $name)
    {
    }
}

#[\Attribute(\Attribute::TARGET_CLASS)]
class Model
{
    public function __construct(public string $name)
    {
    }
}
