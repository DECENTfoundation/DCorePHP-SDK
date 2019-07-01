<?php

namespace DCorePHPTests\Model;

use DCorePHP\Model\Annotation\Modifiable;
use DCorePHP\Model\Annotation\Type;
use DCorePHP\Model\Annotation\Unique;
use DCorePHP\Model\NftModel;

class NftApple extends NftModel
{
    /**
     * @var int
     * @Type("integer")
     */
    public $size;
    /**
     * @var string
     * @Type("string")
     * @Unique
     */
    public $color;
    /**
     * @var boolean
     * @Type("boolean")
     * @Modifiable("nobody")
     */
    public $eaten;

    /**
     * NftApple constructor.
     *
     * @param $size
     * @param $color
     * @param $eaten
     */
    public function __construct($size, $color, $eaten)
    {
        $this->size = $size;
        $this->color = $color;
        $this->eaten = $eaten;
    }
}