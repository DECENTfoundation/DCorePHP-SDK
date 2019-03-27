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
            Operation\AccountUpdate::OPERATION_TYPE => Operation\AccountUpdate::class,
            3 => 'asset_create',
            4 => 'asset_issue',
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
            22 => 'leave_rating_and_comment',
            23 => 'ready_to_publish',
            Operation\ProofOfCustodyOperation::OPERATION_TYPE => Operation\ProofOfCustodyOperation::class,
            25 => 'deliver_keys',
            Operation\Subscribe::OPERATION_TYPE => Operation\Subscribe::class,
            27 => 'subscribe_by_author',
            Operation\AutomaticRenewalOfSubscription::OPERATION_TYPE => Operation\AutomaticRenewalOfSubscription::class,
            29 => 'report_stats',
            30 => 'set_publishing_manager',
            31 => 'set_publishing_right',
            Operation\ContentCancellation::OPERATION_TYPE => Operation\ContentCancellation::class,
            33 => 'asset_fund_pools_operation',
            34 => 'asset_reserve_operation',
            35 => 'asset_claim_fees_operation',
            36 => 'update_user_issued_asset',
            37 => 'update_monitored_asset_operation',
            Operation\ReadyToPublish2Operation::OPERATION_TYPE => Operation\ReadyToPublish2Operation::class,
            Operation\Transfer2::OPERATION_TYPE => Operation\Transfer2::class,
            40 => 'disallow_automatic_renewal_of_subscription',
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
