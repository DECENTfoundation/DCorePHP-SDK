<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\ChainObject;
use DCorePHP\Net\Model\Response\BaseResponse;

class GetHistoryBuyingsByConsumer extends GetPurchase
{

    public function __construct(ChainObject $accountId)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'get_buying_history_objects_by_consumer',
            [$accountId->getId()]
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