<?php

namespace DCorePHP\Exception;

use DCorePHP\DCorePHPException;

class ObjectAlreadyFoundException extends DCorePHPException
{
    public function __construct(string $message)
    {
        parent::__construct("Object already found: {$message}");
    }
}