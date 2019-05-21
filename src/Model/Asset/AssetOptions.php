<?php

namespace DCorePHP\Model\Asset;

use DCorePHP\Exception\ValidationException;
use DCorePHP\Utils\Math;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Validation;

class AssetOptions
{
    public const MAX_SHARE_SUPPLY = '7319777577456890';

    /** @var string */
    private $maxSupply = self::MAX_SHARE_SUPPLY;
    /** @var ExchangeRate */
    private $exchangeRate;
    /** @var bool */
    private $exchangeable = true;
    /** @var array */
    private $extensions = [];

    public function __construct($fixedMaxSupply = false)
    {
        // TODO: Check TS Implementation
         $this->setExtensions([['is_fixed_max_supply' => $fixedMaxSupply]]);
         $this->exchangeRate = ExchangeRate::empty();
    }

    public function getMaxSupply(): string
    {
        return $this->maxSupply;
    }

    /**
     * @param string $maxSupply
     * @return AssetOptions
     */
    public function setMaxSupply(string $maxSupply): self
    {
        $this->maxSupply = $maxSupply;

        return $this;
    }

    public function getExchangeRate(): ExchangeRate
    {
        return $this->exchangeRate;
    }

    public function setExchangeRate(ExchangeRate $exchangeRate): self
    {
        $this->exchangeRate = $exchangeRate;

        return $this;
    }

    public function isExchangeable(): bool
    {
        return $this->exchangeable;
    }

    public function setExchangeable(bool $exchangeable): self
    {
        $this->exchangeable = $exchangeable;

        return $this;
    }

    public function getExtensions(): array
    {
        return $this->extensions;
    }

    public function setExtensions(array $extensions): self
    {
        $this->extensions = $extensions;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'max_supply' => $this->getMaxSupply(),
            'core_exchange_rate' => $this->getExchangeRate()->toArray(),
            'is_exchangeable' => $this->isExchangeable(),
            'extensions' => array_map(function ($extension) {return [1, $extension];}, $this->getExtensions())
        ];
    }

    public function toBytes(): string
    {
        return implode('', [
            str_pad(Math::gmpDecHex(Math::reverseBytesLong($this->getMaxSupply())), 16, '0', STR_PAD_LEFT),
            $this->getExchangeRate()->toBytes(),
            $this->isExchangeable() ? '01' : '00',
            str_pad(count($this->getExtensions()), 2, '0', STR_PAD_LEFT),
            // TODO: isFixedMaxSupply
//            '01'
            $this->isFixedMaxSupply() !== null ? '01' . ($this->isFixedMaxSupply() ? '01' : '00') : '00'
        ]);
    }

    public function isFixedMaxSupply()
    {
        $fms = reset($this->extensions);
        if ($fms && isset($fms['is_fixed_max_supply'])) {
            return $fms['is_fixed_max_supply'];
        }
        $fms_1 = $fms[1];
        if ($fms_1 && isset($fms_1['is_fixed_max_supply'])) {
            return $fms_1['is_fixed_max_supply'];
        }
        return null;
    }

    /**
     * @param string $maxSupply
     * @throws ValidationException
     */
    public static function validateMaxSupply(string $maxSupply): void {
        [$subject, $constraints] = [gmp_cmp($maxSupply, self::MAX_SHARE_SUPPLY), [
            new LessThanOrEqual([
                'value' => 0,
                'message' => 'Max supply max value overflow'])]];
        if (($violations = Validation::createValidator()->validate($subject, $constraints))->count() > 0) {
            throw new ValidationException($violations);
        }
    }
}
