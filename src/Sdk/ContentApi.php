<?php

namespace DCorePHP\Sdk;

use DateTime;
use DCorePHP\Crypto\Credentials;
use DCorePHP\Exception\ObjectNotFoundException;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\CoAuthors;
use DCorePHP\Model\Content\ContentKeys;
use DCorePHP\Model\Content\Content;
use DCorePHP\Model\Memo;
use DCorePHP\Model\Operation\RemoveContentOperation;
use DCorePHP\Model\Operation\AddOrUpdateContentOperation;
use DCorePHP\Model\Operation\PurchaseContentOperation;
use DCorePHP\Model\Operation\TransferOperation;
use DCorePHP\Model\PubKey;
use DCorePHP\Model\RegionalPrice;
use DCorePHP\Model\TransactionConfirmation;
use DCorePHP\Net\Model\Request\GenerateContentKeys;
use DCorePHP\Net\Model\Request\GetContentById;
use DCorePHP\Net\Model\Request\GetContentByURI;
use DCorePHP\Net\Model\Request\GetContentsById;
use DCorePHP\Net\Model\Request\ListPublishingManagers;
use DCorePHP\Net\Model\Request\RestoreEncryptionKey;
use DCorePHP\Net\Model\Request\SearchContent;
use Exception;

class ContentApi extends BaseApi implements ContentApiInterface
{
    /**
     * @inheritdoc
     */
    public function generateKeys(array $seeders): ContentKeys
    {
        $contentKeys = $this->dcoreApi->requestWebsocket(new GenerateContentKeys($seeders));
        if ($contentKeys instanceof ContentKeys) {
            return $contentKeys;
        }

        return null;
    }

    public function get(ChainObject $contentId): Content
    {
        $contents = $this->dcoreApi->requestWebsocket(new GetContentById($contentId));

        return reset($contents) ?: null;
    }

    /**
     * @inheritdoc
     */
    public function getByURI(string $uri): Content
    {
        $content = $this->dcoreApi->requestWebsocket(new GetContentByURI($uri));
        if ($content instanceof Content) {
            return $content;
        }

        throw new ObjectNotFoundException("Content with uri '{$uri}' doesn't exist.");
    }

    /**
     * @inheritdoc
     */
    public function getAll(array $contentIds): array
    {
        return $this->dcoreApi->requestWebsocket(new GetContentsById($contentIds));
    }

