<?php

namespace DCorePHP\Model;

use DCorePHP\DCoreApi;
use DCorePHP\DCoreConstants;
use DCorePHP\Exception\ValidationException;
use DCorePHP\Utils\Math;
use DCorePHP\Utils\VarInt;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Validation;

class NftOptions
{
    /** @var ChainObject */
    private $issuer;
    /** @var string */
    private $maxSupply;
    /** @var bool */
    private $fixedMaxSupply;
    /** @var string */
    private $description;

    public function update(string $maxSupply = null, bool $fixedMaxSupply = null, string $description = null): void
    {
        $maxSupply = $maxSupply ?? $this->maxSupply;
        $fixedMaxSupply = $fixedMaxSupply ?? $this->fixedMaxSupply;
        $description = $description ?? $this->description;

        DCoreApi::require($maxSupply >= $this->maxSupply, "Max supply must be at least $this->maxSupply");
        DCoreApi::require($fixedMaxSupply === $this->fixedMaxSupply || !$this->fixedMaxSupply, 'Max supply must remain fixed');
        DCoreApi::require($maxSupply === $this->maxSupply || !$this->fixedMaxSupply, 'Can not change max supply (it\'s fixed)');

        $this->maxSupply = $maxSupply;
        $this->fixedMaxSupply = $fixedMaxSupply;
        $this->description = $description;
    }

    /**
     * @return ChainObject
     */
    public function getIssuer(): ChainObject
    {
        return $this->issuer;
    }

    /**
     * @param ChainObject|string $issuer
     * @return NftOptions
     * @throws ValidationException
     */
    public function setIssuer($issuer): NftOptions
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
    public function getMaxSupply(): string
    {
        return $this->maxSupply;
    }

    /**
     * @param string $maxSupply
     * @return NftOptions
     */
    public function setMaxSupply(string $maxSupply): NftOptions
    {
        $this->maxSupply = $maxSupply;

        return $this;
    }

    /**
     * @return bool
     */
    public function isFixedMaxSupply(): bool
    {
        return $this->fixedMaxSupply;
    }

    /**
     * @param bool $fixedMaxSupply
     * @return NftOptions
     */
    public function setFixedMaxSupply(bool $fixedMaxSupply): NftOptions
    {
        $this->fixedMaxSupply = $fixedMaxSupply;

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
     * @return NftOptions
     */
    public function setDescription(string $description): NftOptions
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @throws ValidationException
     */
    public function validateMaxSupply(): void {
        [$subject, $constraints] = [gmp_cmp($this->maxSupply, DCoreConstants::UIA_DESCRIPTION_MAX_CHARS), [
            new LessThanOrEqual([
                'value' => 0,
                'message' => 'Max supply max value overflow'])]];
        if (($violations = Validation::createValidator()->validate($subject, $constraints))->count() > 0) {
            throw new ValidationException($violations);
        }
    }

    public function toArray(): array
    {
        return [
            'issuer' => $this->getIssuer()->getId(),
            'max_supply' => $this->getMaxSupply(),
            'fixed_max_supply' => $this->isFixedMaxSupply(),
            'description' => $this->getDescription()
        ];
    }

    public function toBytes(): string
    {
        return implode('', [
            $this->getIssuer()->toBytes(),
            Math::getInt32($this->getMaxSupply()),
            $this->isFixedMaxSupply() ? '01' : '00',
            VarInt::encodeDecToHex(sizeof(Math::stringToByteArray($this->getDescription()))),
            Math::byteArrayToHex(Math::stringToByteArray($this->getDescription())),
        ]);
    }
}