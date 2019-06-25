<?php

namespace DCorePHPTests\Model;

use DCorePHP\Model\NftDataType;
use DCorePHP\Model\NftModel;

class NftApple extends NftModel
{
    /** @var NftDataType */
    private $size;
    /** @var NftDataType */
    private $color;
    /** @var NftDataType */
    private $eaten;

    /**
     * NftApple constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->size = NftDataType::withValues('integer');
        $this->color = NftDataType::withValues('string', true, NftDataType::NOBODY, 'color');
        $this->eaten = NftDataType::withValues('boolean', false, NftDataType::BOTH, 'eaten');
    }

    /**
     * @return NftDataType
     */
    public function getSize(): ?NftDataType
    {
        return $this->size;
    }

    /**
     * @param NftDataType $size
     * @return NftApple
     */
    public function setSize(NftDataType $size): NftApple
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return NftDataType
     */
    public function getColor(): ?NftDataType
    {
        return $this->color;
    }

    /**
     * @param NftDataType $color
     * @return NftApple
     */
    public function setColor(NftDataType $color): NftApple
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return NftDataType
     */
    public function getEaten(): ?NftDataType
    {
        return $this->eaten;
    }

    /**
     * @param NftDataType $eaten
     * @return NftApple
     */
    public function setEaten(NftDataType $eaten): NftApple
    {
        $this->eaten = $eaten;

        return $this;
    }
}