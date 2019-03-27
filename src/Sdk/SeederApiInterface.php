<?php


namespace DCorePHP\Sdk;


use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Content\Seeder;

interface SeederApiInterface
{

    /**
     * Get a seeder by ID
     * @param ChainObject $accountId seeder account
     * @return Seeder object
     */
    public function get(ChainObject $accountId): Seeder;

    /**
     * Get a list of seeders by price, in increasing order.
     * @param int $count maximum number of seeders to retrieve
     * @return Seeder[] a list of seeders
     */
    public function listByPrice(int $count = 100): array;

    /**
     * Get a list of seeders ordered by total upload, in decreasing order
     * @param int $count maximum number of seeders to retrieve
     * @return Seeder[] a list of seeders
     */
    public function listByUpload(int $count = 100): array;

    /**
     * Get a list of seeders by region code
     * @param string $region
     * @return Seeder[] a list of seeders
     */
    public function listByRegion(string $region = 'default'): array;

    /**
     * Get a list of seeders ordered by rating, in decreasing order
     * @param int $count maximum number of seeders to retrieve
     * @return Seeder[] a list of seeders
     */
    public function listByRating(int $count = 100): array;

}