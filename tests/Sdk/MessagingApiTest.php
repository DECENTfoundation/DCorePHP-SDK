<?php

namespace DCorePHPTests\Sdk;

use DCorePHP\Crypto\Credentials;
use DCorePHP\Crypto\ECKeyPair;
use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Messaging\Message;
use DCorePHP\Model\Messaging\MessageResponse;
use DCorePHP\Net\Model\Request\BaseRequest;
use DCorePHP\Net\Model\Request\BroadcastTransactionWithCallback;
use DCorePHP\Net\Model\Request\GetChainId;
use DCorePHP\Net\Model\Request\GetDynamicGlobalProperties;
use DCorePHP\Net\Model\Request\GetMessageObjects;
use DCorePHP\Net\Model\Request\GetRequiredFees;
use DCorePHP\Net\Model\Response\BaseResponse;
use DCorePHP\Utils\Math;
use DCorePHPTests\DCoreSDKTest;

class MessagingApiTest extends DCoreSDKTest
{
    /**
     * @throws ValidationException
     */
    public function testGetAllOperations(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
//        $messageResponses = self::$sdk->getMessagingApi()->getAllOperations(null, new ChainObject(DCoreSDKTest::ACCOUNT_ID_1));
//        foreach ($messageResponses as $messageResponse) {
//            $this->assertInstanceOf(MessageResponse::class, $messageResponse);
//        }
    }

    /**
     * @throws ValidationException
     */
    public function testGetAll(): void
    {
        $messages = self::$sdk->getMessagingApi()->getAll(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1) );
        foreach ($messages as $message) {
            $this->assertInstanceOf(Message::class, $message);
        }
    }

    /**
     * @throws ValidationException
     * @throws \Exception
     */
    public function testGetAllDecryptedForSender(): void
    {
        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        /** @var Message[] $messages */
        $messages = self::$sdk->getMessagingApi()->getAllDecryptedForSender($credentials);

        foreach ($messages as $message) {
            $this->assertInstanceOf(Message::class, $message);
            $this->assertEquals(DCoreSDKTest::ACCOUNT_ID_1, $message->getSender()->getId());
            $this->assertFalse($message->isEncrypted());
        }
    }

    /**
     * @throws ValidationException
     * @throws \Exception
     */
    public function testGetAllDecryptedForReceiver(): void
    {
        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_2), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_2));
        /** @var Message[] $messages */
        $messages = self::$sdk->getMessagingApi()->getAllDecryptedForReceiver($credentials);

        foreach ($messages as $message) {
            $this->assertInstanceOf(Message::class, $message);
            $this->assertEquals(DCoreSDKTest::ACCOUNT_ID_2, $message->getReceiver()->getId());
            $this->assertFalse($message->isEncrypted());
        }
    }

    public function testCreateMessageOperation(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testCreateMessageOperationMultiple(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testCreateMessageOperationUnencryptedMultiple(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testSendMessage(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    public function testSendMessageMultiple(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }

    /**
     * @throws ValidationException
     * @throws \Exception
     */
    public function testSendUnencryptedMessage(): void
    {
        $time = time();
        $msg = "hello messaging api unencrypted FROM PHP at: {$time}";
        // Used in GetMessageObjects -> When mocking server ($msg is dynamic thanks to time() -> mock gets the same result every time)

        $from = new ChainObject(DCoreSDKTest::ACCOUNT_ID_1);
        $to = new ChainObject(DCoreSDKTest::ACCOUNT_ID_2);
        $credentials = new Credentials($from, ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        self::$sdk->getMessagingApi()->sendUnencrypted($credentials, $to, $msg);

        /** @var Message[] $messages */
        $messages = self::$sdk->getMessagingApi()->getAllDecrypted($credentials, $from, $to);
        $messageFound = false;
        foreach ($messages as $message) {
            if ($message->getMessage() === $msg) {
                $messageFound = true;
            }
        }
        $this->assertTrue($messageFound);
    }

    public function testSendMessageUnencryptedMultiple(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
    }
}
