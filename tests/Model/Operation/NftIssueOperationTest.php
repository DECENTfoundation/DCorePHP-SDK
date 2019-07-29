<?php

namespace DCorePHPTests\Model\Operation;

use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\NftModel;
use DCorePHP\Model\NftOptions;
use DCorePHP\Model\Operation\NftCreateOperation;
use DCorePHP\Model\Operation\NftIssueOperation;
use DCorePHP\Model\Operation\NftUpdateOperation;
use DCorePHPTests\DCoreSDKTest;
use DCorePHPTests\Model\NftApple;
use DCorePHPTests\Model\NftTestCase;
use Doctrine\Common\Annotations\AnnotationException;
use ReflectionException;

class NftIssueOperationTest extends NftTestCase
{
    /**
     * @throws ValidationException
     * @throws ReflectionException
     */
    public function testToBytes(): void
    {
        $operation = new NftIssueOperation();
        $operation
            ->setIssuer(DCoreSDKTest::ACCOUNT_ID_1)
            ->setId(new ChainObject('1.10.0'))
            ->setTo(DCoreSDKTest::ACCOUNT_ID_1)
            ->setData((new NftApple(5, 'red', false))->values())
            ->setFee(new AssetAmount());

        $this->assertEquals(
            '2b0000000000000000001b1b0003020500000000000000050372656404000000',
            $operation->toBytes()
        );
    }
}