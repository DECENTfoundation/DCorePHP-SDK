<?php


namespace DCorePHP\Sdk;


use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\Proposal\DeltaParameters;
use DCorePHP\Model\Proposal\FeesParameters;
use DCorePHP\Model\Proposal\ProposalParameters;

class ProposalApi extends BaseApi implements ProposalApiInterface
{

    /**
     * @inheritdoc
     */
    public function getProposedTransactions(string $account): array
    {
        // TODO: Implement getProposedTransactions() method.
    }

    /**
     * @inheritdoc
     */
    public function proposeTransfer(
        string $proposer,
        string $from,
        string $to,
        string $amount,
        string $assetSymbol,
        string $memo,
        string $expiration
    ): BaseOperation
    {
        // TODO: Implement proposeTransfer() method.
    }

    /**
     * @inheritdoc
     */
    public function proposeParameterChange(
        string $proposingAccount,
        string $expirationTime,
        ProposalParameters $proposalParameters,
        bool $broadcast = false
    ): BaseOperation
    {
        // TODO: Implement proposeParameterChange() method.
    }

    /**
     * @inheritdoc
     */
    public function proposeFeeChange(
        string $proposingAccount,
        string $expirationTime,
        FeesParameters $feesParameters,
        bool $broadcast = false
    ): BaseOperation
    {
        // TODO: Implement proposeFeeChange() method.
    }

    /**
     * @inheritdoc
     */
    public function approveProposal(
        string $feePayingAccount,
        string $proposalId,
        DeltaParameters $approvalDelta,
        bool $broadcast = true
    ): BaseOperation
    {
        // TODO: Implement approveProposal() method.
    }
}