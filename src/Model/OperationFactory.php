<?php

namespace DCorePHP\Model;

class OperationFactory
{
    /**
     * @return array
     */
    private static function getTypes(): array
    {
        return [
            Operation\Transfer::OPERATION_TYPE => Operation\Transfer::class,
            Operation\CreateAccount::OPERATION_TYPE => Operation\CreateAccount::class,
            Operation\UpdateAccount::OPERATION_TYPE => Operation\UpdateAccount::class,
            Operation\AssetCreateOperation::OPERATION_TYPE => Operation\AssetCreateOperation::class,
            Operation\AssetIssueOperation::OPERATION_TYPE => Operation\AssetIssueOperation::class,
            5 => 'asset_publish_feed',
            6 => 'miner_create',
            7 => 'miner_update',
            8 => 'miner_update_global_parameters',
            Operation\ProposalCreate::OPERATION_TYPE => Operation\ProposalCreate::class,
            10 => 'proposal_update',
            11 => 'proposal_delete',
            12 => 'withdraw_permission_create',
            13 => 'withdraw_permission_update',
            14 => 'withdraw_permission_claim',
            15 => 'withdraw_permission_delete',
            Operation\VestingBalanceCreate::OPERATION_TYPE => Operation\VestingBalanceCreate::class,
            Operation\VestingBalanceWithdraw::OPERATION_TYPE => Operation\VestingBalanceWithdraw::class,
            Operation\CustomOperation::OPERATION_TYPE => Operation\CustomOperation::class,
            19 => 'assert',
            Operation\ContentSubmitOperation::OPERATION_TYPE => Operation\ContentSubmitOperation::class,
            Operation\RequestToBuy::OPERATION_TYPE => Operation\RequestToBuy::class,
            Operation\LeaveRatingAndComment::OPERATION_TYPE => Operation\LeaveRatingAndComment::class,
            23 => 'ready_to_publish',
            Operation\ProofOfCustodyOperation::OPERATION_TYPE => Operation\ProofOfCustodyOperation::class,
            25 => 'deliver_keys',
            Operation\Subscribe::OPERATION_TYPE => Operation\Subscribe::class,
            27 => 'subscribe_by_author',
            Operation\AutomaticRenewalOfSubscription::OPERATION_TYPE => Operation\AutomaticRenewalOfSubscription::class,
            29 => 'report_stats',
            30 => 'set_publishing_manager',
            31 => 'set_publishing_right',
            Operation\ContentCancellationOperation::OPERATION_TYPE => Operation\ContentCancellationOperation::class,
            Operation\AssetFundPoolsOperation::OPERATION_TYPE => Operation\AssetFundPoolsOperation::class,
            Operation\AssetReserveOperation::OPERATION_TYPE => Operation\AssetReserveOperation::class,
            Operation\AssetClaimFeesOperation::OPERATION_TYPE => Operation\AssetClaimFeesOperation::class,
            Operation\AssetUpdateOperation::OPERATION_TYPE => Operation\AssetUpdateOperation::class,
            37 => 'update_monitored_asset_operation',
            Operation\ReadyToPublish2Operation::OPERATION_TYPE => Operation\ReadyToPublish2Operation::class,
            Operation\Transfer2::OPERATION_TYPE => Operation\Transfer2::class,
            Operation\UpdateAssetIssuedOperation::OPERATION_TYPE => Operation\UpdateAssetIssuedOperation::class, // @todo duplicate operation type
            Operation\ReturnEscrowSubmission::OPERATION_TYPE => Operation\ReturnEscrowSubmission::class,
            Operation\ReturnEscrowBuying::OPERATION_TYPE => Operation\ReturnEscrowBuying::class,
            Operation\PaySeederOperation::OPERATION_TYPE => Operation\PaySeederOperation::class,
            Operation\FinishBuyingOperation::OPERATION_TYPE => Operation\FinishBuyingOperation::class,
            Operation\RenewalOfSubscription::OPERATION_TYPE => Operation\RenewalOfSubscription::class,
            46 => 'unknown',
        ];
    }

    /**
     * @param int $type
     * @param array $rawOperation
     * @return BaseOperation
     * @throws InvalidOperationTypeException
     */
    public static function getOperation(int $type, array $rawOperation): BaseOperation
    {
        if (!array_key_exists($type, self::getTypes())) {
            throw new InvalidOperationTypeException($type, self::getTypes());
        }

        $class = self::getTypes()[$type];

        /** @var BaseOperation $operation */
        $operation = new $class();
        $operation->hydrate($rawOperation);

        return $operation;
    }
}
