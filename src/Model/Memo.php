<?php

namespace DCorePHP\Model;

use DCorePHP\Crypto\Credentials;
use DCorePHP\Crypto\ECKeyPair;
use DCorePHP\Crypto\PublicKey;
use DCorePHP\Crypto\Address;
use DCorePHP\Model\Subscription\AuthMap;
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
        $instance->nonce = '0';
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
        /** @var AuthMap $authMap */
        $authMap = $recipient->getActive()->getKeyAuths()[0];
        return self::fromECKeyPair($message, $credentials->getKeyPair(), Address::decode($authMap->getValue()));
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
        $instance->from = Address::decodeCheckNull($keyPair->getPublic()->toAddress());
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
            return gmp_strval(gmp_add(gmp_init(str_pad(str_replace('.', '', microtime(true)), 18, '0')), random_int(0, PHP_INT_MAX)));
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
        if ($this->getFrom() !== null && $this->getTo() !== null) {
            return [
                'from' => PublicKey::fromWif($this->getFrom()->encode())->toAddress(),
                'to' => PublicKey::fromWif($this->getTo()->encode())->toAddress(),
                'message' => $this->getMessage(),
                'nonce' => $this->getNonce(),
            ];
        }
        return [
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
            $this->getFrom() ? PublicKey::fromWif($this->getFrom()->encode())->toCompressedPublicKey() : str_pad('', 66, '0'),
            $this->getTo() ? PublicKey::fromWif($this->getTo()->encode())->toCompressedPublicKey() : str_pad('', 66, '0'),
            str_pad(Math::gmpDecHex(Math::reverseBytesLong($this->getNonce())), 16, '0', STR_PAD_LEFT),
            Math::writeUnsignedVarIntHex(sizeof(Math::stringToByteArray(hex2bin($this->getMessage())))),
            $this->getMessage()
        ]);
    }
}
