<?php

namespace Core\Annotations\Http;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Get
{
    public function __construct()
    {
    }
}

#[\Attribute(\Attribute::TARGET_METHOD)]
class Post
{
    public function __construct()
    {
    }
}

#[\Attribute(\Attribute::TARGET_METHOD)]
class Put
{
    public function __construct()
    {
    }
}

#[\Attribute(\Attribute::TARGET_METHOD)]
class Delete
{
    public function __construct()
    {
    }
}
