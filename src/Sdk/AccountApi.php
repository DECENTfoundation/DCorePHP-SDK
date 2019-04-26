<?php

namespace DCorePHP\Sdk;

use DCorePHP\Crypto\Credentials;
use DCorePHP\Crypto\ECKeyPair;
use DCorePHP\Crypto\PrivateKey;
use DCorePHP\Crypto\PublicKey;
use DCorePHP\Exception\ObjectNotFoundException;
use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Account;
use DCorePHP\Model\Asset;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\Authority;
use DCorePHP\Model\BrainKeyInfo;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\ElGamalKeys;
use DCorePHP\Model\Memo;
use DCorePHP\Model\Operation\CreateAccount;
use DCorePHP\Model\Operation\Transfer2;
use DCorePHP\Model\Operation\UpdateAccount;
use DCorePHP\Model\Options;
use DCorePHP\Model\Subscription\AuthMap;
use DCorePHP\Model\TransactionConfirmation;
use DCorePHP\Net\Model\Request\Database;
use DCorePHP\Net\Model\Request\GetAccountById;
use DCorePHP\Net\Model\Request\GetAccountByName;
use DCorePHP\Net\Model\Request\GetAccountCount;
use DCorePHP\Net\Model\Request\GetAccountReferences;
use DCorePHP\Net\Model\Request\GetAccountsById;
use DCorePHP\Net\Model\Request\GetFullAccounts;
use DCorePHP\Net\Model\Request\GetKeyReferences;
use DCorePHP\Net\Model\Request\ListAccounts;
use DCorePHP\Net\Model\Request\LookupAccountNames;
use DCorePHP\Net\Model\Request\SearchAccountHistory;
use DCorePHP\Net\Model\Request\SearchAccounts;
use DCorePHP\Resources\BrainKeyDictionary;
use DCorePHP\Crypto\Address;
use DCorePHP\Utils\Crypto;

class AccountApi extends BaseApi implements AccountApiInterface
{
    /**
     * @inheritDoc
     */
    public function exist(string $nameOrId): bool
    {
        return $this->getByNameOrId($nameOrId) ? true : false;
    }

    /**
     * @inheritdoc
     */
    public function get(ChainObject $id): Account
    {
        $account = $this->dcoreApi->requestWebsocket(Database::class, new GetAccountById($id));
        if ($account instanceof Account) {
            return $account;
        }

        throw new ObjectNotFoundException("Account with id '{$id}' doesn't exist.");
    }

    /**
     * @inheritdoc
     */
    public function getByName(string $name): Account
    {
        $account = $this->dcoreApi->requestWebsocket(Database::class, new GetAccountByName($name));
        if ($account instanceof Account) {
            return $account;
        }

        throw new ObjectNotFoundException("Account with name '{$name}' doesn't exist.");
    }

    /**
     * @inheritdoc
     */
    public function getByNameOrId(string $nameOrId): Account
    {
        try {
            if ($chainObject = new ChainObject($nameOrId)) {
                return $this->get($chainObject);
            }
        } catch (ValidationException $e) {
            // do nothing
        }

        return $this->getByName($nameOrId);
    }

    /**
     * @inheritdoc
     */
    public function countAll(): int
    {
        return $this->dcoreApi->requestWebsocket(Database::class, new GetAccountCount());
    }

    /**
     * @inheritDoc
     */
    public function findAllReferencesByKeys(array $keys): array
    {
        return $this->dcoreApi->requestWebsocket(Database::class, new GetKeyReferences($keys)) ?: [];
    }

    /**
     * @inheritDoc
     */
    public function findAllReferencesByAccount(ChainObject $accountId): array
    {
        return $this->dcoreApi->requestWebsocket(Database::class, new GetAccountReferences($accountId));
    }

    /**
     * @inheritDoc
     */
    public function getAll(array $accountIds): array
    {
        return $this->dcoreApi->requestWebsocket(Database::class, new GetAccountsById($accountIds));
    }

    /**
     * @inheritDoc
     */
    public function getFullAccounts(array $namesOrIds, bool $subscribe = false): array
    {
        $inputArray = array_map(function ($nameOrId) {
            if ($nameOrId instanceof ChainObject) {
                return $nameOrId->getId();
            }

            return $nameOrId;
        }, $namesOrIds);
        return $this->dcoreApi->requestWebsocket(Database::class, new GetFullAccounts($inputArray, $subscribe));
    }

    /**
     * @inheritDoc
     */
    public function getAllByNames(array $names): array
    {
        return $this->dcoreApi->requestWebsocket(Database::class, new LookupAccountNames($names));
    }

    /**
     * @inheritdoc
     */
    public function listAllRelative(string $lowerbound = '', int $limit = 100): array
    {
        return $this->dcoreApi->requestWebsocket(Database::class, new ListAccounts($lowerbound, $limit)) ?: [];
    }

