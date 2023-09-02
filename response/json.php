<?php
class Json
{
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    function returnJson()
    {
        echo "<pre>";
        echo json_encode($this->data);
    }
}
