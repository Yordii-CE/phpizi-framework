<?php

namespace Framework\Response;

class Json
{
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    function returnJson()
    {        
        echo "<pre>".json_encode($this->data)."</pre>";
    }
}
