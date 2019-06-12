<?php

namespace DCorePHP\Sdk;

use DCorePHP\Crypto\Credentials;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Messaging\Message;
use DCorePHP\Model\Messaging\MessagePayload;
use DCorePHP\Model\Messaging\MessageResponse;
use DCorePHP\Model\Operation\SendMessageOperation;
use DCorePHP\Model\TransactionConfirmation;
use DCorePHP\Net\Model\Request\GetMessageObjects;
use DCorePHP\Net\Model\Request\Messaging;

class MessagingApi extends BaseApi implements MessagingApiInterface
{
    /**
     * @inheritdoc
     */
    public function getAllOperations(
        ChainObject $sender = null,
        ChainObject $receiver = null,
        int $maxCount = 1000
    ): array {
        return $this->dcoreApi->requestWebsocket(new GetMessageObjects($sender, $receiver, $maxCount));
    }

    /**
     * @inheritdoc
     */
    public function getAll(ChainObject $sender = null, ChainObject $receiver = null, int $maxCount = 1000): array
    {
        return $this->flattenMessageResponses($this->dcoreApi->requestWebsocket(new GetMessageObjects($sender, $receiver, $maxCount)));
    }

    /**
     * @inheritdoc
     */
    public function getAllDecrypted(
        Credentials $credentials,
        ChainObject $sender = null,
        ChainObject $receiver = null,
        int $maxCount = 1000
    ): array {
        return $this->decryptMessages($this->getAll($sender, $receiver, $maxCount), $credentials);
    }

    /**
     * @inheritdoc
     */
    public function getAllDecryptedForSender(Credentials $credentials, int $maxCount = 1000): array
    {
        return $this->getAllDecrypted($credentials, $credentials->getAccount(), null, $maxCount);
    }

    /**
     * @inheritdoc
     */
    public function getAllDecryptedForReceiver(Credentials $credentials, int $maxCount = 1000): array
    {
        return $this->getAllDecrypted($credentials, null, $credentials->getAccount(), $maxCount);
    }

    /**
     * @inheritdoc
     */
    public function createMessageOperation(Credentials $credentials, ChainObject $to, string $message): SendMessageOperation
    {
        return $this->createMessageOperationMultiple($credentials, [$to->getId() => $message]);
    }

    /**
     * @inheritdoc
     */
    public function createMessageOperationMultiple(Credentials $credentials, array $messages): SendMessageOperation
    {
        // TODO: Implement createMessageOperationMultiple() method.
    }

    /**
     * @inheritdoc
     */
    public function createMessageOperationUnencrypted(
        Credentials $credentials,
        ChainObject $to,
        string $message
    ):SendMessageOperation {
        return $this->createMessageOperationUnencryptedMultiple($credentials, [[$to, $message]]);
    }

    /**
     * @inheritdoc
     */
    public function createMessageOperationUnencryptedMultiple(
        Credentials $credentials,
        array $messages
    ):SendMessageOperation {
        $messagePayload = new MessagePayload($credentials->getAccount(), $messages);
        return new SendMessageOperation($messagePayload->toJson(), $credentials->getAccount());
    }

    /**
     * @inheritdoc
     */
    public function send(Credentials $credentials, ChainObject $to, string $message): ?TransactionConfirmation
    {
        return $this->dcoreApi->getBroadcastApi()->broadcastOperationWithECKeyPairWithCallback(
            $credentials->getKeyPair(),
            $this->createMessageOperation($credentials, $to, $message)
        );
    }

    /**
     * @inheritdoc
     */
    public function sendMultiple(Credentials $credentials, array $messages): ?TransactionConfirmation
    {
        return $this->dcoreApi->getBroadcastApi()->broadcastOperationWithECKeyPairWithCallback(
            $credentials->getKeyPair(),
            $this->createMessageOperationMultiple($credentials, $messages)
        );
    }

    /**
     * @inheritdoc
     */
    public function sendUnencrypted(Credentials $credentials, ChainObject $to, string $message): ?TransactionConfirmation
    {
        return $this->dcoreApi->getBroadcastApi()->broadcastOperationWithECKeyPairWithCallback(
            $credentials->getKeyPair(),
            $this->createMessageOperationUnencrypted($credentials, $to, $message)
        );
    }

    /**
     * @inheritdoc
     */
    public function sendUnencryptedMultiple(Credentials $credentials, array $messages): ?TransactionConfirmation
    {
        return $this->dcoreApi->getBroadcastApi()->broadcastOperationWithECKeyPairWithCallback(
            $credentials->getKeyPair(),
            $this->createMessageOperationUnencryptedMultiple($credentials, $messages)
        );
    }

    /**
     * Helper method to flatten array
     *
     * @param MessageResponse[] $messageResponses
     * @return Message[]
     */
    private function flattenMessageResponses(array $messageResponses): array {
        $result = [];
        foreach ($messageResponses as $messageResponse) {
            foreach (Message::create($messageResponse) as $message) {
                $result[] = $message;
            }
        }
        return $result;
    }

    /**
     * Helper method to decryptMessages
     *
     * @param Message[] $messages
     * @param Credentials $credentials
     * @return Message[]
     */
    private function decryptMessages(array $messages, Credentials $credentials): array {
        return array_map(function (Message $message) use($credentials) {return $message->decrypt($credentials);}, $messages);
    }
}