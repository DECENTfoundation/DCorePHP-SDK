<?php

namespace DCorePHPTests\Sdk;

use DCorePHP\Exception\InvalidApiCallException;
use DCorePHP\Exception\ObjectNotFoundException;
use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\ChainObject;
use DCorePHPTests\DCoreSDKTest;
use WebSocket\BadOpcodeException;

class SeederApiTest extends DCoreSDKTest
{
    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ValidationException
     *
     * @doesNotPerformAssertions
     */
    public function testGetSeeder(): void
    {
        try {
            $seeder = self::$sdk->getSeederApi()->get(new ChainObject('1.2.17'));
            $this->assertEquals('1.2.17', $seeder->getSeeder()->getId());
        } catch (ObjectNotFoundException $exception) {}
    }

    /**
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     *
     * @doesNotPerformAssertions
     */
    public function testListSeedersByPrice(): void
    {
        self::$sdk->getSeederApi()->listByPrice();
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     *
     * @doesNotPerformAssertions
     */
    public function testListSeedersByUpload(): void
    {
        self::$sdk->getSeederApi()->listByUpload();
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     *
     * @doesNotPerformAssertions
     */
    public function testListSeedersByRegion(): void
    {
        self::$sdk->getSeederApi()->listByRegion();
    }

    /**
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     *
     * @doesNotPerformAssertions
     */
    public function testListSeedersByRating(): void
    {
        self::$sdk->getSeederApi()->listByRating();
    }

}
