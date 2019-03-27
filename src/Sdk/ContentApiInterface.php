<?php


namespace DCorePHP\Sdk;


use DCorePHP\Crypto\Credentials;
use DCorePHP\Exception\ObjectNotFoundException;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Content\Content;
use DCorePHP\Model\Content\ContentKeys;
use DCorePHP\Model\Content\ContentObject;
use DCorePHP\Model\Content\SubmitContent;
use DCorePHP\Model\Operation\ContentSubmitOperation;
use DCorePHP\Model\Operation\PurchaseContentOperation;
use DCorePHP\Model\Operation\Transfer;
use DCorePHP\Model\PubKey;
use DCorePHP\Model\TransactionConfirmation;
use DCorePHP\Net\Model\Request\SearchContent;

interface ContentApiInterface
{
    /**
     * Generate keys for new content submission.
     *
     * @param ChainObject[] $seeders list of seeder account IDs
     * @return ContentKeys key and key parts
     * @throws \DCorePHP\Exception\InvalidApiCallException
     * @throws \WebSocket\BadOpcodeException
     */
    public function generateKeys(array $seeders): ContentKeys;

    /**
     * Get a content by id
     *
     * @param ChainObject $contentId the id of the content to retrieve
     * @return ContentObject the content corresponding to the provided URI, or null if no matching content was found
     * @throws ObjectNotFoundException
     * @throws \DCorePHP\Exception\InvalidApiCallException
     * @throws \WebSocket\BadOpcodeException
     */
    public function get(ChainObject $contentId): ContentObject;

    /**
     * Get a content by URI
     *
     * @param string $uri the URI of the content to retrieve
     * @return ContentObject the content corresponding to the provided URI, or null if no matching content was found
     * @throws ObjectNotFoundException
     * @throws \DCorePHP\Exception\InvalidApiCallException
     * @throws \WebSocket\BadOpcodeException
     */
    public function getByURI(string $uri): ContentObject;

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
     */
    public function listAllPublishersRelative(string $lowerBound, int $limit = 100): array;

    /**
     * Restores AES key( used to encrypt and decrypt a content) from key particles stored in a buying object
     *
     * @param PubKey $elGamalPrivate
     * @param ChainObject $purchaseId
     * @return mixed restored AES key from key particles
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
     * @param string $startid the id of content object to start searching from
     * @param int $limit maximum number of contents to fetch (must not exceed 100)
     *
     * @return Content[] the contents found
     */
    public function findAll(string $term, string $order = SearchContent::CREATED_DESC, string $user = '', string $regionCode = 'default', string $type, string $startid, int $limit = 100): array;

    /**
     * @param SubmitContent $content
     * @param Credentials $author which will pay operation fee, will owner of content.
     * @param AssetAmount $publishingFee
     * @param AssetAmount $fee
     * @return TransactionConfirmation that content was created.
     * @throws ObjectNotFoundException
     * @throws \Exception
     */
    public function create(SubmitContent $content, Credentials $author, AssetAmount $publishingFee, AssetAmount $fee): ?TransactionConfirmation;

    /**
     * Create a purchase content operation.
     *
     * @param Credentials account $credentials
     * @param ChainObject $contentId, 2.13.*
     *
     * @return PurchaseContentOperation
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function createPurchaseOperation(Credentials $credentials, ChainObject $contentId): PurchaseContentOperation;

    /**
     * Create a purchase content operation with RUI.
     *
     * @param Credentials account $credentials
     * @param string $uri
     *
     * @return PurchaseContentOperation
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function createPurchaseOperationWithUri(Credentials $credentials, string $uri): PurchaseContentOperation;

    /**
     * Buy Content
     *
     * @param Credentials account $credentials
     * @param ChainObject $contentId, 2.13.*
     *
     * @return TransactionConfirmation
     * @throws \DCorePHP\Exception\ValidationException
     * @throws \Exception
     */
    public function purchase(Credentials $credentials, ChainObject $contentId): ?TransactionConfirmation;

    /**
     * Buy Content With Uri
     *
     * @param Credentials account $credentials
     * @param string $uri
     *
     * @return TransactionConfirmation
     * @throws \DCorePHP\Exception\ValidationException
     * @throws \Exception
     */
    public function purchaseWithUri(Credentials $credentials, string $uri): ?TransactionConfirmation;

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
     * @return Transfer transaction confirmation
     */
    public function createTransfer(Credentials $credentials, ChainObject $id, AssetAmount $amount, string $memo = null, AssetAmount $fee = null): Transfer;

    /**
     * This function can be used to cancel submitted content
     * @param ChainObject $authorId the author of the content
     * @param string $uri the URI of the content
     * @param bool $broadcast
     * @param string $authorPrivateKeyWif,
     * @return mixed true to broadcast the transaction on the network
     * @throws \Exception
     */
    public function contentCancellation(ChainObject $authorId, string $uri, string $authorPrivateKeyWif, bool $broadcast);

    /**
     * Downloads encrypted content specified by provided URI.
     * @param string $consumer consumer of the content
     * @param string $uri the URI of the content
     * @param string $regionCodeFrom two letter region code
     * @param bool $broadcast true to broadcast the transaction on the network
     */
    public function downloadContent(string $consumer, string $uri, string $regionCodeFrom, bool $broadcast = false);

    /**
     * Get status about particular download process specified by provided URI
     * @param string $consumer consumer of the content
     * @param string $uri he URI of the content
     * @return mixed download status, or null if no matching download process was found
     */
    public function getDownloadStatus(string $consumer, string $uri);

    /**
     * This function is used to send a request to buy a content. This request is caught by seeders
     * @param ChainObject $consumer consumer of the content
     * @param string $uri the URI of the content
     * @param string $priceAssetName ticker symbol of the asset which will be used to buy content
     * @param string $priceAmount the price of the content
     * @param int $regionCodeFrom two letter region code
     * @param string $authorPrivateKeyWif
     * @param bool $broadcast true to broadcast the transaction on the network
     */
    public function requestToBuy(ChainObject $consumer, string $uri, string $priceAssetName, string $priceAmount, int $regionCodeFrom, string $authorPrivateKeyWif, bool $broadcast = true);

    /**
     * Rates and comments a content
     * @param string $consumer consumer giving the rating
     * @param string $uri the URI of the content
     * @param int $rating the rating. The available options are 1-5
     * @param string $comment the maximum length of a comment is 100 characters
     * @param bool $broadcast true to broadcast the transaction on the network
     * @return BaseOperation
     */
    public function leaveRatingAndComment(string $consumer, string $uri, int $rating, string $comment, bool $broadcast = false): BaseOperation;

    /**
     * Generates AES encryption key
     * @return mixed random encryption key
     */
    public function generateEncryptionKey();

    /**
     * @param string $user content owner
     * @param string $term search term
     * @param string $order order field
     * @param string $regionCode two letter region code
     * @param string $id the id of content object to start searching from
     * @param string $type the application and content type to be filtered, separated by comma
     * @param int $count maximum number of contents to fetch (must not exceed 100)
     * @return array
     */
    public function searchUserContent(string $user, string $term, string $order, string $regionCode, string $id, string $type, int $count = 100): array;

    /**
     * Get author and list of co-authors of a content corresponding to the provided URI.
     * @param string $uri the URI of the content
     * @return array the author of the content and the list of co-authors, if provided
     */
    public function getAuthorAndCoAuthorsByUri(string $uri): array;
}