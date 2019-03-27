<?php

namespace DCorePHP\Model\General;

class MinerRewardInput
{

    /** @var string */
    private $timeToMaintenance;

    /** @var string */
    private $fromAccumulatedFees;

    /** @var int */
    private $blockInterval;

    /**
     * @return string
     */
    public function getTimeToMaintenance(): string
    {
        return $this->timeToMaintenance;
    }

    /**
     * @param string $timeToMaintenance
     * @return MinerRewardInput
     */
    public function setTimeToMaintenance(string $timeToMaintenance): MinerRewardInput
    {
        $this->timeToMaintenance = $timeToMaintenance;

        return $this;
    }

    /**
     * @return string
     */
    public function getFromAccumulatedFees(): string
    {
        return $this->fromAccumulatedFees;
    }

    /**
     * @param string $fromAccumulatedFees
     * @return MinerRewardInput
     */
    public function setFromAccumulatedFees(string $fromAccumulatedFees): MinerRewardInput
    {
        $this->fromAccumulatedFees = $fromAccumulatedFees;

        return $this;
    }

    /**
     * @return int
     */
    public function getBlockInterval(): int
    {
        return $this->blockInterval;
    }

    /**
     * @param int $blockInterval
     * @return MinerRewardInput
     */
    public function setBlockInterval(int $blockInterval): MinerRewardInput
    {
        $this->blockInterval = $blockInterval;

        return $this;
    }

}