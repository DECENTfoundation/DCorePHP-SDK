<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;
use DCorePHP\Utils\Math;
use DCorePHP\Utils\VarInt;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;

class CustomOperation extends BaseOperation
{
    public const OPERATION_TYPE = 18;
    public const OPERATION_NAME = 'custom';
    public const CUSTOM_TYPE = 0;

    /** @var ChainObject */
    private $payer;
    /** @var ChainObject[] */
    private $requiredAuths;
    /** @var string */
    private $data;
    /** @var int */
    private $id;

    /**
     * @param array $rawOperation
     */
    public function hydrate(array $rawOperation): void
    {
        foreach (
            [
                '[fee][amount]' => 'fee.amount',
                '[fee][asset_id]' => 'fee.assetId',
                '[payer]' => 'payer',
                '[required_auths]' => 'requiredAuths',
                '[id]' => 'id',
                '[data]' => 'data',
            ] as $path => $modelPath
        ) {
            try {
                $value = $this->getPropertyAccessor()->getValue($rawOperation, $path);
                $this->getPropertyAccessor()->setValue($this, $modelPath, $value);
            } catch (NoSuchPropertyException $exception) {
                // skip
            }
        }
    }

    public function toArray(): array
    {
        return [
            self::OPERATION_TYPE,
            [
                'id' => $this->getId(),
                'payer' => $this->getPayer()->getId(),
                'required_auths' => array_map(static function (ChainObject $data) {return $data->getId();}, $this->getRequiredAuths()),
                'data' => $this->getData(),
                'fee' => $this->getFee()->toArray()
            ],
        ];
    }

    public function toBytes(): string
    {
        return implode('', [
            $this->getTypeBytes(),
            $this->getFee()->toBytes(),
            $this->getPayer()->toBytes(),
            VarInt::encodeDecToHex(sizeof($this->getRequiredAuths())),
            implode('', array_map(static function (ChainObject $auth) {
                return $auth->toBytes();
            }, $this->getRequiredAuths())),
            Math::getInt16($this->getId()),
            // Data size
            VarInt::encodeDecToHex(sizeof(Math::hexToByteArray($this->getData()))),
            $this->getData()
        ]);
    }

    /**
     * @return ChainObject
     */
    public function getPayer(): ChainObject
    {
        return $this->payer;
    }

    /**
     * @param ChainObject|string $payer
     * @return CustomOperation
     * @throws ValidationException
     */
    public function setPayer($payer): CustomOperation
    {
        if (is_string($payer)) {
            $payer = new ChainObject($payer);
        }

        $this->payer = $payer;

        return $this;
    }

    /**
     * @return ChainObject[]
     */
    public function getRequiredAuths(): array
    {
        return $this->requiredAuths;
    }

    /**
     * @param ChainObject[]|string[] $requiredAuths
     * @return CustomOperation
     * @throws ValidationException
     */
    public function setRequiredAuths(array $requiredAuths): CustomOperation
    {
        $chainObjects = [];
        foreach ($requiredAuths as $requiredAuth) {
            if (is_string($requiredAuth)) {
                $requiredAuth = new ChainObject($requiredAuth);
            }

            $chainObjects[] = $requiredAuth;
        }

        $this->requiredAuths = $chainObjects;

        return $this;
    }

    /**
     * @return string
     */
    public function getData(): string
    {
        return $this->data;
    }

    /**
     * @param string $data
     * @return CustomOperation
     */
    public function setData(string $data): CustomOperation
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return CustomOperation
     */
    public function setId(int $id): CustomOperation
    {
        $this->id = $id;

        return $this;
    }
}
