<?php

namespace DCorePHP\Sdk;

use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Content\Seeder;
use DCorePHP\Net\Model\Request\Database;
use DCorePHP\Net\Model\Request\GetSeeder;
use DCorePHP\Net\Model\Request\ListSeedersByPrice;
use DCorePHP\Net\Model\Request\ListSeedersByRating;
use DCorePHP\Net\Model\Request\ListSeedersByRegion;
use DCorePHP\Net\Model\Request\ListSeedersByUpload;

class SeederApi extends BaseApi implements SeederApiInterface
{

    /**
     * @inheritdoc
     */
    public function get(ChainObject $accountId): Seeder
    {
        return $this->dcoreApi->requestWebsocket(new GetSeeder($accountId));
    }

    /**
     * @inheritdoc
     */
    public function listByPrice(int $count = 100): array
    {
        return $this->dcoreApi->requestWebsocket(new ListSeedersByPrice($count)) ?: [];
    }

    /**
     * @inheritdoc
     */
    public function listByUpload(int $count = 100): array
    {
        return $this->dcoreApi->requestWebsocket(new ListSeedersByUpload($count)) ?: [];
    }

    /**
     * @inheritdoc
     */
    public function listByRegion(string $region = 'default'): array
    {
        return $this->dcoreApi->requestWebsocket(new ListSeedersByRegion($region)) ?: [];
    }

    /**
     * @inheritdoc
     */
    public function listByRating(int $count = 100): array
    {
        return $this->dcoreApi->requestWebsocket(new ListSeedersByRating($count)) ?: [];
    }
}