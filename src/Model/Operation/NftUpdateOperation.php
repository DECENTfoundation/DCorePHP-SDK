<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\NftOptions;

class NftUpdateOperation extends BaseOperation
{
    public const OPERATION_TYPE = 42;

    /** @var ChainObject */
    private $issuer;
    /** @var ChainObject */
    private $id;
    /** @var NftOptions */
    private $options;

    // TODO
}