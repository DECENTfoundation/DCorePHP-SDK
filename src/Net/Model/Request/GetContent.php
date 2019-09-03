<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\CoAuthors;
use DCorePHP\Model\Content\Content;
use DCorePHP\Model\Content\PricePerRegion;
use DCorePHP\Net\Model\Response\BaseResponse;

abstract class GetContent extends BaseRequest
{

    public static function responseToModel(BaseResponse $response)
    {
        return self::resultToModel($response->getResult());
    }

    protected static function resultToModel(array $result)
    {

        $contentObject = new Content();
        foreach (
            [
                '[id]' => 'id',
                '[author]' => 'author',
                '[expiration]' => 'expiration',
                '[created]' => 'created',
                '[size]' => 'size',
                '[URI]' => 'uri',
                '[quorum]' => 'quorum',
                '[key_parts]' => 'key_parts',
                '[_hash]' => 'hash',
                '[last_proof]' => 'lastProof',
                '[is_blocked]' => 'isBlocked',
                '[AVG_rating]' => 'AVGRating',
                '[num_of_ratings]' => 'numOfRatings',
                '[times_bought]' => 'timesBought',
                '[publishing_fee_escrow][amount]' => 'publishingFeeEscrow.amount',
                '[publishing_fee_escrow][asset_id]' => 'publishingFeeEscrow.assetId',
                '[synopsis]' => 'synopsis',
                '[seeder_price]' => 'seederPrice'
            ] as $path => $modelPath
        ) {
            $value = self::getPropertyAccessor()->getValue($result, $path);
            self::getPropertyAccessor()->setValue($contentObject, $modelPath, $value);
        }

        $coAuthors = [];
        foreach ($result['co_authors'] as [$id, $weight]) {
            $coAuthors[] = [new ChainObject($id), $weight];
        }
        $contentObject->setCoauthors((new CoAuthors())->setAuthors($coAuthors));

        $pricePerRegion = new PricePerRegion();
        $prices = [];
        foreach ($result['price'] as $rawMapPrice) {
            foreach ($rawMapPrice as $rawPrice) {
                $assetAmount = (new AssetAmount())->setAmount($rawPrice[1]['amount'])->setAssetId($rawPrice[1]['asset_id']);
                $prices[$rawPrice[0]] = $assetAmount;
            }
        }
        $contentObject->setPrice($pricePerRegion->setPrices($prices));

        return $contentObject;
    }

}