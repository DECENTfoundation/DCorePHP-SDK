<?php

namespace DCorePHP\Model\Asset;

use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\ChainObject;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Validation;

class Asset
{
    public const ROUNDING_UP = 1;
    public const ROUNDING_DOWN = 2;

    /** @var ChainObject */
    private $id;
    /** @var string */
    private $symbol = 'UIA';
    /** @var int */
    private $precision = 0;
    /** @var string */
    private $issuer;
    /** @var string */
    private $description;
    /** @var AssetOptions */
    private $options;
    /** @var ChainObject */
    private $dataId;

    public function __construct()
    {
        $this->setOptions(new AssetOptions());
    }

    /**
     * @param int $amount
     * @param int $roundingMode ROUNDING_UP | ROUNDING_DOWN
     * @return AssetAmount
     * @throws ValidationException
     */
    public function convertFromDct(int $amount, int $roundingMode = self::ROUNDING_UP): AssetAmount
    {
        return $this->convert($amount, $this->getId(), $roundingMode);
    }

    /**
     * @param int $amount
     * @param int $roundingMode ROUNDING_UP | ROUNDING_DOWN
     * @return AssetAmount
     * @throws ValidationException
     */
    public function convertToDct(int $amount, int $roundingMode = self::ROUNDING_UP): AssetAmount
    {
        return $this->convert($amount, new ChainObject('1.3.0'), $roundingMode);
    }

    /**
     * @param int $amount
     * @param ChainObject $toAssetId
     * @param int $roundingMode ROUNDING_UP | ROUNDING_DOWN
     * @return AssetAmount
     * @throws ValidationException
     */
    private function convert(int $amount, ChainObject $toAssetId, int $roundingMode): AssetAmount
    {
        $quote = $this->getOptions()->getExchangeRate()->getQuote();
        $base = $this->getOptions()->getExchangeRate()->getBase();

        foreach (
            [
                [$quote->getAmount(), [new GreaterThan(['value' => 0])]],
                [$base->getAmount(), [new GreaterThan(['value' => 0])]],
            ] as $validations
        ) {
            [$subject, $constraints] = $validations;
            if (($violations = Validation::createValidator()->validate($subject, $constraints))->count() > 0) {
                throw new ValidationException($violations);
            }
        }

        $roundingFunction = $roundingMode === self::ROUNDING_UP ? 'ceil' : 'floor';

        switch ($toAssetId->getId()) {
            case $quote->getAssetId():
                $convertedAmount = $roundingFunction(bcdiv(bcmul($quote->getAmount(), $amount), $base->getAmount(), 10));
                break;
            case $base->getAssetId():
                $convertedAmount = $roundingFunction(bcdiv(bcmul($base->getAmount(), $amount), $quote->getAmount(), 10));
                break;
            default:
                throw new \Exception("Cannot convert {$this->id} with {$this->symbol}:{$toAssetId}");
        }

        return (new AssetAmount())->setAmount($convertedAmount)->setAssetId($toAssetId);
    }

    public function getId(): ?ChainObject
    {
        return $this->id;
    }

    public function setId($id): self
    {
        if (is_string($id)) {
            $id = new ChainObject($id);
        }
        $this->id = $id;

        return $this;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function setSymbol(string $symbol): self
    {
        $this->symbol = $symbol;

        return $this;
    }

    public function getPrecision(): int
    {
        return $this->precision;
    }

    public function setPrecision(int $precision): self
    {
        $this->precision = $precision;

        return $this;
    }

    public function getIssuer(): ?string
    {
        return $this->issuer;
    }

    public function setIssuer(string $issuer): self
    {
        $this->issuer = $issuer;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getOptions(): AssetOptions
    {
        return $this->options;
    }

    public function setOptions(AssetOptions $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function getDataId(): ?ChainObject
    {
        return $this->dataId;
    }

    public function setDataId($dataId): self
    {
        if (is_string($dataId)) {
            $dataId = new ChainObject($dataId);
        }
        $this->dataId = $dataId;

        return $this;
    }
}
