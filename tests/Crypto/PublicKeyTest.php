<?php

namespace DCorePHPTests\Crypto;

use DCorePHP\Crypto\PrivateKey;
use DCorePHP\Crypto\PublicKey;
use DCorePHPTests\DCoreSDKTest;
use PHPUnit\Framework\TestCase;

class PublicKeyTest extends TestCase
{
    public function testFromWif(): void
    {
        $this->assertEquals(
            DCoreSDKTest::PUBLIC_KEY_1,
            PublicKey::fromWif(DCoreSDKTest::PUBLIC_KEY_1)->toAddress()
        );
    }

    public function testFromPoint(): void
    {
        $this->assertEquals(
            DCoreSDKTest::PUBLIC_KEY_1,
            PublicKey::fromPoint(PublicKey::fromWif(DCoreSDKTest::PUBLIC_KEY_1)->toPoint())->toAddress()
        );

        $this->assertEquals(
            DCoreSDKTest::PUBLIC_KEY_2,
            PublicKey::fromPoint(PublicKey::fromWif(DCoreSDKTest::PUBLIC_KEY_2)->toPoint())->toAddress()
        );
    }

    public function testCalculateChecksum(): void
    {
        $class = new \ReflectionClass(PublicKey::class);
        $method = $class->getMethod('calculateChecksum');
        $method->setAccessible(true);

        $publicKey = PublicKey::fromWif(DCoreSDKTest::PUBLIC_KEY_1);
        $checksum = $method->invokeArgs($publicKey, [$publicKey->toCompressedPublicKey()]);

        $this->assertEquals('133e4dec', $checksum);
    }

    public function testFromPrivateKey(): void
    {
        $this->assertEquals(
            DCoreSDKTest::PUBLIC_KEY_1,
            PublicKey::fromPrivateKey(PrivateKey::fromWif(DCoreSDKTest::PRIVATE_KEY_1))->toAddress()
        );
    }

    public function testToCompressedPublicKey(): void
    {
        $this->assertEquals(
            '039cf1a65f567cf37066fbfc13419e16c47953a7194d621ceb2d00f3796f73f43c',
            PublicKey::fromWif(DCoreSDKTest::PUBLIC_KEY_1)->toCompressedPublicKey()
        );
    }

    public function testToAddress(): void
    {
        $this->assertEquals(
            DCoreSDKTest::PUBLIC_KEY_1,
            PublicKey::fromWif(DCoreSDKTest::PUBLIC_KEY_1)->toAddress()
        );
    }

    public function testToPoint(): void
    {
        $this->assertEquals(
            DCoreSDKTest::PUBLIC_KEY_1,
            PublicKey::fromPoint(PublicKey::fromWif(DCoreSDKTest::PUBLIC_KEY_1)->toPoint())->toAddress()
        );
    }
}
