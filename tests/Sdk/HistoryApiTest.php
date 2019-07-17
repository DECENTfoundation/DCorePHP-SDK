<?php

namespace DCorePHPTests\Sdk;

use DCorePHP\Exception\InvalidApiCallException;
use DCorePHP\Exception\ObjectNotFoundException;
use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\BalanceChange;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\InvalidOperationTypeException;
use DCorePHP\Model\OperationHistory;
use DCorePHP\Model\OperationHistoryComposed;
use DCorePHP\Net\Model\Request\CouldNotParseOperationTypeException;
use DCorePHPTests\DCoreSDKTest;
use WebSocket\BadOpcodeException;

class HistoryApiTest extends DCoreSDKTest
{
    /**
     * @throws InvalidApiCallException
     * @throws ValidationException
     * @throws InvalidOperationTypeException
     * @throws BadOpcodeException
     */
    public function testGetOperation(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
//        $operation = self::$sdk->getHistoryApi()->getOperation(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), new ChainObject('1.7.919365'));
//        $this->assertEquals('342', $operation->getOperation()->getBlockNum());
//        $this->assertEquals('119', $operation->getOperation()->getVirtualOperation());
//        $this->assertEquals(0, $operation->getFee()->getAmount());
    }

    /**
     * @throws InvalidApiCallException
     * @throws InvalidOperationTypeException
     * @throws CouldNotParseOperationTypeException
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
     * @throws CouldNotParseOperationTypeException
     * @throws InvalidApiCallException
     * @throws InvalidOperationTypeException
     * @throws ValidationException
     * @throws ObjectNotFoundException
     */
    public function testFindAllTransfersComposed(): void
    {
        $operations = self::$sdk->getHistoryApi()->findAllTransfersComposed(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), '1.7.0', '1.7.0', 2);

        foreach ($operations as $operation) {
            $this->assertInstanceOf(OperationHistoryComposed::class, $operation);
        }
    }

    /**
     * @throws ValidationException
     * @throws InvalidOperationTypeException
     */
    public function testListOperationsRelative(): void
    {
        $operations = self::$sdk->getHistoryApi()->listOperationsRelative(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), 0, 10);

        foreach ($operations as $operation) {
            $this->assertInstanceOf(OperationHistory::class, $operation);
        }
    }

    /**
     * @throws ValidationException
     * @throws InvalidOperationTypeException
     */
    public function testFindAllOperations(): void
    {
        $balances = self::$sdk->getHistoryApi()->findAllOperations(new ChainObject('1.2.27'), [], null, '0', '0', '2', 3);

        foreach ($balances as $balance) {
            $this->assertInstanceOf(BalanceChange::class, $balance);
        }
    }

    /**
     * @throws \DCorePHP\Exception\InvalidApiCallException
     * @throws \DCorePHP\Exception\ValidationException
     * @throws \WebSocket\BadOpcodeException
     */
    public function testIsConfirmed(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
//        $this->assertTrue(self::$sdk->getHistoryApi()->isConfirmed(
//            new ChainObject('1.7.919362')
//        ));
    }
}
