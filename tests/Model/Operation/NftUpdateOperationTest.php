<?php

namespace DCorePHPTests\Model\Operation;

use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\NftModel;
use DCorePHP\Model\NftOptions;
use DCorePHP\Model\Operation\NftCreateOperation;
use DCorePHP\Model\Operation\NftUpdateOperation;
use DCorePHPTests\DCoreSDKTest;
use DCorePHPTests\Model\NftApple;
use DCorePHPTests\Model\NftTestCase;
use Doctrine\Common\Annotations\AnnotationException;
use ReflectionException;

class NftUpdateOperationTest extends NftTestCase
{
    /**
     * @throws ValidationException
     */
    public function testToBytes(): void
    {
        $options = new NftOptions();
        $options
            ->setIssuer(DCoreSDKTest::ACCOUNT_ID_1)
            ->setMaxSupply(100)
            ->setFixedMaxSupply(false)
            ->setDescription('an apple');

        $operation = new NftUpdateOperation();
        $operation
            ->setIssuer(DCoreSDKTest::ACCOUNT_ID_1)
            ->setId('1.10.0')
            ->setOptions($options)
            ->setFee(new AssetAmount());

        $this->assertEquals(
            '2a0000000000000000001b001b640000000008616e206170706c6500',
            $operation->toBytes()
        );
    }
}