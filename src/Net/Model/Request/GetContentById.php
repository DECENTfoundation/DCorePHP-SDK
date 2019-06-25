<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\ChainObject;
use DCorePHP\Net\Model\Response\BaseResponse;

class GetContentById extends GetContent
{

    public function __construct(ChainObject $contentId)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'get_objects',
            [[$contentId->getId()]]
        );
    }

    public static function responseToModel(BaseResponse $response)
    {
        $contents = [];
        foreach ($response->getResult() as $rawContent) {
            $contents[] = parent::resultToModel($rawContent);
        }
        return $contents;
    }
}