<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Net\Model\Response\BaseResponse;

class HeadBlockTime extends BaseRequest
{
    public function __construct()
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'head_block_time'
        );
    }

    /**
     * @param BaseResponse $response
     * @return \DateTime
     * @throws \Exception
     */
    public static function responseToModel(BaseResponse $response): \DateTime
    {
        return new \DateTime($response->getResult());
    }
}