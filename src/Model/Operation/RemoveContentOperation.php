<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;

class RemoveContentOperation extends BaseOperation
{
    public const OPERATION_TYPE = 32;
    public const OPERATION_NAME = 'content_cancellation';

    /** @var ChainObject */
    private $author;

    /** @var string */
    private $uri;

    /**
     * @return ChainObject
     */
    public function getAuthor(): ChainObject
    {
        return $this->author;
    }

    /**
     * @param ChainObject|string $author
     *
     * @return RemoveContentOperation
     * @throws ValidationException
     */
    public function setAuthor($author): RemoveContentOperation
    {
        if (is_string($author)) {
            $author = new ChainObject($author);
        }
        $this->author = $author;

        return $this;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     *
     * @return RemoveContentOperation
     */
    public function setUri(string $uri): RemoveContentOperation
    {
        $this->uri = $uri;

        return $this;
    }

    public function toArray(): array
    {
        return [
            self::OPERATION_TYPE,
            [
                'author' => $this->getAuthor()->getId(),
                'URI' => $this->getUri(),
                'fee' => $this->getFee()->toArray()
            ],
        ];
    }

    public function toBytes(): string
    {
        return implode('', [
            $this->getTypeBytes(),
            $this->getFee()->toBytes(),
            $this->getAuthor()->toBytes(),
            dechex(strlen(unpack('H*', $this->getUri())[1]) / 2).unpack('H*', $this->getUri())[1]
        ]);
    }

}
