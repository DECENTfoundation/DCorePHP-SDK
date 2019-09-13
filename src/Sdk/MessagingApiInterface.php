<?php

namespace DCorePHP\Sdk;

use DCorePHP\Crypto\Credentials;
use DCorePHP\Exception\InvalidApiCallException;
use DCorePHP\Exception\ObjectNotFoundException;
use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Messaging\Message;
use DCorePHP\Model\Messaging\MessageResponse;
use DCorePHP\Model\Operation\SendMessageOperation;
use DCorePHP\Model\TransactionConfirmation;
use Exception;
use WebSocket\BadOpcodeException;

interface MessagingApiInterface
{
    /**
     * Find all message operations
     *
     * @param ChainObject $sender filter by sender account id
     * @param ChainObject $receiver filter by receiver account id
     * @param int $maxCount max items to return
     *
     * @return MessageResponse[] a vector of message objects
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function findAllOperations(ChainObject $sender = null, ChainObject $receiver = null, int $maxCount = 1000): array;

    /**
     * Find all messages
     *
     * @param ChainObject $sender name of message sender. If you dont want to filter by sender then let it empty
     * @param ChainObject $receiver name of message receiver. If you dont want to filter by receiver then let it empty
     * @param int $maxCount maximal number of last messages to be displayed
     *
     * @return Message[] a list of messages
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function findAll(ChainObject $sender = null, ChainObject $receiver = null, int $maxCount = 1000): array;

    /**
     * Find all messages and decrypt
     *
     * @param Credentials $credentials account credentials used for decryption, must be either sender's or receiver's
     * @param ChainObject $sender filter by sender account id
     * @param ChainObject $receiver filter by receiver account id
     * @param int $maxCount max items to return
     *
     * @return Message[] a list of messages
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function findAllDecrypted(Credentials $credentials, ChainObject $sender = null, ChainObject $receiver = null, int $maxCount = 1000): array;

    /**
     * Find all messages for sender and decrypt
     *
     * @param Credentials $credentials sender account credentials with decryption keys
     * @param int $maxCount max items to return
     *
     * @return Message[] a list of messages
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function findAllDecryptedForSender(Credentials $credentials, int $maxCount = 1000): array;

    /**
     * Find all messages for receiver and decrypt
     *
     * Receives message objects by sender and/or receiver
     * @param Credentials $credentials sender account credentials with decryption keys
     * @param int $maxCount max items to return
     *
     * @return Message[] a list of messages
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function findAllDecryptedForReceiver(Credentials $credentials, int $maxCount = 1000): array;

    /**
     * Create message operation, send a message to one receiver
     *
     * @param Credentials $credentials sender account credentials
     * @param ChainObject $to receiver address
     * @param string $message a message to send
     *
     * @return SendMessageOperation
     *
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws BadOpcodeException
     * @throws ValidationException*
     */
    public function createMessageOperation(Credentials $credentials, ChainObject $to, string $message): SendMessageOperation;

    /**
     * Create message operation, send messages to multiple receivers
     *
     * @param Credentials $credentials sender account credentials
     * @param array $messages a list of pairs of receiver account id and message
     *
     * @return SendMessageOperation
     *
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     * @throws BadOpcodeException
     * @throws ValidationException
     * @throws Exception
     */
    public function createMessageOperationMultiple(Credentials $credentials, array $messages): SendMessageOperation;

    /**
     * Create message operation, send a message to one receiver, unencrypted
     *
     * @param Credentials $credentials sender account credentials
     * @param ChainObject $to receiver address
     * @param string $message a message to send
     *
     * @return SendMessageOperation
     *
     * @throws ValidationException
     */
    public function createMessageOperationUnencrypted(Credentials $credentials, ChainObject $to, string $message): SendMessageOperation;

    /**
     * Create message operation, send a message to one receiver, unencrypted
     *
     * @param Credentials $credentials sender account credentials
     * @param array messages a list of pairs of receiver account id and message
     *
     * @return SendMessageOperation
     *
     * @throws ValidationException
     */
    public function createMessageOperationUnencryptedMultiple(Credentials $credentials, array $messages): SendMessageOperation;

    /**
     * Send message to one receiver
     *
     * @param Credentials $credentials
     * @param ChainObject $to
     * @param string $message
     *
     * @return TransactionConfirmation|null
     *
     * @throws Exception
     */
    public function send(Credentials $credentials, ChainObject $to, string $message): ?TransactionConfirmation;

    /**
     * Send message to multiple receivers
     *
     * @param Credentials $credentials
     * @param array $messages
     *
     * @return TransactionConfirmation|null
     *
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException

     * @throws BadOpcodeException
     * @throws Exception
     */
    public function sendMultiple(Credentials $credentials, array $messages): ?TransactionConfirmation;

    /**
     * Send message to one receiver, unencrypted
     *
     * @param Credentials $credentials
     * @param ChainObject $to
     * @param string $message
     *
     * @return TransactionConfirmation|null
     *
     * @throws ValidationException
     * @throws Exception
     */
    public function sendUnencrypted(Credentials $credentials, ChainObject $to, string $message): ?TransactionConfirmation;

    /**
     * Send message to multiple receivers, unencrypted
     *
     * @param Credentials $credentials
     * @param array $messages
     *
     * @return TransactionConfirmation|null
     *
     * @throws ValidationException
     * @throws Exception
     */
    public function sendUnencryptedMultiple(Credentials $credentials, array $messages): ?TransactionConfirmation;

    /**
     * @param array $ids
     *
     * @return Message[]
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function getAll(array $ids): array;

    /**
     * @param ChainObject $id
     *
     * @return Message
     * @throws ObjectNotFoundException
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function get(ChainObject $id): Message;
}