<?php

namespace DCorePHP\Crypto;

use DCorePHP\Crypto\ECKeyPair;
use DCorePHP\Crypto\PublicKey;
use DCorePHPTests\DCoreSDKTest;
use Mdanter\Ecc\Primitives\PointInterface;
use StephenHill\Base58;
use StephenHill\BCMathService;

class Address
{
    /** @var PointInterface */
    private $publicKeyPoint;
    /** @var string */
    private $prefix;

    /**
     * @param PointInterface $publicKeyPoint
     * @param string $prefix
     */
    public function __construct(PointInterface $publicKeyPoint, string $prefix = 'DCT')
    {
        $this->publicKeyPoint = $publicKeyPoint;
        $this->prefix = $prefix;
    }

    /**
     * @param string $publicKeyWif
     * @return bool
     */
    public static function isValid(string $publicKeyWif): bool
    {
        try {
            self::decode($publicKeyWif);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function encode(): string
    {
        $base58 = new Base58(null, new BCMathService());

        $compressedPublicKey = PublicKey::fromPoint($this->getPublicKeyPoint())->toCompressedPublicKey();
        $checksum = self::calculateChecksum($compressedPublicKey);

        return $this->getPrefix() . $base58->encode(hex2bin($compressedPublicKey . $checksum));
    }

    /**
     * @param string $publicKeyWif
     * @return Address
     * @throws \Exception
     */
    public static function decode(string $publicKeyWif): self
    {
        $base58 = new Base58(null, new BCMathService());

        $decoded = bin2hex($base58->decode(substr($publicKeyWif, 3, strlen($publicKeyWif))));
        $publicKey = substr($decoded, 0, -8);
        $checksum = substr($decoded, -8, strlen($decoded));
        $keyPair = ECKeyPair::fromCompressedPublicKey($publicKey);

        if (self::calculateChecksum($publicKey) !== $checksum) {
            throw new \Exception('Checksum doesn\'t match');
        }

        return new self($keyPair->getPublic()->toPoint(), substr($publicKeyWif, 0, 3));
    }

    /**
     * @param string $publicKeyWif
     * @return Address|null
     * @throws \Exception
     */
    public static function decodeCheckNull(string $publicKeyWif): ?self
    {
        $base58 = new Base58(null, new BCMathService());

        $decoded = bin2hex($base58->decode(substr($publicKeyWif, 3, strlen($publicKeyWif))));
        $publicKey = substr($decoded, 0, -8);

        return preg_match('/^0+$/', $publicKey) ? null : self::decode($publicKeyWif);
    }

    /**
     * @param string $compressedPublicKey
     * @return string
     */
    private static function calculateChecksum(string $compressedPublicKey): string
    {
        return substr(hash('ripemd160', hex2bin($compressedPublicKey)), 0, 8);
    }

    /**
     * @return PointInterface
     */
    public function getPublicKeyPoint(): PointInterface
    {
        return $this->publicKeyPoint;
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }
}