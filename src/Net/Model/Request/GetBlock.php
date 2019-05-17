<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\SignedBlock;
use DCorePHP\Net\Model\Response\BaseResponse;

class GetBlock extends BaseRequest
{
    public function __construct(string $blockNum)
    {
        parent::__construct(
            self::API_GROUP_DATABASE,
            'get_block',
            [$blockNum]
        );
    }

    public static function responseToModel(BaseResponse $response): SignedBlock
    {
        $rawSignedBlock = $response->getResult();
        $block = new SignedBlock();

        foreach (
            [
                '[previous]' => 'previous',
                '[timestamp]' => 'timestamp',
                '[miner]' => 'miner',
                '[transaction_merkle_root]' => 'transactionMerkleRoot',
                '[extensions]' => 'extensions',
                '[miner_signature]' => 'minerSignature',
                '[transactions]' => 'transactions'
            ] as $path => $modelPath
        ) {
            $value = self::getPropertyAccessor()->getValue($rawSignedBlock, $path);
            self::getPropertyAccessor()->setValue($block, $modelPath, $value);
        }

        return $block;
    }
}