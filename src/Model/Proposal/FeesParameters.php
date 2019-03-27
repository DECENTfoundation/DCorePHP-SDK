<?php


namespace DCorePHP\Model\Proposal;


class FeesParameters
{

    /** @var Fee */
    private $transfer;
    /** @var BasicFee */
    private $accountCreate;
    /** @var Fee */
    private $accountUpdate;
    /** @var BasicFee */
    private $assetCreate;
    /** @var Fee */
    private $assetUpdate;
    /** @var Fee */
    private $assetPublishFeed;
    /** @var Fee */
    private $minerCreate;
    /** @var Fee */
    private $minerUpdate;
    /** @var Fee */
    private $minerUpdateGlobalParameters;
    /** @var Fee */
    private $proposalCreate;
    /** @var Fee */
    private $proposalUpdate;
    /** @var Fee */
    private $proposalDelete;
    /** @var Fee */
    private $withdrawPermissionCreate;
    /** @var Fee */
    private $withdrawPermissionUpdate;
    /** @var Fee */
    private $withdrawPermissionClaim;
    /** @var Fee */
    private $withdrawPermissionDelete;
    /** @var Fee */
    private $vestingBalanceCreate;
    /** @var Fee */
    private $vestingBalance_withdraw;
    /** @var Fee */
    private $custom;
    /** @var Fee */
    private $assert;
    /** @var Fee */
    private $contentSubmit;
    /** @var Fee */
    private $requestToBuy;
    /** @var Fee */
    private $leaveRatingAndComment;
    /** @var Fee */
    private $readyToPublish;
    /** @var Fee */
    private $proofOfCustody;
    /** @var Fee */
    private $deliverKeys;
    /** @var Fee */
    private $subscribe;
    /** @var Fee */
    private $subscribeByAuthor;
    /** @var Fee */
    private $automaticRenewalOfSubscription;
    /** @var Fee */
    private $reportStats;
    /** @var Fee */
    private $setPublishing_manager;
    /** @var Fee */
    private $setPublishingRight;
    /** @var Fee */
    private $contentCancellation;
    /** @var Fee */
    private $assetFundPoolsOperation;
    /** @var Fee */
    private $assetReserveOperation;
    /** @var Fee */
    private $assetClaimFeesOperation;
    /** @var Fee */
    private $updateUserIssuedAsset;
    /** @var Fee */
    private $updateMonitoredAssetOperation;
    /** @var Fee */
    private $readyToPublish2;
    /** @var Fee */
    private $transfer2;
    /** @var Fee */
    private $disallowAutomaticRenewalOfSubscription;
    /** @var Fee */
    private $returnEscrowSubmission;
    /** @var Fee */
    private $returnEscrowBuying;
    /** @var Fee */
    private $paySeeder;
    /** @var Fee */
    private $finishBuying;
    /** @var Fee */
    private $renewalOfSubscription;

    /**
     * @return Fee
     */
    public function getTransfer(): Fee
    {
        return $this->transfer;
    }

    /**
     * @param Fee $transfer
     * @return FeesParameters
     */
    public function setTransfer(Fee $transfer): FeesParameters
    {
        $this->transfer = $transfer;

        return $this;
    }

    /**
     * @return BasicFee
     */
    public function getAccountCreate(): BasicFee
    {
        return $this->accountCreate;
    }

    /**
     * @param BasicFee $accountCreate
     * @return FeesParameters
     */
    public function setAccountCreate(BasicFee $accountCreate): FeesParameters
    {
        $this->accountCreate = $accountCreate;

        return $this;
    }

    /**
     * @return Fee
     */
    public function getAccountUpdate(): Fee
    {
        return $this->accountUpdate;
    }

    /**
     * @param Fee $accountUpdate
     * @return FeesParameters
     */
    public function setAccountUpdate(Fee $accountUpdate): FeesParameters
    {
        $this->accountUpdate = $accountUpdate;

        return $this;
    }

    /**
     * @return BasicFee
     */
    public function getAssetCreate(): BasicFee
    {
        return $this->assetCreate;
    }

    /**
     * @param BasicFee $assetCreate
     * @return FeesParameters
     */
    public function setAssetCreate(BasicFee $assetCreate): FeesParameters
    {
        $this->assetCreate = $assetCreate;

        return $this;
    }

