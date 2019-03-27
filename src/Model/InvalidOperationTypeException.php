<?php

namespace DCorePHP\Model;

use DCorePHP\DCorePHPException;

class InvalidOperationTypeException extends DCorePHPException
{
    public function __construct(int $type, array $types)
    {
        parent::__construct(sprintf(
            'Operation type %d is not valid. Valid types are: %s',
            $type,
            json_encode(array_keys($types))
        ));
    }
}