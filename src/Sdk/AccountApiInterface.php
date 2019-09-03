<?php


namespace DCorePHP\Sdk;


use DCorePHP\Crypto\Address;
use DCorePHP\Crypto\Credentials;
use DCorePHP\Crypto\PrivateKey;
use DCorePHP\Exception\InvalidApiCallException;
use DCorePHP\Exception\ObjectNotFoundException;
use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Account;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\Authority;
use DCorePHP\Model\BrainKeyInfo;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Operation\AccountCreateOperation;
use DCorePHP\Model\Operation\AccountUpdateOperation;
use DCorePHP\Model\Operation\TransferOperation;
use DCorePHP\Model\Options;
use DCorePHP\Model\TransactionConfirmation;
use DCorePHP\Model\TransactionDetail;
use DCorePHP\Net\Model\Request\SearchAccountHistory;
use DCorePHP\Net\Model\Request\SearchAccounts;
use Exception;
use WebSocket\BadOpcodeException;

interface AccountApiInterface
{
    /**
     * Check if the account exist.
     *
     * @param string $nameOrId account id or name
     *
     * @return bool account exists in DCore database
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     * @throws ObjectNotFoundException
     */
    public function exist(string $nameOrId): bool;

    /**
     * get Account object by id
     *
     * @param ChainObject $id the id of the account
     *
     * @return Account an account
     * @throws ObjectNotFoundException if account wasn't found
     * @throws InvalidApiCallException if DCore API returned error
     * @throws BadOpcodeException
     */
    public function get(ChainObject $id): Account;

    /**
     * get Account object by name
     *
     * @param string $name the name of the account
     *
     * @return Account an account
     * @throws ObjectNotFoundException if account wasn't found
     * @throws InvalidApiCallException if DCore API returned error
     * @throws BadOpcodeException
     */
    public function getByName(string $name): Account;

    /**
     * Get account by name or id.
     *
     * @param string $nameOrId account id or name
     *
     * @return Account an account if exist
     *
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     * @throws ObjectNotFoundException
     */
    public function getByNameOrId(string $nameOrId): Account;

    /**
     * Returns the number of accounts registered on the blockchain.
     *
     * @return int
     * @throws InvalidApiCallException if DCore API returned error
     * @throws BadOpcodeException
     */
    public function countAll(): int;

    /**
     * Get account object ids by public key addresses.
     *
     * @param array $keys WIF formatted public keys of the account, eg. DCT5j2bMj7XVWLxUW7AXeMiYPambYFZfCcMroXDvbCfX1VoswcZG4
     *
     * @return ChainObject[] of account object ids
     *
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function findAllReferencesByKeys(array $keys): array;

    /**
     * Get all accounts that refer to the account id in their owner or active authorities.
     *
     * @param ChainObject $accountId
     *
     * @return ChainObject[] of account object ids
     *
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function findAllReferencesByAccount(ChainObject $accountId): array;

    /**
     * Get account objects by ids.
     *
     * @param array $accountIds ids of the account, 1.2.*
     *
     * @return Account[] account list if found
     *
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function getAll(array $accountIds): array;

    /**
     * Fetch all objects relevant to the specified accounts and subscribe to updates.
     *
     * @param array $namesOrIds list of account names or ids
     * @param bool $subscribe true to subscribe to updates
     *
     * @return array map of names or ids to account, or empty map if not present
     *
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function getFullAccounts(array $namesOrIds, bool $subscribe = false): array;

    /**
     * Get a list of accounts by name.
     *
     * @param array $names to retrieve
     *
     * @return array of accounts
     *
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function getAllByNames(array $names): array;

    /**
     * Returns a list of all account names and their account ids, sorted by account name
     *
     * @param string $lowerBound the name of the first account to return. If the named account does not exist, the list will start at the account that comes after lowerbound
     * @param int $limit the maximum number of accounts to return (max: 1000)
     *
     * @return array
     *
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function listAllRelative(string $lowerBound = '', int $limit = 100): array;

    /**
     * Get registered accounts that match search term.
     *
     * @param string $searchTerm will try to partially match account name or id
     * @param string $order sort data by field
     * @param string $id object_id to start searching from
     * @param int $limit maximum number of results to return ( must not exceed 1000 )
     * @return Account[] map of account names to corresponding IDs
     * @throws InvalidApiCallException if DCore API returned error
     * @throws BadOpcodeException
     */
    public function findAll(string $searchTerm = '', string $order = SearchAccounts::ORDER_NAME_DESC, string $id = '0.0.0', int $limit = 100): array;

