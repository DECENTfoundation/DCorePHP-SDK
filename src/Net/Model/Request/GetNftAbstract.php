<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\Nft;
use DCorePHP\Model\NftDataType;
use DCorePHP\Net\Model\Response\BaseResponse;

abstract class GetNftAbstract extends BaseRequest
{
    public static function responseToModel(BaseResponse $response)
    {
        return self::resultToModel($response->getResult());
    }

    protected static function resultToModel(array $result)
    {
        $nft = new Nft();

        foreach (
            [
                '[id]' => 'id',
                '[symbol]' => 'symbol',
                '[options][issuer]' => 'options.issuer',
                '[options][max_supply]' => 'options.maxSupply',
                '[options][fixed_max_supply]' => 'options.fixedMaxSupply',
                '[options][description]' => 'options.description',
                '[transferable]' => 'transferable',
                '[current_supply]' => 'currentSupply'
            ] as $path => $modelPath
        ) {
            $value = self::getPropertyAccessor()->getValue($result, $path);
            self::getPropertyAccessor()->setValue($nft, $modelPath, $value);
        }

        $definitions = [];
        foreach ($result['definitions'] as $rawDefinition) {
            $definition = new NftDataType();
            foreach (
                [
                    '[unique]' => 'unique',
                    '[modifiable]' => 'modifiable',
                    '[type]' => 'type',
                    '[name]' => 'name',
                ] as $path => $modelPath
            ) {
                $value = self::getPropertyAccessor()->getValue($rawDefinition, $path);
                self::getPropertyAccessor()->setValue($definition, $modelPath, $value);
            }
            $definitions[] = $definition;
        }
        $nft->setDefinitions($definitions);

        return $nft;
    }
}