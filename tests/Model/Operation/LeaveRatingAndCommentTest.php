<?php

namespace DCorePHPTests\Model\Operation;

use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Operation\LeaveRatingAndComment;
use DCorePHPTests\DCoreSDKTest;
use PHPUnit\Framework\TestCase;

class LeaveRatingAndCommentTest extends TestCase
{
    /**
     * @throws ValidationException
     */
    public function testToBytes(): void
    {
        $operation = new LeaveRatingAndComment();
        $operation
            ->setUri('http://decent.ch?PHP&testtime=1557143278')
            ->setConsumer(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1))
            ->setRating(5)
            ->setComment('PHP Rating Comment')
            ->setFee(new AssetAmount());

        $this->assertEquals(
            '1600000000000000000028687474703a2f2f646563656e742e63683f504850267465737474696d653d313535373134333237381b1250485020526174696e6720436f6d6d656e740500000000000000',
            $operation->toBytes()
        );
    }
}