<?php

namespace DCorePHPTests\Crypto;

use DCorePHP\Crypto\ECKeyPair;
use DCorePHP\Crypto\PrivateKey;
use DCorePHP\Crypto\PublicKey;
use DCorePHPTests\DCoreSDKTest;
use kornrunner\Secp256k1;
use PHPUnit\Framework\TestCase;

class ECKeyPairTest extends TestCase
{
    public function testFromPrivate(): void
    {
        $this->assertEquals(
            DCoreSDKTest::PRIVATE_KEY_1,
            ECKeyPair::fromPrivate('6a580c0110d83829afedbf339d64dfa5fb5b21b011445ba792f0f2bb17273473')->getPrivate()->toWif()
        );
    }

    public function testFromBase58(): void
    {
        $this->assertEquals(
            DCoreSDKTest::PRIVATE_KEY_1,
            ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1)->getPrivate()->toWif()
        );
    }

    public function testFromCompressedpublicKey(): void
    {
        $this->assertEquals(
            DCoreSDKTest::PUBLIC_KEY_1,
            ECKeyPair::fromCompressedPublicKey('02c03f8e840c1699fd7808c2bb858e249c688c5be8acf0a0c1c484ab0cfb27f0a8')->getPublic()->toAddress()
        );

        $this->assertEquals(
            DCoreSDKTest::PUBLIC_KEY_2,
            ECKeyPair::fromCompressedPublicKey('02e0ced80260630f641f61f6d6959f32b5c43b1a38be55666b98abfe8bafcc556b')->getPublic()->toAddress()
        );
    }

    public function testFromPublicKeyPoint(): void
    {
        $this->assertEquals(
            DCoreSDKTest::PUBLIC_KEY_1,
            ECKeyPair::fromPublicKeyPoint(
                PublicKey::fromWif(DCoreSDKTest::PUBLIC_KEY_1)->toPoint()
            )->getPublic()->toAddress()
        );
    }

    public function testRecoverFromSignature(): void
    {
        $signature = (new Secp256k1())->sign('bae69f774bd176065d1fb9aa7bf9441cfd19775643a22ea18e98b79fdecce15b', PrivateKey::fromWif(DCoreSDKTest::PRIVATE_KEY_1)->toHex());

        $this->assertEquals(
            PublicKey::fromWif(DCoreSDKTest::PUBLIC_KEY_1)->toCompressedPublicKey(),
            ECKeyPair::recoverFromSignature(31, $signature, 'bae69f774bd176065d1fb9aa7bf9441cfd19775643a22ea18e98b79fdecce15b')->getPublic()->toCompressedPublicKey()
        );
    }

    public function testGetGetPrivate(): void
    {
        $this->assertEquals(
            DCoreSDKTest::PRIVATE_KEY_1,
            ECKeyPair::fromPrivate('6a580c0110d83829afedbf339d64dfa5fb5b21b011445ba792f0f2bb17273473')->getPrivate()->toWif()
        );
    }

    public function testGetPublic(): void
    {
        $this->assertEquals(
            DCoreSDKTest::PUBLIC_KEY_1,
            ECKeyPair::fromCompressedPublicKey('02c03f8e840c1699fd7808c2bb858e249c688c5be8acf0a0c1c484ab0cfb27f0a8')->getPublic()->toAddress()
        );

        $this->assertEquals(
            DCoreSDKTest::PUBLIC_KEY_1,
            ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1)->getPublic()->toAddress()
        );
    }

    public function testSignature(): void
    {
        $this->assertEquals(
            '1f246288a9167fa71269bdb2ceaefa02334cecf1363db112b560476aad76ac1d5a66e17ffb6a209261aa6052142373955fb007ed815a46ee3cca1402862a85ae19',
            ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1)->signature('e061b1d5fbff20e80b5b010120a10700000000000022076d696b6565656501000000000102a01c045821676cfc191832ad22cc5c9ade0ea1760131c87ff2dd3fed2f13dd33010001000000000102a01c045821676cfc191832ad22cc5c9ade0ea1760131c87ff2dd3fed2f13dd33010002a01c045821676cfc191832ad22cc5c9ade0ea1760131c87ff2dd3fed2f13dd33030000000000000000000000000000000000000000')
        );
    }

    public function testEquals(): void
    {
        $ECKeyPair1 = ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1);
        $ECKeyPair2 = ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1);
        $ECKeyPair3 = ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_2);

        $this->assertTrue($ECKeyPair1->equals($ECKeyPair2));
        $this->assertFalse($ECKeyPair1->equals($ECKeyPair3));
    }
}