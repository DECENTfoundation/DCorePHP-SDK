<?php


namespace DCorePHP\Model\Explorer;


class VestingPolicy
{

    /** @var int */
    private $vestingSeconds;
    /** @var string */
    private $startClaim;
    /** @var string */
    private $coinSecondsEarned;
    /** @var string */
    private $coinSecondsEarnedLastUpdate;

    /**
     * @return int
     */
    public function getVestingSeconds(): int
    {
        return $this->vestingSeconds;
    }

    /**
     * @param int $vestingSeconds
     * @return VestingPolicy
     */
    public function setVestingSeconds(int $vestingSeconds): VestingPolicy
    {
        $this->vestingSeconds = $vestingSeconds;

        return $this;
    }

    /**
     * @return string
     */
    public function getStartClaim(): string
    {
        return $this->startClaim;
    }

    /**
     * @param string $startClaim
     * @return VestingPolicy
     */
    public function setStartClaim(string $startClaim): VestingPolicy
    {
        $this->startClaim = $startClaim;

        return $this;
    }

    /**
     * @return string
     */
    public function getCoinSecondsEarned(): string
    {
        return $this->coinSecondsEarned;
    }

    /**
     * @param string $coinSecondsEarned
     * @return VestingPolicy
     */
    public function setCoinSecondsEarned(string $coinSecondsEarned): VestingPolicy
    {
        $this->coinSecondsEarned = $coinSecondsEarned;

        return $this;
    }

    /**
     * @return string
     */
    public function getCoinSecondsEarnedLastUpdate(): string
    {
        return $this->coinSecondsEarnedLastUpdate;
    }

    /**
     * @param string $coinSecondsEarnedLastUpdate
     * @return VestingPolicy
     */
    public function setCoinSecondsEarnedLastUpdate(string $coinSecondsEarnedLastUpdate): VestingPolicy
    {
        $this->coinSecondsEarnedLastUpdate = $coinSecondsEarnedLastUpdate;

        return $this;
    }

}