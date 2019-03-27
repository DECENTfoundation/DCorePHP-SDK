<?php

namespace DCorePHP\Model\General;

class FeeScheduleParameters
{

    /** @var int */
    private $operationType;

    /** @var FeeParameter */
    private $feeParameter;

    /**
     * @return int
     */
    public function getOperationType(): int
    {
        return $this->operationType;
    }

    /**
     * @param int $operationType
     * @return FeeScheduleParameters
     */
    public function setOperationType(int $operationType): FeeScheduleParameters
    {
        $this->operationType = $operationType;

        return $this;
    }

    /**
     * @return FeeParameter
     */
    public function getFeeParameter(): FeeParameter
    {
        return $this->feeParameter;
    }

    /**
     * @param FeeParameter $feeParameter
     * @return FeeScheduleParameters
     */
    public function setFeeParameter(FeeParameter $feeParameter): FeeScheduleParameters
    {
        $this->feeParameter = $feeParameter;

        return $this;
    }

}