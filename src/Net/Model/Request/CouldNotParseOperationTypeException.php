<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\DCorePHPException;

class CouldNotParseOperationTypeException extends DCorePHPException
{
    public function __construct(array $rawOperation)
    {
        parent::__construct('Could not parse operation type and operation from operation history: %s', [
            json_encode($rawOperation)
        ]);
    }
}