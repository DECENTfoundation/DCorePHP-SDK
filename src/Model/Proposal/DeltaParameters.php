<?php


namespace DCorePHP\Model\Proposal;


class DeltaParameters
{
    /** @var string[] */
    private $activeApprovalsToAdd;
    /** @var string[] */
    private $activeApprovalsToRemove;
    /** @var string[] */
    private $ownerApprovalsToAdd;
    /** @var string[] */
    private $ownerApprovalsToRemove;
    /** @var string[] */
    private $keyApprovalsToAdd;
    /** @var string[] */
    private $keyApprovalsToRemove;

    /**
     * @return string[]
     */
    public function getActiveApprovalsToAdd(): array
    {
        return $this->activeApprovalsToAdd;
    }

    /**
     * @param string[] $activeApprovalsToAdd
     * @return DeltaParameters
     */
    public function setActiveApprovalsToAdd(array $activeApprovalsToAdd): DeltaParameters
    {
        $this->activeApprovalsToAdd = $activeApprovalsToAdd;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getActiveApprovalsToRemove(): array
    {
        return $this->activeApprovalsToRemove;
    }

    /**
     * @param string[] $activeApprovalsToRemove
     * @return DeltaParameters
     */
    public function setActiveApprovalsToRemove(array $activeApprovalsToRemove): DeltaParameters
    {
        $this->activeApprovalsToRemove = $activeApprovalsToRemove;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getOwnerApprovalsToAdd(): array
    {
        return $this->ownerApprovalsToAdd;
    }

    /**
     * @param string[] $ownerApprovalsToAdd
     * @return DeltaParameters
     */
    public function setOwnerApprovalsToAdd(array $ownerApprovalsToAdd): DeltaParameters
    {
        $this->ownerApprovalsToAdd = $ownerApprovalsToAdd;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getOwnerApprovalsToRemove(): array
    {
        return $this->ownerApprovalsToRemove;
    }

    /**
     * @param string[] $ownerApprovalsToRemove
     * @return DeltaParameters
     */
    public function setOwnerApprovalsToRemove(array $ownerApprovalsToRemove): DeltaParameters
    {
        $this->ownerApprovalsToRemove = $ownerApprovalsToRemove;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getKeyApprovalsToAdd(): array
    {
        return $this->keyApprovalsToAdd;
    }

    /**
     * @param string[] $keyApprovalsToAdd
     * @return DeltaParameters
     */
    public function setKeyApprovalsToAdd(array $keyApprovalsToAdd): DeltaParameters
    {
        $this->keyApprovalsToAdd = $keyApprovalsToAdd;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getKeyApprovalsToRemove(): array
    {
        return $this->keyApprovalsToRemove;
    }

    /**
     * @param string[] $keyApprovalsToRemove
     * @return DeltaParameters
     */
    public function setKeyApprovalsToRemove(array $keyApprovalsToRemove): DeltaParameters
    {
        $this->keyApprovalsToRemove = $keyApprovalsToRemove;

        return $this;
    }

}