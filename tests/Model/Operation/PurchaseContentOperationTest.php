<?php

namespace DCorePHPTests\Model\Operation;

use DCorePHP\Crypto\Credentials;
use DCorePHP\Crypto\ECKeyPair;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Content\ContentObject;
use DCorePHP\Model\Content\PricePerRegion;
use DCorePHP\Model\Operation\PurchaseContentOperation;
use DCorePHPTests\DCoreSDKTest;
use PHPUnit\Framework\TestCase;

class PurchaseContentOperationTest extends TestCase
{
    /**
     * @throws \DCorePHP\Exception\ValidationException
     * @throws \Exception
     */
    public function testToBytes(): void
    {
        $credentials = new Credentials(new ChainObject('1.2.34'), ECKeyPair::fromBase58(DCoreSDKTest::PRIVATE_KEY_1));
        $content = (new ContentObject())
            ->setAuthor('1.2.34')
            ->setPrice((new PricePerRegion())->setPrices([1 => (new AssetAmount())->setAssetId(new ChainObject('1.3.0'))->setAmount(1000)]))
            ->setURI('http://decent.ch?testtime=1552986916');
        $operation = new PurchaseContentOperation($credentials, $content);

        $this->assertEquals(
            '1500000000000000000024687474703a2f2f646563656e742e63683f7465737474696d653d3135353239383639313622e803000000000000000100000002302e',
            $operation->toBytes()
        );
    }
}