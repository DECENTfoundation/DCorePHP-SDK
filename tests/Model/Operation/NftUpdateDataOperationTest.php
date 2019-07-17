<?php

namespace DCorePHPTests\Model\Operation;

use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\NftModel;
use DCorePHP\Model\NftOptions;
use DCorePHP\Model\Operation\NftCreateOperation;
use DCorePHP\Model\Operation\NftUpdateDataOperation;
use DCorePHP\Model\Operation\NftUpdateOperation;
use DCorePHPTests\DCoreSDKTest;
use DCorePHPTests\Model\NftApple;
use DCorePHPTests\Model\NftTestCase;
use Doctrine\Common\Annotations\AnnotationException;
use ReflectionException;

class NftUpdateDataOperationTest extends NftTestCase
{
    /**
     * @throws AnnotationException
     * @throws ReflectionException
     * @throws ValidationException
     */
    public function testToBytes(): void
    {
        $operation = new NftUpdateDataOperation();
        $operation
            ->setModifier(DCoreSDKTest::ACCOUNT_ID_1)
            ->setId('1.10.1')
            ->setData((new NftApple(5, 'red', true))->createUpdate())
            ->setFee(new AssetAmount());

        $this->assertEquals(
            '2d0000000000000000001b010105656174656e040100',
            $operation->toBytes()
        );
    }
}