    /**
     * @return Fee
     */
    public function getAssetUpdate(): Fee
    {
        return $this->assetUpdate;
    }

    /**
     * @param Fee $assetUpdate
     * @return FeesParameters
     */
    public function setAssetUpdate(Fee $assetUpdate): FeesParameters
    {
        $this->assetUpdate = $assetUpdate;

        return $this;
    }

    /**
     * @return Fee
     */
    public function getAssetPublishFeed(): Fee
    {
        return $this->assetPublishFeed;
    }

    /**
     * @param Fee $assetPublishFeed
     * @return FeesParameters
     */
    public function setAssetPublishFeed(Fee $assetPublishFeed): FeesParameters
    {
        $this->assetPublishFeed = $assetPublishFeed;

        return $this;
    }

    /**
     * @return Fee
     */
    public function getMinerCreate(): Fee
    {
        return $this->minerCreate;
    }

    /**
     * @param Fee $minerCreate
     * @return FeesParameters
     */
    public function setMinerCreate(Fee $minerCreate): FeesParameters
    {
        $this->minerCreate = $minerCreate;

        return $this;
    }

    /**
     * @return Fee
     */
    public function getMinerUpdate(): Fee
    {
        return $this->minerUpdate;
    }

    /**
     * @param Fee $minerUpdate
     * @return FeesParameters
     */
    public function setMinerUpdate(Fee $minerUpdate): FeesParameters
    {
        $this->minerUpdate = $minerUpdate;

        return $this;
    }

    /**
     * @return Fee
     */
    public function getMinerUpdateGlobalParameters(): Fee
    {
        return $this->minerUpdateGlobalParameters;
    }

    /**
     * @param Fee $minerUpdateGlobalParameters
     * @return FeesParameters
     */
    public function setMinerUpdateGlobalParameters(Fee $minerUpdateGlobalParameters): FeesParameters
    {
        $this->minerUpdateGlobalParameters = $minerUpdateGlobalParameters;

        return $this;
    }

    /**
     * @return Fee
     */
    public function getProposalCreate(): Fee
    {
        return $this->proposalCreate;
    }

    /**
     * @param Fee $proposalCreate
     * @return FeesParameters
     */
    public function setProposalCreate(Fee $proposalCreate): FeesParameters
    {
        $this->proposalCreate = $proposalCreate;

        return $this;
    }

    /**
     * @return Fee
     */
    public function getProposalUpdate(): Fee
    {
        return $this->proposalUpdate;
    }

    /**
     * @param Fee $proposalUpdate
     * @return FeesParameters
     */
    public function setProposalUpdate(Fee $proposalUpdate): FeesParameters
    {
        $this->proposalUpdate = $proposalUpdate;

        return $this;
    }

    /**
     * @return Fee
     */
    public function getProposalDelete(): Fee
    {
        return $this->proposalDelete;
    }

    /**
     * @param Fee $proposalDelete
     * @return FeesParameters
     */
    public function setProposalDelete(Fee $proposalDelete): FeesParameters
    {
        $this->proposalDelete = $proposalDelete;

        return $this;
    }

    /**
     * @return Fee
     */
    public function getWithdrawPermissionCreate(): Fee
    {
        return $this->withdrawPermissionCreate;
    }

    /**
     * @param Fee $withdrawPermissionCreate
     * @return FeesParameters
     */
    public function setWithdrawPermissionCreate(Fee $withdrawPermissionCreate): FeesParameters
    {
        $this->withdrawPermissionCreate = $withdrawPermissionCreate;

        return $this;
    }

    /**
     * @return Fee
     */
    public function getWithdrawPermissionUpdate(): Fee
    {
        return $this->withdrawPermissionUpdate;
    }

    /**
     * @param Fee $withdrawPermissionUpdate
     * @return FeesParameters
     */
    public function setWithdrawPermissionUpdate(Fee $withdrawPermissionUpdate): FeesParameters
    {
        $this->withdrawPermissionUpdate = $withdrawPermissionUpdate;

        return $this;
    }

    /**
     * @return Fee
     */
    public function getWithdrawPermissionClaim(): Fee
    {
        return $this->withdrawPermissionClaim;
    }

