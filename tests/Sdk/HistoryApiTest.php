<?php

namespace DCorePHPTests\Sdk;

use DCorePHP\Exception\InvalidApiCallException;
use DCorePHP\Exception\ObjectNotFoundException;
use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\BalanceChange;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\InvalidOperationTypeException;
use DCorePHP\Model\Operation\AddOrUpdateContentOperation;
use DCorePHP\Model\Operation\NftCreateOperation;
use DCorePHP\Model\OperationHistory;
use DCorePHP\Model\OperationHistoryComposed;
use DCorePHP\Net\Model\Request\CouldNotParseOperationTypeException;
use DCorePHPTests\DCoreSDKTest;
use WebSocket\BadOpcodeException;

class HistoryApiTest extends DCoreSDKTest
{

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        // Used to create an operation
        ContentApiTest::testAdd();
    }

    /**
     * @throws InvalidApiCallException
     * @throws ValidationException
     * @throws BadOpcodeException
     * @throws ObjectNotFoundException
     */
    public function testGetOperation(): void
    {
        $change = self::$sdk->getHistoryApi()->getOperation(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), new ChainObject('1.7.0'));

        /** @var AddOrUpdateContentOperation $operation */
        $operation = $change->getOperation()->getOperation();
        $this->assertEquals('1.2.27', $operation->getAuthor()->getId());
        $this->assertEquals('1', $operation->getSize());
    }

    /**
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     * @throws ValidationException
     */
    public function testListOperations(): void
    {
        $operations = self::$sdk->getHistoryApi()->listOperations(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1));

        foreach ($operations as $operation) {
            $this->assertInstanceOf(OperationHistory::class, $operation);
        }
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ValidationException
     * @throws ObjectNotFoundException
     */
    public function testFindAllTransfersComposed(): void
    {
        $operations = self::$sdk->getHistoryApi()->findAllTransfersComposed(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), '1.7.0', '1.7.0', 2);

        if (empty($operations)) {
            $this->assertTrue(true);
        } else {
            foreach ($operations as $operation) {
                $this->assertInstanceOf(OperationHistoryComposed::class, $operation);
            }
        }
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ValidationException
     */
    public function testListOperationsRelative(): void
    {
        $operations = self::$sdk->getHistoryApi()->listOperationsRelative(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1));

        foreach ($operations as $operation) {
            $this->assertInstanceOf(OperationHistory::class, $operation);
        }
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ValidationException
     */
    public function testFindAllOperations(): void
    {
        $balances = self::$sdk->getHistoryApi()->findAllOperations(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1));

        foreach ($balances as $balance) {
            $this->assertInstanceOf(BalanceChange::class, $balance);
        }
    }

    /**
     */
    public function testIsConfirmed(): void
    {
        $confirmed = self::$sdk->getHistoryApi()->isConfirmed(new ChainObject('1.7.0'));
        $this->assertTrue($confirmed);
    }
}
