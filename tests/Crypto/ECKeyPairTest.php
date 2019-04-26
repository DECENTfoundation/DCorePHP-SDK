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
            ECKeyPair::fromPrivate('13a9b612a993aaf5b6f9de0b4a9a373d8ff3f19036bef5d7d51bad55820563eb')->getPrivate()->toWif()
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
            ECKeyPair::fromCompressedPublicKey('02cf2c986e78776c21e5a75d42dd858dfe8ef06cf663ee0e8363db89ad5999d84f')->getPublic()->toAddress()
        );

        $this->assertEquals(
            DCoreSDKTest::PUBLIC_KEY_2,
            ECKeyPair::fromCompressedPublicKey('0242e0431837a5843252a0ecfab9565bdb20bdb0fc4c88398455f64589fdc7b93d')->getPublic()->toAddress()
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
            ECKeyPair::fromPrivate('13a9b612a993aaf5b6f9de0b4a9a373d8ff3f19036bef5d7d51bad55820563eb')->getPrivate()->toWif()
        );
    }

    public function testGetPublic(): void
    {
        $this->assertEquals(
            DCoreSDKTest::PUBLIC_KEY_1,
            ECKeyPair::fromCompressedPublicKey('02cf2c986e78776c21e5a75d42dd858dfe8ef06cf663ee0e8363db89ad5999d84f')->getPublic()->toAddress()
        );

        $this->assertEquals(
            DCoreSDKTest::PUBLIC_KEY_1,
            ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1)->getPublic()->toAddress()
        );
    }

    public function testSignature(): void
    {
        $this->assertEquals(
            '1f660d8c65db7d586a4f33ce0161c94e79880abeff119961ec5b5713062b870f7739dd7a996f8741ce3bad7566a8e3b261637f54e47364c6c8c5847fd328f53be2',
            ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_2)->signature('366507de4ced67d6b55c012720a107000000000000232200000000000201b09aea2900000000000100000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000c0000000041686f79205048500000', '17401602b201b3c45a3ad98afc6fb458f91f519bd30d1058adf6f2bed66376bc')
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