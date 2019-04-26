<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Model\BaseOperation;

class EmptyOperation extends BaseOperation
{

    public $OPERATION_TYPE;

    public function __construct($type)
    {
        parent::__construct();
        $this->OPERATION_TYPE = $type;
    }

    public function toBytes(): string
    {
        return '00';
    }
}