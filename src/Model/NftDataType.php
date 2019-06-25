<?php

namespace DCorePHP\Model;

use DCorePHP\DCoreConstants;
use DCorePHP\Utils\Math;
use DCorePHP\Utils\VarInt;
use InvalidArgumentException;

class NftDataType
{
    private const modifiableOrdinal = [self::NOBODY => 0, self::ISSUER => 1, self::OWNER => 2, self::BOTH => 3];
    public const NOBODY = 'nobody';
    public const ISSUER = 'issuer';
    public const OWNER = 'owner';
    public const BOTH = 'both';

    private const typeOrdinal = [self::TYPE_STRING => 0, self::TYPE_INTEGER => 1, self::TYPE_BOOLEAN => 2];
    public const TYPE_STRING = 'string';
    public const TYPE_INTEGER = 'integer';
    public const TYPE_BOOLEAN = 'boolean';

    /** @var mixed */
    private $type;
    /** @var bool */
    private $unique = false;
    /** @var string */
    private $modifiable = self::NOBODY;
    /** @var string */
    private $name;

    public static function withValues($type, bool $unique = false, string $modifiable = self::NOBODY, string $name = null): NftDataType
    {
        $dataType = new self();
        return $dataType
            ->setType($type)
            ->setUnique($unique)
            ->setModifiable($modifiable)
            ->setName($name);
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return NftDataType
     */
    public function setType($type): NftDataType
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return bool
     */
    public function isUnique(): bool
    {
        return $this->unique;
    }

    /**
     * @param bool $unique
     * @return NftDataType
     */
    public function setUnique(bool $unique): NftDataType
    {
        $this->unique = $unique;

        return $this;
    }

    /**
     * @return string
     */
    public function getModifiable(): string
    {
        return $this->modifiable;
    }

    /**
     * @param string $modifiable
     * @return NftDataType
     */
    public function setModifiable(string $modifiable): NftDataType
    {
        $this->modifiable = $modifiable;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return NftDataType
     */
    public function setName(?string $name): NftDataType
    {
        $this->name = $name;

        return $this;
    }

    public function validate(): void
    {
        if ($this->modifiable !== self::NOBODY && $this->name === null) {
            throw new InvalidArgumentException('modifiable data type must have name');
        }

        if ($this->name !== null && strlen($this->name) > DCoreConstants::NFT_NAME_MAX_CHARS) {
            throw new InvalidArgumentException('name cannot be longer then ' . DCoreConstants::NFT_NAME_MAX_CHARS . 'chars');
        }
    }

    public function toArray(): array
    {
        return [
            'type' => $this->getType(),
            'unique' => $this->isUnique(),
            'modifiable' => $this->getModifiable(),
            'name' => $this->getName()
        ];
    }

    public function toBytes(): string
    {
        return implode('', [
            $this->isUnique() ? '01' : '00',
            Math::getInt64('' . self::modifiableOrdinal[$this->getModifiable()]),
            Math::getInt64('' . self::typeOrdinal[$this->getType()]),
            $this->getName() ?
                '01' .
                VarInt::encodeDecToHex(sizeof(Math::stringToByteArray($this->getName()))) .
                Math::byteArrayToHex(Math::stringToByteArray($this->getName()))
                : '00'
        ]);
    }
}