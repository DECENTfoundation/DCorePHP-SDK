<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\ChainObject;
use DCorePHP\Net\Model\Response\BaseResponse;

class SearchBuyings extends GetPurchase
{
    public const SIZE_ASC = '+size';
    public const PRICE_ASC = '+price';
    public const CREATED_ASC = '+created';
    public const PURCHASED_ASC = '+purchased';
    public const SIZE_DESC = '-size';
    public const PRICE_DESC = '-price';
    public const CREATED_DESC = '-created';
    public const PURCHASED_DESC = '-purchased';

    public function __construct(
        ChainObject $consumer,
        string $term,
        string $from,
        string $order,
        int $limit
    ) {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'get_buying_objects_by_consumer',
            [$consumer->getId(), $order, $from, $term, $limit]
        );
    }

    public static function responseToModel(BaseResponse $response): array
    {
        $purchases = [];
        foreach ($response->getResult() as $rawPurchase) {
            $purchases[] = self::resultToModel($rawPurchase);
        }

        return $purchases;
    }
}