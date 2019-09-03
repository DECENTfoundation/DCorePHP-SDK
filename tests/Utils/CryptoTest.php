<?php

namespace DCorePHPTests\Utils;

use DCorePHP\Crypto\ECKeyPair;
use DCorePHP\Utils\Crypto;
use DCorePHP\Crypto\PrivateKey;
use DCorePHP\Crypto\PublicKey;
use DCorePHPTests\DCoreSDKTest;
use PHPUnit\Framework\TestCase;

class CryptoTest extends TestCase
{
    /** @var Crypto */
    private $crypto;

    protected function setUp()
    {
        $this->crypto = Crypto::getInstance();
    }

    /**
     * @throws \Exception
     */
    public function testGetSharedSecret()
    {
        foreach (
            [
                [
                    DCoreSDKTest::PRIVATE_KEY_1,
                    DCoreSDKTest::PUBLIC_KEY_2,
                    '735604672334802432',
                    '7a726341c13e80a7e7199003c452e98e27656c777c3ec150463c153a94edeae869975603adcb6f0aa21fd18d05bff0cd248979560b6565e9b393f0668cb80bb6'
                ],
                [
                    '5Jd7zdvxXYNdUfnEXt5XokrE3zwJSs734yQ36a1YaqioRTGGLtn',
                    'DCT6bVmimtYSvWQtwdrkVVQGHkVsTJZVKtBiUqf4YmJnrJPnk89QP',
                    '10872523688190906880',
                    'b96c3a08a0daceedf59da2368258790e79f8434b64dce3fc970284144b42634fa6755db65e206aac53bb7a6fad3854abb0f04a04bdf37d1af70f4408ec44ae8a'
                ]
            ] as $item
        ) {
            [$private, $public, $nonce, $expected] = $item;
            $this->assertEquals($expected, $this->crypto->getSharedSecret(PrivateKey::fromWif($private), PublicKey::fromWif($public), $nonce));
        }
    }

    /**
     * @throws \Exception
     */
    public function testEncryptWithChecksum()
    {
        foreach (
            [
                [
                    'hello messaging api',
                    DCoreSDKTest::PRIVATE_KEY_1,
                    DCoreSDKTest::PUBLIC_KEY_2,
                    '17391111264393218816',
                    'a331f7b9f710c91c5c54449deb6f49ff6d5429dee189a9bd3b1b3f573cd246c0'
                ],
                [
                    'hello memo here i am',
                    '5Jd7zdvxXYNdUfnEXt5XokrE3zwJSs734yQ36a1YaqioRTGGLtn',
                    'DCT6bVmimtYSvWQtwdrkVVQGHkVsTJZVKtBiUqf4YmJnrJPnk89QP',
                    '10872523688190906880',
                    'b23f6afb8eb463704d3d752b1fd8fb804c0ce32ba8d18eeffc20a2312e7c60fa'
                ]
            ] as $item
        ) {
            [$message, $private, $public, $nonce, $expected] = $item;
            $this->assertEquals($expected, $this->crypto->encryptWithChecksum($message, PrivateKey::fromWif($private), PublicKey::fromWif($public), $nonce));
        }
    }

    /**
     * @throws \Exception
     */
    public function testDecryptWithChecksum()
    {
        foreach (
            [
                [
                    'a331f7b9f710c91c5c54449deb6f49ff6d5429dee189a9bd3b1b3f573cd246c0',
                    DCoreSDKTest::PRIVATE_KEY_1,
                    DCoreSDKTest::PUBLIC_KEY_2,
                    '17391111264393218816',
                    'hello messaging api',
                ],
                [
                    'b23f6afb8eb463704d3d752b1fd8fb804c0ce32ba8d18eeffc20a2312e7c60fa',
                    '5Jd7zdvxXYNdUfnEXt5XokrE3zwJSs734yQ36a1YaqioRTGGLtn',
                    'DCT6bVmimtYSvWQtwdrkVVQGHkVsTJZVKtBiUqf4YmJnrJPnk89QP',
                    '10872523688190906880',
                    'hello memo here i am',
                ],
            ] as $item
        ) {
            [$message, $private, $public, $nonce, $expected] = $item;
            $this->assertEquals($expected, $this->crypto->decryptWithChecksum($message, PrivateKey::fromWif($private), PublicKey::fromWif($public), $nonce));
        }
    }

    public function testEncryptAndDecrypt(): void
    {
        $encrypted = 'b23f6afb8eb463704d3d752b1fd8fb804c0ce32ba8d18eeffc20a2312e7c60fa';
        $plain = 'hello memo here i am';
        $nonce = '10872523688190906880';
        $to = PublicKey::fromWif('DCT6bVmimtYSvWQtwdrkVVQGHkVsTJZVKtBiUqf4YmJnrJPnk89QP');
        $key = ECKeyPair::fromBase58('5Jd7zdvxXYNdUfnEXt5XokrE3zwJSs734yQ36a1YaqioRTGGLtn');
        $encryptedMessage = $this->crypto->encryptWithChecksum($plain, $key->getPrivate(), $to, $nonce);
        $this->assertEquals($encrypted, $encryptedMessage);
        $decryptedMessage = $this->crypto->decryptWithChecksum($encrypted, $key->getPrivate(), $to, $nonce);
        $this->assertEquals($plain, $decryptedMessage);
    }
}
