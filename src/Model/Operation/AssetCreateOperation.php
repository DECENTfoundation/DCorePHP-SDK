<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\Asset;
use DCorePHP\Model\Asset\AssetOptions;
use DCorePHP\Model\Asset\MonitoredAssetOptions;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;
use DCorePHP\Utils\Math;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Validation;

class AssetCreateOperation extends BaseOperation
{
    public const OPERATION_TYPE = 3;
    public const OPERATION_NAME = 'asset_create';

    /** @var ChainObject */
    private $issuer;
    /** @var string */
    private $symbol;
    /** @var int */
    private $precision;
    /** @var string */
    private $description;
    /** @var AssetOptions */
    private $options;
    /** @var MonitoredAssetOptions */
    private $monitoredOptions;

    /**
     * @return ChainObject
     */
    public function getIssuer(): ChainObject
    {
        return $this->issuer;
    }

    /**
     * @param ChainObject | string $issuer
     * @return AssetCreateOperation
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setIssuer($issuer): AssetCreateOperation
    {
        if (is_string($issuer)) {
            $issuer = new ChainObject($issuer);
        }
        $this->issuer = $issuer;

        return $this;
    }

    /**
     * @return string
     */
    public function getSymbol(): string
    {
        return $this->symbol;
    }

    /**
     * @param string $symbol
     * @return AssetCreateOperation
     * @throws ValidationException
     */
    public function setSymbol(string $symbol): AssetCreateOperation
    {
        [$subject, $constraints] = [Asset::isValidSymbol($symbol), [new IsTrue(['message' => "Invalid asset symbol: ${symbol}"])]];
        if (($violations = Validation::createValidator()->validate($subject, $constraints))->count() > 0) {
            throw new ValidationException($violations);
        }

        $this->symbol = $symbol;

        return $this;
    }

    /**
     * @return int
     */
    public function getPrecision(): int
    {
        return $this->precision;
    }

    /**
     * @param int $precision
     * @return AssetCreateOperation
     */
    public function setPrecision(int $precision): AssetCreateOperation
    {
        $this->precision = $precision;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return AssetCreateOperation
     * @throws ValidationException
     */
    public function setDescription(string $description): AssetCreateOperation
    {
        [$subject, $constraints] = [strlen($description), [
            new LessThanOrEqual([
                'value' => 1000,
                'message' => 'Description cannot be longer then 1000 chars'])]];
        if (($violations = Validation::createValidator()->validate($subject, $constraints))->count() > 0) {
            throw new ValidationException($violations);
        }

        $this->description = $description;

        return $this;
    }

    /**
     * @return AssetOptions
     */
    public function getOptions(): AssetOptions
    {
        return $this->options;
    }

    /**
     * @param AssetOptions $options
     * @return AssetCreateOperation
     */
    public function setOptions(AssetOptions $options): AssetCreateOperation
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @return MonitoredAssetOptions | null
     */
    public function getMonitoredOptions(): ?MonitoredAssetOptions
    {
        return $this->monitoredOptions;
    }

    /**
     * @param MonitoredAssetOptions | null $monitoredOptions
     * @return AssetCreateOperation
     */
    public function setMonitoredOptions(?MonitoredAssetOptions $monitoredOptions): AssetCreateOperation
    {
        $this->monitoredOptions = $monitoredOptions;

        return $this;
    }

    public function toArray(): array
    {
        return [
            self::OPERATION_TYPE,
            [
                'fee' => $this->getFee()->toArray(),
                'issuer' => $this->getIssuer()->getId(),
                'symbol' => $this->getSymbol(),
                'precision' => $this->getPrecision(),
                'description' => $this->getDescription(),
                'options' => $this->getOptions()->toArray(),
//                'monitored_asset_opts' => $this->getMonitoredOptions() ? $this->getMonitoredOptions()->toArray() : ''
                'extensions' => $this->getExtensions()
            ],
        ];
    }

    public function toBytes(): string
    {
        return implode('', [
            $this->getTypeBytes(),
            $this->getFee()->toBytes(),
            $this->getIssuer()->toBytes(),
            Math::writeUnsignedVarIntHex(sizeof(Math::stringToByteArray($this->getSymbol()))),
            Math::byteArrayToHex(Math::stringToByteArray($this->getSymbol())),
            str_pad(Math::gmpDecHex($this->getPrecision()), 2, '0', STR_PAD_LEFT),
            Math::writeUnsignedVarIntHex(sizeof(Math::stringToByteArray($this->getDescription()))),
            Math::byteArrayToHex(Math::stringToByteArray($this->getDescription())),
            $this->getOptions()->toBytes(),
            $this->getMonitoredOptions() ? $this->getMonitoredOptions()->toBytes() : '00',
            '01',
            // TODO: Extensions Array
            $this->getExtensions() ? '01' : '00'
        ]);
    }
}
