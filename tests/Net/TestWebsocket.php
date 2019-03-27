<?php

namespace DCorePHPTests\Net;

use DCorePHPTests\DCoreSDKTest;
use Symfony\Component\Process\Process;

class TestWebsocket extends DCoreSDKTest
{
    /**
     * test multiple parallel calls to websocket
     * to prove that our websocket implementation is capable of handling multiple client connection at the same time
     */
    public function testSendParallel()
    {
        $processes = [];
        for ($i = 0; $i < 10; $i++) {
            // test getAccountById
//            $processes[$i] = Process::fromShellCommandline('/opt/project/vendor/phpunit/phpunit/phpunit --bootstrap /opt/project/vendor/autoload.php --no-configuration --filter "/(::testGetAccountById)( .*)?$/" DCorePHPTests\Sdk\AccountApiTest /opt/project/tests/Sdk/AccountApiTest.php');

            // test transfer
            $processes[$i] = Process::fromShellCommandline('/opt/project/vendor/phpunit/phpunit/phpunit --bootstrap /opt/project/vendor/autoload.php --no-configuration --filter "/(::testTransfer)( .*)?$/" DCorePHPTests\Sdk\AccountApiTest /opt/project/tests/Sdk/AccountApiTest.php');

            $processes[$i]->start();
        }

        for ($i = 0; $i < 10; $i++) {
            while ($processes[$i]->isRunning()) {
                // waiting for process to finish
            }

//            dump($processes[$i]->getOutput());

            $this->assertRegExp('/OK\s\(1\stest,\s/', $processes[$i]->getOutput(), $processes[$i]->getOutput());
        }
    }
}