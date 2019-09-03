<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Net\Model\Response\BaseResponse;

class SearchContent extends GetContent
{

    public const ORDER_NONE = '';
    public const AUTHOR_ASC = '+author';
    public const RATING_ASC = '+rating';
    public const SIZE_ASC = '+size';
    public const PRICE_ASC = '+price';
    public const CREATED_ASC = '+created';
    public const EXPIRATION_ASC = '+expiration';
    public const AUTHOR_DESC = '-author';
    public const RATING_DESC = '-rating';
    public const SIZE_DESC = '-size';
    public const PRICE_DESC = '-price';
    public const CREATED_DESC = '-created';
    public const EXPIRATION_DESC = '-expiration';

    public function __construct($term, $order, $user, $regionCode, $id, $type, $count)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'search_content',
            [$term, $order, $user, $regionCode, $id, $type, $count]
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