<?php

namespace App\Exceptions\Custom;

use Exception;

class StockUnavailibilityException extends Exception
{
    private $data = '';
    protected $code = 403;

    public function __construct($message,array $data)
    {
        $this->data = $data;
        parent::__construct($message,$this->code);
    }

    public function getData()
    {
        return $this->data;
    }
}
