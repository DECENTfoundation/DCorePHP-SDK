<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Net\Model\Response\BaseResponse;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

abstract class BaseRequest
{
    /** @var string */
    protected $apiGroup;
    /** @var string */
    protected $method;
    /** @var array */
    protected $params;
    /** @var string */
    protected $jsonrpc;
    /** @var int */
    protected $id;
    /** @var int */
    private $resultNumber = 1;

    /**
     * @param string $apiGroup
     * @param string $method
     * @param array $params tuple, needs to resolve to json array after json_encode() call
     * @param string $jsonRpc
     * @param integer $id
     */
    public function __construct(
        string $apiGroup,
        string $method,
        array $params = [],
        $jsonRpc = '2.0',
        $id = 1
    )
    {
        $this->apiGroup = $apiGroup;
        $this->method = $method;
        $this->params = $params;
        $this->jsonrpc = $jsonRpc;
        $this->id = $id;
    }

    public function getApiGroup(): string
    {
        return $this->apiGroup;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function getJsonrpc(): string
    {
        return $this->jsonrpc;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param int $resultNumber
     * @return BaseRequest
     */
    public function setResultNumber(int $resultNumber): BaseRequest
    {
        $this->resultNumber = $resultNumber;
        return $this;
    }

    public function toJson(): string
    {
        return json_encode([
            'jsonrpc' => $this->jsonrpc,
            'id' => $this->id,
            'method' => 'call',
            'params' => [
                $this->resultNumber,
                $this->method,
                $this->params
            ],
        ]);
    }

    abstract public static function responseToModel(BaseResponse $response);

    protected static function getPropertyAccessor(): PropertyAccessor
    {
        return PropertyAccess::createPropertyAccessor();
    }
}