    /**
     * @param Fee $withdrawPermissionClaim
     * @return FeesParameters
     */
    public function setWithdrawPermissionClaim(Fee $withdrawPermissionClaim): FeesParameters
    {
        $this->withdrawPermissionClaim = $withdrawPermissionClaim;

        return $this;
    }

    /**
     * @return Fee
     */
    public function getWithdrawPermissionDelete(): Fee
    {
        return $this->withdrawPermissionDelete;
    }

    /**
     * @param Fee $withdrawPermissionDelete
     * @return FeesParameters
     */
    public function setWithdrawPermissionDelete(Fee $withdrawPermissionDelete): FeesParameters
    {
        $this->withdrawPermissionDelete = $withdrawPermissionDelete;

        return $this;
    }

    /**
     * @return Fee
     */
    public function getVestingBalanceCreate(): Fee
    {
        return $this->vestingBalanceCreate;
    }

    /**
     * @param Fee $vestingBalanceCreate
     * @return FeesParameters
     */
    public function setVestingBalanceCreate(Fee $vestingBalanceCreate): FeesParameters
    {
        $this->vestingBalanceCreate = $vestingBalanceCreate;

        return $this;
    }

    /**
     * @return Fee
     */
    public function getVestingBalanceWithdraw(): Fee
    {
        return $this->vestingBalance_withdraw;
    }

    /**
     * @param Fee $vestingBalance_withdraw
     * @return FeesParameters
     */
    public function setVestingBalanceWithdraw(Fee $vestingBalance_withdraw): FeesParameters
    {
        $this->vestingBalance_withdraw = $vestingBalance_withdraw;

        return $this;
    }

    /**
     * @return Fee
     */
    public function getCustom(): Fee
    {
        return $this->custom;
    }

    /**
     * @param Fee $custom
     * @return FeesParameters
     */
    public function setCustom(Fee $custom): FeesParameters
    {
        $this->custom = $custom;

        return $this;
    }

    /**
     * @return Fee
     */
    public function getAssert(): Fee
    {
        return $this->assert;
    }

    /**
     * @param Fee $assert
     * @return FeesParameters
     */
    public function setAssert(Fee $assert): FeesParameters
    {
        $this->assert = $assert;

        return $this;
    }

    /**
     * @return Fee
     */
    public function getContentSubmit(): Fee
    {
        return $this->contentSubmit;
    }

    /**
     * @param Fee $contentSubmit
     * @return FeesParameters
     */
    public function setContentSubmit(Fee $contentSubmit): FeesParameters
    {
        $this->contentSubmit = $contentSubmit;

        return $this;
    }

    /**
     * @return Fee
     */
    public function getRequestToBuy(): Fee
    {
        return $this->requestToBuy;
    }

    /**
     * @param Fee $requestToBuy
     * @return FeesParameters
     */
    public function setRequestToBuy(Fee $requestToBuy): FeesParameters
    {
        $this->requestToBuy = $requestToBuy;

        return $this;
    }

    /**
     * @return Fee
     */
    public function getLeaveRatingAndComment(): Fee
    {
        return $this->leaveRatingAndComment;
    }

    /**
     * @param Fee $leaveRatingAndComment
     * @return FeesParameters
     */
    public function setLeaveRatingAndComment(Fee $leaveRatingAndComment): FeesParameters
    {
        $this->leaveRatingAndComment = $leaveRatingAndComment;

        return $this;
    }

    /**
     * @return Fee
     */
    public function getReadyToPublish(): Fee
    {
        return $this->readyToPublish;
    }

    /**
     * @param Fee $readyToPublish
     * @return FeesParameters
     */
    public function setReadyToPublish(Fee $readyToPublish): FeesParameters
    {
        $this->readyToPublish = $readyToPublish;

        return $this;
    }

    /**
     * @return Fee
     */
    public function getProofOfCustody(): Fee
    {
        return $this->proofOfCustody;
    }

    /**
     * @param Fee $proofOfCustody
     * @return FeesParameters
     */
    public function setProofOfCustody(Fee $proofOfCustody): FeesParameters
    {
        $this->proofOfCustody = $proofOfCustody;

        return $this;
    }

    /**
     * @return Fee
     */
    public function getDeliverKeys(): Fee
    {
        return $this->deliverKeys;
    }

