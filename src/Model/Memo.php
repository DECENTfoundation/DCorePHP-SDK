<?php

namespace DCorePHP\Model;

use DCorePHP\Crypto\Credentials;
use DCorePHP\Crypto\ECKeyPair;
use DCorePHP\Crypto\PublicKey;
use DCorePHP\Crypto\Address;
use DCorePHP\Utils\Crypto;
use DCorePHP\Utils\Math;

class Memo
{
    /** @var Address */
    private $from;
    /** @var string */
    private $nonce;
    /** @var Address */
    private $to;
    /** @var string */
    private $message;

    public function __construct()
    {
        $this->setNonce($this->generateNonce());
    }

    public static function withMessage(string $message): Memo
    {
        $instance = new self();
        $instance->message = '00000000' . Math::byteArrayToHex(Math::stringToByteArray($message));
        return $instance;
    }

    /**
     * Static constructor with Credentials
     *
     * @param string $message
     * @param Credentials $credentials
     * @param Account $recipient
     * @return Memo
     * @throws \Exception
     */
    public static function withCredentials(string $message, Credentials $credentials, Account $recipient): Memo
    {
        return self::fromECKeyPair($message, $credentials->getKeyPair(), $recipient->getActive()->getKeyAuths()[0][0]);
    }

    /**
     * Static constructor with ECKeyPair
     *
     * @param string $message
     * @param ECKeyPair $keyPair
     * @param Address $recipient
     * @param string|null $nonce
     * @return Memo
     * @throws \Exception
     */
    public static function fromECKeyPair(string $message, ECKeyPair $keyPair, Address $recipient, string $nonce = null): Memo
    {
        $instance = new self();
        $instance->nonce = $nonce ?? Crypto::getInstance()->generateNonce();
        $instance->from = Address::decodeCheckNull($keyPair->getPublic());
        $instance->to = $recipient;
        $instance->message = Crypto::getInstance()->encryptWithChecksum($message, $keyPair->getPrivate(), PublicKey::fromWif($recipient->encode()), $instance->getNonce());

        return $instance;
    }

    public function getFrom(): ?Address
    {
        return $this->from;
    }

    public function setFrom(?Address $from): self
    {
        $this->from = $from;

        return $this;
    }

    public function getTo(): ?Address
    {
        return $this->to;
    }

    public function setTo(?Address $to): self
    {
        $this->to = $to;

        return $this;
    }

    public function getNonce(): ?string
    {
        return $this->nonce;
    }

    public function setNonce(?string $nonce): self
    {
        $this->nonce = $nonce;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return !($this->getFrom() && $this->getTo() && $this->getNonce());
    }

    /**
     * @return string
     */
    private function generateNonce(): string
    {
        try {
            return (string)((int)str_pad(str_replace('.', '', microtime(true)), 18, '0') + random_int(0, 100000000000));
        } catch (\Exception $e) {
            return $this->generateNonce();
        }
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function toArray(): array
    {
        return [
            'from' => PublicKey::fromWif($this->getFrom()->encode())->toAddress(),
            'to' => PublicKey::fromWif($this->getTo()->encode())->toAddress(),
            'message' => $this->getMessage(),
            'nonce' => $this->getNonce(),
        ];
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function toBytes(): string
    {
        return implode('', [
            '01',
            PublicKey::fromWif($this->getFrom()->encode())->toCompressedPublicKey(),
            PublicKey::fromWif($this->getTo()->encode())->toCompressedPublicKey(),
            str_pad(dechex(Math::reverseBytesLong($this->getNonce())), 16, '0', STR_PAD_LEFT),
            dechex(\strlen($this->getMessage()) / 2) . $this->getMessage(),
        ]);
    }
}
