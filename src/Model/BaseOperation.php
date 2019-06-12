<?php

namespace DCorePHP\Model;

use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Utils\Math;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

abstract class BaseOperation
{
    public const OPERATION_TYPE = 0;
    public const OPERATION_NAME = '';

    /** @var string */
    private $name;
    /** @var int */
    private $type;
    /** @var AssetAmount */
    private $fee;
    /** @var array $extensions */
    private $extensions = [];

    public function __construct()
    {
        $this->setName(static::OPERATION_NAME);
        $this->setType(static::OPERATION_TYPE);
        $this->fee = new AssetAmount();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return AssetAmount
     */
    public function getFee(): AssetAmount
    {
        return $this->fee;
    }

    /**
     * @param AssetAmount $fee
     * @return self
     */
    public function setFee(AssetAmount $fee): self
    {
        $this->fee = $fee;

        return $this;
    }

    /**
     * @return array
     */
    public function getExtensions(): array
    {
        return $this->extensions;
    }

    /**
     * @param array $extensions
     * @return BaseOperation
     */
    public function setExtensions(array $extensions): BaseOperation
    {
        $this->extensions = $extensions;

        return $this;
    }

    /**
     * @todo make abstract
     * @return array
     */
    public function toArray(): array
    {
        return [];
    }

    /**
     * @todo make abstract
     * @return string
     */
    public function toBytes(): string
    {
        return '';
    }

    /**
     * @return string
     */
    protected function getTypeBytes(): string
    {
        return Math::getInt8(static::OPERATION_TYPE);
    }

    /**
     * @param array $rawOperation
     */
    public function hydrate(array $rawOperation): void
    {
    }

    protected function getPropertyAccessor(): PropertyAccessor
    {
        return PropertyAccess::createPropertyAccessor();
    }
}
