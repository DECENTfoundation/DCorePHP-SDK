<?php


namespace DCorePHP\Model\Proposal;


class BasicFee
{
    /** @var int */
    private $basicFee;

    /**
     * @return int
     */
    public function getBasicFee(): int
    {
        return $this->basicFee;
    }

    /**
     * @param int $basicFee
     * @return BasicFee
     */
    public function setBasicFee(int $basicFee): BasicFee
    {
        $this->basicFee = $basicFee;

        return $this;
    }

}