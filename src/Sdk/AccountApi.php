<?php

namespace DCorePHP\Sdk;

use DCorePHP\Crypto\Credentials;
use DCorePHP\Crypto\ECKeyPair;
use DCorePHP\Exception\ObjectNotFoundException;
use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Account;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\Authority;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Memo;
use DCorePHP\Model\Operation\AccountCreateOperation;
use DCorePHP\Model\Operation\AccountUpdateOperation;
use DCorePHP\Model\Operation\TransferOperation;
use DCorePHP\Model\Options;
use DCorePHP\Model\Subscription\AuthMap;
use DCorePHP\Model\TransactionConfirmation;
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
use DCorePHP\Crypto\Address;
use Exception;

class AccountApi extends BaseApi implements AccountApiInterface
{
    /**
     * @inheritdoc
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
        $account = $this->dcoreApi->requestWebsocket(new GetAccountById($id));
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
        $account = $this->dcoreApi->requestWebsocket(new GetAccountByName($name));
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
        return $this->dcoreApi->requestWebsocket(new GetAccountCount());
    }

    /**
     * @inheritdoc
     */
    public function findAllReferencesByKeys(array $keys): array
    {
        return $this->dcoreApi->requestWebsocket(new GetKeyReferences($keys)) ?: [];
    }

    /**
     * @inheritdoc
     */
    public function findAllReferencesByAccount(ChainObject $accountId): array
    {
        return $this->dcoreApi->requestWebsocket(new GetAccountReferences($accountId));
    }

    /**
     * @inheritdoc
     */
    public function getAll(array $accountIds): array
    {
        return $this->dcoreApi->requestWebsocket(new GetAccountsById($accountIds));
    }

    /**
     * @inheritdoc
     */
    public function getFullAccounts(array $namesOrIds, bool $subscribe = false): array
    {
        $inputArray = array_map(static function ($nameOrId) {
            if ($nameOrId instanceof ChainObject) {
                return $nameOrId->getId();
            }

            return $nameOrId;
        }, $namesOrIds);
        return $this->dcoreApi->requestWebsocket(new GetFullAccounts($inputArray, $subscribe));
    }

    /**
     * @inheritdoc
     */
    public function getAllByNames(array $names): array
    {
        return $this->dcoreApi->requestWebsocket(new LookupAccountNames($names));
    }

    /**
     * @inheritdoc
     */
    public function listAllRelative(string $lowerbound = '', int $limit = 100): array
    {
        return $this->dcoreApi->requestWebsocket(new ListAccounts($lowerbound, $limit)) ?: [];
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
        return $this->dcoreApi->requestWebsocket(new SearchAccounts($term, $order, $startObjectId, $limit)) ?: [];
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
        return $this->dcoreApi->requestWebsocket(new SearchAccountHistory($accountId, $order, $from, $limit)) ?: [];
    }

    /**
     * @inheritdoc
     */
    public function createCredentials(string $account, string $privateKey): Credentials
    {
        return new Credentials(new ChainObject($this->getByName($account)->getId()), ECKeyPair::fromBase58($privateKey));
    }

    /**
     * @inheritdoc
     */
    public function createTransfer(
        Credentials $credentials,
        string $nameOrId,
        AssetAmount $amount,
        string $memo = null,
        bool $encrypted = true,
        AssetAmount $fee = null
    ): TransferOperation {
        $fee = $fee ?: new AssetAmount();

        if ((($memo === '' || $memo === null) || !$encrypted) && ChainObject::isValid($nameOrId)) {
            $transferOperation = new TransferOperation();
            $transferOperation
                ->setFrom($credentials->getAccount())
                ->setTo(new ChainObject($nameOrId))
                ->setAmount($amount)
                ->setMemo($memo ? Memo::withMessage($memo) : null)
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
        $transferOperation = new TransferOperation();
        $transferOperation
            ->setFrom($credentials->getAccount())
            ->setTo($receiver->getId())
            ->setAmount($amount)
            ->setMemo($msg)
            ->setFee($fee);

        return $transferOperation;
    }

    /**
     * @inheritdoc
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
     * @param ChainObject $registrar
     * @param string $name
     * @param Address $address
     * @param $fee
     *
     * @return AccountCreateOperation
     * @throws ValidationException
     * @throws Exception
     */
    public function createAccountOperation(ChainObject $registrar, string $name, Address $address, $fee): AccountCreateOperation {
        $operation = new AccountCreateOperation();
        $operation->setRegistrar($registrar);
        $operation->setAccountName($name);
        $operation->setOwner((new Authority())->setKeyAuths([(new AuthMap())->setValue($address)]));
        $operation->setActive((new Authority())->setKeyAuths([(new AuthMap())->setValue($address)]));
        $operation->setOptions((new Options())->setMemoKey($address));
        $operation->setFee($fee);

        return $operation;
    }

    /**
     * @param Credentials $registrar
     * @param string $name
     * @param Address $address
     * @param null $fee
     *
     * @return TransactionConfirmation
     * @throws ValidationException
     * @throws Exception
     */
    public function create(Credentials $registrar, string $name, Address $address, $fee = null): TransactionConfirmation
    {
        $fee = $fee ?: new AssetAmount();
        return $this->dcoreApi->getBroadcastApi()->broadcastOperationWithECKeyPairWithCallback(
            $registrar->getKeyPair(),
            $this->createAccountOperation($registrar->getAccount(), $name, $address, $fee)
        );
    }

    /**
     * @inheritDoc
     */
    public function createUpdateOperation(string $nameOrId, $fee): AccountUpdateOperation
    {
        $account = $this->getByNameOrId($nameOrId);

        $operation = new AccountUpdateOperation();
        $operation->setAccountId($account->getId());
        $operation->setOwner($account->getOwner());
        $operation->setActive($account->getActive());
        $operation->setOptions($account->getOptions());
        $operation->setFee($fee);

        return $operation;
    }

    /**
     * @inheritDoc
     */
    public function update(Credentials $credentials, Options $options = null, Authority $active = null, Authority $owner = null, $fee = null): TransactionConfirmation
    {
        $fee = $fee ?: new AssetAmount();

        $operation = $this->createUpdateOperation($credentials->getAccount()->getId(), $fee);
        $operation->setOptions($options ?: $operation->getOptions());
        $operation->setActive($active ?: $operation->getActive());
        $operation->setOwner($owner ?: $operation->getOwner());

        return $this->dcoreApi->getBroadcastApi()->broadcastOperationWithECKeyPairWithCallback(
            $credentials->getKeyPair(),
            $operation
        );
    }
}
