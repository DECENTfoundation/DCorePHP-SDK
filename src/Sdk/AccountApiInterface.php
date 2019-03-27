<?php


namespace DCorePHP\Sdk;


use DCorePHP\Crypto\Credentials;
use DCorePHP\Crypto\PrivateKey;
use DCorePHP\Model\Account;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\BrainKeyInfo;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\ElGamalKeys;
use DCorePHP\Model\Operation\Transfer2;
use DCorePHP\Model\TransactionConfirmation;
use DCorePHP\Model\TransactionDetail;
use DCorePHP\Net\Model\Request\SearchAccountHistory;
use DCorePHP\Net\Model\Request\SearchAccounts;

interface AccountApiInterface
{
    /**
     * Check if the account exist.
     *
     * @param string $nameOrId account id or name
     *
     * @return bool account exists in DCore database
     */
    public function exist(string $nameOrId): bool;

    /**
     * get Account object by id
     *
     * @param ChainObject $id the id of the account
     * @return Account an account
     * @throws \DCorePHP\Exception\ObjectNotFoundException if account wasn't found
     * @throws \DCorePHP\Exception\InvalidApiCallException if DCore API returned error
     * @throws \WebSocket\BadOpcodeException
     */
    public function get(ChainObject $id): Account;

    /**
     * get Account object by name
     *
     * @param string $name the name of the account
     * @return Account an account
     * @throws \DCorePHP\Exception\ObjectNotFoundException if account wasn't found
     * @throws \DCorePHP\Exception\InvalidApiCallException if DCore API returned error
     * @throws \WebSocket\BadOpcodeException
     */
    public function getByName(string $name): Account;

    /**
     * Get account by name or id.
     *
     * @param string $nameOrId account id or name
     * @return Account an account if exist
     */
    public function getByNameOrId(string $nameOrId): Account;

    /**
     * Returns the number of accounts registered on the blockchain.
     *
     * @return int
     * @throws \DCorePHP\Exception\InvalidApiCallException if DCore API returned error
     * @throws \WebSocket\BadOpcodeException
     */
    public function countAll(): int;

    /**
     * Get account object ids by public key addresses.
     *
     * @param array $keys WIF formatted public keys of the account, eg. DCT5j2bMj7XVWLxUW7AXeMiYPambYFZfCcMroXDvbCfX1VoswcZG4
     *
     * @return ChainObject[] of account object ids
     */
    public function findAllReferencesByKeys(array $keys): array;

    /**
     * Get all accounts that refer to the account id in their owner or active authorities.
     *
     * @param ChainObject $accountId
     *
     * @return ChainObject[] of account object ids
     */
    public function findAllReferencesByAccount(ChainObject $accountId): array;

    /**
     * Get account objects by ids.
     *
     * @param array $accountIds ids of the account, 1.2.*
     *
     * @return Account[] account list if found
     */
    public function getAll(array $accountIds): array;

    /**
     * Fetch all objects relevant to the specified accounts and subscribe to updates.
     *
     * @param array $namesOrIds list of account names or ids
     * @param bool $subscribe true to subscribe to updates
     *
     * @return array map of names or ids to account, or empty map if not present
     */
    public function getFullAccounts(array $namesOrIds, bool $subscribe = false): array;

    /**
     * Get a list of accounts by name.
     *
     * @param array $names to retrieve
     * @return array of accounts
     */
    public function getAllByNames(array $names): array;

    /**
     * Returns a list of all account names and their account ids, sorted by account name
     *
     * @param string $lowerBound the name of the first account to return. If the named account does not exist, the list will start at the account that comes after lowerbound
     * @param int $limit the maximum number of accounts to return (max: 1000)
     * @return array
     * @throws \WebSocket\BadOpcodeException
     * @throws \DCorePHP\Exception\InvalidApiCallException
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
     * @throws \DCorePHP\Exception\InvalidApiCallException if DCore API returned error
     * @throws \WebSocket\BadOpcodeException
     */
    public function findAll(string $searchTerm = '', string $order = SearchAccounts::ORDER_NAME_DESC, string $id = '0.0.0', int $limit = 100): array;

    /**
     * Create API credentials.
     *
     * @param string $account name
     * @param string $privateKey in wif base58 format, eg. 5Jd7zdvxXYNdUfnEXt5XokrE3zwJSs734yQ36a1YaqioRTGGLtn
     * @return Credentials
     * @throws \Exception
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
     * @throws \Exception
     * @throws \DCorePHP\Exception\InvalidApiCallException
     * @throws \WebSocket\BadOpcodeException
     * @return Transfer2
     */
    public function createTransfer(Credentials $credentials, string $nameOrId, AssetAmount $amount, string $memo = null, bool $encrypted = true, AssetAmount $fee = null): Transfer2;

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
     * @throws \Exception
     * @throws \DCorePHP\Exception\InvalidApiCallException
     * @throws \WebSocket\BadOpcodeException
     * @return TransactionConfirmation
     */
    public function transfer(Credentials $credentials, string $nameOrId, AssetAmount $amount, string $memo = null, bool $encrypted = true, AssetAmount $fee = null): ?TransactionConfirmation;

