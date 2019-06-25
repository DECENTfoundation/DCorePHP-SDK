<?php

namespace DCorePHPTests\Model\Operation;

use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Operation\AssetIssueOperation;
use DCorePHPTests\DCoreSDKTest;
use PHPUnit\Framework\TestCase;

class AssetIssueOperationTest extends TestCase
{
    /**
     * @throws ValidationException
     * @throws \Exception
     */
    public function testToBytes(): void
    {

        $operation = new AssetIssueOperation();
        $operation
            ->setIssuer(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1))
            ->setAssetToIssue((new AssetAmount())->setAmount(200)->setAssetId(new ChainObject('1.3.41')))
            ->setIssueToAccount(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1))
            ->setMemo(null)
            ->setFee(new AssetAmount());

        $this->assertEquals(
            '040000000000000000001bc800000000000000291b0000',
            $operation->toBytes()
        );
    }
}