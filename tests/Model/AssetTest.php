<?php

namespace DCorePHPTests\Model;

use DCorePHP\Model\Asset\Asset;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\Asset\AssetOptions;
use DCorePHP\Model\Asset\AssetOptionsExchangeRate;
use DCorePHP\Model\ChainObject;
use PHPUnit\Framework\TestCase;

class AssetTest extends TestCase
{
    public function testConversionToDctRoundingUp(): void
    {
        $testAsset = new Asset();
        $testAsset
            ->setId(new ChainObject('1.3.1'))
            ->setOptions(
                (new AssetOptions())
                    ->setExchangeRate(
                        (new AssetOptionsExchangeRate())
                            ->setBase((new AssetAmount())->setAmount(10))
                            ->setQuote((new AssetAmount())->setAmount(3)->setAssetId(new ChainObject('1.3.1')))
                    )
                    ->setExchangeable(true)
            )
        ;

        $this->assertEquals(17, $testAsset->convertToDct(5)->getAmount());
    }

    public function testConversionToDctRoundingUpSwitchedBaseAndQuote(): void
    {
        $testAsset = new Asset();
        $testAsset
            ->setId(new ChainObject('1.3.1'))
            ->setOptions(
                (new AssetOptions())
                    ->setExchangeRate(
                        (new AssetOptionsExchangeRate())
                            ->setBase((new AssetAmount())->setAmount(3)->setAssetId(new ChainObject('1.3.1')))
                            ->setQuote((new AssetAmount())->setAmount(10))
                    )
                    ->setExchangeable(true)
            )
        ;

        $this->assertEquals(17, $testAsset->convertToDct(5)->getAmount());
    }

    public function testConversionFromDctRoundingUp(): void
    {
        $testAsset = new Asset();
        $testAsset
            ->setId(new ChainObject('1.3.1'))
            ->setOptions(
                (new AssetOptions())
                    ->setExchangeRate(
                        (new AssetOptionsExchangeRate())
                            ->setBase((new AssetAmount())->setAmount(10))
                            ->setQuote((new AssetAmount())->setAmount(3)->setAssetId(new ChainObject('1.3.1')))
                    )
                    ->setExchangeable(true)
            )
        ;

        $this->assertEquals(2, $testAsset->convertFromDct(5)->getAmount());
    }

    public function testConversionFromDctRoundingUpSwitchedBaseAndQuote(): void
    {
        $testAsset = new Asset();
        $testAsset
            ->setId(new ChainObject('1.3.1'))
            ->setOptions(
                (new AssetOptions())
                    ->setExchangeRate(
                        (new AssetOptionsExchangeRate())
                            ->setBase((new AssetAmount())->setAmount(3)->setAssetId(new ChainObject('1.3.1')))
                            ->setQuote((new AssetAmount())->setAmount(10))
                    )
                    ->setExchangeable(true)
            )
        ;

        $this->assertEquals(2, $testAsset->convertFromDct(5)->getAmount());
    }

    public function testConversionToDctRoundingDown(): void
    {
        $testAsset = new Asset();
        $testAsset
            ->setId(new ChainObject('1.3.1'))
            ->setOptions(
                (new AssetOptions())
                    ->setExchangeRate(
                        (new AssetOptionsExchangeRate())
                            ->setBase((new AssetAmount())->setAmount(10))
                            ->setQuote((new AssetAmount())->setAmount(3)->setAssetId(new ChainObject('1.3.1')))
                    )
                    ->setExchangeable(true)
            )
        ;

        $this->assertEquals(16, $testAsset->convertToDct(5, Asset::ROUNDING_DOWN)->getAmount());
    }

    public function testConversionToDctRoundingDownSwitchedBaseAndQuote(): void
    {
        $testAsset = new Asset();
        $testAsset
            ->setId(new ChainObject('1.3.1'))
            ->setOptions(
                (new AssetOptions())
                    ->setExchangeRate(
                        (new AssetOptionsExchangeRate())
                            ->setBase((new AssetAmount())->setAmount(3)->setAssetId(new ChainObject('1.3.1')))
                            ->setQuote((new AssetAmount())->setAmount(10))
                    )
                    ->setExchangeable(true)
            )
        ;

        $this->assertEquals(16, $testAsset->convertToDct(5, Asset::ROUNDING_DOWN)->getAmount());
    }

    public function testConversionFromDctRoundingDown(): void
    {
        $testAsset = new Asset();
        $testAsset
            ->setId(new ChainObject('1.3.1'))
            ->setOptions(
                (new AssetOptions())
                    ->setExchangeRate(
                        (new AssetOptionsExchangeRate())
                            ->setBase((new AssetAmount())->setAmount(10))
                            ->setQuote((new AssetAmount())->setAmount(3)->setAssetId(new ChainObject('1.3.1')))
                    )
                    ->setExchangeable(true)
            )
        ;

        $this->assertEquals(1, $testAsset->convertFromDct(5, Asset::ROUNDING_DOWN)->getAmount());
    }

    public function testConversionFromDctRoundingDownSwitchedBaseAndQuote(): void
    {
        $testAsset = new Asset();
        $testAsset
            ->setId(new ChainObject('1.3.1'))
            ->setOptions(
                (new AssetOptions())
                    ->setExchangeRate(
                        (new AssetOptionsExchangeRate())
                            ->setBase((new AssetAmount())->setAmount(3)->setAssetId(new ChainObject('1.3.1')))
                            ->setQuote((new AssetAmount())->setAmount(10))
                    )
                    ->setExchangeable(true)
            )
        ;

        $this->assertEquals(1, $testAsset->convertFromDct(5, Asset::ROUNDING_DOWN)->getAmount());
    }

    /**
     * @expectedException \DCorePHP\Exception\ValidationException
     */
    public function testConvertBaseException(): void
    {
        $testAsset = new Asset();
        $testAsset
            ->setId(new ChainObject('1.3.1'))
            ->setOptions(
                (new AssetOptions())
                    ->setExchangeRate(
                        (new AssetOptionsExchangeRate())
                            ->setBase((new AssetAmount())->setAmount(-1))
                            ->setQuote((new AssetAmount())->setAmount(3)->setAssetId(new ChainObject('1.3.1')))
                    )
                    ->setExchangeable(true)
            )
        ;

        $this->assertEquals(17, $testAsset->convertToDct(5)->getAmount());
    }

    /**
     * @expectedException \DCorePHP\Exception\ValidationException
     */
    public function testConvertQuoteException(): void
    {
        $testAsset = new Asset();
        $testAsset
            ->setId(new ChainObject('1.3.1'))
            ->setOptions(
                (new AssetOptions())
                    ->setExchangeRate(
                        (new AssetOptionsExchangeRate())
                            ->setBase((new AssetAmount())->setAmount(10))
                            ->setQuote((new AssetAmount())->setAmount(-1)->setAssetId(new ChainObject('1.3.1')))
                    )
                    ->setExchangeable(true)
            )
        ;

        $this->assertEquals(17, $testAsset->convertToDct(5)->getAmount());
    }
}