<?php

namespace DCorePHP\Model\General;

class FeeSchedule
{

    /** @var FeeScheduleParameters */
    private $parameters;

    /** @var string */
    private $scale;

    /**
     * @return FeeScheduleParameters
     */
    public function getParameters(): FeeScheduleParameters
    {
        return $this->parameters;
    }

    /**
     * @param FeeScheduleParameters[] $parameters
     * @return FeeSchedule
     */
    public function setParameters(array $parameters): FeeSchedule
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @return string
     */
    public function getScale(): string
    {
        return $this->scale;
    }

    /**
     * @param string $scale
     * @return FeeSchedule
     */
    public function setScale(string $scale): FeeSchedule
    {
        $this->scale = $scale;

        return $this;
    }

}