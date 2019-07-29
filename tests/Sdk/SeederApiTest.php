<?php

namespace DCorePHPTests\Sdk;

use DCorePHP\Model\Content\Seeder;
use DCorePHP\Model\ChainObject;
use DCorePHP\Net\Model\Request\BaseRequest;
use DCorePHP\Net\Model\Request\GetSeederAbstract;
use DCorePHP\Net\Model\Request\ListSeedersByPrice;
use DCorePHP\Net\Model\Request\ListSeedersByUpload;
use DCorePHP\Net\Model\Response\BaseResponse;
use DCorePHPTests\DCoreSDKTest;

class SeederApiTest extends DCoreSDKTest
{
    /**
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function testGetSeeder(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
//        $seeder = self::$sdk->getSeederApi()->get(new ChainObject('1.2.17'));
//        $this->assertEquals('1.2.17', $seeder->getSeeder()->getId());
    }

    public function testListSeedersByPrice(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
//        $seeders = self::$sdk->getSeederApi()->listByPrice();
//        foreach ($seeders as $seeder) {
//            $this->assertInstanceOf(Seeder::class, $seeder);
//        }
    }

    public function testListSeedersByUpload(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
//        $seeders = self::$sdk->getSeederApi()->listByUpload();
//
//        foreach ($seeders as $seeder) {
//            $this->assertInstanceOf(Seeder::class, $seeder);
//        }
    }

    public function testListSeedersByRegion(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
//        $seeders = self::$sdk->getSeederApi()->listByRegion();
//        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    public function testListSeedersByRating(): void
    {
        $this->markTestIncomplete('This test has not been implemented yet.'); // @todo
//        $seeders = self::$sdk->getSeederApi()->listByRating();
//
//        foreach ($seeders as $seeder) {
//            $this->assertInstanceOf(Seeder::class, $seeder);
//        }
    }

}
