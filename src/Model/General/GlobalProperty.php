<?php

namespace DCorePHP\Model\General;

use DCorePHP\Model\ChainObject;

class GlobalProperty
{

    /** @var ChainObject */
    private $id;

    /** @var GlobalParameters */
    private $parameters;

    /** @var string */
    private $nextAvailableVoteId;

    /** @var ChainObject[] */
    private $activeMiners;

    public function __construct()
    {
        $this->parameters = new GlobalParameters();
    }

    /**
     * @return ChainObject
     */
    public function getId(): ChainObject
    {
        return $this->id;
    }

    /**
     * @param ChainObject | string $id
     * @return GlobalProperty
     */
    public function setId($id): GlobalProperty
    {
        if (is_string($id)) {
            $id = new ChainObject($id);
        }
        $this->id = $id;

        return $this;
    }

    /**
     * @return GlobalParameters
     */
    public function getParameters(): GlobalParameters
    {
        return $this->parameters;
    }

    /**
     * @param GlobalParameters $parameters
     * @return GlobalProperty
     */
    public function setParameters(GlobalParameters $parameters): GlobalProperty
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @return string
     */
    public function getNextAvailableVoteId(): string
    {
        return $this->nextAvailableVoteId;
    }

    /**
     * @param string $nextAvailableVoteId
     * @return GlobalProperty
     */
    public function setNextAvailableVoteId(string $nextAvailableVoteId): GlobalProperty
    {
        $this->nextAvailableVoteId = $nextAvailableVoteId;

        return $this;
    }

    /**
     * @return ChainObject[]
     */
    public function getActiveMiners(): array
    {
        return $this->activeMiners;
    }

    /**
     * @param ChainObject[] $activeMiners
     * @return GlobalProperty
     */
    public function setActiveMiners(array $activeMiners): GlobalProperty
    {
        $this->activeMiners = $activeMiners;

        return $this;
    }

}