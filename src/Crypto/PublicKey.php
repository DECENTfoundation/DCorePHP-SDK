<?php

namespace DCorePHP\Crypto;

use Mdanter\Ecc\EccFactory;
use Mdanter\Ecc\Primitives\PointInterface;
use StephenHill\Base58;
use StephenHill\BCMathService;

class PublicKey
{
    const PUBLIC_KEY_EVEN = '02';
    const PUBLIC_KEY_ODD = '03';
    /** @var string compressed public key format = 02 | 03 + X coordinate */
    private $publicKey;

    /**
     * @param string $publicKey compressed public key format = 02 | 03 + X coordinate
     */
    public function __construct(string $publicKey)
    {
        $this->publicKey = $publicKey;
    }

    /**
     * @param string $publicKeyWif
     * @return PublicKey
     * @throws \Exception
     */
    public static function fromWif(string $publicKeyWif): self
    {
        $base58 = new Base58(null, new BCMathService());

        $decoded = bin2hex($base58->decode(substr($publicKeyWif, 3, strlen($publicKeyWif))));
        $checksum = substr($decoded, -8, strlen($decoded));
        $publicKey = substr($decoded, 0, -8);

        if (self::calculateChecksum($publicKey) !== $checksum) {
            throw new \Exception('Checksum doesn\'t match');
        }

        return new PublicKey($publicKey);
    }

    /**
     * @param PointInterface $point
     * @return PublicKey
     */
    public static function fromPoint(PointInterface $point): self
    {
        // 02 if Y is even
        // 03 if Y is odd
        $even = hexdec(substr(gmp_strval($point->getY(), 16), -1)) % 2 === 0;
        $compressedPublicKey = ($even ? self::PUBLIC_KEY_EVEN : self::PUBLIC_KEY_ODD) . gmp_strval($point->getX(), 16);

        return new self($compressedPublicKey);
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
     * @param PrivateKey $privateKey
     * @return PublicKey
     * @throws \Exception
     */
    public static function fromPrivateKey(PrivateKey $privateKey): PublicKey
    {
        return $privateKey->toPublicKey();
    }

    /**
     * @return string|null
     */
    public function toCompressedPublicKey(): ?string
    {
        return $this->publicKey;
    }

    /**
     * bitcoin address
     *
     * I don't know which address version it is,
     * it looks similar to https://en.bitcoin.it/wiki/Technical_background_of_version_1_Bitcoin_addresses
     * but there are less steps required to make it work
     *
     * see also
     * https://bitcoin.stackexchange.com/a/1852/89329
     * https://en.bitcoin.it/wiki/Address
     *
     * @param string $addressPrefix
     * @return string
     * @throws \Exception
     */
    public function toAddress(string $addressPrefix = 'DCT'): string
    {
        $base58 = new Base58(null, new BCMathService());

        $publicKey = $this->toCompressedPublicKey(); // 02c03f8e840c1699fd7808c2bb858e249c688c5be8acf0a0c1c484ab0cfb27f0a8

        $checksum = hash('ripemd160', pack('H*', $publicKey)); // 951c37f57422e42bba03dff1c0ce6560651dc95e
        $checksum = substr($checksum, 0, 8); // 951c37f5

        $address = $publicKey . $checksum; // 02c03f8e840c1699fd7808c2bb858e249c688c5be8acf0a0c1c484ab0cfb27f0a8951c37f5
        $address = $base58->encode(pack('H*', $address)); // 6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz
        $address = $addressPrefix . $address; // DCT6MA5TQQ6UbMyMaLPmPXE2Syh5G3ZVhv5SbFedqLPqdFChSeqTz

        return $address;
    }

    /**
     * @return PointInterface
     */
    public function toPoint(): PointInterface
    {
        $generatorPoint = EccFactory::getSecgCurves()->generator256k1();

        /** @var \GMP $xCoord */
        $xCoord = gmp_init(substr($this->toCompressedPublicKey(), 2), 16);
        /** @var \GMP $yCoord */
        $yCoord = $generatorPoint->getCurve()->recoverYfromX(strpos($this->toCompressedPublicKey(), self::PUBLIC_KEY_ODD) === 0, $xCoord);

        return $generatorPoint->getPublicKeyFrom($xCoord, $yCoord)->getPoint();
    }
}
