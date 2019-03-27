<?php

namespace DCorePHP\Model;

class OperationHistory
{
    /** @var string */
    private $id;
    /** @var BaseOperation */
    private $operation;
    /** @var array */
    private $result;
    /** @var string */
    private $blockNum;
    /** @var string */
    private $trxInBlock;
    /** @var string */
    private $operationNumInTrx;
    /** @var string */
    private $virtualOperation;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return OperationHistory
     */
    public function setId(string $id): OperationHistory
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return BaseOperation
     */
    public function getOperation(): BaseOperation
    {
        return $this->operation;
    }

    /**
     * @param BaseOperation $operation
     * @return OperationHistory
     */
    public function setOperation(BaseOperation $operation): OperationHistory
    {
        $this->operation = $operation;
        return $this;
    }

    /**
     * @return array
     */
    public function getResult(): array
    {
        return $this->result;
    }

    /**
     * @param array $result
     * @return OperationHistory
     */
    public function setResult(array $result): OperationHistory
    {
        $this->result = $result;
        return $this;
    }

    /**
     * @return string
     */
    public function getBlockNum(): string
    {
        return $this->blockNum;
    }

    /**
     * @param string $blockNum
     * @return OperationHistory
     */
    public function setBlockNum(string $blockNum): OperationHistory
    {
        $this->blockNum = $blockNum;
        return $this;
    }

    /**
     * @return string
     */
    public function getTrxInBlock(): string
    {
        return $this->trxInBlock;
    }

    /**
     * @param string $trxInBlock
     * @return OperationHistory
     */
    public function setTrxInBlock(string $trxInBlock): OperationHistory
    {
        $this->trxInBlock = $trxInBlock;
        return $this;
    }

    /**
     * @return string
     */
    public function getOperationNumInTrx(): string
    {
        return $this->operationNumInTrx;
    }

    /**
     * @param string $operationNumInTrx
     * @return OperationHistory
     */
    public function setOperationNumInTrx(string $operationNumInTrx): OperationHistory
    {
        $this->operationNumInTrx = $operationNumInTrx;
        return $this;
    }

    /**
     * @return string
     */
    public function getVirtualOperation(): string
    {
        return $this->virtualOperation;
    }

    /**
     * @param string $virtualOperation
     * @return OperationHistory
     */
    public function setVirtualOperation(string $virtualOperation): OperationHistory
    {
        $this->virtualOperation = $virtualOperation;
        return $this;
    }
}