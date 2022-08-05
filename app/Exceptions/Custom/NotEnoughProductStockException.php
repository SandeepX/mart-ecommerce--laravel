<?php


namespace App\Exceptions\Custom;

use Exception;

class NotEnoughProductStockException extends Exception
{
    private $data = '';

    public function __construct($message,$data)
    {
        $this->data = $data;
        parent::__construct($message);
    }

    public function getData()
    {
        return $this->data;
    }
}
