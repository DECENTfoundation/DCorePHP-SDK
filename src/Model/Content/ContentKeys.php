<?php

namespace DCorePHP\Model\Content;

use DCorePHP\Model\Operation\KeyParts;

class ContentKeys
{

    /** @var string */
    private $key;

    /** @var KeyParts[] */
    private $keyParts;

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return ContentKeys
     */
    public function setKey(string $key): ContentKeys
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @return KeyParts[]
     */
    public function getKeyParts(): array
    {
        return $this->keyParts;
    }

    /**
     * @param KeyParts[] $keyParts
     * @return ContentKeys
     */
    public function setKeyParts(array $keyParts): ContentKeys
    {
        $this->keyParts = $keyParts;

        return $this;
    }

}