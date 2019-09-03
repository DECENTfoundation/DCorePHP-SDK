<?php

namespace DCorePHP\Sdk;

use DateTime;
use DCorePHP\Crypto\Credentials;
use DCorePHP\Exception\InvalidApiCallException;
use DCorePHP\Exception\ObjectNotFoundException;
use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\CoAuthors;
use DCorePHP\Model\Content\ContentKeys;
use DCorePHP\Model\Content\Content;
use DCorePHP\Model\Operation\AddOrUpdateContentOperation;
use DCorePHP\Model\Operation\RemoveContentOperation;
use DCorePHP\Model\Operation\PurchaseContentOperation;
use DCorePHP\Model\Operation\TransferOperation;
use DCorePHP\Model\PubKey;
use DCorePHP\Model\TransactionConfirmation;
use DCorePHP\Net\Model\Request\SearchContent;
use Exception;
use WebSocket\BadOpcodeException;

interface ContentApiInterface
{
    /**
     * Generate keys for new content submission.
     *
     * @param ChainObject[] $seeders list of seeder account IDs
     *
     * @return ContentKeys key and key parts
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function generateKeys(array $seeders): ContentKeys;

    /**
     * Get a content by id
     *
     * @param ChainObject $contentId the id of the content to retrieve
     *
     * @return Content the content corresponding to the provided URI, or null if no matching content was found
     * @throws ObjectNotFoundException
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function get(ChainObject $contentId): Content;

    /**
     * Get a content by URI
     *
     * @param string $uri the URI of the content to retrieve
     *
     * @return Content the content corresponding to the provided URI, or null if no matching content was found
     * @throws ObjectNotFoundException
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function getByURI(string $uri): Content;

    /**
     * Get contents byt Ids
     *
     * @param array $contentIds
     * @return array
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function getAll(array $contentIds): array;

    /**
     * Check if content exist by url
     *
     * @param string $uri
     * @return bool
     */
    public function exist(string $uri): bool;

    /**
     * Get a list of accounts holding publishing manager status.
     *
     * @param string $lowerBound the name of the first account to return. If the named account does not exist, the list will start at the account that comes after lowerBound
     * @param int $limit the maximum number of accounts to return (max: 100)
     * @return ChainObject[] a list of publishing managers
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function listAllPublishersRelative(string $lowerBound, int $limit = 100): array;

    /**
     * Restores AES key( used to encrypt and decrypt a content) from key particles stored in a buying object
     *
     * @param PubKey $elGamalPrivate
     * @param ChainObject $purchaseId
     * @return mixed restored AES key from key particles
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function restoreEncryptionKey(PubKey $elGamalPrivate, ChainObject $purchaseId): string;

    /**
     * Search for term in contents (author, title and description).
     *
     * @param string $term search term
     * @param string $order order field
     * @param string $user content owner
     * @param string $regionCode two letter region code
     * @param string $type the application and content type to be filtered, separated by comma
     * @param string $startId the id of content object to start searching from
     * @param int $limit maximum number of contents to fetch (must not exceed 100)
     *
     * @return Content[] the contents found
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function findAll(string $term, string $order = SearchContent::CREATED_DESC, string $user = '', string $regionCode = 'default', string $type = '0.0.0', string $startId = '1.0.0', int $limit = 100): array;

    /**
     * Create a purchase content operation.
     *
     * @param Credentials account $credentials
     * @param ChainObject $contentId, 2.13.*
     *
     * @return PurchaseContentOperation
     *
     * @throws ValidationException
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     * @throws ObjectNotFoundException
     */
    public function createPurchaseOperation(Credentials $credentials, ChainObject $contentId): PurchaseContentOperation;

    /**
     * Create a purchase content operation with RUI.
     *
     * @param Credentials account $credentials
     * @param string $uri
     *
     * @return PurchaseContentOperation
     *
     * @throws ValidationException
     * @throws ObjectNotFoundException
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function createPurchaseOperationWithUri(Credentials $credentials, string $uri): PurchaseContentOperation;

    /**
     * Buy Content
     *
     * @param Credentials account $credentials
     * @param ChainObject $contentId, 2.13.*
     *
     * @return TransactionConfirmation
     * @throws ValidationException
     * @throws Exception
     */
    public function purchase(Credentials $credentials, ChainObject $contentId): ?TransactionConfirmation;

    /**
     * Buy Content With Uri
     *
     * @param Credentials account $credentials
     * @param string $uri
     *
     * @return TransactionConfirmation
     * @throws ValidationException
     * @throws Exception
     */
    public function purchaseWithUri(Credentials $credentials, string $uri): ?TransactionConfirmation;

