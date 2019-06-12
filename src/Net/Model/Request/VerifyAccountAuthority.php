<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Crypto\Address;
use DCorePHP\Net\Model\Response\BaseResponse;

class VerifyAccountAuthority extends BaseRequest
{
    public function __construct(string $nameOrId, array $keys)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'verify_account_authority',
            [$nameOrId, array_map(function (Address $key) { return $key->encode(); }, $keys)]
        );
    }

    public static function responseToModel(BaseResponse $response)
    {
        return $response->getResult();
    }
}