    /**
     * Create API credentials.
     *
     * @param string $account name
     * @param string $privateKey in wif base58 format, eg. 5Jd7zdvxXYNdUfnEXt5XokrE3zwJSs734yQ36a1YaqioRTGGLtn
     * @return Credentials
     * @throws Exception
     */
    public function createCredentials(string $account, string $privateKey): Credentials;

    /**
     * Create a transfer operation.
     *
     * @param Credentials account $credentials
     * @param string $nameOrId account id or account name
     * @param AssetAmount $amount to send with asset type
     * @param string|null $memo optional message
     * @param bool $encrypted is visible only for sender and receiver, unencrypted is visible publicly
     * @param AssetAmount $fee for the operation
     *
     * @return TransferOperation
     *@throws Exception
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function createTransfer(Credentials $credentials, string $nameOrId, AssetAmount $amount, string $memo = null, bool $encrypted = true, AssetAmount $fee = null): TransferOperation;


    /**
     * Returns the operations on the named account. This returns a list of transaction detail objects, which describe past the past activity on the account.
     *
     * @param ChainObject $accountId the name or id of the account
     * @param string $order sort data by field
     * @param string $from object_id to start searching from
     * @param int $limit the number of entries to return (starting from the most recent) (max 100)
     * @return TransactionDetail[] a list of transaction detail objects
     * @throws BadOpcodeException
     * @throws InvalidApiCallException
     */
    public function searchAccountHistory(ChainObject $accountId, string $from = '0.0.0', string $order = SearchAccountHistory::ORDER_TIME_DESC, int $limit = 100): array;

    /**
     * Make a transfer.
     *
     * @param Credentials account $credentials
     * @param string $nameOrId account id or account name
     * @param AssetAmount $amount to send with asset type
     * @param string|null $memo optional message
     * @param bool $encrypted is visible only for sender and receiver, unencrypted is visible publicly
     * @param AssetAmount $fee for the operation
     *
     * @throws Exception
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     * @return TransactionConfirmation
     */
    public function transfer(Credentials $credentials, string $nameOrId, AssetAmount $amount, string $memo = null, bool $encrypted = true, AssetAmount $fee = null): ?TransactionConfirmation;

    /**
     * Create a register new account operation.
     *
     * @param ChainObject $registrar
     * @param string $name
     * @param Address $address
     * @param $fee
     *
     * @return AccountCreateOperation
     */
    public function createAccountOperation(ChainObject $registrar, string $name, Address $address, $fee): AccountCreateOperation;

    /**
     * Create a new account.
     *
     * @param Credentials $registrar
     * @param string $name
     * @param Address $address
     * @param null $fee
     *
     * @return TransactionConfirmation
     */
    public function create(Credentials $registrar, string $name, Address $address, $fee = null): TransactionConfirmation;

    /**
     * Create update account operation. Fills model with actual account values.
     *
     * @param string $nameOrId
     * @param $fee
     *
     * @return AccountUpdateOperation
     *
     * @throws ValidationException
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     * @throws ObjectNotFoundException
     */
    public function createUpdateOperation(string $nameOrId, $fee): AccountUpdateOperation;

    /**
     * Update account.
     *
     * @param Credentials $credentials
     * @param Options|null $options
     * @param Authority|null $active
     * @param Authority|null $owner
     * @param null $fee
     *
     * @return TransactionConfirmation
     *
     * @throws Exception
     */
    public function update(Credentials $credentials, Options $options = null, Authority $active = null, Authority $owner = null, $fee = null): TransactionConfirmation;
}