<?php

namespace App\Repository;

trait TokenRestPassword
{

    public $code = null;

    public function __construct()
    {

        $this->code =  rand(1, 9).rand(1, 9).rand(1, 9).rand(1, 9);

    }

    public function getCode ()
    {

        if ($this->code == null) {

            $this->code =  rand(1, 9).rand(1, 9).rand(1, 9).rand(1, 9);

        }

        return $this->code;

    }

}
