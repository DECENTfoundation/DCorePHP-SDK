<?php

namespace DCorePHP\Model\Mining;

use DCorePHP\Model\ChainObject;

class MinerId
{
    /** @var ChainObject */
    private $id;
    /** @var string */
    private $name;

    /**
     * @return ChainObject
     */
    public function getId(): ChainObject
    {
        return $this->id;
    }

    /**
     * @param ChainObject|string $id
     * @return MinerId
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setId($id): MinerId
    {
        if (is_string($id)) {
            $id = new ChainObject($id);
        }
        $this->id = $id;

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
     * @return MinerId
     */
    public function setName(string $name): MinerId
    {
        $this->name = $name;

        return $this;
    }
}