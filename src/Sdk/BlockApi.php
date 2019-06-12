<?php

namespace DCorePHP\Sdk;

use DCorePHP\Model\BlockHeader;
use DCorePHP\Model\SignedBlock;
use DCorePHP\Net\Model\Request\GetBlock;
use DCorePHP\Net\Model\Request\GetBlockHeader;
use DCorePHP\Net\Model\Request\HeadBlockTime;

class BlockApi extends BaseApi implements BlockApiInterface
{
    /**
     * @inheritDoc
     */
    public function get(string $blockNum): SignedBlock
    {
        return $this->dcoreApi->requestWebsocket(new GetBlock($blockNum));
    }

    /**
     * @inheritDoc
     */
    public function getHeader(string $blockNum): BlockHeader
    {
        return $this->dcoreApi->requestWebsocket(new GetBlockHeader($blockNum));
    }

    /**
     * @inheritDoc
     */
    public function getHeadTime(): \DateTime
    {
        return $this->dcoreApi->requestWebsocket(new HeadBlockTime());
    }
}