    /**
     * @inheritdoc
     */
    public function findAll(
        string $term = '',
        string $order = SearchAccounts::ORDER_NONE,
        string $startObjectId = '0.0.0',
        int $limit = 100
    ): array
    {
        return $this->dcoreApi->requestWebsocket(Database::class, new SearchAccounts($term, $order, $startObjectId, $limit)) ?: [];
    }

    /**
     * @inheritdoc
     * @deprecated use history API
     */
    public function searchAccountHistory(
        ChainObject $accountId,
        string $from = '0.0.0',
        string $order = SearchAccountHistory::ORDER_TIME_DESC,
        int $limit = 100
    ): array {
        return $this->dcoreApi->requestWebsocket(Database::class, new SearchAccountHistory($accountId, $order, $from, $limit)) ?: [];
    }

    /**
     * @inheritDoc
     */
    public function createCredentials(string $account, string $privateKey): Credentials
    {
        return new Credentials(new ChainObject($this->getByName($account)->getId()), ECKeyPair::fromBase58($privateKey));
    }

    /**
     * @inheritDoc
     */
    public function createTransfer(
        Credentials $credentials,
        string $nameOrId,
        AssetAmount $amount,
        string $memo = null,
        bool $encrypted = true,
        AssetAmount $fee = null
    ): Transfer2 {
        $fee = $fee ?: new AssetAmount();

        if ((($memo === '' || $memo === null) || !$encrypted) && ChainObject::isValid($nameOrId)) {
            $transferOperation = new Transfer2();
            $transferOperation
                ->setFrom($credentials->getAccount())
                ->setTo(new ChainObject($nameOrId))
                ->setAmount($amount)
                ->setMemo(Memo::withMessage($memo))
                ->setFee($fee);
            return $transferOperation;
        }

        $receiver = $this->getByNameOrId($nameOrId);
        $msg = null;
        if ($memo) {
            if ($encrypted) {
                $msg = Memo::withCredentials($memo, $credentials, $receiver);
            } else {
                $msg = Memo::withMessage($memo);
            }
        }
        $transferOperation = new Transfer2();
        $transferOperation
            ->setFrom($credentials->getAccount())
            ->setTo($receiver->getId())
            ->setAmount($amount)
            ->setMemo($msg)
            ->setFee($fee);

        return $transferOperation;
    }

    /**
     * @inheritDoc
     */
    public function transfer(
        Credentials $credentials,
        string $nameOrId,
        AssetAmount $amount,
        string $memo = null,
        bool $encrypted = true,
        AssetAmount $fee = null
    ): ?TransactionConfirmation {
        $fee = $fee ?: new AssetAmount();

        return $this->dcoreApi->getBroadcastApi()->broadcastOperationWithECKeyPairWithCallback(
            $credentials->getKeyPair(),
            $this->createTransfer($credentials, $nameOrId, $amount, $memo, $encrypted, $fee)
        );
    }

    /**
     * @inheritdoc
     */
    public function derivePrivateKey(string $brainKey, int $sequenceNumber = 0): PrivateKey
    {
        return PrivateKey::fromBrainKey($brainKey, $sequenceNumber);
    }

    /**
     * @inheritdoc
     */
    public function suggestBrainKey(): string
    {
        $dictionary = BrainKeyDictionary::getDictionary()['en'];
        $count = count($dictionary);
        if ($count !== 49744) {
            throw new \Exception("Expecting 49744 words in dictionary, got {$count}");
        }

        $brainKey = [];
        do {
            $randomNumber = random_int(0, count($dictionary) - 1);
            $brainKey[] = $dictionary[$randomNumber];
            unset($dictionary[$randomNumber]);
        } while (count($brainKey) < 16);

        $brainKey = implode(' ', $brainKey);
        $brainKey = $this->normalizeBrainKey($brainKey);

        return $brainKey;
    }

    /**
     * @inheritdoc
     */
    public function generateBrainKeyElGamalKey(): array
    {
        $brainKey = $this->suggestBrainKey();
        $privateKey = PrivateKey::fromBrainKey($brainKey);
        $publicKey = PublicKey::fromPrivateKey($privateKey);

        $elGamalKeys = new ElGamalKeys();
        $elGamalKeys->setPrivateKey($privateKey->toElGamalPrivateKey());
        $elGamalKeys->setPublicKey($privateKey->toElGamalPublicKey());

        $brainKeyInfo = new BrainKeyInfo();
        $brainKeyInfo
            ->setBrainPrivateKey($brainKey)
            ->setPublicKey($publicKey->toAddress())
            ->setWifPrivateKey($privateKey->toWif());

        return [
            $brainKeyInfo,
            $elGamalKeys
        ];
    }

