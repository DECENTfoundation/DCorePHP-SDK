<?php

namespace DCorePHP\Sdk;

use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Operation\CustomOperation;
use DCorePHP\Model\Operation\EmptyOperation;
use DCorePHP\Model\Operation\ProposalCreate;
use DCorePHP\Model\Operation\ProposalUpdate;
use DCorePHP\Model\Operation\WithdrawPermissionClaim;
use DCorePHP\Model\ProcessedTransaction;
use DCorePHP\Model\Transaction;
use DCorePHP\Net\Model\Request\Database;
use DCorePHP\Net\Model\Request\GetPotentialSignatures;
use DCorePHP\Net\Model\Request\GetRequiredFees;
use DCorePHP\Net\Model\Request\GetRequiredSignatures;
use DCorePHP\Net\Model\Request\ValidateTransaction;
use DCorePHP\Net\Model\Request\VerifyAccountAuthority;
use DCorePHP\Net\Model\Request\VerifyAuthority;
use InvalidArgumentException;

class ValidationApi extends BaseApi implements ValidationApiInterface
{
    /**
     * @inheritDoc
     */
    public function getRequiredSignatures(Transaction $transaction, array $keys): array
    {
        return $this->dcoreApi->requestWebsocket(Database::class, new GetRequiredSignatures($transaction, $keys));
    }

    /**
     * @inheritDoc
     */
    public function getPotentialSignatures(Transaction $transaction): array
    {
        return $this->dcoreApi->requestWebsocket(Database::class, new GetPotentialSignatures($transaction));
    }

    /**
     * @inheritDoc
     */
    public function verifyAuthority(Transaction $transaction): bool
    {
        return $this->dcoreApi->requestWebsocket(Database::class, new VerifyAuthority($transaction));
    }

    /**
     * @inheritDoc
     */
    public function verifyAccountAuthority(string $nameOrId, array $keys): bool
    {
        return $this->dcoreApi->requestWebsocket(Database::class, new VerifyAccountAuthority($nameOrId, $keys));
    }

    /**
     * @inheritDoc
     */
    public function validateTransaction(Transaction $transaction): ProcessedTransaction
    {
        return $this->dcoreApi->requestWebsocket(Database::class, new ValidateTransaction($transaction));
    }

    /**
     * @inheritDoc
     */
    public function getFees(array $op, ChainObject $assetId = null): array
    {
        return $this->dcoreApi->requestWebsocket(Database::class, new GetRequiredFees($op, $assetId));
    }

    /**
     * @inheritDoc
     */
    public function getFee(BaseOperation $op, ChainObject $assetId = null): AssetAmount
    {
        $fees = $this->getFees([$op], $assetId);
        return reset($fees);
    }

    /**
     * @inheritDoc
     */
    public function getFeeByType($type, ChainObject $assetId = null): AssetAmount
    {
        if (in_array(
            $type,
            [
                ProposalCreate::OPERATION_TYPE,
                ProposalUpdate::OPERATION_TYPE,
                WithdrawPermissionClaim::OPERATION_TYPE,
                CustomOperation::OPERATION_TYPE
            ],
            true
        )) {
            throw new InvalidArgumentException('This type of operation is not allowed!');
        }
        return $this->getFee(new EmptyOperation($type), $assetId);
    }
}