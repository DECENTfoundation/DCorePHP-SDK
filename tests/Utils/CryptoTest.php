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
            'ad78ca053d024534ced1930381f58aea1abb24e08bfa0dfeb8c8ecf084c6ba48aef29bbcb0166eaf9c6a5d44bb06a388d7b80079ca4bcc62d6f5ecc00147c85a',
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
            '25f4f1ec0456d5b7aaf1abb0257464b5e077f7b092437748ec4359e802c33a58',
            $encryptedMessage
        );
    }

    public function testDecryptWithChecksum()
    {
        $encryptedMessage = $this->crypto->decryptWithChecksum(
            '25f4f1ec0456d5b7aaf1abb0257464b5e077f7b092437748ec4359e802c33a58',
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
