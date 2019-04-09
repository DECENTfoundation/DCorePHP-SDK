<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\BlockHeader;
use DCorePHP\Net\Model\Response\BaseResponse;

class GetBlockHeader extends BaseRequest
{
    public function __construct(string $blockNum)
    {
        parent::__construct(
            'database',
            'get_block_header',
            [$blockNum]
        );
    }

    public static function responseToModel(BaseResponse $response): BlockHeader
    {
        $rawBlockHeader = $response->getResult();
        $header = new BlockHeader();

        foreach (
            [
                '[previous]' => 'previous',
                '[timestamp]' => 'timestamp',
                '[miner]' => 'miner',
                '[transaction_merkle_root]' => 'transactionMerkleRoot'
            ] as $path => $modelPath
        ) {
            $value = self::getPropertyAccessor()->getValue($rawBlockHeader, $path);
            self::getPropertyAccessor()->setValue($header, $modelPath, $value);
        }

        return $header;
    }
}