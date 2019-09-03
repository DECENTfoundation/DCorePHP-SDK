<?php

namespace DCorePHP\Model;

use DCorePHP\Utils\Math;
use DCorePHP\Utils\VarInt;

class CoAuthors
{
    /**
     * @var array
     * [[ChainObject, Int], ...]
     */
    private $authors = [];

    /**
     * @return array
     */
    public function getAuthors(): array
    {
        return $this->authors;
    }

    /**
     * @param array $authors
     *
     * @return CoAuthors
     */
    public function setAuthors(array $authors): CoAuthors
    {
        $this->authors = $authors;

        return $this;
    }

    public function toArray(): array
    {
        $result = [];
        /**
         * @var ChainObject $id
         * @var int $weight
         */
        foreach ($this->getAuthors() as [$id, $weight]) {
            $result[] = [$id->getId(), $weight];
        }
        return $result;
    }

    public function toBytes(): string
    {
        $result = VarInt::encodeDecToHex(sizeof($this->getAuthors()));
        /**
         * @var ChainObject $id
         * @var int $weight
         */
        foreach ($this->getAuthors() as [$id, $weight]) {
            $result .= $id->toBytes() . Math::getInt32Reversed($weight);
        }
        return $result;
    }
}