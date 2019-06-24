<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Memo;
use DCorePHP\Model\NftOptions;

class NftTransferOperation extends BaseOperation
{
    public const OPERATION_TYPE = 44;

    /** @var ChainObject */
    private $from;
    /** @var ChainObject */
    private $to;
    /** @var ChainObject */
    private $id;
    /** @var Memo */
    private $memo = null;

    // TODO
}