<?php

namespace DCorePHPTests\Model\Operation;

use DCorePHP\Crypto\Credentials;
use DCorePHP\Crypto\ECKeyPair;
use DCorePHP\Exception\InvalidApiCallException;
use DCorePHP\Exception\ObjectNotFoundException;
use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Memo;
use DCorePHP\Model\Messaging\MessagePayload;
use DCorePHP\Model\Messaging\MessagePayloadReceiver;
use DCorePHP\Model\Operation\SendMessageOperation;
use DCorePHP\Utils\Math;
use DCorePHPTests\DCoreSDKTest;
use Exception;
use WebSocket\BadOpcodeException;

class SendMessageTest extends DCoreSDKTest
{
    /**
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws ValidationException
     * @throws BadOpcodeException
     * @throws Exception
     */
    public function testToBytesEncrypted(): void
    {
        $sender = self::$sdk->getAccountApi()->get(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1));
        $recipient = self::$sdk->getAccountApi()->get(new ChainObject(DCoreSDKTest::ACCOUNT_ID_2));
        $keyPair = ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1);
        $msg = Memo::fromECKeyPair('hello messaging api', $keyPair, $recipient->getOptions()->getMemoKey(), '10216254519122646016');
        $payloadReceiver = new MessagePayloadReceiver($recipient->getId(), $msg->getMessage(), $recipient->getOptions()->getMemoKey(), $msg->getNonce());
        $payload = new MessagePayload($sender->getId(), [$payloadReceiver], $sender->getOptions()->getMemoKey());
        $operation = new SendMessageOperation();
        $operation
            ->setId(SendMessageOperation::CUSTOM_TYPE_MESSAGE)
            ->setData(Math::byteArrayToHex(Math::stringToByteArray($payload->toJson())))
            ->setPayer($sender->getId())
            ->setRequiredAuths([$sender->getId()]);

        $this->assertEquals(
            '120000000000000000001b011b0100a1027b2266726f6d223a22312e322e3237222c227265636569766572735f64617461223a5b7b22746f223a22312e322e3238222c2264617461223a2265336265326132326339313063383766306436356662303539343866393061623431643364613866393034383061316235316533623465363165353339346539222c227075625f746f223a2244435438324d5443515661395444466d7a335a77614c7a7346416d434c6f4a7a727446756770463732767362754531437043774b79222c226e6f6e6365223a223130323136323534353139313232363436303136227d5d2c227075625f66726f6d223a2244435438324d5443515661395444466d7a335a77614c7a7346416d434c6f4a7a727446756770463732767362754531437043774b79227d',
            $operation->toBytes()
        );
    }

    /**
     * @throws ValidationException
     * @throws Exception
     */
    public function testToBytesUnencrypted(): void
    {
        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        $memo = Memo::withMessage('hello messaging api unencrypted');
        $payloadReceivers = [new MessagePayloadReceiver(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), $memo->getMessage())];
        $payload = new MessagePayload($credentials->getAccount(), $payloadReceivers);
        $operation = new SendMessageOperation();
        $operation
            ->setId(SendMessageOperation::CUSTOM_TYPE_MESSAGE)
            ->setData(Math::byteArrayToHex(Math::stringToByteArray($payload->toJson())))
            ->setPayer($credentials->getAccount())
            ->setRequiredAuths([$credentials->getAccount()]);

        $this->assertEquals(
            '120000000000000000001b011b010084017b2266726f6d223a22312e322e3237222c227265636569766572735f64617461223a5b7b22746f223a22312e322e3237222c2264617461223a2230303030303030303638363536633663366632303664363537333733363136373639366536373230363137303639323037353665363536653633373237393730373436353634227d5d7d',
            $operation->toBytes()
        );
    }

    /**
     * @throws ValidationException
     * @throws Exception
     */
    public function testCreateMessageOperationUnencrypted(): void
    {
        $msg = 'hello messaging api unencrypted FROM PHP';
        $from = new ChainObject(DCoreSDKTest::ACCOUNT_ID_1);
        $to = new ChainObject(DCoreSDKTest::ACCOUNT_ID_2);
        $credentials = new Credentials($from, ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        $msgOp = self::$sdk->getMessagingApi()->createMessageOperationUnencrypted($credentials, $to, $msg);

        $this->assertEquals(DCoreSDKTest::ACCOUNT_ID_1, $msgOp->getPayer()->getId());
        $this->assertEquals('7b2266726f6d223a22312e322e3237222c227265636569766572735f64617461223a5b7b22746f223a22312e322e3238222c2264617461223a2230303030303030303638363536633663366632303664363537333733363136373639366536373230363137303639323037353665363536653633373237393730373436353634323034363532346634643230353034383530227d5d7d', $msgOp->getData());
    }
}