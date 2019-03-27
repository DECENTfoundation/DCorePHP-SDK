<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Net\Model\Response\BaseResponse;

class Login extends BaseRequest
{
    public function __construct()
    {
        parent::__construct(
            'login',
            'login',
            ['', '']
        );
    }

    /**
     * @param BaseResponse $response
     * @return bool
     */
    public static function responseToModel(BaseResponse $response): bool
    {
        return $response->getResult();
    }
}
