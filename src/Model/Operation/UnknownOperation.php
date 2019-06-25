<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Model\BaseOperation;

class UnknownOperation extends BaseOperation
{
    public const OPERATION_NAME = 'unknown_operation';

    /** @var array */
    private $raw;

    public function __construct(int $type, array $rawOperation)
    {
        parent::__construct();
        $this->setType($type);
        $this->raw = $rawOperation;
    }

    /**
     * @return array
     */
    public function getRaw(): array
    {
        return $this->raw;
    }

    /**
     * @param array $raw
     * @return UnknownOperation
     */
    public function setRaw(array $raw): UnknownOperation
    {
        $this->raw = $raw;

        return $this;
    }
}