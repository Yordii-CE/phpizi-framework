<?php

namespace Framework\Definitions\Exceptions;

class ControllerException extends \Exception
{
    protected $message;

    public function __construct($message)
    {
        $this->message = $message;
    }
}