    /**
     * Calculate derived private key apart from primary (with sequence number 0).
     * NOTE: May be used as additional keys when creating account - owner, memo key
     *
     * @param string $brainKey Brain key string.
     * @param int $sequenceNumber Sequence number to derive private key from it. If selected 0, primary private key is generated.
     * @return PrivateKey derived private key
     */
    public function derivePrivateKey(string $brainKey, int $sequenceNumber): PrivateKey;

    /**
     * Returns the operations on the named account. This returns a list of transaction detail objects, which describe past the past activity on the account.
     *
     * @param ChainObject $accountId the name or id of the account
     * @param string $order sort data by field
     * @param string $from object_id to start searching from
     * @param int $limit the number of entries to return (starting from the most recent) (max 100)
     * @return TransactionDetail[] a list of transaction detail objects
     * @throws \WebSocket\BadOpcodeException
     * @throws \DCorePHP\Exception\InvalidApiCallException
     */
    public function searchAccountHistory(ChainObject $accountId, string $from = '0.0.0', string $order = SearchAccountHistory::ORDER_TIME_DESC, int $limit = 100): array;

    /**
     * Suggests a safe brain key to use for creating your account.
     * create_account_with_brain_key() requires you to specify a brain key,
     * a long passphrase that provides enough entropy to generate cryptographic keys.
     * This function will suggest a suitably random string that should be easy to write down
     * (and, with effort, memorize).
     *
     * @return string a suggested brain key
     */
    public function suggestBrainKey(): string;

    /**
     * Suggests a safe brain key to use for creating your account.
     * This function also generates el_gamal_key_pair corresponding to the brain key.
     *
     * create_account_with_brain_key() requires you to specify a brain key,
     * a long passphrase that provides enough entropy to generate cryptographic keys.
     * This function will suggest a suitably random string that should be easy to write down (and, with effort, memorize).
     *
     * @return array a suggested brain key and corresponding El Gamal key pair
     * @throws \Exception
     */
    public function generateBrainKeyElGamalKey(): array;

    /**
     * Calculates the private key and public key corresponding to any brain key.
     * @param string $brainKey the brain key to be used for calculation
     * @return BrainKeyInfo
     * @throws \Exception
     */
    public function getBrainKeyInfo(string $brainKey): BrainKeyInfo;

    /**
     * Registers a third party's account on the blockckain.
     * This function is used to register an account for which you do not own the private keys.
     * When acting as a registrar, an end user will generate their own private keys and send you the public keys.
     * The registrar will use this function to register the account on behalf of the end user.
     *
     * The owner key represents absolute control over the account. Generally, the only time the owner key is required is to update the active key.
     * The active key represents the hot key of the account. This key has control over nearly all operations the account may perform.
     *
     * @param string $name the name of the account, must be unique on the blockchain and contains at least 5 characters
     * @param string $publicOwnerKeyWif the owner key for the new account
     * @param string $publicActiveKeyWif the active key for the new account
     * @param string $publicMemoKeyWif
     * @param ChainObject $registrarAccountId ID of the account which will pay the fee to register the user
     * @param string $registrarPrivateKeyWif private key of the account which will pay the fee to register the user
     * @param bool $broadcast true to broadcast the transaction on the network
     * @return void
     * @throws \WebSocket\BadOpcodeException
     * @throws \DCorePHP\Exception\InvalidApiCallException
     */
    public function registerAccount(
        string $name,
        string $publicOwnerKeyWif,
        string $publicActiveKeyWif,
        string $publicMemoKeyWif,
        ChainObject $registrarAccountId,
        string $registrarPrivateKeyWif,
        bool $broadcast = false
    ): void;

    /**
     * Creates a new account and registers it on the blockchain
     * @param string $brainKey the brain key used for generating the account's private keys
     * @param string $accountName the name of the account, must be unique on the blockchain and contains at least 5 characters
     * @param ChainObject $registrarAccountId
     * @param string $registrarPrivateKeyWif
     * @param bool $broadcast true to broadcast the transaction on the network
     * @return void
     */
    public function createAccountWithBrainKey(
        string $brainKey,
        string $accountName,
        ChainObject $registrarAccountId,
        string $registrarPrivateKeyWif,
        bool $broadcast = false
    ): void;

    /**
     * Generates private El Gamal key and corresponding public key
     * @return ElGamalKeys
     */
    public function generateElGamalKeys(): ElGamalKeys;

    /**
     * Gets unique El Gamal key pair for consumer
     * @param string $consumer
     * @return ElGamalKeys
     */
    public function getElGammalKey(string $consumer): ElGamalKeys;
}