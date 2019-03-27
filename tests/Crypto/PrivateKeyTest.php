<?php

namespace DCorePHPTests\Crypto;

use DCorePHP\Crypto\PrivateKey;
use DCorePHP\Crypto\PublicKey;
use DCorePHPTests\DCoreSDKTest;
use PHPUnit\Framework\TestCase;

class PrivateKeyTest extends TestCase
{
    public function testFromWif(): void
    {
        $this->assertEquals(
            DCoreSDKTest::PRIVATE_KEY_1,
            PrivateKey::fromWif(DCoreSDKTest::PRIVATE_KEY_1)->toWif()
        );
    }

    public function testFromHex(): void
    {
        $this->assertEquals(
            '5J4eFhjREJA7hKG6KcvHofHMXyGQZCDpQE463PAaKo9xXY6UDPq',
            PrivateKey::fromHex('20991828d456b389d0768ed7fb69bf26b9bb87208dd699ef49f10481c20d3e18')->toWif()
        );
    }

    public function testFromBrainKey(): void
    {
        $this->assertEquals(
            '5K5uAt9rPndBfEWth2fgBjDQx4gtEX9jRBB9unbo4VdVAx8jems',
            PrivateKey::fromBrainKey('FAILING AHIMSA INFLECT RETOUR OVERWEB PODIUM UNPILED DEVELIN BATED PUDGILY EXUDATE PASTEL ISOTOPY OSOPHY SELLAR SWAYING')->toWif()
        );
    }

    public function testToWif(): void
    {
        $this->assertEquals(
            DCoreSDKTest::PRIVATE_KEY_1,
            PrivateKey::fromWif(DCoreSDKTest::PRIVATE_KEY_1)->toWif()
        );
    }

    public function testToHex(): void
    {
        $this->assertEquals(
            '20991828d456b389d0768ed7fb69bf26b9bb87208dd699ef49f10481c20d3e18',
            PrivateKey::fromHex('20991828d456b389d0768ed7fb69bf26b9bb87208dd699ef49f10481c20d3e18')->toHex()
        );
    }

    public function testToPublicKeyPoint(): void
    {
        $this->assertEquals(
            DCoreSDKTest::PUBLIC_KEY_1,
            PublicKey::fromPoint(PrivateKey::fromWif(DCoreSDKTest::PRIVATE_KEY_1)->toPublicKeyPoint())->toAddress()
        );
    }

    public function testToPublicKey(): void
    {
        $this->assertEquals(
            DCoreSDKTest::PUBLIC_KEY_1,
            PrivateKey::fromWif(DCoreSDKTest::PRIVATE_KEY_1)->toPublicKey()->toAddress()
        );
    }

    public function testToElGamalPrivateKey(): void
    {
        $this->assertEquals(
            '8149734503494312909116126763927194608124629667940168421251424974828815164868905638030541425377704620941193711130535974967507480114755414928915429397074890',
            (PrivateKey::fromWif(DCoreSDKTest::PRIVATE_KEY_1))->toElGamalPrivateKey()
        );
    }

    public function testToElGamalPublicKey(): void
    {
        $this->assertEquals(
            '5182545488318095000498180568539728214545472470974958338942426759510121851708530625921436777555517288139787965253547588340803542762268721656138876002028437',
            (PrivateKey::fromWif(DCoreSDKTest::PRIVATE_KEY_1))->toElGamalPublicKey()
        );
    }

    public function testGetKey(): void
    {
        $this->assertEquals(
            '6a580c0110d83829afedbf339d64dfa5fb5b21b011445ba792f0f2bb17273473',
            PrivateKey::fromWif(DCoreSDKTest::PRIVATE_KEY_1)->getKey()
        );
    }
}