    /**
     * @param Fee $deliverKeys
     * @return FeesParameters
     */
    public function setDeliverKeys(Fee $deliverKeys): FeesParameters
    {
        $this->deliverKeys = $deliverKeys;

        return $this;
    }

    /**
     * @return Fee
     */
    public function getSubscribe(): Fee
    {
        return $this->subscribe;
    }

    /**
     * @param Fee $subscribe
     * @return FeesParameters
     */
    public function setSubscribe(Fee $subscribe): FeesParameters
    {
        $this->subscribe = $subscribe;

        return $this;
    }

    /**
     * @return Fee
     */
    public function getSubscribeByAuthor(): Fee
    {
        return $this->subscribeByAuthor;
    }

    /**
     * @param Fee $subscribeByAuthor
     * @return FeesParameters
     */
    public function setSubscribeByAuthor(Fee $subscribeByAuthor): FeesParameters
    {
        $this->subscribeByAuthor = $subscribeByAuthor;

        return $this;
    }

    /**
     * @return Fee
     */
    public function getAutomaticRenewalOfSubscription(): Fee
    {
        return $this->automaticRenewalOfSubscription;
    }

    /**
     * @param Fee $automaticRenewalOfSubscription
     * @return FeesParameters
     */
    public function setAutomaticRenewalOfSubscription(Fee $automaticRenewalOfSubscription): FeesParameters
    {
        $this->automaticRenewalOfSubscription = $automaticRenewalOfSubscription;

        return $this;
    }

    /**
     * @return Fee
     */
    public function getReportStats(): Fee
    {
        return $this->reportStats;
    }

    /**
     * @param Fee $reportStats
     * @return FeesParameters
     */
    public function setReportStats(Fee $reportStats): FeesParameters
    {
        $this->reportStats = $reportStats;

        return $this;
    }

    /**
     * @return Fee
     */
    public function getSetPublishingManager(): Fee
    {
        return $this->setPublishing_manager;
    }

    /**
     * @param Fee $setPublishing_manager
     * @return FeesParameters
     */
    public function setSetPublishingManager(Fee $setPublishing_manager): FeesParameters
    {
        $this->setPublishing_manager = $setPublishing_manager;

        return $this;
    }

    /**
     * @return Fee
     */
    public function getSetPublishingRight(): Fee
    {
        return $this->setPublishingRight;
    }

    /**
     * @param Fee $setPublishingRight
     * @return FeesParameters
     */
    public function setSetPublishingRight(Fee $setPublishingRight): FeesParameters
    {
        $this->setPublishingRight = $setPublishingRight;

        return $this;
    }

    /**
     * @return Fee
     */
    public function getContentCancellation(): Fee
    {
        return $this->contentCancellation;
    }

    /**
     * @param Fee $contentCancellation
     * @return FeesParameters
     */
    public function setContentCancellation(Fee $contentCancellation): FeesParameters
    {
        $this->contentCancellation = $contentCancellation;

        return $this;
    }

    /**
     * @return Fee
     */
    public function getAssetFundPoolsOperation(): Fee
    {
        return $this->assetFundPoolsOperation;
    }

    /**
     * @param Fee $assetFundPoolsOperation
     * @return FeesParameters
     */
    public function setAssetFundPoolsOperation(Fee $assetFundPoolsOperation): FeesParameters
    {
        $this->assetFundPoolsOperation = $assetFundPoolsOperation;

        return $this;
    }

    /**
     * @return Fee
     */
    public function getAssetReserveOperation(): Fee
    {
        return $this->assetReserveOperation;
    }

    /**
     * @param Fee $assetReserveOperation
     */
    public function setAssetReserveOperation(Fee $assetReserveOperation): void
    {
        $this->assetReserveOperation = $assetReserveOperation;

        return $this;
    }

    /**
     * @return Fee
     */
    public function getAssetClaimFeesOperation(): Fee
    {
        return $this->assetClaimFeesOperation;
    }

    /**
     * @param Fee $assetClaimFeesOperation
     * @return FeesParameters
     */
    public function setAssetClaimFeesOperation(Fee $assetClaimFeesOperation): FeesParameters
    {
        $this->assetClaimFeesOperation = $assetClaimFeesOperation;

        return $this;
    }

