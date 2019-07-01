<?php

namespace DCorePHP;

use DCorePHP\Exception\InvalidApiCallException;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\BlockData;
use DCorePHP\Model\DynamicGlobalProps;
use DCorePHP\Model\Transaction;
use DCorePHP\Net\JsonRpc;
use DCorePHP\Net\Model\Request\BaseRequest;
use DCorePHP\Net\Websocket;
use DCorePHP\Sdk\AccountApi;
use DCorePHP\Sdk\AssetApi;
use DCorePHP\Sdk\BalanceApi;
use DCorePHP\Sdk\BlockApi;
use DCorePHP\Sdk\BroadcastApi;
use DCorePHP\Sdk\CallbackApi;
use DCorePHP\Sdk\ContentApi;
use DCorePHP\Sdk\GeneralApi;
use DCorePHP\Sdk\HistoryApi;
use DCorePHP\Sdk\MessagingApi;
use DCorePHP\Sdk\MiningApi;
use DCorePHP\Sdk\NftApi;
use DCorePHP\Sdk\ProposalApi;
use DCorePHP\Sdk\PurchaseApi;
use DCorePHP\Sdk\SeederApi;
use DCorePHP\Sdk\SubscriptionApi;
use DCorePHP\Sdk\TransactionApi;
use DCorePHP\Sdk\ValidationApi;
use InvalidArgumentException;
use WebSocket\BadOpcodeException;

class DCoreApi extends DCoreSdk
{
    /**
     * default transaction expiration in seconds used when broadcasting transactions,
     * after the expiry the transaction is removed from recent pool and will be dismissed if not included in DCore block
     */
    public const TRANSACTION_EXPIRATION = 30;
    public const REQ_LIMIT_MAX = 100;

    /** @var array */
    protected $permissions = [];
    /** @var JsonRpc */
    protected $jsonRpc;
    /** @var Websocket */
    protected $websocket;
    /** @var AccountApi */
    private $accountApi;
    /** @var MessagingApi */
    private $messagingApi;
    /** @var MiningApi */
    private $miningApi;
    /** @var SeederApi */
    private $seederApi;
    /** @var AssetApi */
    private $assetApi;
    /** @var SubscriptionApi */
    private $subscriptionApi;
    /** @var ProposalApi */
    private $proposalApi;
    /** @var ContentApi */
    private $contentApi;
    /** @var GeneralApi */
    private $generalApi;
    /** @var HistoryApi */
    private $historyApi;
    /** @var PurchaseApi */
    private $purchaseApi;
    /** @var TransactionApi*/
    private $transactionApi;
    /** @var ValidationApi */
    private $validationApi;
    /** @var CallbackApi */
    private $callBackApi;
    /** @var BlockApi */
    private $blockApi;
    /** @var BroadcastApi */
    private $broadcastApi;
    /** @var BalanceApi */
    private $balanceApi;
    /** @var NftApi */
    private $nftApi;

    public function __construct(string $dcoreApiUrl, string $dcoreWebsocketUrl, bool $debug = false)
    {
        parent::__construct($dcoreApiUrl, $dcoreWebsocketUrl, $debug);
        $this->accountApi = new AccountApi($this);
        $this->messagingApi = new MessagingApi($this);
        $this->miningApi = new MiningApi($this);
        $this->seederApi = new SeederApi($this);
        $this->assetApi = new AssetApi($this);
        $this->subscriptionApi = new SubscriptionApi($this);
        $this->proposalApi = new ProposalApi($this);
        $this->contentApi = new ContentApi($this);
        $this->generalApi = new GeneralApi($this);
        $this->historyApi = new HistoryApi($this);
        $this->purchaseApi = new PurchaseApi($this);
        $this->transactionApi = new TransactionApi($this);
        $this->validationApi = new ValidationApi($this);
        $this->callBackApi = new CallbackApi($this);
        $this->blockApi = new BlockApi($this);
        $this->broadcastApi = new BroadcastApi($this);
        $this->balanceApi = new BalanceApi($this);
        $this->nftApi = new NftApi($this);
    }

