<?php

namespace DCorePHP\Model;

use DCorePHP\Exception\ValidationException;
use ReflectionException;

class NftData
{
    /** @var ChainObject */
    private $id;
    /** @var ChainObject */
    private $nftId;
    /** @var ChainObject */
    private $owner;
    /** @var mixed */
    private $data;

    /**
     * @param NftData $nft
     * @param $class
     *
     * @return NftData
     * @throws ValidationException
     * @throws ReflectionException
     */
    public static function init(NftData $nft, $class): NftData
    {
        $instance = new self();
        $data = NftModel::make($nft->getData(), $class);
        return $instance->setId($nft->getId())->setNftId($nft->getNftId())->setOwner($nft->getOwner())->setData($data);
    }

    /**
     * @return ChainObject
     */
    public function getId(): ChainObject
    {
        return $this->id;
    }

    /**
     * @param ChainObject|string $id
     *
     * @return NftData
     * @throws ValidationException
     */
    public function setId($id): NftData
    {
        if (is_string($id)) {
            $id = new ChainObject($id);
        }
        $this->id = $id;

        return $this;
    }

    /**
     * @return ChainObject
     */
    public function getNftId(): ChainObject
    {
        return $this->nftId;
    }

    /**
     * @param ChainObject|string $nftId
     *
     * @return NftData
     * @throws ValidationException
     */
    public function setNftId($nftId): NftData
    {
        if (is_string($nftId)) {
            $nftId = new ChainObject($nftId);
        }
        $this->nftId = $nftId;

        return $this;
    }

    /**
     * @return ChainObject
     */
    public function getOwner(): ChainObject
    {
        return $this->owner;
    }

    /**
     * @param ChainObject|string $owner
     *
     * @return NftData
     * @throws ValidationException
     */
    public function setOwner($owner): NftData
    {
        if (is_string($owner)) {
            $owner = new ChainObject($owner);
        }
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     *
     * @return NftData
     */
    public function setData($data): NftData
    {
        $this->data = $data;

        return $this;
    }
}