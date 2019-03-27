<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Model\ChainObject;
use DCorePHP\Utils\Math;

class SendMessageOperation extends CustomOperation
{
    public function __construct(string $messagePayloadJson, ChainObject $payer, array $requiredAuths = null)
    {
        parent::__construct();
        $this
            ->setId(self::CUSTOM_TYPE_MESSAGE)
            ->setData(Math::byteArrayToHex(Math::stringToByteArray($messagePayloadJson)))
            ->setPayer($payer)
            ->setRequiredAuths($requiredAuths ?? [$payer]);
    }
}