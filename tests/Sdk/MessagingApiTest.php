<?php

namespace DCorePHPTests\Sdk;

use DCorePHP\Crypto\Credentials;
use DCorePHP\Crypto\ECKeyPair;
use DCorePHP\Exception\InvalidApiCallException;
use DCorePHP\Exception\ObjectNotFoundException;
use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Messaging\Message;
use DCorePHP\Model\Messaging\MessageResponse;
use DCorePHPTests\DCoreSDKTest;
use Exception;
use WebSocket\BadOpcodeException;

class MessagingApiTest extends DCoreSDKTest
{
    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::testSendMessageMultiple();
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ValidationException
     * @throws ObjectNotFoundException
     * @throws Exception
     */
    public static function testSendMessageMultiple(): void
    {
        $time = time();
        $msg = "hello messaging api unencrypted FROM PHP at: {$time}";

        $from = new ChainObject(DCoreSDKTest::ACCOUNT_ID_1);
        $to = new ChainObject(DCoreSDKTest::ACCOUNT_ID_2);
        $credentials = new Credentials($from, ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        self::$sdk->getMessagingApi()->sendMultiple($credentials, [DCoreSDKTest::ACCOUNT_ID_2 => $msg]);

        /** @var Message[] $messages */
        $messages = self::$sdk->getMessagingApi()->getAllDecrypted($credentials, $from, $to);
        $messageFound = false;
        foreach ($messages as $message) {
            if ($message->getMessage() === $msg && !$message->isEncrypted()) {
                $messageFound = true;
            }
        }
        self::assertTrue($messageFound);
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ValidationException
     * @throws Exception
     */
    public function testSendMessageUnencryptedMultiple(): void
    {
        $time = time();
        $msg = "hello messaging api unencrypted FROM PHP at: {$time}";
        // Used in GetMessageObjects -> When mocking server ($msg is dynamic thanks to time() -> mock gets the same result every time)

        $from = new ChainObject(DCoreSDKTest::ACCOUNT_ID_1);
        $to = new ChainObject(DCoreSDKTest::ACCOUNT_ID_2);
        $credentials = new Credentials($from, ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        self::$sdk->getMessagingApi()->sendUnencryptedMultiple($credentials, [DCoreSDKTest::ACCOUNT_ID_2 => $msg]);

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

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ValidationException
     */
    public function testGetAllOperations(): void
    {
        /** @var MessageResponse[] $messageResponses */
        $messageResponses = self::$sdk->getMessagingApi()->getAllOperations(null, new ChainObject(DCoreSDKTest::ACCOUNT_ID_2));
        foreach ($messageResponses as $messageResponse) {
            $this->assertEquals(DCoreSDKTest::ACCOUNT_ID_2, $messageResponse->getReceiversData()[0]->getReceiver()->getId());
        }
    }

    /**
     * @throws ValidationException
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
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
     * @throws Exception
     */
    public function testGetAllDecryptedForSender(): void
    {
        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        /** @var Message[] $messages */
        $messages = self::$sdk->getMessagingApi()->getAllDecryptedForSender($credentials);

        foreach ($messages as $message) {
            dump($message->getMessage());
            $this->assertInstanceOf(Message::class, $message);
            $this->assertEquals(DCoreSDKTest::ACCOUNT_ID_1, $message->getSender()->getId());
            $this->assertFalse($message->isEncrypted());
        }
    }

    /**
     * @throws ValidationException
     * @throws Exception
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
}
