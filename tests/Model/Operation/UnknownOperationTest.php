<?php

namespace DCorePHPTests\Model\Operation;

use DCorePHP\Model\Operation\UnknownOperation;
use DCorePHP\Model\OperationFactory;
use PHPUnit\Framework\TestCase;

class UnknownOperationTest extends TestCase
{

    /**
     * @throws \Exception
     */
    public function testHydrate(): void
    {
        $randomType = 999999;
        $rawOperation = [
            $randomType,
            [
                'fee' => [
                    'amount' => 100000,
                    'asset_id' => '1.3.0',
                ],
                'from' => '1.2.33',
                'to' => '1.2.34',
                'amount' => [
                    'amount' => 1324500000,
                    'asset_id' => '1.3.0',
                ],
                'memo' => [
                    'from' => 'DCT5ftpyM9YmQgyRFMNCBjV18zrVWhqF5ZHd7EmJhtgXGRqmXghDf',
                    'to' => 'DCT7hh2kpMHcz8y3b6jzLEJBu2Fbw3w5RRQH3f3BnVyeWVqBTsjPt',
                    'nonce' => '7264110441315408131',
                    'message' => 'b5855569ae12fe4a00c07032b953a2fe',
                ],
                'extensions' => [],
            ],
            'result' => [0, []],
            'block_num' => 1278537,
            'trx_in_block' => 0,
            'op_in_trx' => 0,
            'virtual_op' => 26355,
        ];

        $operation = OperationFactory::getOperation($randomType, $rawOperation);

        $this->assertInstanceOf(UnknownOperation::class, $operation);
    }

}