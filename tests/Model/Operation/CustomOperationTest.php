<?php

namespace DCorePHPTests\Model\Operation;

use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Operation\CustomOperation;
use DCorePHPTests\DCoreSDKTest;
use PHPUnit\Framework\TestCase;

class CustomOperationTest extends TestCase
{
    /**
     * @throws ValidationException
     */
    public function testToBytes(): void
    {
        $operation = new CustomOperation();
        $operation
            ->setPayer(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1))
            ->setRequiredAuths([new ChainObject(DCoreSDKTest::ACCOUNT_ID_1)])
            ->setData('7b2266726f6d223a22312e322e3237222c227265636569766572735f64617461223a5b7b22746f223a22312e322e3238222c2264617461223a2230303030303030303638363536633663366632303664363537333733363136373639366536373230363137303639323037353665363536653633373237393730373436353634323034363532346634643230353034383530227d5d7d')
            ->setId(1)
            ->setFee(new AssetAmount());

        $this->assertEquals(
            '120000000000000000001b011b010096017b2266726f6d223a22312e322e3237222c227265636569766572735f64617461223a5b7b22746f223a22312e322e3238222c2264617461223a2230303030303030303638363536633663366632303664363537333733363136373639366536373230363137303639323037353665363536653633373237393730373436353634323034363532346634643230353034383530227d5d7d',
            $operation->toBytes()
        );
    }
}