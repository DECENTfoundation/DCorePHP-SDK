<?php

namespace DCorePHP\Net\Model\Request;

use DCorePHP\Crypto\Address;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Messaging\MessageReceiver;
use DCorePHP\Model\Messaging\MessageResponse;
use DCorePHP\Net\Model\Response\BaseResponse;

class GetMessages extends BaseRequest
{
    public function __construct(array $ids)
    {
        parent::__construct(
            self::API_GROUP_MESSAGING,
            'get_messages',
            [array_map(static function (ChainObject $id) { return $id->getId(); }, $ids)]
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
                    '[sender]' => 'sender'
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