    /**
     * get JsonRpc client
     *
     * @return JsonRpc
     */
    public function getJsonRpc(): JsonRpc
    {
        if (!$this->jsonRpc) {
            $this->jsonRpc = JsonRpc::getInstance($this->getDcoreApiUrl());
        }

        return $this->jsonRpc;
    }

    /**
     * get Websocket client
     *
     * @return Websocket
     */
    public function getWebsocket(): Websocket
    {
        if (!$this->websocket) {
            $this->websocket = Websocket::getInstance($this->getDcoreWebsocketUrl(), $this->isDebug());
        }

        return $this->websocket;
    }

    /**
     * @param BaseRequest $request
     * @return mixed
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function requestWebsocket(BaseRequest $request)
    {
        return $this->getWebsocket()->send($request);
    }

    /**
     * @return AccountApi
     */
    public function getAccountApi(): AccountApi
    {
        return $this->accountApi;
    }

    /**
     * @param AccountApi $accountApi
     */
    public function setAccountApi(AccountApi $accountApi): void
    {
        $this->accountApi = $accountApi;
    }

    /**
     * @return MessagingApi
     */
    public function getMessagingApi(): MessagingApi
    {
        return $this->messagingApi;
    }

    /**
     * @param MessagingApi $messagingApi
     */
    public function setMessagingApi(MessagingApi $messagingApi): void
    {
        $this->messagingApi = $messagingApi;
    }

    /**
     * @return MiningApi
     */
    public function getMiningApi(): MiningApi
    {
        return $this->miningApi;
    }

    /**
     * @param MiningApi $miningApi
     */
    public function setMiningApi(MiningApi $miningApi): void
    {
        $this->miningApi = $miningApi;
    }

    /**
     * @return SeederApi
     */
    public function getSeederApi(): SeederApi
    {
        return $this->seederApi;
    }

    /**
     * @param SeederApi $seederApi
     */
    public function setSeedingApi(SeederApi $seederApi): void
    {
        $this->seederApi = $seederApi;
    }

    /**
     * @return AssetApi
     */
    public function getAssetApi(): AssetApi
    {
        return $this->assetApi;
    }

    /**
     * @param AssetApi $assetApi
     */
    public function setAssetApi(AssetApi $assetApi): void
    {
        $this->assetApi = $assetApi;
    }

    /**
     * @return SubscriptionApi
     */
    public function getSubscriptionApi(): SubscriptionApi
    {
        return $this->subscriptionApi;
    }

    /**
     * @param SubscriptionApi $subscriptionApi
     */
    public function setSubscriptionApi(SubscriptionApi $subscriptionApi): void
    {
        $this->subscriptionApi = $subscriptionApi;
    }

    /**
     * @return ProposalApi
     */
    public function getProposalApi(): ProposalApi
    {
        return $this->proposalApi;
    }

    /**
     * @param ProposalApi $proposalApi
     */
    public function setProposalApi(ProposalApi $proposalApi): void
    {
        $this->proposalApi = $proposalApi;
    }

    /**
     * @return ContentApi
     */
    public function getContentApi(): ContentApi
    {
        return $this->contentApi;
    }

    /**
     * @param ContentApi $contentApi
     */
    public function setContentApi(ContentApi $contentApi): void
    {
        $this->contentApi = $contentApi;
    }

    /**
     * @return GeneralApi
     */
    public function getGeneralApi(): GeneralApi
    {
        return $this->generalApi;
    }

    /**
     * @param GeneralApi $generalApi
     */
    public function setGeneralApi(GeneralApi $generalApi): void
    {
        $this->generalApi = $generalApi;
    }

    /**
     * @return HistoryApi
     */
    public function getHistoryApi(): HistoryApi
    {
        return $this->historyApi;
    }

    /**
     * @param HistoryApi $historyApi
     * @return DCoreApi
     */
    public function setHistoryApi(HistoryApi $historyApi): DCoreApi
    {
        $this->historyApi = $historyApi;

        return $this;
    }

