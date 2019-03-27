<?php

namespace DCorePHP\Exception;

use DCorePHP\DCorePHPException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationException extends DCorePHPException
{
    /**
     * @param ConstraintViolationListInterface $violationList
     */
    public function __construct(ConstraintViolationListInterface $violationList)
    {
        $violations = [];
        foreach ($violationList as $violation) {
            $violations[] = $violation->getMessage();
        }

        parent::__construct('Validation exception: ' . json_encode($violations));
    }
}