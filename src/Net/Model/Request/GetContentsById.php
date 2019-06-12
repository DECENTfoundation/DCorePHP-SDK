<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\ChainObject;
use DCorePHP\Net\Model\Response\BaseResponse;

class GetContentsById extends GetContent
{
    public function __construct(array $contentIds)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'get_objects',
            [array_map(function (ChainObject $contentId) {return $contentId->getId();}, $contentIds)]
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