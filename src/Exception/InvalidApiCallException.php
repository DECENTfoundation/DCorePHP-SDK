<?php

namespace DCorePHP\Exception;

use DCorePHP\DCorePHPException;

class InvalidApiCallException extends DCorePHPException
{
    public function __construct(string $message)
    {
        parent::__construct("API call is not valid, please check your input data. DCore API response: {$message}");
    }
}