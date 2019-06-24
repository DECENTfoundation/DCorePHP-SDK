<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\NftOptions;

class NftUpdateDataOperation extends BaseOperation
{
    public const OPERATION_TYPE = 45;

    /** @var ChainObject */
    private $modifier;
    /** @var ChainObject */
    private $id;
    /** @var array */
    private $data;

    // TODO
}