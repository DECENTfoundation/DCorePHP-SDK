<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\Content\Content;
use DCorePHP\Net\Model\Response\BaseResponse;

class SearchContent extends BaseRequest
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
            'database',
            'search_content',
            [$term, $order, $user, $regionCode, $id, $type, $count]
        );
    }

    public static function responseToModel(BaseResponse $response): array
    {
        $contents = [];
        foreach ($response->getResult() as $rawContent) {
            $content = new Content();

            foreach (
                [
                    '[id]' => 'id',
                    '[author]' => 'author',
                    '[expiration]' => 'expiration',
                    '[created]' => 'created',
                    '[price][amount]' => 'price.amount',
                    '[price][asset_id]' => 'price.assetId',
                    '[status]' => 'status',
                    '[size]' => 'size',
                    '[URI]' => 'uri',
                    '[_hash]' => 'hash',
                    '[AVG_rating]' => 'AVGRating',
                    '[times_bought]' => 'timesBought',
                    '[synopsis]' => 'synopsis'
                ] as $path => $modelPath
            ) {
                $value = self::getPropertyAccessor()->getValue($rawContent, $path);
                self::getPropertyAccessor()->setValue($content, $modelPath, $value);
            }

            $contents[] = $content;

        }

        return $contents;
    }
}