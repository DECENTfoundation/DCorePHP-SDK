<?php

namespace DCorePHPTests\Model\Operation;

use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\NftModel;
use DCorePHP\Model\NftOptions;
use DCorePHP\Model\Operation\NftCreateOperation;
use DCorePHP\Model\Operation\NftTransferOperation;
use DCorePHP\Model\Operation\NftUpdateOperation;
use DCorePHPTests\DCoreSDKTest;
use DCorePHPTests\Model\NftApple;
use DCorePHPTests\Model\NftTestCase;
use Doctrine\Common\Annotations\AnnotationException;
use Exception;
use ReflectionException;

class NftTransferOperationTest extends NftTestCase
{
    /**
     * @throws ValidationException
     * @throws Exception
     */
    public function testToBytes(): void
    {
        $operation = new NftTransferOperation();
        $operation
            ->setFrom(DCoreSDKTest::ACCOUNT_ID_1)
            ->setTo(DCoreSDKTest::ACCOUNT_ID_2)
            ->setId('1.11.0')
            ->setFee(new AssetAmount());

        $this->assertEquals(
            '2c0000000000000000001b1c000000',
            $operation->toBytes()
        );
    }
}