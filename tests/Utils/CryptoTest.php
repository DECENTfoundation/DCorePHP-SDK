<?php

namespace DCorePHPTests\Utils;

use DCorePHP\Utils\Crypto;
use DCorePHP\Crypto\PrivateKey;
use DCorePHP\Crypto\PublicKey;
use DCorePHPTests\DCoreSDKTest;
use PHPUnit\Framework\TestCase;

class CryptoTest extends TestCase
{
    private $crypto;

    protected function setUp()
    {
        $this->crypto = Crypto::getInstance();
    }

    public function testGenerateNonce(): void
    {
        $nonces = [];
        do {
            $nonces[] = $this->crypto->generateNonce();
        } while (count($nonces) < 100);

        $this->assertCount(100, array_unique($nonces));
    }

    public function testGetSharedSecret()
    {
        $sharedSecret = $this->crypto->getSharedSecret(
            PrivateKey::fromWif(DCoreSDKTest::PRIVATE_KEY_1),
            PublicKey::fromWif(DCoreSDKTest::PUBLIC_KEY_2),
            '735604672334802432'
        );

        $this->assertEquals(
            '58da9659ac00197df1bf09e3c6fe9ede74b78357d7df2727a2e8893efafdd2c9c8a77685a16f6d5517be40b52b6a81573ba9222e4493b6acbca455acc29b9333',
            $sharedSecret
        );
    }

    public function testEncryptWithChecksum()
    {
        $encryptedMessage = $this->crypto->encryptWithChecksum(
            'hello messaging api',
            PrivateKey::fromWif(DCoreSDKTest::PRIVATE_KEY_1),
            PublicKey::fromWif(DCoreSDKTest::PUBLIC_KEY_2),
            '17391111264393218816'
        );

        $this->assertEquals(
            '4e2e37edec71eadba4eb8171f7ec1468dc8f5c9b5c218baef9447fd7b998fd83',
            $encryptedMessage
        );
    }

    public function testDecryptWithChecksum()
    {
        $encryptedMessage = $this->crypto->decryptWithChecksum(
            '4e2e37edec71eadba4eb8171f7ec1468dc8f5c9b5c218baef9447fd7b998fd83',
            PrivateKey::fromWif(DCoreSDKTest::PRIVATE_KEY_2),
            PublicKey::fromWif(DCoreSDKTest::PUBLIC_KEY_1),
            '17391111264393218816'
        );

        $this->assertEquals(
            'hello messaging api',
            $encryptedMessage
        );
    }
}
