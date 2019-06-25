<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\ChainObject;
use DCorePHP\Model\PubKey;
use DCorePHP\Net\Model\Response\BaseResponse;

class RestoreEncryptionKey extends BaseRequest
{

    public function __construct(PubKey $privateElGamal, ChainObject $purchaseId)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'restore_encryption_key',
            [$privateElGamal->toArray(), $purchaseId->getId()]
        );
    }

    public static function responseToModel(BaseResponse $response): string
    {
        return $response->getResult();
    }
}