<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Memo;
use DCorePHP\Model\NftOptions;

class NftIssueOperation extends BaseOperation
{
    public const OPERATION_TYPE = 43;

    /** @var ChainObject */
    private $issuer;
    /** @var ChainObject */
    private $id;
    /** @var ChainObject */
    private $to;
    /** @var array */
    private $data = [];
    /** @var Memo */
    private $memo = null;

    // TODO
}