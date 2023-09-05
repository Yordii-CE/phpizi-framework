<?php

namespace Framework\Request;

class Body
{
    protected $data = [];

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function __get($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    public function __set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function getAssocArray()
    {
        return $this->data;
    }
}