    /**
     * @inheritdoc
     */
    public function exist(string $uri): bool
    {
        try {
            $this->getByURI($uri);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function listAllPublishersRelative(string $lowerBound, int $limit = 100): array
    {
        return $this->dcoreApi->requestWebsocket(new ListPublishingManagers($lowerBound, $limit)) ?: [];
    }

    /**
     * @inheritdoc
     */
    public function restoreEncryptionKey(PubKey $privateElGamal, ChainObject $purchaseId): string
    {
        return $this->dcoreApi->requestWebsocket(new RestoreEncryptionKey($privateElGamal, $purchaseId));
    }

    /**
     * @inheritdoc
     */
    public function findAll(
        string $term,
        string $order = SearchContent::CREATED_DESC,
        string $user = '',
        string $regionCode = RegionalPrice::REGIONS_ALL,
        string $type = '0.0.0',
        string $startId = '1.0.0',
        int $count = 100
    ): array
    {
        return $this->dcoreApi->requestWebsocket(new SearchContent($term, $order, $user, $regionCode, $type, $startId, $count)) ?: [];
    }

    /**
     * @inheritdoc
     */
    public function createPurchaseOperation(Credentials $credentials, ChainObject $contentId): PurchaseContentOperation
    {
        $content = $this->get($contentId);
        $operation = new PurchaseContentOperation();
        $operation
            ->setUri($content->getURI())
            ->setConsumer($credentials->getAccount())
            ->setPrice($content->regionalPrice()->getPrice())
            ->setPublicElGamal(parse_url($content->getURI(), PHP_URL_SCHEME) !== 'ipfs' ? new PubKey() : (new PubKey())->setPubKey($credentials->getKeyPair()->getPrivate()->toElGamalPublicKey()))
            ->setRegionCode($content->regionalPrice()->getRegion());
        return $operation;
    }

    /**
     * @inheritdoc
     */
    public function createPurchaseOperationWithUri(Credentials $credentials, string $uri): PurchaseContentOperation
    {
        $content = $this->getByURI($uri);
        $operation = new PurchaseContentOperation();
        $operation
            ->setUri($content->getURI())
            ->setConsumer($credentials->getAccount())
            ->setPrice($content->regionalPrice()->getPrice())
            ->setPublicElGamal(parse_url($content->getURI(), PHP_URL_SCHEME) !== 'ipfs' ? new PubKey() : (new PubKey())->setPubKey($credentials->getKeyPair()->getPrivate()->toElGamalPublicKey()))
            ->setRegionCode($content->regionalPrice()->getRegion());
        return $operation;
    }

    /**
     * @inheritdoc
     */
    public function purchase(Credentials $credentials, ChainObject $contentId): ?TransactionConfirmation
    {
        return $this->dcoreApi->getBroadcastApi()->broadcastOperationWithECKeyPairWithCallback(
            $credentials->getKeyPair(),
            $this->createPurchaseOperation($credentials, $contentId)
        );
    }

    /**
     * @inheritdoc
     */
    public function purchaseWithUri(Credentials $credentials, string $uri): ?TransactionConfirmation
    {
        return $this->dcoreApi->getBroadcastApi()->broadcastOperationWithECKeyPairWithCallback(
            $credentials->getKeyPair(),
            $this->createPurchaseOperationWithUri($credentials, $uri)
        );
    }

    /**
     * @inheritdoc
     */
    public function createTransfer(
        Credentials $credentials,
        ChainObject $id,
        AssetAmount $amount,
        string $memo = null,
        $fee = null
    ): TransferOperation {
        $operation = new TransferOperation();
        $operation
            ->setFrom($credentials->getAccount())
            ->setTo($id)
            ->setAmount($amount)
            ->setMemo($memo ? Memo::withMessage($memo) : null)
            ->setFee($fee);
        return $operation;
    }

    /**
     * @inheritDoc
     */
    public function transfer(Credentials $credentials, ChainObject $id, AssetAmount $amount, string $memo = null, $fee = null): TransactionConfirmation
    {
        $fee = $fee ?: new AssetAmount();
        return $this->dcoreApi->getBroadcastApi()->broadcastOperationWithECKeyPairWithCallback(
            $credentials->getKeyPair(),
            $this->createTransfer($credentials, $id, $amount, $memo, $fee)
        );
    }

    /**
     * @inheritDoc
     */
    public function createRemoveContentByIdOperation(ChainObject $id, $fee = null): RemoveContentOperation
    {
        $content = $this->get($id);
        $operation = new RemoveContentOperation();
        $operation
            ->setAuthor($content->getAuthor())
            ->setUri($content->getURI())
            ->setFee($fee);
        return $operation;
    }

    /**
     * @inheritDoc
     */
    public function createRemoveContentByUriOperation(string $uri, $fee = null): RemoveContentOperation
    {
        $content = $this->getByURI($uri);
        $operation = new RemoveContentOperation();
        $operation
            ->setAuthor($content->getAuthor())
            ->setUri($content->getURI())
            ->setFee($fee);
        return $operation;
    }

    /**
     * @inheritDoc
     */
    public function removeById(Credentials $credentials, ChainObject $id, AssetAmount $fee = null): ?TransactionConfirmation
    {
        $fee = $fee ?: new AssetAmount();
        return $this->dcoreApi->getBroadcastApi()->broadcastOperationWithECKeyPairWithCallback(
            $credentials->getKeyPair(),
            $this->createRemoveContentByIdOperation($id, $fee)
        );
    }

    /**
     * @inheritDoc
     */
    public function removeByUrl(Credentials $credentials, string $content, AssetAmount $fee = null): ?TransactionConfirmation
    {
        $fee = $fee ?: new AssetAmount();
        return $this->dcoreApi->getBroadcastApi()->broadcastOperationWithECKeyPairWithCallback(
            $credentials->getKeyPair(),
            $this->createRemoveContentByUriOperation($content, $fee)
        );
    }

    /**
     * @inheritDoc
     */
    public function createAddContentOperation(ChainObject $author, CoAuthors $coAuthors, string $uri, array $price, DateTime $expiration, string $synopsis, $fee = null): AddOrUpdateContentOperation
    {
        $operation = new AddOrUpdateContentOperation();
        $operation
            ->setAuthor($author)
            ->setCoauthors($coAuthors)
            ->setUri($uri)
            ->setPrice($price)
            ->setExpiration($expiration)
            ->setSynopsis($synopsis)
            ->setFee($fee);
        return $operation;
    }

    /**
     * @inheritDoc
     */
    public function add(Credentials $credentials, CoAuthors $coAuthors, string $uri, array $price, DateTime $expiration, string $synopsis, $fee = null): TransactionConfirmation
    {
        $fee = $fee ?: new AssetAmount();
        return $this->dcoreApi->getBroadcastApi()->broadcastOperationWithECKeyPairWithCallback(
            $credentials->getKeyPair(),
            $this->createAddContentOperation($credentials->getAccount(), $coAuthors, $uri, $price, $expiration, $synopsis, $fee)
        );
    }

    private function createContentOperation(Content $old, $fee = null): AddOrUpdateContentOperation
    {
        $operation = new AddOrUpdateContentOperation();
        $operation
            ->setSize($old->getSize())
            ->setAuthor($old->getAuthor())
            ->setCoauthors($old->getCoauthors())
            ->setUri($old->getUri())
            ->setPrice($old->getPrice()->regionalPrice())
            ->setHash($old->getHash())
            ->setSeeders($old->getSeederPrice())
            ->setKeyParts($old->getKeyParts())
            ->setExpiration($old->getExpiration())
            ->setPublishingFee($old->getPublishingFeeEscrow())
            ->setSynopsis($old->getSynopsis())
            ->setCustodyData($old->getCustodyData())
            ->setFee($fee);
        return $operation;
    }

    /**
     * @inheritDoc
     */
    public function createUpdateContentWithIdOperation(ChainObject $id, $fee = null): AddOrUpdateContentOperation
    {
        $content = $this->get($id);
        return $this->createContentOperation($content, $fee);
    }

    /**
     * @inheritDoc
     */
    public function createUpdateContentWithUriOperation(ChainObject $uri, $fee = null): AddOrUpdateContentOperation
    {
        $content = $this->getByURI($uri);
        return $this->createContentOperation($content, $fee);
    }

    private function update(Credentials $credentials, Content $content, string $synopsis = null, array $price = null, CoAuthors $coAuthors = null, $fee = null)
    {
        $fee = $fee ?: new AssetAmount();
        $operation = $this->createContentOperation($content, $fee);
        if ($synopsis !== null) $operation->setSynopsis($synopsis);
        if ($price !== null) $operation->setPrice($price);
        if ($coAuthors !== null) $operation->setCoauthors($coAuthors);

        return $this->dcoreApi->getBroadcastApi()->broadcastOperationWithECKeyPairWithCallback(
            $credentials->getKeyPair(),
            $operation
        );
    }

    /**
     * @inheritDoc
     */
    public function updateWithId(Credentials $credentials, ChainObject $id, string $synopsis = null, array $price = null, CoAuthors $coAuthors = null, $fee = null): TransactionConfirmation
    {
        $fee = $fee ?: new AssetAmount();
        $content = $this->get($id);
        return $this->update($credentials, $content, $synopsis, $price, $coAuthors, $fee);
    }

    /**
     * @inheritDoc
     */
    public function updateWithUri(Credentials $credentials, string $uri, string $synopsis = null, array $price = null, CoAuthors $coAuthors = null, $fee = null): TransactionConfirmation
    {
        $fee = $fee ?: new AssetAmount();
        $content = $this->getByURI($uri);
        return $this->update($credentials, $content, $synopsis, $price, $coAuthors, $fee);
    }
}
