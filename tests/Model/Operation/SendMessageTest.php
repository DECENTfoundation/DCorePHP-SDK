<?php

namespace DCorePHPTests\Model\Operation;

use DCorePHP\Crypto\Credentials;
use DCorePHP\Crypto\ECKeyPair;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Memo;
use DCorePHP\Model\Messaging\MessagePayload;
use DCorePHP\Model\Messaging\MessagePayloadReceiver;
use DCorePHP\Model\Operation\SendMessageOperation;
use DCorePHPTests\DCoreSDKTest;;

class SendMessageTest extends DCoreSDKTest
{
    // TODO: quotes in json payload (nonce)
//    /**
//     * @throws \DCorePHP\Exception\InvalidApiCallException
//     * @throws \DCorePHP\Exception\ObjectNotFoundException
//     * @throws \DCorePHP\Exception\ValidationException
//     * @throws \WebSocket\BadOpcodeException
//     * @throws \Exception
//     */
//    public function testToBytesEncrypted(): void
//    {
//        $sender = $this->sdk->getAccountApi()->get(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1));
//        $recipient = $this->sdk->getAccountApi()->get(new ChainObject(DCoreSDKTest::ACCOUNT_ID_2));
//        $keyPair = ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1);
//        $msg = Memo::fromECKeyPair('hello messaging api', $keyPair, $recipient->getOptions()->getMemoKey(), '10216254519122646016');
//        $payloadReceiver = new MessagePayloadReceiver($recipient->getId(), $msg->getMessage(), $recipient->getOptions()->getMemoKey(), $msg->getNonce());
//        $payload = new MessagePayload($sender->getId(), [$payloadReceiver], $sender->getOptions()->getMemoKey());
//        $operation = new SendMessageOperation($payload->toJson(), $sender->getId());
//        dump($payload->toJson());
//
//        $this->assertEquals(
//            '1200000000000000000022012201009f027b2266726f6d223a22312e322e3334222c227265636569766572735f64617461223a5b7b22746f223a22312e322e3335222c2264617461223a2239623933633537343130656137643830626136373530383139653036643665326237626138316366393162626637653331643236353930396234363739306164222c227075625f746f223a224443543662566d696d745953765751747764726b56565147486b5673544a5a564b74426955716634596d4a6e724a506e6b38395150222c226e6f6e6365223a31303231363235343531393132323634363031367d5d2c227075625f66726f6d223a22444354364d41355451513655624d794d614c506d505845325379683547335a566876355362466564714c507164464368536571547a227d',
//            $operation->toBytes()
//        );
//    }

    /**
     * @throws \DCorePHP\Exception\ValidationException
     * @throws \Exception
     */
    public function testToBytesUnencrypted(): void
    {
        $credentials = new Credentials(new ChainObject(DCoreSDKTest::ACCOUNT_ID_1), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        $messages = [[new ChainObject(DCoreSDKTest::ACCOUNT_ID_2) , 'hello messaging api unencrypted']];
        $payload = new MessagePayload($credentials->getAccount(), $messages);
        $operation = new SendMessageOperation($payload->toJson(), $credentials->getAccount());

        $this->assertEquals(
            '12000000000000000000220122010084017b2266726f6d223a22312e322e3334222c227265636569766572735f64617461223a5b7b22746f223a22312e322e3335222c2264617461223a2230303030303030303638363536633663366632303664363537333733363136373639366536373230363137303639323037353665363536653633373237393730373436353634227d5d7d',
            $operation->toBytes()
        );
    }
}