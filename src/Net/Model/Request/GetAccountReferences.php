<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\ChainObject;
use DCorePHP\Net\Model\Response\BaseResponse;

class GetAccountReferences extends BaseRequest
{
    public function __construct(ChainObject $accountId)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'get_account_references',
            [$accountId->getId()]
        );
    }

    /**
     * @param BaseResponse $response
     * @return array
     * @throws \DCorePHP\Exception\ValidationException
     */
    public static function responseToModel(BaseResponse $response): array
    {
        $chainObjects = [];

        foreach ($response->getResult() as $reference) {
            $chainObjects[] = new ChainObject($reference);
        }

        return $chainObjects;
    }
}