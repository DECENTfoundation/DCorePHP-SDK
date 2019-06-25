<?php

namespace DCorePHP\Model;

class TransactionConfirmation
{

    /** @var string */
    private $id;
    /** @var string */
    private $blockNum;
    /** @var string */
    private $trxNum;
    /** @var ProcessedTransaction */
    private $transaction;

    /**
     * TransactionConfirmation constructor.
     */
    public function __construct()
    {
        $this->transaction = new ProcessedTransaction();
    }


    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return TransactionConfirmation
     */
    public function setId(string $id): TransactionConfirmation
    {
        $this->id = $id;

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
     * @return TransactionConfirmation
     */
    public function setBlockNum(string $blockNum): TransactionConfirmation
    {
        $this->blockNum = $blockNum;

        return $this;
    }

    /**
     * @return string
     */
    public function getTrxNum(): string
    {
        return $this->trxNum;
    }

    /**
     * @param string $trxNum
     * @return TransactionConfirmation
     */
    public function setTrxNum(string $trxNum): TransactionConfirmation
    {
        $this->trxNum = $trxNum;

        return $this;
    }

    /**
     * @return ProcessedTransaction
     */
    public function getTransaction(): ProcessedTransaction
    {
        return $this->transaction;
    }

    /**
     * @param ProcessedTransaction $transaction
     * @return TransactionConfirmation
     */
    public function setTransaction(ProcessedTransaction $transaction): TransactionConfirmation
    {
        $this->transaction = $transaction;

        return $this;
    }

}