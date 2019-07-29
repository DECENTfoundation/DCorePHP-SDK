<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Net\Model\Response\BaseResponse;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

abstract class BaseRequest
{
    public const API_GROUP_DATABASE = 'database_api';
    public const API_GROUP_LOGIN = 'login_api';
    public const API_GROUP_BROADCAST = 'network_broadcast_api';
    public const API_GROUP_HISTORY = 'history_api';
    public const API_GROUP_CRYPTO = 'crypto_api';
    public const API_GROUP_MESSAGING = 'messaging_api';
    private const CAST_TO_INT_FLAG = '/"CASTTOINT-([^"]+)"/';

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
    /** @var bool */
    private $withCallback;

    /**
     * @param string $apiGroup
     * @param string $method
     * @param array $params tuple, needs to resolve to json array after json_encode() call
     * @param bool $withCallback
     * @param string $jsonRpc
     * @param integer $id
     */
    public function __construct(
        string $apiGroup,
        string $method,
        array $params = [],
        $withCallback = false,
        $jsonRpc = '2.0',
        $id = 1
    )
    {
        $this->apiGroup = $apiGroup;
        $this->method = $method;
        $this->params = $params;
        $this->jsonrpc = $jsonRpc;
        $this->id = $id;
        $this->withCallback = $withCallback;
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
     * @return bool
     */
    public function isWithCallback(): bool
    {
        return $this->withCallback;
    }

    /**
     * @param bool $withCallback
     * @return BaseRequest
     */
    public function setWithCallback(bool $withCallback): BaseRequest
    {
        $this->withCallback = $withCallback;

        return $this;
    }

    public function toJson(): string
    {
        $json = json_encode([
            'jsonrpc' => $this->jsonrpc,
            'id' => $this->id,
            'method' => 'call',
            'params' => [
                $this->apiGroup,
                $this->method,
                $this->params
            ],
        ]);
        return preg_replace(self::CAST_TO_INT_FLAG, '${1}', $json);
    }

    abstract public static function responseToModel(BaseResponse $response);

    protected static function getPropertyAccessor(): PropertyAccessor
    {
        return PropertyAccess::createPropertyAccessor();
    }
}