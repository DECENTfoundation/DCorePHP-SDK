<?php

namespace DCorePHPTests\Model;

use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\Asset;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\Asset\AssetOptions;
use DCorePHP\Model\Asset\ExchangeRate;
use DCorePHP\Model\ChainObject;
use PHPUnit\Framework\TestCase;

class AssetTest extends TestCase
{
    /**
     * @throws ValidationException
     */
    public function testConversionToDctRoundingUp(): void
    {
        $testAsset = new Asset();
        $testAsset
            ->setId(new ChainObject('1.3.1'))
            ->setOptions(
                (new AssetOptions())
                    ->setExchangeRate(new ExchangeRate((new AssetAmount())->setAmount(10), (new AssetAmount())->setAmount(3)->setAssetId(new ChainObject('1.3.1'))))
                    ->setExchangeable(true)
            )
        ;

        $this->assertEquals(17, $testAsset->convertToDct(5)->getAmount());
    }

    /**
     * @throws ValidationException
     */
    public function testConversionToDctRoundingUpSwitchedBaseAndQuote(): void
    {
        $testAsset = new Asset();
        $testAsset
            ->setId(new ChainObject('1.3.1'))
            ->setOptions(
                (new AssetOptions())
                    ->setExchangeRate(new ExchangeRate((new AssetAmount())->setAmount(3)->setAssetId(new ChainObject('1.3.1')), (new AssetAmount())->setAmount(10)))
                    ->setExchangeable(true)
            )
        ;

        $this->assertEquals(17, $testAsset->convertToDct(5)->getAmount());
    }

    /**
     * @throws ValidationException
     */
    public function testConversionFromDctRoundingUp(): void
    {
        $testAsset = new Asset();
        $testAsset
            ->setId(new ChainObject('1.3.1'))
            ->setOptions(
                (new AssetOptions())
                    ->setExchangeRate(new ExchangeRate((new AssetAmount())->setAmount(10), (new AssetAmount())->setAmount(3)->setAssetId(new ChainObject('1.3.1'))))
                    ->setExchangeable(true)
            )
        ;

        $this->assertEquals(2, $testAsset->convertFromDct(5)->getAmount());
    }

    /**
     * @throws ValidationException
     */
    public function testConversionFromDctRoundingUpSwitchedBaseAndQuote(): void
    {
        $testAsset = new Asset();
        $testAsset
            ->setId(new ChainObject('1.3.1'))
            ->setOptions(
                (new AssetOptions())
                    ->setExchangeRate(new ExchangeRate((new AssetAmount())->setAmount(3)->setAssetId(new ChainObject('1.3.1')), (new AssetAmount())->setAmount(10)))
                    ->setExchangeable(true)
            )
        ;

        $this->assertEquals(2, $testAsset->convertFromDct(5)->getAmount());
    }

    /**
     * @throws ValidationException
     */
    public function testConversionToDctRoundingDown(): void
    {
        $testAsset = new Asset();
        $testAsset
            ->setId(new ChainObject('1.3.1'))
            ->setOptions(
                (new AssetOptions())
                    ->setExchangeRate(new ExchangeRate((new AssetAmount())->setAmount(10), (new AssetAmount())->setAmount(3)->setAssetId(new ChainObject('1.3.1'))))
                    ->setExchangeable(true)
            )
        ;

        $this->assertEquals(16, $testAsset->convertToDct(5, Asset::ROUNDING_DOWN)->getAmount());
    }

    /**
     * @throws ValidationException
     */
    public function testConversionToDctRoundingDownSwitchedBaseAndQuote(): void
    {
        $testAsset = new Asset();
        $testAsset
            ->setId(new ChainObject('1.3.1'))
            ->setOptions(
                (new AssetOptions())
                    ->setExchangeRate(new ExchangeRate((new AssetAmount())->setAmount(3)->setAssetId(new ChainObject('1.3.1')), (new AssetAmount())->setAmount(10)))
                    ->setExchangeable(true)
            )
        ;

        $this->assertEquals(16, $testAsset->convertToDct(5, Asset::ROUNDING_DOWN)->getAmount());
    }

    /**
     * @throws ValidationException
     */
    public function testConversionFromDctRoundingDown(): void
    {
        $testAsset = new Asset();
        $testAsset
            ->setId(new ChainObject('1.3.1'))
            ->setOptions(
                (new AssetOptions())
                    ->setExchangeRate(new ExchangeRate((new AssetAmount())->setAmount(10), (new AssetAmount())->setAmount(3)->setAssetId(new ChainObject('1.3.1'))))
                    ->setExchangeable(true)
            )
        ;

        $this->assertEquals(1, $testAsset->convertFromDct(5, Asset::ROUNDING_DOWN)->getAmount());
    }

    /**
     * @throws ValidationException
     */
    public function testConversionFromDctRoundingDownSwitchedBaseAndQuote(): void
    {
        $testAsset = new Asset();
        $testAsset
            ->setId(new ChainObject('1.3.1'))
            ->setOptions(
                (new AssetOptions())
                    ->setExchangeRate(new ExchangeRate((new AssetAmount())->setAmount(3)->setAssetId(new ChainObject('1.3.1')), (new AssetAmount())->setAmount(10)))
                    ->setExchangeable(true)
            )
        ;

        $this->assertEquals(1, $testAsset->convertFromDct(5, Asset::ROUNDING_DOWN)->getAmount());
    }

    /**
     * @throws ValidationException
     */
    public function testConvertBaseException(): void
    {
        $this->expectException(ValidationException::class);
        $testAsset = new Asset();
        $testAsset
            ->setId(new ChainObject('1.3.1'))
            ->setOptions(
                (new AssetOptions())
                    ->setExchangeRate(new ExchangeRate((new AssetAmount())->setAmount(-1), (new AssetAmount())->setAmount(3)->setAssetId(new ChainObject('1.3.1'))))
                    ->setExchangeable(true)
            );

        $this->assertEquals(17, $testAsset->convertToDct(5)->getAmount());
    }

    /**
     * @throws ValidationException
     */
    public function testConvertQuoteException(): void
    {
        $this->expectException(ValidationException::class);
        $testAsset = new Asset();
        $testAsset
            ->setId(new ChainObject('1.3.1'))
            ->setOptions(
                (new AssetOptions())
                    ->setExchangeRate(new ExchangeRate((new AssetAmount())->setAmount(10), (new AssetAmount())->setAmount(-1)->setAssetId(new ChainObject('1.3.1'))))
                    ->setExchangeable(true)
            )
        ;

        $this->assertEquals(17, $testAsset->convertToDct(5)->getAmount());
    }
}