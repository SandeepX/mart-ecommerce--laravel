<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 11/13/2020
 * Time: 12:27 PM
 */

namespace App\Exceptions\Custom;

use Exception;
class InactiveProductException extends Exception
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