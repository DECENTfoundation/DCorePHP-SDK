<?php

namespace DCorePHP\Crypto;

use Mdanter\Ecc\EccFactory;
use Mdanter\Ecc\Primitives\PointInterface;
use StephenHill\Base58;
use StephenHill\BCMathService;

/**
 * https://en.bitcoinwiki.org/wiki/Base58
 * wif is hex (base16) private key prefixed with version (128 decimal = 80 hex), suffixed with checksum (8 bits) and encoded to base58
 * https://stackoverflow.com/a/48102827/1910306
 */
class PrivateKey
{
    /**
     * @var string private key in ECDSA format
     * https://en.bitcoin.it/wiki/Wallet_import_format
     */
    private $privateKey;

    private function __construct(string $privateKey)
    {
        $this->privateKey = $privateKey;
    }

    /**
     * Create instance of class PrivateKey from WIF
     *
     * @param string $encodedPrivateKeyWif
     * @return self
     * @throws \Exception
     */
    public static function fromWif(string $encodedPrivateKeyWif): self
    {
        $base58 = new Base58(null, new BCMathService());
        $privateKey = bin2hex($base58->decode($encodedPrivateKeyWif));
        $privateKey = substr($privateKey, 0, -8); // drop checksum - last 4 bytes
        $privateKey = substr($privateKey, 2, \strlen($privateKey)); // drop first byte

        return new PrivateKey($privateKey);
    }

    /**
     * Create instance of class PrivateKey from hex private key
     *
     * @param string $hex
     * @return self
     */
    public static function fromHex(string $hex): self
    {
        return new PrivateKey($hex);
    }

    /**
     * @param string $brainKey
     * @param int $sequence
     * @return PrivateKey
     * @throws \Exception
     */
    public static function fromBrainKey(string $brainKey, int $sequence = 0): self
    {
        if ($sequence > 0) {
            throw new \Exception("Invalid sequence '{$sequence}'");
        }

        $brainKey = preg_replace('/[\t\n\v\f\r ]+/', ' ', $brainKey);
        $brainKey = trim($brainKey);

        return PrivateKey::fromHex(hash('sha256', hash('sha256', "{$brainKey} {$sequence}")));
    }

    /**
     * Encode a private key into base58.
     *
     * @return string base58 encoded private key
     * @throws \Exception
     */
    public function toWif(): string
    {
        // add 128 decimal = 80 hex prefix to private key
        $privateKey = '80' . $this->privateKey;

        // append checksum - 8 bits to private key
        $checksum = strtoupper(hash('sha256', pack('H*', $privateKey)));
        $checksum = strtoupper(hash('sha256', pack('H*', $checksum)));
        $checksum = substr($checksum, 0, 8);
        $privateWif = $privateKey . $checksum;

        $binPrivateWif = pack('H*', $privateWif);

        // convert from hex to base58
        $base58 = new Base58(null, new BCMathService());

        return $base58->encode($binPrivateWif);
    }

    /**
     * get private key in hex format
     *
     * @return string
     */
    public function toHex(): string
    {
        return $this->privateKey;
    }

    /**
     * @return PointInterface
     */
    public function toPublicKeyPoint(): PointInterface
    {
        $generatorPoint = EccFactory::getSecgCurves()->generator256k1();
        $privateKey = $generatorPoint->getPrivateKeyFrom(gmp_init($this->toHex(), 16));

        return $privateKey->getPublicKey()->getPoint();
    }

    /**
     * @return PublicKey
     * @throws \Exception
     */
    public function toPublicKey(): PublicKey
    {
        return PublicKey::fromPoint($this->toPublicKeyPoint());
    }

    /**
     * take hex representation of private key
     * convert it to binary string
     * hash the binary string
     * convert hash to decimal big integer
     * return big integer as string
     *
     * @return string
     */
    public function toElGamalPrivateKey(): string
    {
        $hash = gmp_init(hash('sha512', hex2bin($this->toHex())), 16);

        return gmp_strval($hash);
    }

    /**
     * take el gamal private key
     * calculate - base raised into power exp modulo mod
     * return big integer as string
     *
     * @return string
     */
    public function toElGamalPublicKey(): string
    {
        $base = gmp_init('3', 10);
        $exponent = $this->toElGamalPrivateKey();
        $mod = gmp_init('11760620558671662461946567396662025495126946227619472274601251081547302009186313201119191293557856181195016058359990840577430081932807832465057884143546419', 10);

        return gmp_strval(gmp_powm($base, $exponent, $mod));
    }

    public function getKey(): ?string
    {
        return $this->privateKey;
    }
}
