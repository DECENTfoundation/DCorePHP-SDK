<?php

namespace DCorePHPTests\Model;

use DCorePHP\Model\Authority;
use DCorePHP\Model\Subscription\AuthMap;
use PHPUnit\Framework\TestCase;

class AuthorityTest extends TestCase
{
    public function testToBytes()
    {
        $keyAuths = new AuthMap();
        $keyAuths->setValue('DCT6718kUCCksnkeYD1YySWkXb1VLpzjkFfHHMirCRPexp5gDPJLU');

        $authority = new Authority();
        $authority->setKeyAuths([$keyAuths]);

        $this->assertEquals(
            '01000000000102a01c045821676cfc191832ad22cc5c9ade0ea1760131c87ff2dd3fed2f13dd330100',
            $authority->toBytes()
        );
    }
}
