<?php


namespace App\Exceptions\Custom;

use Exception;
class EmailRequestLimitException extends Exception
{
    private $data = '';
    protected $code = 429;

    public function __construct($message,$data)
    {
        $this->data = $data;
        parent::__construct($message,$this->code);
    }

    public function getData()
    {
        return $this->data;
    }
}
