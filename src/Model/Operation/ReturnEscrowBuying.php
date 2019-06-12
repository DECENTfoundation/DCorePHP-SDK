<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\BaseOperation;

class ReturnEscrowBuying extends BaseOperation
{
    public const OPERATION_TYPE = 43;
    public const OPERATION_NAME = 'return_escrow_buying';

    /** @var string */
    private $author;
    /** @var AssetAmount */
    private $escrow;
    /** @var string */
    private $content;

    public function __construct()
    {
        parent::__construct();
        $this->escrow = new AssetAmount();
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @param string $author
     * @return ReturnEscrowSubmission
     */
    public function setAuthor(string $author): ReturnEscrowSubmission
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return AssetAmount
     */
    public function getEscrow(): AssetAmount
    {
        return $this->escrow;
    }

    /**
     * @param AssetAmount $escrow
     * @return ReturnEscrowSubmission
     */
    public function setEscrow(AssetAmount $escrow): ReturnEscrowSubmission
    {
        $this->escrow = $escrow;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return ReturnEscrowSubmission
     */
    public function setContent(string $content): ReturnEscrowSubmission
    {
        $this->content = $content;
        return $this;
    }
}
