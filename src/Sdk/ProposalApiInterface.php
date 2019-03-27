<?php

namespace DCorePHP\Sdk;

use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\Proposal\DeltaParameters;
use DCorePHP\Model\Proposal\FeesParameters;
use DCorePHP\Model\Proposal\ProposalObject;
use DCorePHP\Model\Proposal\ProposalParameters;

interface ProposalApiInterface
{

    /** Lists proposed transactions relevant to a user
     * @param string $account the name or id of the account
     * @return ProposalObject[] a list of proposed transactions
     */
    public function getProposedTransactions(string $account): array;

    /**
     * @param string $proposer proposer
     * @param string $from the name or id of the account sending the funds
     * @param string $to the name or id of the account receiving the funds
     * @param string $amount the amount to send (in nominal units – to send half of a DCT, specify 0.5)
     * @param string $assetSymbol the symbol or id of the asset to send
     * @param string $memo a memo to attach to the transaction
     * @param string $expiration expiration time
     * @return BaseOperation
     */
    public function proposeTransfer(string $proposer, string $from, string $to, string $amount, string $assetSymbol, string $memo, string $expiration): BaseOperation;

    /**
     * Creates a transaction to propose a parameter change
     * @param string $proposingAccount the account paying the fee to propose the transaction
     * @param string $expirationTime timestamp specifying when the proposal will either take effect or expire
     * @param ProposalParameters $proposalParameters the values to change; all other chain parameters are filled in with default values
     * @param bool $broadcast true if you wish to broadcast the transaction
     * @return BaseOperation the signed version of the transaction
     */
    public function proposeParameterChange(string $proposingAccount, string $expirationTime, ProposalParameters $proposalParameters, bool $broadcast = false): BaseOperation;

    /**
     * Propose a fee change
     * @param string $proposingAccount the account paying the fee to propose the transaction
     * @param string $expirationTime timestamp specifying when the proposal will either take effect or expire
     * @param FeesParameters $feesParameters map of operation type to new fee
     * @param bool $broadcast true if you wish to broadcast the transaction
     * @return BaseOperation the signed version of the transaction
     */
    public function proposeFeeChange(string $proposingAccount, string $expirationTime, FeesParameters $feesParameters, bool $broadcast = false): BaseOperation;

    /**
     * Approve or disapprove a proposal
     * @param string $feePayingAccount the account paying the fee for the operation
     * @param string $proposalId the proposal to modify
     * @param DeltaParameters $approvalDelta members contain approvals to create or remove
     * @param bool $broadcast true if you wish to broadcast the transaction
     * @return BaseOperation the signed version of the transaction
     */
    public function approveProposal(string $feePayingAccount, string $proposalId, DeltaParameters $approvalDelta, bool $broadcast = true): BaseOperation;

}