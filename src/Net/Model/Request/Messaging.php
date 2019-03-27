<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Net\Model\Response\BaseResponse;

class Messaging extends BaseRequest
{
    public function __construct()
    {
        parent::__construct(
            'messaging',
            'messaging'
        );
    }

    /**
     * @param BaseResponse $response
     * @return int result number
     */
    public static function responseToModel(BaseResponse $response): int
    {
        return $response->getResult();
    }
}