<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Net\Model\Response\BaseResponse;

class GetContentByURI extends GetContent
{

    public function __construct(string $uri)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'get_content',
            [$uri]
        );
    }

    public static function responseToModel(BaseResponse $response)
    {
        $result = $response->getResult();
        return $result === null ? null : self::resultToModel($result);
    }
}