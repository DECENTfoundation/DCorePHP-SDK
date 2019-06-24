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
            '7a726341c13e80a7e7199003c452e98e27656c777c3ec150463c153a94edeae869975603adcb6f0aa21fd18d05bff0cd248979560b6565e9b393f0668cb80bb6',
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
            'a331f7b9f710c91c5c54449deb6f49ff6d5429dee189a9bd3b1b3f573cd246c0',
            $encryptedMessage
        );
    }

    public function testDecryptWithChecksum()
    {
        $encryptedMessage = $this->crypto->decryptWithChecksum(
            'a331f7b9f710c91c5c54449deb6f49ff6d5429dee189a9bd3b1b3f573cd246c0',
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
