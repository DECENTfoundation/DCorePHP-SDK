<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;

class ContentCancellation extends BaseOperation
{
    public const OPERATION_TYPE = 32;
    public const OPERATION_NAME = 'content_cancellation';

    /** @var ChainObject */
    private $authorId;

    /** @var string */
    private $uri;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return ChainObject
     */
    public function getId(): ChainObject
    {
        return $this->authorId;
    }

    /**
     * @param ChainObject $authorId
     * @return ContentCancellation
     */
    public function setId(ChainObject $authorId): ContentCancellation
    {
        $this->authorId = $authorId;

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
     * @return ContentCancellation
     */
    public function setUri(string $uri): ContentCancellation
    {
        $this->uri = $uri;

        return $this;
    }

}
