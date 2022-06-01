<?php

class DbTableFunction extends IdeoObject
{

    private $func;

    public function __construct($func)
    {
        $this->func = $func;
    }

    public function __toString()
    {
        return (string)$this->func;
    }

}