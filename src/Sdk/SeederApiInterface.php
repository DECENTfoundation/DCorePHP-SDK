<?php

namespace DCorePHP\Sdk;

use DCorePHP\Exception\InvalidApiCallException;
use DCorePHP\Exception\ObjectNotFoundException;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Content\Seeder;
use DCorePHP\Model\RegionalPrice;
use WebSocket\BadOpcodeException;

interface SeederApiInterface
{

    /**
     * Get a seeder by ID
     *
     * @param ChainObject $accountId seeder account
     *
     * @return Seeder object
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     * @throws ObjectNotFoundException
     */
    public function get(ChainObject $accountId): Seeder;

    /**
     * Get a list of seeders by price, in increasing order.
     *
     * @param int $count maximum number of seeders to retrieve
     *
     * @return Seeder[] a list of seeders
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function listByPrice(int $count = 100): array;

    /**
     * Get a list of seeders ordered by total upload, in decreasing order
     *
     * @param int $count maximum number of seeders to retrieve
     *
     * @return Seeder[] a list of seeders
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function listByUpload(int $count = 100): array;

    /**
     * Get a list of seeders by region code
     *
     * @param string $region
     *
     * @return Seeder[] a list of seeders
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function listByRegion(string $region = RegionalPrice::REGIONS_ALL): array;

    /**
     * Get a list of seeders ordered by rating, in decreasing order
     *
     * @param int $count maximum number of seeders to retrieve
     *
     * @return Seeder[] a list of seeders
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function listByRating(int $count = 100): array;

}