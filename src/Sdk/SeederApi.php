<?php

namespace DCorePHP\Sdk;

use DCorePHP\Exception\ObjectNotFoundException;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Content\Seeder;
use DCorePHP\Model\RegionalPrice;
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
        $seeder = $this->dcoreApi->requestWebsocket(new GetSeeder($accountId));
        if ($seeder instanceof Seeder) {
            return $seeder;
        }
        throw new ObjectNotFoundException("Seeder with id $accountId not found!");
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
    public function listByRegion(string $region = RegionalPrice::REGIONS_ALL): array
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