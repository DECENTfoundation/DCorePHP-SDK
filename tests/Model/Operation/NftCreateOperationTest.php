<?php

namespace DCorePHPTests\Model\Operation;

use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\NftModel;
use DCorePHP\Model\NftOptions;
use DCorePHP\Model\Operation\NftCreateOperation;
use DCorePHPTests\DCoreSDKTest;
use DCorePHPTests\Model\NftApple;
use DCorePHPTests\Model\NftTestCase;
use Doctrine\Common\Annotations\AnnotationException;
use ReflectionException;

class NftCreateOperationTest extends NftTestCase
{
    /**
     * @throws ValidationException
     * @throws AnnotationException
     * @throws ReflectionException
     */
    public function testToBytes(): void
    {
        $options = new NftOptions();
        $options
            ->setIssuer(DCoreSDKTest::ACCOUNT_ID_1)
            ->setMaxSupply(100)
            ->setFixedMaxSupply(false)
            ->setDescription('an apple');

        $operation = new NftCreateOperation();
        $operation
            ->setSymbol('APPLE')
            ->setOptions($options)
            ->setDefinitions(NftModel::createDefinitions(NftApple::class))
            ->setTransferable(true)
            ->setFee(new AssetAmount());

        $this->assertEquals(
            '29000000000000000000054150504c451b640000000008616e206170706c65030000000000000000000100000000000000010473697a6501000000000000000000000000000000000105636f6c6f7200030000000000000002000000000000000105656174656e0100',
            $operation->toBytes()
        );
    }
}