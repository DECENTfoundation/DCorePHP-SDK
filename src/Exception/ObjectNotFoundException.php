<?php

namespace DCorePHP\Exception;

use DCorePHP\DCorePHPException;

class ObjectNotFoundException extends DCorePHPException
{
    public function __construct(string $message)
    {
        parent::__construct("Object does not exist: {$message}");
    }
}