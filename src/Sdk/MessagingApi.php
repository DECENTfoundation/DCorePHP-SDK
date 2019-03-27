<?php

namespace DCorePHP\Sdk;

use DCorePHP\Crypto\Credentials;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Content\ContentObject;
use DCorePHP\Model\Messaging\Message;
use DCorePHP\Model\Messaging\MessagePayload;
use DCorePHP\Model\Messaging\MessageResponse;
use DCorePHP\Model\Operation\SendMessageOperation;
use DCorePHP\Model\TransactionConfirmation;
use DCorePHP\Net\Model\Request\Database;
use DCorePHP\Net\Model\Request\GetMessageObjects;
use DCorePHP\Net\Model\Request\GetRequiredFees;
use DCorePHP\Net\Model\Request\Messaging;

class MessagingApi extends BaseApi implements MessagingApiInterface
{
    /**
     * @inheritDoc
     */
    public function getAllOperations(
        ChainObject $sender = null,
        ChainObject $receiver = null,
        int $maxCount = 1000
    ): array {
        return $this->dcoreApi->requestWebsocket(Messaging::class, new GetMessageObjects($sender, $receiver, $maxCount));
    }

    /**
     * @inheritDoc
     */
    public function getAll(ChainObject $sender = null, ChainObject $receiver = null, int $maxCount = 1000): array
    {
        return $this->flattenMessageResponses($this->dcoreApi->requestWebsocket(Messaging::class, new GetMessageObjects($sender, $receiver, $maxCount)));
    }

    /**
     * @inheritDoc
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
     * @inheritDoc
     */
    public function getAllDecryptedForSender(Credentials $credentials, int $maxCount = 1000): array
    {
        return $this->getAllDecrypted($credentials, $credentials->getAccount(), null, $maxCount);
    }

    /**
     * @inheritDoc
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
     * @inheritDoc
     */
    public function createMessageOperationMultiple(Credentials $credentials, array $messages): SendMessageOperation
    {
        // TODO: Implement createMessageOperationMultiple() method.
    }

    /**
     * @inheritDoc
     */
    public function createMessageOperationUnencrypted(
        Credentials $credentials,
        ChainObject $to,
        string $message
    ):SendMessageOperation {
        return $this->createMessageOperationUnencryptedMultiple($credentials, [[$to, $message]]);
    }

    /**
     * @inheritDoc
     */
    public function createMessageOperationUnencryptedMultiple(
        Credentials $credentials,
        array $messages
    ):SendMessageOperation {
        $messagePayload = new MessagePayload($credentials->getAccount(), $messages);
        return new SendMessageOperation($messagePayload->toJson(), $credentials->getAccount());
    }

    /**
     * @inheritDoc
     */
    public function send(Credentials $credentials, ChainObject $to, string $message): ?TransactionConfirmation
    {
        return $this->dcoreApi->getBroadcastApi()->broadcastOperationWithECKeyPairWithCallback(
            $credentials->getKeyPair(),
            $this->createMessageOperation($credentials, $to, $message)
        );
    }

    /**
     * @inheritDoc
     */
    public function sendMultiple(Credentials $credentials, array $messages): ?TransactionConfirmation
    {
        return $this->dcoreApi->getBroadcastApi()->broadcastOperationWithECKeyPairWithCallback(
            $credentials->getKeyPair(),
            $this->createMessageOperationMultiple($credentials, $messages)
        );
    }

    /**
     * @inheritDoc
     */
    public function sendUnencrypted(Credentials $credentials, ChainObject $to, string $message): ?TransactionConfirmation
    {
        return $this->dcoreApi->getBroadcastApi()->broadcastOperationWithECKeyPairWithCallback(
            $credentials->getKeyPair(),
            $this->createMessageOperationUnencrypted($credentials, $to, $message)
        );
    }

    /**
     * @inheritDoc
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