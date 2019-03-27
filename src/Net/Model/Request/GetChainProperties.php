<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\General\ChainProperty;
use DCorePHP\Net\Model\Response\BaseResponse;

class GetChainProperties extends BaseRequest
{

    public function __construct()
    {
        parent::__construct(
            'database',
            'get_chain_properties'
        );
    }

    public static function responseToModel(BaseResponse $response): ChainProperty
    {
        $rawChainProperty = $response->getResult();
        $chainProperty = new ChainProperty();

        foreach (
            [
                '[id]' => 'id',
                '[chain_id]' => 'chainId',
                '[immutable_parameters][min_miner_count]' => 'parameters.minMinerCount',
                '[immutable_parameters][num_special_accounts]' => 'parameters.specialAccounts',
                '[immutable_parameters][num_special_assets]' => 'parameters.specialAssets',
            ] as $path => $modelPath
        ) {
            $value = self::getPropertyAccessor()->getValue($rawChainProperty, $path);
            self::getPropertyAccessor()->setValue($chainProperty, $modelPath, $value);
        }

        return $chainProperty;
    }
}