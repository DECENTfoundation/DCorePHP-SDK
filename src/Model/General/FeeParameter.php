<?php

namespace DCorePHP\Model\General;

use DCorePHP\Model\Asset\AssetAmount;

class FeeParameter
{

    /** @var AssetAmount */
    private $fee;

    /** @var int */
    private $pricePerKb;

    /**
     * @return AssetAmount
     */
    public function getFee(): AssetAmount
    {
        return $this->fee;
    }

    /**
     * @param AssetAmount $fee
     * @return FeeParameter
     */
    public function setFee(AssetAmount $fee): FeeParameter
    {
        $this->fee = $fee;

        return $this;
    }

    /**
     * @return int
     */
    public function getPricePerKb(): int
    {
        return $this->pricePerKb;
    }

    /**
     * @param int $pricePerKb
     * @return FeeParameter
     */
    public function setPricePerKb(int $pricePerKb): FeeParameter
    {
        $this->pricePerKb = $pricePerKb;

        return $this;
    }

}