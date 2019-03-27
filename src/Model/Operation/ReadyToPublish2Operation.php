<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\PubKey;

class ReadyToPublish2Operation extends BaseOperation
{
    public const OPERATION_TYPE = 38;
    public const OPERATION_NAME = 'ready_to_publish2';

    /** @var ChainObject */
    private $seeder;
    /** @var int */
    private $space;
    /** @var PubKey */
    private $pubKey;
    /** @var int */
    private $pricePerMByte;
    /** @var string */
    private $ipfsId;
    /** @var string */
    private $regionCode;

    public function __construct()
    {
        parent::__construct();
        $this->pubKey = new PubKey();
    }

    /**
     * @param array $rawOperation
     */
    public function hydrate(array $rawOperation): void
    {
        foreach (
            [
                '[fee][amount]' => 'fee.amount',
                '[fee][asset_id]' => 'fee.assetId',
                '[seeder]' => 'seeder',
                '[space]' => 'space',
                '[pubKey][s]' => 'pubKey.pubKey',
                '[price_per_MByte]' => 'pricePerMByte',
                '[ipfs_ID]' => 'ipfsId',
                '[region_code]' => 'regionCode',
            ] as $path => $modelPath
        ) {
            try {
                $value = $this->getPropertyAccessor()->getValue($rawOperation, $path);
                $this->getPropertyAccessor()->setValue($this, $modelPath, $value);
            } catch (\Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException $exception) {
                // skip
            }
        }
    }

    /**
     * @return ChainObject
     */
    public function getSeeder(): ?ChainObject
    {
        return $this->seeder;
    }

    /**
     * @param ChainObject|string $seeder
     * @return ReadyToPublish2Operation
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setSeeder($seeder): ReadyToPublish2Operation
    {
        if (is_string($seeder)) {
            $seeder = new ChainObject($seeder);
        }

        $this->seeder = $seeder;
        return $this;
    }

    /**
     * @return int
     */
    public function getSpace(): ?int
    {
        return $this->space;
    }

    /**
     * @param int $space
     * @return ReadyToPublish2Operation
     */
    public function setSpace(int $space): ReadyToPublish2Operation
    {
        $this->space = $space;
        return $this;
    }

    /**
     * @return PubKey
     */
    public function getPubKey(): PubKey
    {
        return $this->pubKey;
    }

    /**
     * @param PubKey $pubKey
     * @return ReadyToPublish2Operation
     */
    public function setPubKey(PubKey $pubKey): ReadyToPublish2Operation
    {
        $this->pubKey = $pubKey;
        return $this;
    }

    /**
     * @return int
     */
    public function getPricePerMByte(): ?int
    {
        return $this->pricePerMByte;
    }

    /**
     * @param int $pricePerMByte
     * @return ReadyToPublish2Operation
     */
    public function setPricePerMByte(int $pricePerMByte): ReadyToPublish2Operation
    {
        $this->pricePerMByte = $pricePerMByte;
        return $this;
    }

    /**
     * @return string
     */
    public function getIpfsId(): ?string
    {
        return $this->ipfsId;
    }

    /**
     * @param string $ipfsId
     * @return ReadyToPublish2Operation
     */
    public function setIpfsId(string $ipfsId): ReadyToPublish2Operation
    {
        $this->ipfsId = $ipfsId;
        return $this;
    }

    /**
     * @return string
     */
    public function getRegionCode(): ?string
    {
        return $this->regionCode;
    }

    /**
     * @param string $regionCode
     * @return ReadyToPublish2Operation
     */
    public function setRegionCode(string $regionCode): ReadyToPublish2Operation
    {
        $this->regionCode = $regionCode;
        return $this;
    }
}