<?php

namespace DCorePHPTests;

use DCorePHP\DCoreApi;
use PHPUnit\Framework\TestCase;

abstract class DCoreSDKTest extends TestCase
{
    public const ACCOUNT_ID_1 = '1.2.27';
    public const ACCOUNT_ID_2 = '1.2.28';
    public const ACCOUNT_NAME_1 = 'public-account-9';
    public const ACCOUNT_NAME_2 = 'public-account-10';
    public const PRIVATE_KEY_1 = '5JuJbrKZgAATcouJnwpaxPbHMAMDXSgUpQSfxTXzkSUufcnpTUa';
    public const PRIVATE_KEY_2 = '5JuJbrKZgAATcouJnwpaxPbHMAMDXSgUpQSfxTXzkSUufcnpTUa';
    public const PUBLIC_KEY_1 = 'DCT82MTCQVa9TDFmz3ZwaLzsFAmCLoJzrtFugpF72vsbuE1CpCwKy';
    public const PUBLIC_KEY_2 = 'DCT82MTCQVa9TDFmz3ZwaLzsFAmCLoJzrtFugpF72vsbuE1CpCwKy';

    /** @var DCoreApi */
    protected static $sdk;

    public static function setUpBeforeClass()
    {
        self::$sdk = new DCoreApi(
            'http://dcore:8090/',
            'ws://dcore:8090/',
            true
        );
    }

    public static function tearDownAfterClass()
    {
        self::$sdk = null;
    }
}
