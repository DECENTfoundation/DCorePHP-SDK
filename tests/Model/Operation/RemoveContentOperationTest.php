<?php

namespace DCorePHPTests\Model\Operation;

use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\Operation\RemoveContentOperation;
use DCorePHPTests\DCoreSDKTest;
use PHPUnit\Framework\TestCase;

class RemoveContentOperationTest extends TestCase
{
    /**
     * @throws ValidationException
     */
    public function testToBytes(): void
    {
        $operation = new RemoveContentOperation();
        $operation
            ->setAuthor(DCoreSDKTest::ACCOUNT_ID_1)
            ->setUri('http://decent.ch')
            ->setFee(new AssetAmount());

        $this->assertEquals(
            '200000000000000000001b10687474703a2f2f646563656e742e6368',
            $operation->toBytes()
        );
    }
}