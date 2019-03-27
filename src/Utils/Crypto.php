<?php

namespace DCorePHP\Utils;

use DCorePHP\Crypto\PrivateKey;
use DCorePHP\Crypto\PublicKey;
use Mdanter\Ecc\Crypto\EcDH\EcDH;
use Mdanter\Ecc\EccFactory;

class Crypto
{
    /** @var int */
    private $uniqueNonceEntropy;

    private function __construct()
    {

    }

    /**
     * @return Crypto
     */
    public static function getInstance(): Crypto
    {
        static $instance = null;

        if ($instance === null) {
            $instance = new Crypto();
        }

        return $instance;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function generateNonce(): string
    {
        if (!$this->uniqueNonceEntropy) {
            $entropy = $this->uniqueNonceEntropy = random_int(0, 999);
        } else {
            $entropy = ++$this->uniqueNonceEntropy % 256;
        }

        $nonce = (string)str_replace('.', '', microtime(true));
        $nonce .= $entropy;

        return $nonce;
    }

    /**
     * @param PrivateKey $privateKey
     * @param PublicKey $publicKey
     * @param string $nonce
     * @return string
     * @throws \Exception
     */
    public function getSharedSecret(PrivateKey $privateKey, PublicKey $publicKey, string $nonce = ''): string
    {
        $G = EccFactory::getSecgCurves()->generator256k1();
        $adapter = EccFactory::getAdapter();
        $ecdh = new EcDH($adapter);

        $compressedPublicKey = $publicKey->toCompressedPublicKey();
        $num = hexdec(mb_substr($compressedPublicKey, 0, 2)); // 2
        /** @var \GMP $Xcoord */
        $Xcoord = gmp_init(substr($compressedPublicKey, 2), 16); // e0ced80260630f641f61f6d6959f32b5c43b1a38be55666b98abfe8bafcc556b
        /** @var \GMP $yCoord */
        $yCoord = $G->getCurve()->recoverYfromX(!($num % 2 === 0), $Xcoord); // 8770a7de5c2d7c39fda1048b3c05f40261ae82c5cef5c4e2e065caf1380525c0

        $ecdh->setSenderKey($G->getPrivateKeyFrom(gmp_init($privateKey->toHex(), 16)));
        $ecdh->setRecipientKey($G->getPublicKeyFrom($Xcoord, $yCoord));

        $calculateSharedKey = gmp_strval($ecdh->calculateSharedKey(), 16); // e4d03d9995ebb1b61b11e5e8631a70cdfdd2691df320ad3187751b256cccf808
        $sharedKey = hash('sha512', $nonce . hash('sha512', hex2bin($calculateSharedKey))); // 58da9659ac00197df1bf09e3c6fe9ede74b78357d7df2727a2e8893efafdd2c9c8a77685a16f6d5517be40b52b6a81573ba9222e4493b6acbca455acc29b9333

        return $sharedKey;
    }

    /**
     * perform AES 256 bit encryption, in CBC mode, using Pkcs7 padding
     *
     * @param string $message plain text message to be encrypted
     * @param \DCorePHP\Crypto\PrivateKey $privateKey senders private key
     * @param \DCorePHP\Crypto\PublicKey $publicKey recipients public key
     * @param string $nonce unique number
     * @return string
     * @throws \Exception
     */
    public function encryptWithChecksum(string $message, PrivateKey $privateKey, PublicKey $publicKey, string $nonce = ''): string
    {
        // command line proof, that implementation is correct:
        // echo -n "1b1bd76568656c6c6f206d656d6f2068657265206920616d" | openssl enc -aes256 -K 4305eca167c414cf05cf71c50e4e0ffceb8825628a0af540af52c2aa93dc9f10 -iv 32d5b681049a3169e3ca8b3820290f3b | xxd -p | tr -d $'\n'
        // 1d577fde010bd33ace35f3992dc52332de647f67e5799010737d5d0c8c1d0b74e4b06586f288e65fd371ac71146d15f689f6660016581afba656fb478544140a

        $checksum = substr(hash('sha256', $message), 0, 8);
        $payload = $checksum . unpack('H*', $message)[1];
        $sharedSecret = $this->getSharedSecret($privateKey, $publicKey, $nonce);
        $initializationVector = substr($sharedSecret, 64, 32);
        $key = substr($sharedSecret, 0, 64);

        $ciphertext = openssl_encrypt(hex2bin($payload), 'aes-256-cbc', hex2bin($key), true, hex2bin($initializationVector));

        return bin2hex($ciphertext);
    }

    /**
     * perform AES 256 bit decryption, in CBC mode, using Pkcs7 padding
     *
     * @param string $encryptedMessage encrypted message to be decrypted
     * @param \DCorePHP\Crypto\PrivateKey $privateKey recipients private key
     * @param \DCorePHP\Crypto\PublicKey $publicKey sender public key
     * @param string $nonce unique number which was used in encryption
     * @return string
     * @throws \Exception
     */
    public function decryptWithChecksum(string $encryptedMessage, PrivateKey $privateKey, PublicKey $publicKey, string $nonce = ''): string
    {
        $sharedSecret = $this->getSharedSecret($privateKey, $publicKey, $nonce);
        $initializationVector = substr($sharedSecret, 64, 32);
        $key = substr($sharedSecret, 0, 64);
        $payload = openssl_decrypt(hex2bin($encryptedMessage), 'aes-256-cbc', hex2bin($key), true, hex2bin($initializationVector));
        return substr($payload, 4);
    }
}
