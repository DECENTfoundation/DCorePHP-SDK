<?php

namespace DCorePHP\Sdk;

use DCorePHP\Model\BlockHeader;
use DCorePHP\Model\SingleBlock;

class BlockApi extends BaseApi implements BlockApiInterface
{
    /**
     * @inheritDoc
     */
    public function get(string $blockNum): SingleBlock
    {
        // TODO: Implement get() method.
    }

    /**
     * @inheritDoc
     */
    public function getHeader(string $blockNum): BlockHeader
    {
        // TODO: Implement getHeader() method.
    }

    /**
     * @inheritDoc
     */
    public function getHeadTime(): int
    {
        // TODO: Implement getHeadTime() method.
    }
}