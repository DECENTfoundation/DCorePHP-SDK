<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Messaging\MessageReceiver;
use DCorePHP\Model\Messaging\MessageResponse;
use DCorePHP\Net\Model\Response\BaseResponse;
use DCorePHP\Crypto\Address;

class GetMessageObjects extends BaseRequest
{
    public function __construct(?ChainObject $sender, ?ChainObject $receiver, int $maxCount) {
        parent::__construct(
            'messaging',
            'get_message_objects',
            [$sender ? $sender->getId() : null, $receiver ? $receiver->getId() : null, $maxCount]
        );
    }

    public static function responseToModel(BaseResponse $response)
    {
        $messageResponses = [];
        foreach ($response->getResult() as $rawMessageResponse) {
            $messageResponse = new MessageResponse();
            foreach (
                [
                    '[id]' => 'id',
                    '[created]' => 'created',
                    '[sender]' => 'sender',
                    '[text]' => 'text'
                ] as $path => $modelPath
            ) {
                $value = self::getPropertyAccessor()->getValue($rawMessageResponse, $path);
                self::getPropertyAccessor()->setValue($messageResponse, $modelPath, $value);
            }

            $senderAddress = Address::decodeCheckNull($rawMessageResponse['sender_pubkey']);
            $messageResponse->setSenderAddress($senderAddress);
            $receiversData = [];
            foreach ($rawMessageResponse['receivers_data'] as $receiverData) {
                $messageReceiver = new MessageReceiver();
                foreach (
                    [
                        '[receiver]' => 'receiver',
                        '[nonce]' => 'nonce',
                        '[data]' => 'data'
                    ] as $path => $modelPath
                ) {
                    $value = self::getPropertyAccessor()->getValue($receiverData, $path);
                    self::getPropertyAccessor()->setValue($messageReceiver, $modelPath, $value);
                }
                $messageReceiver->setReceiverAddress(Address::decodeCheckNull($receiverData['receiver_pubkey']));

                $receiversData[] = $messageReceiver;
            }

            $messageResponse->setReceiversData($receiversData);

            $messageResponses[] = $messageResponse;
        }

        return $messageResponses;
    }
}