<?php

use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\Memo;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Operation\Transfer2;
use DCorePHP\Crypto\Address;
use DCorePHP\Utils\Crypto;
use DCorePHP\Crypto\PrivateKey;
use DCorePHP\Crypto\PublicKey;
use DCorePHPTests\DCoreSDKTest;
use PHPUnit\Framework\TestCase;

class TransferOperationTest extends TestCase
{
    public function testHydrate(): void
    {
        $transfer = new Transfer2();
        $transfer->hydrate([
            'fee' => [
                'amount' => 500000,
                'asset_id' => '1.3.0',
            ],
            'from' => '1.2.687',
            'to' => '1.2.34',
            'amount' => [
                'amount' => 100,
                'asset_id' => '1.3.44',
            ],
            'memo' => [
                'from' => 'DCT1111111111111111111111111111111114T1Anm',
                'to' => 'DCT1111111111111111111111111111111114T1Anm',
                'nonce' => 0,
                'message' => '00000000',
            ],
            'extensions' => [],
        ]);

        $this->assertEquals(500000, $transfer->getFee()->getAmount());
        $this->assertEquals('1.3.0', $transfer->getFee()->getAssetId());
        $this->assertEquals('1.2.687', $transfer->getFrom());
        $this->assertEquals('1.2.34', $transfer->getTo());
        $this->assertEquals(100, $transfer->getAmount()->getAmount());
        $this->assertEquals('1.3.44', $transfer->getAmount()->getAssetId());
        $this->assertNull($transfer->getMemo()->getFrom());
        $this->assertNull($transfer->getMemo()->getTo());
        $this->assertEquals(0, $transfer->getMemo()->getNonce());
        $this->assertEquals('00000000', $transfer->getMemo()->getMessage());
    }

    /**
     * @throws \DCorePHP\Exception\ValidationException
     * @throws Exception
     */
    public function testToBytes(): void
    {
        $senderPrivateKeyWif = DCoreSDKTest::PRIVATE_KEY_1;
        $senderPublicKeyWif = DCoreSDKTest::PUBLIC_KEY_1;
        $recipientPublicKeyWif = DCoreSDKTest::PUBLIC_KEY_2;

        $operation = new Transfer2();
        $operation
            ->setFrom('1.2.34')
            ->setTo('1.2.35')
            ->setAmount(
                (new AssetAmount())
                    ->setAssetId(new ChainObject('1.3.0'))
                    ->setAmount(1)
            )->setFee(
                (new AssetAmount())
                    ->setAssetId(new ChainObject('1.3.0'))
                    ->setAmount(0)
            )->setMemo(
                (new Memo())
                    ->setFrom(Address::decodeCheckNull($senderPublicKeyWif))
                    ->setTo(Address::decodeCheckNull($recipientPublicKeyWif))
                    ->setNonce('735604672334802432')
                    ->setMessage(Crypto::getInstance()->encryptWithChecksum(
                        'hello memo here i am',
                        PrivateKey::fromWif($senderPrivateKeyWif),
                        PublicKey::fromWif($recipientPublicKeyWif),
                        '735604672334802432'
                    ))
            );

        $this->assertEquals(
            '2700000000000000000022230000000000020101000000000000000001039cf1a65f567cf37066fbfc13419e16c47953a7194d621ceb2d00f3796f73f43c039cf1a65f567cf37066fbfc13419e16c47953a7194d621ceb2d00f3796f73f43c002ea2558d64350a206b05acc7415e7d0abea1477fc6426a8799f67123ba721eb701cbe24b3c8e233000',
            $operation->toBytes()
        );
    }
}
