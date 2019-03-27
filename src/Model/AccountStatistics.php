<?php

namespace DCorePHP\Model;

class AccountStatistics
{
    /** @var ChainObject */
    private $id;
    /** @var ChainObject */
    private $owner;
    /** @var ChainObject */
    private $mostRecentOp;
    /** @var string */
    private $totalOps;
    /** @var string */
    private $totalCoreInOrders;
    /** @var string */
    private $pendingFees;
    /** @var string */
    private $pendingVestedFees;

    /**
     * @return ChainObject
     */
    public function getId(): ChainObject
    {
        return $this->id;
    }

    /**
     * @param ChainObject|string $id
     * @return AccountStatistics
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setId($id): AccountStatistics
    {
        if (is_string($id)){
            $id = new ChainObject($id);
        }
        $this->id = $id;

        return $this;
    }

    /**
     * @return ChainObject
     */
    public function getOwner(): ChainObject
    {
        return $this->owner;
    }

    /**
     * @param ChainObject|string $owner
     * @return AccountStatistics
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setOwner($owner): AccountStatistics
    {
        if (is_string($owner)){
            $owner = new ChainObject($owner);
        }
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return ChainObject
     */
    public function getMostRecentOp(): ChainObject
    {
        return $this->mostRecentOp;
    }

    /**
     * @param ChainObject|string $mostRecentOp
     * @return AccountStatistics
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setMostRecentOp($mostRecentOp): AccountStatistics
    {
        if (is_string($mostRecentOp)){
            $mostRecentOp = new ChainObject($mostRecentOp);
        }
        $this->mostRecentOp = $mostRecentOp;

        return $this;
    }

    /**
     * @return string
     */
    public function getTotalOps(): string
    {
        return $this->totalOps;
    }

    /**
     * @param string $totalOps
     * @return AccountStatistics
     */
    public function setTotalOps(string $totalOps): AccountStatistics
    {
        $this->totalOps = $totalOps;

        return $this;
    }

    /**
     * @return string
     */
    public function getTotalCoreInOrders(): string
    {
        return $this->totalCoreInOrders;
    }

    /**
     * @param string $totalCoreInOrders
     * @return AccountStatistics
     */
    public function setTotalCoreInOrders(string $totalCoreInOrders): AccountStatistics
    {
        $this->totalCoreInOrders = $totalCoreInOrders;

        return $this;
    }

    /**
     * @return string
     */
    public function getPendingFees(): string
    {
        return $this->pendingFees;
    }

    /**
     * @param string $pendingFees
     * @return AccountStatistics
     */
    public function setPendingFees(string $pendingFees): AccountStatistics
    {
        $this->pendingFees = $pendingFees;

        return $this;
    }

    /**
     * @return string
     */
    public function getPendingVestedFees(): string
    {
        return $this->pendingVestedFees;
    }

    /**
     * @param string $pendingVestedFees
     * @return AccountStatistics
     */
    public function setPendingVestedFees(string $pendingVestedFees): AccountStatistics
    {
        $this->pendingVestedFees = $pendingVestedFees;

        return $this;
    }
}