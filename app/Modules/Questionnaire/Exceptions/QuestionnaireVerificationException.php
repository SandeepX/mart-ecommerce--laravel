<?php

namespace App\Modules\Questionnaire\Exceptions;

use Exception;

class QuestionnaireVerificationException extends Exception
{
    private $data = '';
    protected $code = 422;

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
