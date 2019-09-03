<?php

namespace DCorePHP\Sdk;

use DateTime;
use DCorePHP\Exception\InvalidApiCallException;
use DCorePHP\Model\BlockHeader;
use DCorePHP\Model\SignedBlock;
use WebSocket\BadOpcodeException;

interface BlockApiInterface
{
    /**
     * Retrieve a full, signed block.
     *
     * @param string $blockNum height of the block to be returned
     *
     * @return SignedBlock the referenced block
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function get(string $blockNum): SignedBlock;

    /**
     * Retrieve a block header.
     *
     * @param string $blockNum height of the block whose header should be returned
     *
     * @return BlockHeader header of the referenced block
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function getHeader(string $blockNum): BlockHeader;

    /**
     * Query the last local block.
     *
     * @return DateTime the block time
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function getHeadTime(): DateTime;
}