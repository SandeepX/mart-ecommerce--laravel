<?php

namespace App\Exceptions\Custom;

use Exception;

class PermissionDeniedException extends Exception
{
    private $data = '';

    public function __construct($message,$data=null)
    {
        $this->data = $data;
        parent::__construct($message);
    }

    public function getData()
    {
        return $this->data;
    }
}
