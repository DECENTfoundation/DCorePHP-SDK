<?php

namespace DCorePHPTests\Model\Operation;

use DCorePHP\Crypto\Credentials;
use DCorePHP\Crypto\ECKeyPair;
use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Operation\PurchaseContentOperation;
use DCorePHP\Model\PubKey;
use DCorePHPTests\DCoreSDKTest;
use Exception;
use PHPUnit\Framework\TestCase;

class PurchaseContentOperationTest extends TestCase
{
    /**
     * @throws ValidationException
     * @throws Exception
     */
    public function testToBytes(): void
    {
        $credentials = new Credentials(new ChainObject('1.2.34'), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));

        $operation = new PurchaseContentOperation();
        $operation
            ->setConsumer($credentials->getAccount())
            ->setPrice((new AssetAmount())->setAssetId(new ChainObject('1.3.0'))->setAmount(1000))
            ->setUri('http://decent.ch?testtime=1552986916')
            ->setPublicElGamal(parse_url('http://decent.ch?testtime=1552986916', PHP_URL_SCHEME) !== 'ipfs' ? new PubKey() : (new PubKey())->setPubKey($credentials->getKeyPair()->getPrivate()->toElGamalPublicKey()))
            ->setRegionCode(2);

        $this->assertEquals(
            '1500000000000000000024687474703a2f2f646563656e742e63683f7465737474696d653d3135353239383639313622e803000000000000000200000002302e',
            $operation->toBytes()
        );
    }
}