    /**
     * @inheritdoc
     */
    public function getBrainKeyInfo(string $brainKey): BrainKeyInfo
    {
        $brainKey = $this->normalizeBrainKey($brainKey);
        $privateKey = PrivateKey::fromBrainKey($brainKey);
        $publicKey = PublicKey::fromPrivateKey($privateKey);

        $brainKeyInfo = new BrainKeyInfo();
        $brainKeyInfo
            ->setBrainPrivateKey($brainKey)
            ->setPublicKey($publicKey->toAddress())
            ->setWifPrivateKey($privateKey->toWif());

        return $brainKeyInfo;
    }

    /**
     * @inheritdoc
     */
    public function normalizeBrainKey(string $brainKey): string
    {
        $brainKey = preg_replace('/[\t\n\v\f\r ]+/', ' ', $brainKey);
        $brainKey = trim($brainKey);
        $brainKey = strtoupper($brainKey);

        return $brainKey;
    }

    /**
     * @inheritdoc
     */
    public function registerAccount(
        string $name,
        string $publicOwnerKeyWif,
        string $publicActiveKeyWif,
        string $publicMemoKeyWif,
        ChainObject $registrarAccountId,
        string $registrarPrivateKeyWif,
        bool $broadcast = true
    ): void {
        $options = new Options();
        $options
            ->setMemoKey(Address::decode($publicMemoKeyWif))
            ->setVotingAccount(new ChainObject('1.2.3'))
            ->setAllowSubscription(false)
            ->setPricePerSubscribe((new Asset\AssetAmount())->setAmount(0)->setAssetId(new ChainObject('1.3.0')))
            ->setNumMiner(0)
            ->setVotes([])
            ->setExtensions([])
            ->setSubscriptionPeriod(0);

        $operation = new CreateAccount();
        $operation
            ->setAccountName($name)
            ->setOwner((new Authority())->setKeyAuths([(new AuthMap())->setValue($publicOwnerKeyWif)]))
            ->setActive((new Authority())->setKeyAuths([(new AuthMap())->setValue($publicActiveKeyWif)]))
            ->setRegistrar($registrarAccountId)
            ->setOptions($options)
            ->setName(CreateAccount::OPERATION_NAME)
            ->setType(CreateAccount::OPERATION_TYPE)
            ->setFee(new AssetAmount());

        $this->dcoreApi->getBroadcastApi()->broadcastOperationWithECKeyPairWithCallback(
            ECKeyPair::fromBase58($registrarPrivateKeyWif),
            $operation
        );
    }

    /**
     * @inheritdoc
     */
    public function createAccountWithBrainKey(
        string $brainKey,
        string $accountName,
        ChainObject $registrarAccountId,
        string $registrarPrivateKeyWif,
        bool $broadcast = true
    ): void {
        $brainKeyInfo = $this->getBrainKeyInfo($brainKey);

        $this->registerAccount(
            $accountName,
            $brainKeyInfo->getPublicKey(),
            $brainKeyInfo->getPublicKey(),
            $brainKeyInfo->getPublicKey(),
            $registrarAccountId,
            $registrarPrivateKeyWif,
            $broadcast
        );
    }

    /**
     * @inheritdoc
     */
    public function updateAccount(
        ChainObject $accountId,
        Options $options,
        string $privateKeyWif,
        bool $broadcast = true
    ): ?TransactionConfirmation {
        $account = $this->get($accountId);
        $accountOptions = $account->getOptions();

        $newOptions = new Options();
        $newOptions
            ->setMemoKey($options->getMemoKey() ?: $accountOptions->getMemoKey()->encode())
            ->setVotingAccount($options->getVotingAccount() ?: $accountOptions->getVotingAccount())
            ->setAllowSubscription($options->getAllowSubscription() ?: $accountOptions->getAllowSubscription())
            ->setPricePerSubscribe($options->getPricePerSubscribe() ?: $accountOptions->getPricePerSubscribe())
            ->setNumMiner($options->getNumMiner() ?: $accountOptions->getNumMiner())
            ->setVotes($options->getVotes() ?: $accountOptions->getVotes())
            ->setExtensions($options->getExtensions() ?: $accountOptions->getExtensions())
            ->setSubscriptionPeriod($options->getSubscriptionPeriod() ?: $accountOptions->getSubscriptionPeriod());

        $operation = new UpdateAccount();
        $operation
            ->setAccountId($accountId)
            ->setOptions($newOptions);

        return $this->dcoreApi->getBroadcastApi()->broadcastOperationWithECKeyPairWithCallback(ECKeyPair::fromBase58($privateKeyWif), $operation);
    }

    /**
     * @inheritdoc
     */
    public function generateElGamalKeys(): ElGamalKeys
    {
        return $this->generateBrainKeyElGamalKey()[1];
    }

    /**
     * @inheritdoc
     */
    public function getElGammalKey(string $consumer): ElGamalKeys
    {
        // TODO: Implement getElGammalKey() method.
    }
}
