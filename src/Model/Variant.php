<?php

namespace DCorePHP\Model;

use DCorePHP\Utils\Math;
use DCorePHP\Utils\VarInt;
use InvalidArgumentException;

class Variant
{
    public const INT64_TYPE = 1;
    public const UINT64_TYPE = 2;
    public const BOOL_TYPE = 4;
    public const STRING_TYPE = 5;

    /** @var string */
    private $type;
    /** @var mixed */
    private $value;
    /** @var string */
    private $name;

    /**
     * Variant constructor.
     *
     * @param string $type
     * @param mixed $value
     * @param string $name
     */
    public function __construct(string $type, $value, string $name = null)
    {
        $this->type = $type;
        $this->value = $value;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return Variant
     */
    public function setType($type): Variant
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     *
     * @return Variant
     */
    public function setValue($value): Variant
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Variant
     */
    public function setName(string $name): Variant
    {
        $this->name = $name;

        return $this;
    }

    public function toBytes(): string {
        switch ($this->getType()) {
            case 'integer':
                $type = gmp_cmp($this->getValue(), 0) >= 0 ? self::UINT64_TYPE : self::INT64_TYPE;
                $result = [
                    '0' . $type,
                    Math::getInt64($this->getValue())
                ];
                break;
            case 'boolean':
                $result = [
                    '0' . self::BOOL_TYPE,
                    $this->getValue() ? '01' : '00',
                ];
                break;
            case 'string':
                $result = [
                    '0' . self::STRING_TYPE,
                    VarInt::encodeDecToHex(sizeof(Math::stringToByteArray($this->getValue()))),
                    Math::byteArrayToHex(Math::stringToByteArray($this->getValue())),
                ];
                break;
            default:
                throw new InvalidArgumentException("Type '$this->type' is not allowed!");
        }
        return implode('', $result);
    }
}