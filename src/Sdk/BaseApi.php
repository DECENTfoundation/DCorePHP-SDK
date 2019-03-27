<?php


namespace DCorePHP\Sdk;

use DCorePHP\DCoreApi;

class BaseApi
{
    /** @var DCoreApi */
    protected $dcoreApi;

    public function __construct(DCoreApi $dcoreApi)
    {
        $this->dcoreApi = $dcoreApi;
    }
}