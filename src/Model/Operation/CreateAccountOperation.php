<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Model\Authority;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Options;
use DCorePHP\Utils\Math;
use DCorePHP\Utils\VarInt;

class CreateAccountOperation extends BaseOperation
{
    public const OPERATION_TYPE = 1;
    public const OPERATION_NAME = 'account_create';

    /** @var string */
    private $accountName;
    /** @var Authority */
    private $owner;
    /** @var Authority */
    private $active;
    /** @var ChainObject */
    private $registrar;
    /** @var Options */
    private $options;

    /**
     * @return string
     */
    public function getAccountName(): string
    {
        return $this->accountName;
    }

    /**
     * @param string $accountName
     * @return CreateAccountOperation
     * @throws \Exception
     */
    public function setAccountName(string $accountName): CreateAccountOperation
    {
        if (!preg_match('/^[a-z][a-z0-9-]+[a-z0-9](?:\.[a-z][a-z0-9-]+[a-z0-9])*$/', $accountName)) {
            throw new \Exception("Account name '{$accountName}' is not valid. Name doesn't match pattern '/^[a-z][a-z0-9-]+[a-z0-9](?:\.[a-z][a-z0-9-]+[a-z0-9])*$/'");
        }

        if (!in_array(strlen($accountName), range(5, 63))) {
            throw new \Exception("Account name '{$accountName}' is not valid. Name doesn't match required length 5..63 characters");
        }

        $this->accountName = $accountName;
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
     * @return CreateAccountOperation
     */
    public function setOwner(Authority $owner): CreateAccountOperation
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
     * @return CreateAccountOperation
     */
    public function setActive(Authority $active): CreateAccountOperation
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return ChainObject
     */
    public function getRegistrar(): ChainObject
    {
        return $this->registrar;
    }

    /**
     * @param ChainObject|string $registrar
     * @return CreateAccountOperation
     */
    public function setRegistrar($registrar): CreateAccountOperation
    {
        if (is_string($registrar)) {
            $registrar = new ChainObject($registrar);
        }

        $this->registrar = $registrar;
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
     * @return CreateAccountOperation
     */
    public function setOptions(Options $options): CreateAccountOperation
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @return array
     */
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
                'extensions' => [],
            ],
        ];
    }

    /**
     * @return string
     * @throws \Exception
     */
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
