<?php

namespace DCorePHP\Model;

use DCorePHP\Exception\ValidationException;

class Account
{
    /** @var ChainObject */
    private $id;
    /** @var ChainObject */
    private $registrar;
    /** @var string */
    private $name;
    /** @var Authority */
    private $owner;
    /** @var Authority */
    private $active;
    /** @var Options */
    private $options;
    /** @var Publishing */
    private $rightsToPublish;
    /** @var string */
    private $statistics;
    /** @var int */
    private $topControlFlags;

    public function __construct()
    {
        $this->options = new Options();
        $this->owner = new Authority();
        $this->active = new Authority();
        $this->rightsToPublish = new Publishing();
    }

    public function getId(): ChainObject
    {
        return $this->id;
    }

    /**
     * @param ChainObject|string $id
     * @return Account
     * @throws ValidationException
     */
    public function setId($id): Account
    {
        if (is_string($id)) {
            $id = new ChainObject($id);
        }
        $this->id = $id;

        return $this;
    }

    /**
     * @return ChainObject
     */
    public function getRegistrar(): ChainObject
    {
        return $this->registrar;
    }

    /**
     * @param ChainObject|string $registrar
     *
     * @return Account
     * @throws ValidationException
     */
    public function setRegistrar($registrar): Account
    {
        if (is_string($registrar)) {
            $registrar = new ChainObject($registrar);
        }
        $this->registrar = $registrar;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Account
     */
    public function setName(string $name): Account
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Authority
     */
    public function getOwner(): Authority
    {
        return $this->owner;
    }

    /**
     * @param Authority $owner
     *
     * @return Account
     */
    public function setOwner(Authority $owner): Account
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Authority
     */
    public function getActive(): Authority
    {
        return $this->active;
    }

    /**
     * @param Authority $active
     *
     * @return Account
     */
    public function setActive(Authority $active): Account
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return Options
     */
    public function getOptions(): Options
    {
        return $this->options;
    }

    /**
     * @param Options $options
     *
     * @return Account
     */
    public function setOptions(Options $options): Account
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @return Publishing
     */
    public function getRightsToPublish(): Publishing
    {
        return $this->rightsToPublish;
    }

    /**
     * @param Publishing $rightsToPublish
     *
     * @return Account
     */
    public function setRightsToPublish(Publishing $rightsToPublish): Account
    {
        $this->rightsToPublish = $rightsToPublish;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatistics(): string
    {
        return $this->statistics;
    }

    /**
     * @param string $statistics
     *
     * @return Account
     */
    public function setStatistics(string $statistics): Account
    {
        $this->statistics = $statistics;

        return $this;
    }

    /**
     * @return int
     */
    public function getTopControlFlags(): int
    {
        return $this->topControlFlags;
    }

    /**
     * @param int $topControlFlags
     *
     * @return Account
     */
    public function setTopControlFlags(int $topControlFlags): Account
    {
        $this->topControlFlags = $topControlFlags;

        return $this;
    }
}
