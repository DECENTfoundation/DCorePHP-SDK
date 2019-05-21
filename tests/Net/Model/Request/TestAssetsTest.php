<?php

namespace DCorePHPTests\Net\Model\Request;

use DCorePHP\Model\Asset\Asset;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\Asset\AssetOptions;
use DCorePHP\Model\Asset\ExchangeRate;
use DCorePHP\Model\ChainObject;
use DCorePHP\Net\Model\Request\GetAssets;
use DCorePHP\Net\Model\Response\BaseResponse;
use PHPUnit\Framework\TestCase;

class TestAssetsTest extends TestCase
{
    public function testResponseToModel()
    {
        $getAssets = new GetAssets([new ChainObject('1.3.0')]);
        $assets = $getAssets::responseToModel(new BaseResponse(json_encode([
            'id' => 123, 'result' => [[
                'id' => '1.3.0',
                'symbol' => 'DCT',
                'precision' => 8,
                'issuer' => '1.2.1',
                'description' => 'some testing description',
                'options' => [
                    'max_supply' => '7319777577456900',
                    'core_exchange_rate' => [
                        'base' => [
                            'amount' => 1,
                            'asset_id' => '1.3.0',
                        ],
                        'quote' => [
                            'amount' => 1,
                            'asset_id' => '1.3.0',
                        ],
                    ],
                    'is_exchangeable' => true,
                    'extensions' => [
                        [
                            'name' => 'test'
                        ]
                    ],
                ],
                'dynamic_asset_data_id' => '2.3.0',
            ]]])));

        $this->assertCount(1, $assets);

        /** @var Asset $asset */
        $asset = reset($assets);
        $this->assertInstanceOf(Asset::class, $asset);
        $this->assertEquals('1.3.0', $asset->getId()->getId());
        $this->assertEquals('DCT', $asset->getSymbol());
        $this->assertEquals(8, $asset->getPrecision());
        $this->assertEquals('1.2.1', $asset->getIssuer());
        $this->assertEquals('some testing description', $asset->getDescription());
        $this->assertEquals('2.3.0', $asset->getDataId());

        $options = $asset->getOptions();
        $this->assertInstanceOf(AssetOptions::class, $options);
        $this->assertEquals('7319777577456900', $options->getMaxSupply());
        $this->assertTrue($options->isExchangeable());
        $this->assertEquals([['name' => 'test']], $options->getExtensions());

        $exchangeRate = $options->getExchangeRate();
        $this->assertInstanceOf(ExchangeRate::class, $exchangeRate);

        $base = $exchangeRate->getBase();
        $this->assertInstanceOf(AssetAmount::class, $base);
        $this->assertEquals(1, $base->getAmount());
        $this->assertEquals('1.3.0', $base->getAssetId());

        $quote = $exchangeRate->getQuote();
        $this->assertInstanceOf(AssetAmount::class, $quote);
        $this->assertEquals(1, $quote->getAmount());
        $this->assertEquals('1.3.0', $quote->getAssetId());
    }
}
