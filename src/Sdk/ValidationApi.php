<?php

namespace DCorePHP\Sdk;

use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Operation\AddOrUpdateContentOperation;
use DCorePHP\Model\Operation\AssertOperation;
use DCorePHP\Model\Operation\AssetCreateOperation;
use DCorePHP\Model\Operation\AssetIssueOperation;
use DCorePHP\Model\Operation\CustomOperation;
use DCorePHP\Model\Operation\EmptyOperation;
use DCorePHP\Model\Operation\ProposalCreate;
use DCorePHP\Model\Operation\ProposalUpdate;
use DCorePHP\Model\Operation\WithdrawPermissionClaim;
use DCorePHP\Model\ProcessedTransaction;
use DCorePHP\Model\Transaction;
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
     * @inheritdoc
     */
    public function getRequiredSignatures(Transaction $transaction, array $keys): array
    {
        return $this->dcoreApi->requestWebsocket(new GetRequiredSignatures($transaction, $keys));
    }

    /**
     * @inheritdoc
     */
    public function getPotentialSignatures(Transaction $transaction): array
    {
        return $this->dcoreApi->requestWebsocket(new GetPotentialSignatures($transaction));
    }

    /**
     * @inheritdoc
     */
    public function verifyAuthority(Transaction $transaction): bool
    {
        return $this->dcoreApi->requestWebsocket(new VerifyAuthority($transaction));
    }

    /**
     * @inheritdoc
     */
    public function verifyAccountAuthority(string $nameOrId, array $keys): bool
    {
        return $this->dcoreApi->requestWebsocket(new VerifyAccountAuthority($nameOrId, $keys));
    }

    /**
     * @inheritdoc
     */
    public function validateTransaction(Transaction $transaction): ProcessedTransaction
    {
        return $this->dcoreApi->requestWebsocket(new ValidateTransaction($transaction));
    }

    /**
     * @inheritdoc
     */
    public function getFees(array $op, ChainObject $assetId = null): array
    {
        return $this->dcoreApi->requestWebsocket(new GetRequiredFees($op, $assetId));
    }

    /**
     * @inheritdoc
     */
    public function getFee(BaseOperation $op, ChainObject $assetId = null): AssetAmount
    {
        $fees = $this->getFees([$op], $assetId);
        return reset($fees);
    }

    /**
     * @inheritDoc
     */
    public function getFeesForType(array $types, ChainObject$assetId = null): array {
        $assetId = $assetId ?: new ChainObject('1.3.0');

        $notAllowed = [
            AssetCreateOperation::OPERATION_TYPE,
            AssetIssueOperation::OPERATION_TYPE,
            ProposalCreate::OPERATION_TYPE,
            ProposalUpdate::OPERATION_TYPE,
            WithdrawPermissionClaim::OPERATION_TYPE,
            CustomOperation::OPERATION_TYPE,
            AssertOperation::OPERATION_TYPE,
            AddOrUpdateContentOperation::OPERATION_TYPE
        ];

        if (!empty(array_intersect($types, $notAllowed))) {
            throw new InvalidArgumentException('This type of operation is not allowed!');
        }

        return $this->getFees(array_map(static function ($type) { return new EmptyOperation($type); }, $types), $assetId);
    }

    /**
     * @inheritdoc
     */
    public function getFeeForType($type, ChainObject $assetId = null): AssetAmount
    {
        $fees = $this->getFeesForType([$type], $assetId);
        return reset($fees);
    }
}