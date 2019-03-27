<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\ChainObject;
use DCorePHP\Net\Model\Response\BaseResponse;

class GetKeyReferences extends BaseRequest
{
    public function __construct($keys)
    {
        parent::__construct(
            'database',
            'get_key_references',
            [$keys]
        );
    }

    /**
     * @param BaseResponse $response
     * @return ChainObject[]
     * @throws \DCorePHP\Exception\ValidationException
     */
    public static function responseToModel(BaseResponse $response): array
    {
        $chainObjects = [];

        $result = $response->getResult();
        foreach (reset($result) as $reference) {
            $chainObjects[] = new ChainObject($reference);
        }

        return $chainObjects;
    }
}