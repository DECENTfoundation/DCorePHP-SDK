<?php

namespace DCorePHP\Sdk;

use DCorePHP\Model\BlockHeader;
use DCorePHP\Model\SingleBlock;

interface BlockApiInterface
{
    /**
     * Retrieve a full, signed block.
     *
     * @param string $blockNum height of the block to be returned
     *
     * @return SingleBlock the referenced block
     */
    public function get(string $blockNum): SingleBlock;

    /**
     * Retrieve a block header.
     *
     * @param string $blockNum height of the block whose header should be returned
     *
     * @return BlockHeader header of the referenced block
     */
    public function getHeader(string $blockNum): BlockHeader;

    /**
     * Query the last local block.
     *
     * @return int the block time
     */
    public function getHeadTime(): int;
}