<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;
use DCorePHP\Utils\Math;

class ContentCancellationOperation extends BaseOperation
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
     * @return ContentCancellationOperation
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setAuthor($author): ContentCancellationOperation
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
     * @return ContentCancellationOperation
     */
    public function setUri(string $uri): ContentCancellationOperation
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