    /**
     * @param ChainObject $id
     * @param null $fee
     *
     * @return RemoveContentOperation
     *
     * @throws ObjectNotFoundException
     * @throws ValidationException
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function createRemoveContentByIdOperation(ChainObject $id, $fee = null): RemoveContentOperation;

    /**
     * @param string $uri
     * @param null $fee
     *
     * @return RemoveContentOperation
     *
     * @throws ObjectNotFoundException
     * @throws ValidationException
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function createRemoveContentByUriOperation(string $uri, $fee = null): RemoveContentOperation;

    /**
     * Delete content by id
     *
     * @param Credentials $credentials of account which will pay operation fee
     * @param ChainObject $content
     * @param AssetAmount $fee for the operation
     *
     * @return TransactionConfirmation|null
     *
     * @throws ValidationException
     * @throws Exception
     */
    public function removeById(Credentials $credentials, ChainObject $content, AssetAmount $fee): ?TransactionConfirmation;

    /**
     * Delete content by uri
     *
     * @param Credentials $author Credentials of account which will pay operation fee,
     * will owner of content.
     * @param string $url
     * @param AssetAmount $fee for the operation
     *
     * @return TransactionConfirmation|null
     *
     * @throws ValidationException
     * @throws Exception
     */
    public function removeByIUrl(Credentials $author, string $url, AssetAmount $fee): ?TransactionConfirmation;

    /**
     * Create request to submit content operation.
     *
     * @param ChainObject $author
     * @param CoAuthors $coAuthors
     * @param string $uri
     * @param array $price
     * @param DateTime $expiration
     * @param string $synopsis
     * @param null $fee
     *
     * @return AddOrUpdateContentOperation
     *
     * @throws ValidationException
     * @throws Exception
     */
    public function createAddContentOperation(ChainObject $author, CoAuthors $coAuthors, string $uri, array $price, DateTime $expiration, string $synopsis, $fee = null): AddOrUpdateContentOperation;

    /**
     * Add content.
     *
     * @param Credentials $credentials
     * @param CoAuthors $coAuthors
     * @param string $uri
     * @param array $price
     * @param DateTime $expiration
     * @param string $synopsis
     * @param null $fee
     *
     * @return TransactionConfirmation
     *
     * @throws ValidationException
     * @throws Exception
     */
    public function add(Credentials $credentials, CoAuthors $coAuthors, string $uri, array $price, DateTime $expiration, string $synopsis, $fee = null): TransactionConfirmation;

    /**
     * Create request to update content operation. Fills the model with actual content values.
     *
     * @param ChainObject $id
     * @param null $fee
     *
     * @return AddOrUpdateContentOperation
     *
     * @throws ObjectNotFoundException
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function createUpdateContentWithIdOperation(ChainObject $id, $fee = null): AddOrUpdateContentOperation;

    /**
     * Create request to update content operation. Fills the model with actual content values.
     *
     * @param ChainObject $uri
     * @param null $fee
     *
     * @return AddOrUpdateContentOperation
     *
     * @throws ObjectNotFoundException
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function createUpdateContentWithUriOperation(ChainObject $uri, $fee = null): AddOrUpdateContentOperation;

    /**
     * Update content.
     *
     * @param Credentials $credentials
     * @param ChainObject $id
     * @param string|null $synopsis
     * @param array|null $price
     * @param CoAuthors|null $coAuthors
     * @param null $fee
     *
     * @return TransactionConfirmation
     *
     * @throws ObjectNotFoundException
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function updateWithId(Credentials $credentials, ChainObject $id, string $synopsis = null, array $price = null, CoAuthors $coAuthors = null, $fee = null): TransactionConfirmation;

    /**
     * Update content. Update parameters are functions that have current values as arguments.
     *
     * @param Credentials $credentials
     * @param string $uri
     * @param string|null $synopsis
     * @param array|null $price
     * @param CoAuthors|null $coAuthors
     * @param null $fee
     *
     * @return TransactionConfirmation
     *
     * @throws ObjectNotFoundException
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function updateWithUri(Credentials $credentials, string $uri, string $synopsis = null, array $price = null, CoAuthors $coAuthors = null, $fee = null): TransactionConfirmation;

    /**
     * Transfers an amount of one asset to content. Amount is transferred to author and co-authors of the content, if they are specified.
     * Fees are paid by the "from" account.
     *
     * @param Credentials account $credentials
     * @param ChainObject content $id
     * @param AssetAmount $amount to send with asset type
     * @param string $memo optional unencrypted message
     * @param AssetAmount $fee for the operation, if left [BaseOperation.FEE_UNSET] the fee will be computed in DCT asset
     *
     * @return TransferOperation
     *
     * @throws ValidationException
     */
    public function createTransfer(Credentials $credentials, ChainObject $id, AssetAmount $amount, string $memo = null, $fee = null): TransferOperation;

    /**
     * Transfers an amount of one asset to content. Amount is transferred to author and co-authors of the content, if they are specified.
     * Fees are paid by the "from" account.
     *
     * @param Credentials $credentials
     * @param ChainObject $id
     * @param AssetAmount $amount
     * @param string|null $memo
     * @param null $fee
     *
     * @return TransactionConfirmation
     *
     * @throws ValidationException
     * @throws Exception
     */
    public function transfer(Credentials $credentials, ChainObject $id, AssetAmount $amount, string $memo = null, $fee = null): TransactionConfirmation;
}