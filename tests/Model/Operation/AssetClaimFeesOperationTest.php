<?php

namespace DCorePHPTests\Model\Operation;

use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Operation\AssetClaimFeesOperation;
use PHPUnit\Framework\TestCase;

class AssetClaimFeesOperationTest extends TestCase
{
    /**
     * @throws ValidationException
     */
    public function testToBytes(): void
    {

        $operation = new AssetClaimFeesOperation();
        $operation
            ->setDct((new AssetAmount())->setAmount(0))
            ->setUia((new AssetAmount())->setAmount(200)->setAssetId('1.3.35'))
            ->setIssuer(new ChainObject('1.2.27'))
            ->setFee(new AssetAmount());

        $this->assertEquals(
            '230000000000000000001bc8000000000000002300000000000000000000',
            $operation->toBytes()
        );
    }
}