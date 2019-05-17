<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;
use DCorePHP\Net\Model\Response\BaseResponse;

class GetRequiredFees extends BaseRequest
{
    /**
     * @param BaseOperation[] $operations
     * @param ChainObject|null $assetId
     */
    public function __construct(array $operations, ChainObject $assetId = null)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'get_required_fees',
            [
                array_map(function (BaseOperation $operation) {
                    return $operation->toArray();
                }, $operations),
                $assetId ? $assetId->getId() : '1.3.0'
            ]
        );
    }

    /**
     * @param BaseResponse $response
     * @return AssetAmount[]
     */
    public static function responseToModel(BaseResponse $response): array
    {
        $fees = [];

        foreach ($response->getResult() as $rawFee) {
            $fee = new AssetAmount();

            foreach (
                [
                    '[amount]' => 'amount',
                    '[asset_id]' => 'assetId',
                ] as $path => $modelPath
            ) {
                $value = self::getPropertyAccessor()->getValue($rawFee, $path);
                self::getPropertyAccessor()->setValue($fee, $modelPath, $value);
            }

            $fees[] = $fee;
        }

        return $fees;
    }
}
