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
            '4900706026858872097837520756965778751446408585467639790038966718789108333694970154933986499383532241954739437823282972649753164439694461437202683476240114',
            (PrivateKey::fromWif(DCoreSDKTest::PRIVATE_KEY_1))->toElGamalPrivateKey()
        );
    }

    public function testToElGamalPublicKey(): void
    {
        $this->assertEquals(
            '7207926897681710373605117452736110547874762674690538310951791205451363920749388117814244907786517865135638223584692338001010176270872455939805189981892960',
            (PrivateKey::fromWif(DCoreSDKTest::PRIVATE_KEY_1))->toElGamalPublicKey()
        );
    }

    public function testGetKey(): void
    {
        $this->assertEquals(
            '13a9b612a993aaf5b6f9de0b4a9a373d8ff3f19036bef5d7d51bad55820563eb',
            PrivateKey::fromWif(DCoreSDKTest::PRIVATE_KEY_1)->getKey()
        );
    }
}
