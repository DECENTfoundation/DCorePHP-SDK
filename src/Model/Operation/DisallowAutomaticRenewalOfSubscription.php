<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Model\BaseOperation;

class DisallowAutomaticRenewalOfSubscription extends BaseOperation
{
    public const OPERATION_TYPE = 40;
    public const OPERATION_NAME = 'disallow_automatic_renewal_of_subscription';
}
