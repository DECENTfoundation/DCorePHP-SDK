<?php

namespace DCorePHPTests\Model;

use DCorePHP\Model\Subscription\AuthMap;
use PHPUnit\Framework\TestCase;

class AuthMapTest extends TestCase
{
    public function testToBytes()
    {
        $keyAuths = new AuthMap();
        $keyAuths->setValue('DCT6718kUCCksnkeYD1YySWkXb1VLpzjkFfHHMirCRPexp5gDPJLU');

        $this->assertEquals(
            '02a01c045821676cfc191832ad22cc5c9ade0ea1760131c87ff2dd3fed2f13dd330100',
            $keyAuths->toBytes()
        );
    }
}