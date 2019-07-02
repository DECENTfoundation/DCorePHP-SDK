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
     *
     * @param null $size
     * @param null $color
     * @param null $eaten
     */
    public function __construct($size = null, $color = null, $eaten = null)
    {
        parent::__construct();
        $this->size = NftDataType::withValues('integer', $size);
        $this->color = NftDataType::withValues('string', $color,true, NftDataType::NOBODY, 'color');
        $this->eaten = NftDataType::withValues('boolean', $eaten, false, NftDataType::BOTH, 'eaten');
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