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
            '1621731919311203368641307719638422813471230230924769569362734193819060263601623598524770244954588523184349831064132376629002270412248152773445054178433489',
            (PrivateKey::fromWif(DCoreSDKTest::PRIVATE_KEY_1))->toElGamalPrivateKey()
        );
    }

    public function testToElGamalPublicKey(): void
    {
        $this->assertEquals(
            '9091018935637217748855729752308116297145407124369997101758003751748289463020377607571037254627966187389667523178005882720792456627319757295042800482773544',
            (PrivateKey::fromWif(DCoreSDKTest::PRIVATE_KEY_1))->toElGamalPublicKey()
        );
    }

    public function testGetKey(): void
    {
        $this->assertEquals(
            '8f16d23d0e570672ed31e22eb1c7ef68faa7f0fc1407723367b6e94862177c79',
            PrivateKey::fromWif(DCoreSDKTest::PRIVATE_KEY_1)->getKey()
        );
    }
}
