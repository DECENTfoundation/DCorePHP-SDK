<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Net\Model\Response\BaseResponse;

class NetworkBroadcast extends BaseRequest
{
    public function __construct()
    {
        parent::__construct(
            'database',
            'network_broadcast'
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