    /**
     * @return Fee
     */
    public function getUpdateUserIssuedAsset(): Fee
    {
        return $this->updateUserIssuedAsset;
    }

    /**
     * @param Fee $updateUserIssuedAsset
     * @return FeesParameters
     */
    public function setUpdateUserIssuedAsset(Fee $updateUserIssuedAsset): FeesParameters
    {
        $this->updateUserIssuedAsset = $updateUserIssuedAsset;

        return $this;
    }

    /**
     * @return Fee
     */
    public function getUpdateMonitoredAssetOperation(): Fee
    {
        return $this->updateMonitoredAssetOperation;
    }

    /**
     * @param Fee $updateMonitoredAssetOperation
     * @return FeesParameters
     */
    public function setUpdateMonitoredAssetOperation(Fee $updateMonitoredAssetOperation): FeesParameters
    {
        $this->updateMonitoredAssetOperation = $updateMonitoredAssetOperation;

        return $this;
    }

    /**
     * @return Fee
     */
    public function getReadyToPublish2(): Fee
    {
        return $this->readyToPublish2;
    }

    /**
     * @param Fee $readyToPublish2
     * @return FeesParameters
     */
    public function setReadyToPublish2(Fee $readyToPublish2): FeesParameters
    {
        $this->readyToPublish2 = $readyToPublish2;

        return $this;
    }

    /**
     * @return Fee
     */
    public function getTransfer2(): Fee
    {
        return $this->transfer2;
    }

    /**
     * @param Fee $transfer2
     * @return FeesParameters
     */
    public function setTransfer2(Fee $transfer2): FeesParameters
    {
        $this->transfer2 = $transfer2;

        return $this;
    }

    /**
     * @return Fee
     */
    public function getDisallowAutomaticRenewalOfSubscription(): Fee
    {
        return $this->disallowAutomaticRenewalOfSubscription;
    }

    /**
     * @param Fee $disallowAutomaticRenewalOfSubscription
     * @return FeesParameters
     */
    public function setDisallowAutomaticRenewalOfSubscription(Fee $disallowAutomaticRenewalOfSubscription): FeesParameters
    {
        $this->disallowAutomaticRenewalOfSubscription = $disallowAutomaticRenewalOfSubscription;

        return $this;
    }

    /**
     * @return Fee
     */
    public function getReturnEscrowSubmission(): Fee
    {
        return $this->returnEscrowSubmission;
    }

    /**
     * @param Fee $returnEscrowSubmission
     * @return FeesParameters
     */
    public function setReturnEscrowSubmission(Fee $returnEscrowSubmission): FeesParameters
    {
        $this->returnEscrowSubmission = $returnEscrowSubmission;

        return $this;
    }

    /**
     * @return Fee
     */
    public function getReturnEscrowBuying(): Fee
    {
        return $this->returnEscrowBuying;
    }

    /**
     * @param Fee $returnEscrowBuying
     * @return FeesParameters
     */
    public function setReturnEscrowBuying(Fee $returnEscrowBuying): FeesParameters
    {
        $this->returnEscrowBuying = $returnEscrowBuying;

        return $this;
    }

    /**
     * @return Fee
     */
    public function getPaySeeder(): Fee
    {
        return $this->paySeeder;
    }

    /**
     * @param Fee $paySeeder
     * @return FeesParameters
     */
    public function setPaySeeder(Fee $paySeeder): FeesParameters
    {
        $this->paySeeder = $paySeeder;

        return $this;
    }

    /**
     * @return Fee
     */
    public function getFinishBuying(): Fee
    {
        return $this->finishBuying;
    }

    /**
     * @param Fee $finishBuying
     * @return FeesParameters
     */
    public function setFinishBuying(Fee $finishBuying): FeesParameters
    {
        $this->finishBuying = $finishBuying;

        return $this;
    }

    /**
     * @return Fee
     */
    public function getRenewalOfSubscription(): Fee
    {
        return $this->renewalOfSubscription;
    }

    /**
     * @param Fee $renewalOfSubscription
     * @return FeesParameters
     */
    public function setRenewalOfSubscription(Fee $renewalOfSubscription): FeesParameters
    {
        $this->renewalOfSubscription = $renewalOfSubscription;

        return $this;
    }

}