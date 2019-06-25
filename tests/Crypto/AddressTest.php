<?php

namespace DCorePHPTests\Crypto;

use DCorePHP\Crypto\Address;
use DCorePHP\Crypto\PublicKey;
use DCorePHPTests\DCoreSDKTest;
use PHPUnit\Framework\TestCase;

class AddressTest extends TestCase
{
    public function testIsValid(): void
    {
        $this->assertTrue(Address::isValid(DCoreSDKTest::PUBLIC_KEY_1));
        $this->assertFalse(Address::isValid('SomeInvalidPublicKey'));
    }

    public function testEncodeDecode(): void
    {
        $this->assertEquals(
            DCoreSDKTest::PUBLIC_KEY_1,
            Address::decode(DCoreSDKTest::PUBLIC_KEY_1)->encode()
        );

        $this->assertEquals(
            DCoreSDKTest::PUBLIC_KEY_2,
            Address::decode(DCoreSDKTest::PUBLIC_KEY_2)->encode()
        );
    }

    public function testDecodeCheckNull(): void
    {
        $this->assertInstanceOf(Address::class, Address::decodeCheckNull(DCoreSDKTest::PUBLIC_KEY_1));
        $this->assertNull(Address::decodeCheckNull('DCT1111111111111111111111111111111114T1Anm'));
    }

    public function testCalculateChecksum(): void
    {
        $class = new \ReflectionClass(Address::class);
        $method = $class->getMethod('calculateChecksum');
        $method->setAccessible(true);

        $address = Address::decode(DCoreSDKTest::PUBLIC_KEY_1);
        $checksum = $method->invokeArgs($address, [PublicKey::fromWif(DCoreSDKTest::PUBLIC_KEY_1)->toCompressedPublicKey()]);

        $this->assertEquals('133e4dec', $checksum);
    }

    public function testGetPublicKeyPoint(): void
    {
        $this->assertEquals(
            DCoreSDKTest::PUBLIC_KEY_1,
            PublicKey::fromPoint(Address::decode(DCoreSDKTest::PUBLIC_KEY_1)->getPublicKeyPoint())->toAddress()
        );
    }

    public function testGetPrefix(): void
    {
        $this->assertEquals(
            substr(DCoreSDKTest::PUBLIC_KEY_1, 0, 3),
            Address::decode(DCoreSDKTest::PUBLIC_KEY_1)->getPrefix()
        );
    }
}
