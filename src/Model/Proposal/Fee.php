<?php


namespace DCorePHP\Model\Proposal;


class Fee
{
    /** @var int */
    private $fee;
    /** @var int */
    private $pricePerKbyte;

    /**
     * @return int
     */
    public function getFee(): int
    {
        return $this->fee;
    }

    /**
     * @param int $fee
     * @return Fee
     */
    public function setFee(int $fee): Fee
    {
        $this->fee = $fee;

        return $this;
    }

    /**
     * @return int
     */
    public function getPricePerKbyte(): int
    {
        return $this->pricePerKbyte;
    }

    /**
     * @param int $pricePerKbyte
     * @return Fee
     */
    public function setPricePerKbyte(int $pricePerKbyte): Fee
    {
        $this->pricePerKbyte = $pricePerKbyte;

        return $this;
    }

}