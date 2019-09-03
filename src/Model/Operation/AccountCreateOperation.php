<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Authority;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Options;
use DCorePHP\Utils\Math;
use DCorePHP\Utils\VarInt;
use Exception;
use InvalidArgumentException;

class AccountCreateOperation extends BaseOperation
{
    public const OPERATION_TYPE = 1;
    public const OPERATION_NAME = 'account_create';

    /** @var ChainObject */
    private $registrar;
    /** @var string */
    private $name;
    /** @var Authority */
    private $owner;
    /** @var Authority */
    private $active;
    /** @var Options */
    private $options;

    /**
     * @return ChainObject
     */
    public function getRegistrar(): ChainObject
    {
        return $this->registrar;
    }

    /**
     * @param ChainObject|string $registrar
     *
     * @return AccountCreateOperation
     * @throws ValidationException
     */
    public function setRegistrar($registrar): AccountCreateOperation
    {
        if (is_string($registrar)) {
            $registrar = new ChainObject($registrar);
        }
        $this->registrar = $registrar;

        return $this;
    }

    /**
     * @return string
     */
    public function getAccountName(): string
    {
        return $this->name;
    }

    /**
     * @param string $accountName
     *
     * @return AccountCreateOperation
     * @throws Exception
     */
    public function setAccountName(string $accountName): AccountCreateOperation
    {
        if (!preg_match('/^[a-z][a-z0-9-]+[a-z0-9](?:\.[a-z][a-z0-9-]+[a-z0-9])*$/', $accountName)) {
            throw new InvalidArgumentException("Account name '{$accountName}' is not valid. Name doesn't match pattern '/^[a-z][a-z0-9-]+[a-z0-9](?:\.[a-z][a-z0-9-]+[a-z0-9])*$/'");
        }

        if (!in_array(strlen($accountName), range(5, 63), true)) {
            throw new InvalidArgumentException("Account name '{$accountName}' is not valid. Name doesn't match required length 5..63 characters");
        }

        $this->name = $accountName;
        return $this;
    }

    /**
     * @return Authority
     */
    public function getOwner(): Authority
    {
        return $this->owner;
    }

    /**
     * @param Authority $owner
     *
     * @return AccountCreateOperation
     */
    public function setOwner(Authority $owner): AccountCreateOperation
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Authority
     */
    public function getActive(): Authority
    {
        return $this->active;
    }

    /**
     * @param Authority $active
     *
     * @return AccountCreateOperation
     */
    public function setActive(Authority $active): AccountCreateOperation
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return Options
     */
    public function getOptions(): Options
    {
        return $this->options;
    }

    /**
     * @param Options $options
     *
     * @return AccountCreateOperation
     */
    public function setOptions(Options $options): AccountCreateOperation
    {
        $this->options = $options;

        return $this;
    }

    public function toArray(): array
    {
        return [
            self::OPERATION_TYPE,
            [
                'fee' => $this->getFee()->toArray(),
                'registrar' => $this->getRegistrar()->getId(),
                'name' => $this->getAccountName(),
                'owner' => $this->getOwner()->toArray(),
                'active' => $this->getActive()->toArray(),
                'options' => $this->getOptions()->toArray(),
                'extensions' => $this->getExtensions(),
            ],
        ];
    }

    public function toBytes(): string
    {
        return implode('', [
            $this->getTypeBytes(),
            $this->getFee()->toBytes(),
            $this->getRegistrar()->toBytes(),
            VarInt::encodeDecToHex(sizeof(Math::stringToByteArray($this->getAccountName()))),
            Math::byteArrayToHex(Math::stringToByteArray($this->getAccountName())),
            $this->getOwner()->toBytes(),
            $this->getActive()->toBytes(),
            $this->getOptions()->toBytes(),
            '00',
        ]);
    }
}