<?php

namespace DCorePHP\Model;

class Account
{
    /** @var ChainObject */
    private $id;
    /** @var string */
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
        $this->setOptions(new Options());
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
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setId($id): Account
    {
        if (is_string($id)) {
            $id = new ChainObject($id);
        }
        $this->id = $id;

        return $this;
    }

    public function getRegistrar(): string
    {
        return $this->registrar;
    }

    public function setRegistrar(string $registrar): Account
    {
        $this->registrar = $registrar;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Account
    {
        $this->name = $name;

        return $this;
    }

    public function getOwner(): Authority
    {
        return $this->owner;
    }

    public function setOwner(Authority $owner): Account
    {
        $this->owner = $owner;

        return $this;
    }

    public function getActive(): Authority
    {
        return $this->active;
    }

    public function setActive(Authority $active): Account
    {
        $this->active = $active;

        return $this;
    }

    public function getOptions(): Options
    {
        return $this->options;
    }

    public function setOptions(Options $options): Account
    {
        $this->options = $options;

        return $this;
    }

    public function getRightsToPublish(): Publishing
    {
        return $this->rightsToPublish;
    }

    public function setRightsToPublish(Publishing $rightsToPublish): Account
    {
        $this->rightsToPublish = $rightsToPublish;

        return $this;
    }

    public function getStatistics(): string
    {
        return $this->statistics;
    }

    public function setStatistics(string $statistics): Account
    {
        $this->statistics = $statistics;

        return $this;
    }

    public function getTopControlFlags(): int
    {
        return $this->topControlFlags;
    }

    public function setTopControlFlags(int $topControlFlags): Account
    {
        $this->topControlFlags = $topControlFlags;

        return $this;
    }
}
