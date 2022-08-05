<?php


namespace App\Exceptions\Custom;

use Exception;
class ProductNotEligibleToOrderException extends Exception
{

    private $data = '';

    public function __construct($message,array $data)
    {
        $this->data = $data;
        parent::__construct($message);
    }

    public function getData()
    {
        return $this->data;
    }
}