    /**
     * @return PurchaseApi
     */
    public function getPurchaseApi(): PurchaseApi
    {
        return $this->purchaseApi;
    }

    /**
     * @param PurchaseApi $purchaseApi
     * @return DCoreApi
     */
    public function setPurchaseApi(PurchaseApi $purchaseApi): DCoreApi
    {
        $this->purchaseApi = $purchaseApi;

        return $this;
    }

    /**
     * @return TransactionApi
     */
    public function getTransactionApi(): TransactionApi
    {
        return $this->transactionApi;
    }

    /**
     * @param TransactionApi $transactionApi
     * @return DCoreApi
     */
    public function setTransactionApi(TransactionApi $transactionApi): DCoreApi
    {
        $this->transactionApi = $transactionApi;

        return $this;
    }

    /**
     * @return ValidationApi
     */
    public function getValidationApi(): ValidationApi
    {
        return $this->validationApi;
    }

    /**
     * @param ValidationApi $validationApi
     * @return DCoreApi
     */
    public function setValidationApi(ValidationApi $validationApi): DCoreApi
    {
        $this->validationApi = $validationApi;

        return $this;
    }

    /**
     * @return CallbackApi
     */
    public function getCallBackApi(): CallbackApi
    {
        return $this->callBackApi;
    }

    /**
     * @param CallbackApi $callBackApi
     * @return DCoreApi
     */
    public function setCallBackApi(CallbackApi $callBackApi): DCoreApi
    {
        $this->callBackApi = $callBackApi;

        return $this;
    }

    /**
     * @return BlockApi
     */
    public function getBlockApi(): BlockApi
    {
        return $this->blockApi;
    }

    /**
     * @param BlockApi $blockApi
     * @return DCoreApi
     */
    public function setBlockApi(BlockApi $blockApi): DCoreApi
    {
        $this->blockApi = $blockApi;

        return $this;
    }

    /**
     * @return BroadcastApi
     */
    public function getBroadcastApi(): BroadcastApi
    {
        return $this->broadcastApi;
    }

    /**
     * @param BroadcastApi $broadcastApi
     * @return DCoreApi
     */
    public function setBroadcastApi(BroadcastApi $broadcastApi): DCoreApi
    {
        $this->broadcastApi = $broadcastApi;

        return $this;
    }

    /**
     * @return BalanceApi
     */
    public function getBalanceApi(): BalanceApi
    {
        return $this->balanceApi;
    }

    /**
     * @param BalanceApi $balanceApi
     * @return DCoreApi
     */
    public function setBalanceApi(BalanceApi $balanceApi): DCoreApi
    {
        $this->balanceApi = $balanceApi;

        return $this;
    }

    /**
     * @return NftApi
     */
    public function getNftApi(): NftApi
    {
        return $this->nftApi;
    }

    /**
     * @param NftApi $nftApi
     * @return DCoreApi
     */
    public function setNftApi(NftApi $nftApi): DCoreApi
    {
        $this->nftApi = $nftApi;

        return $this;
    }

    /**
     * @param BaseOperation[] $operations
     * @param int $expiration
     * @return Transaction
     * @throws \Exception
     */
    public function prepareTransaction(array $operations, int $expiration): Transaction
    {
        /** @var $dynamicGlobalProperties DynamicGlobalProps */
        $dynamicGlobalProperties = $this->getGeneralApi()->getDynamicGlobalProperties();
        $dynamicGlobalProperties->setExpirationInterval($expiration);

        $chainId = $this->getGeneralApi()->getChainId();

        foreach ($operations as $operation) {
            if ($operation->getFee()->getAmount() === 0) {
                $fee = $this->getValidationApi()->getFee($operation, $operation->getFee()->getAssetId());
                $operation->setFee($fee);
            }
        }

        $transaction = new Transaction();
        $transaction
            ->setBlockData(BlockData::fromDynamicGlobalProps($dynamicGlobalProperties, $expiration))
            ->setOperations($operations)
            ->setExtensions([])
            ->setChainId($chainId);

        return $transaction;
    }
}
