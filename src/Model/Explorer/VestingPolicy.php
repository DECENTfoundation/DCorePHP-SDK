<?php


namespace DCorePHP\Model\Explorer;


class VestingPolicy
{

    /** @var string */
    private $vestingSeconds;
    /** @var \DateTime */
    private $startClaim;
    /** @var string */
    private $coinSecondsEarned;
    /** @var \DateTime */
    private $coinSecondsEarnedLastUpdate;

    /**
     * @return string
     */
    public function getVestingSeconds(): string
    {
        return $this->vestingSeconds;
    }

    /**
     * @param string $vestingSeconds
     * @return VestingPolicy
     */
    public function setVestingSeconds(string $vestingSeconds): VestingPolicy
    {
        $this->vestingSeconds = $vestingSeconds;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStartClaim(): \DateTime
    {
        return $this->startClaim;
    }

    /**
     * @param \DateTime|string $startClaim
     * @return VestingPolicy
     * @throws \Exception
     */
    public function setStartClaim($startClaim): VestingPolicy
    {
        $this->startClaim = $startClaim instanceof \DateTime ? $startClaim : new \DateTime($startClaim);

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
     * @return \DateTime
     */
    public function getCoinSecondsEarnedLastUpdate(): \DateTime
    {
        return $this->coinSecondsEarnedLastUpdate;
    }

    /**
     * @param \DateTime|string $coinSecondsEarnedLastUpdate
     * @return VestingPolicy
     * @throws \Exception
     */
    public function setCoinSecondsEarnedLastUpdate($coinSecondsEarnedLastUpdate): VestingPolicy
    {
        $this->coinSecondsEarnedLastUpdate = $coinSecondsEarnedLastUpdate instanceof \DateTime ? $coinSecondsEarnedLastUpdate : new \DateTime($coinSecondsEarnedLastUpdate);

        return $this;
    }

}