<?php

namespace DCorePHP\Sdk;

use DCorePHP\Crypto\Credentials;
use DCorePHP\Crypto\PrivateKey;
use DCorePHP\Exception\InvalidApiCallException;
use DCorePHP\Exception\ObjectAlreadyFoundException;
use DCorePHP\Exception\ObjectNotFoundException;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Content\ContentKeys;
use DCorePHP\Model\Content\ContentObject;
use DCorePHP\Model\Content\SubmitContent;
use DCorePHP\Model\Operation\ContentCancellation;
use DCorePHP\Model\Operation\ContentSubmitOperation;
use DCorePHP\Model\Operation\PurchaseContentOperation;
use DCorePHP\Model\Operation\RequestToBuy;
use DCorePHP\Model\Operation\Transfer;
use DCorePHP\Model\PubKey;
use DCorePHP\Model\Transaction;
use DCorePHP\Model\TransactionConfirmation;
use DCorePHP\Net\Model\Request\BroadcastTransactionWithCallback;
use DCorePHP\Net\Model\Request\Database;
use DCorePHP\Net\Model\Request\GenerateContentKeys;
use DCorePHP\Net\Model\Request\GetContentById;
use DCorePHP\Net\Model\Request\GetContentByURI;
use DCorePHP\Net\Model\Request\GetRequiredFees;
use DCorePHP\Net\Model\Request\ListPublishingManagers;
use DCorePHP\Net\Model\Request\NetworkBroadcast;
use DCorePHP\Net\Model\Request\RestoreEncryptionKey;
use DCorePHP\Net\Model\Request\SearchContent;
use WebSocket\BadOpcodeException;

class ContentApi extends BaseApi implements ContentApiInterface
{
    /**
     * @inheritdoc
     */
    public function generateKeys(array $seeders): ContentKeys
    {
        $contentKeys = $this->dcoreApi->requestWebsocket(Database::class, new GenerateContentKeys($seeders));
        if ($contentKeys instanceof ContentKeys) {
            return $contentKeys;
        }

        return null;
    }

    public function get(ChainObject $contentId): ContentObject
    {
        $contents = $this->dcoreApi->requestWebsocket(Database::class, new GetContentById($contentId));

        return reset($contents) ?: null;
    }

    /**
     * @inheritdoc
     */
    public function getByURI(string $uri): ContentObject
    {
        $content = $this->dcoreApi->requestWebsocket(Database::class, new GetContentByURI($uri));
        if ($content instanceof ContentObject) {
            return $content;
        }

        throw new ObjectNotFoundException("Content with uri '{$uri}' doesn't exist.");
    }

    /**
     * @inheritDoc
     */
    public function exist(string $uri): bool
    {
        try {
            $this->getByURI($uri);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function listAllPublishersRelative(string $lowerBound, int $limit = 100): array
    {
        return $this->dcoreApi->requestWebsocket(Database::class, new ListPublishingManagers($lowerBound, $limit)) ?: [];
    }

    /**
     * @inheritdoc
     */
    public function restoreEncryptionKey(PubKey $privateElGamal, ChainObject $purchaseId): string
    {
        return $this->dcoreApi->requestWebsocket(Database::class, new RestoreEncryptionKey($privateElGamal, $purchaseId));
    }

    /**
     * @inheritdoc
     */
    public function findAll(
        string $term = '',
        string $order = SearchContent::ORDER_NONE,
        string $user = '',
        string $regionCode = '',
        string $id = '0.0.0',
        string $type = '1',
        int $count = 100
    ): array
    {
        return $this->dcoreApi->requestWebsocket(Database::class, new SearchContent($term, $order, $user, $regionCode, $id, $type, $count)) ?: [];
    }

    public function create(SubmitContent $content, Credentials $author, AssetAmount $publishingFee, AssetAmount $fee): ?TransactionConfirmation {
        if ($this->exist($content->getUri())) {
            throw new ObjectAlreadyFoundException("Content with uri: {$content->getUri()} already exists!");
        }

        return $this->dcoreApi->getBroadcastApi()->broadcastOperationWithECKeyPairWithCallback($author->getKeyPair(), (
            new ContentSubmitOperation())
            ->setContent($content)
            ->setAuthor($author->getAccount())
            ->setPublishingFee($publishingFee)
            ->setFee($fee)
        );
    }

    /**
     * @inheritDoc
     */
    public function createPurchaseOperation(Credentials $credentials, ChainObject $contentId): PurchaseContentOperation
    {
        return new PurchaseContentOperation($credentials, $this->get($contentId));
    }

    /**
     * @inheritDoc
     */
    public function createPurchaseOperationWithUri(Credentials $credentials, string $uri): PurchaseContentOperation
    {
        return new PurchaseContentOperation($credentials, $this->getByURI($uri));
    }

    /**
     * @inheritDoc
     */
    public function purchase(Credentials $credentials, ChainObject $contentId): ?TransactionConfirmation
    {
        return $this->dcoreApi->getBroadcastApi()->broadcastOperationWithECKeyPairWithCallback(
            $credentials->getKeyPair(),
            $this->createPurchaseOperation($credentials, $contentId)
        );
    }

    /**
     * @inheritDoc
     */
    public function purchaseWithUri(Credentials $credentials, string $uri): ?TransactionConfirmation
    {
        return $this->dcoreApi->getBroadcastApi()->broadcastOperationWithECKeyPairWithCallback(
            $credentials->getKeyPair(),
            $this->createPurchaseOperationWithUri($credentials, $uri)
        );
    }

    /**
     * @inheritDoc
     */
    public function createTransfer(
        Credentials $credentials,
        ChainObject $id,
        AssetAmount $amount,
        string $memo = null,
        AssetAmount $fee = null
    ): Transfer {
        // TODO: Implement createTransfer() method.
    }

    /**
     * @inheritdoc
     */
    public function contentCancellation(ChainObject $authorId, string $uri, string $authorPrivateKeyWif, bool $broadcast)
    {
        /** @var $dynamicGlobalProperties $dynamicGlobalProps */
        $dynamicGlobalProperties = $this->dcoreApi->getGeneralApi()->getDynamicGlobalProperties();

        $operation = new ContentCancellation();
        $operation
            ->setId($authorId)
            ->setUri($uri);

//        /** @var AssetAmount[] $fees */
//        $fees = $this->dcoreApi->requestWebsocket(Database::class, new GetRequiredFees([$operation], $assetId));
//        $operation->setFee(clone reset($fees));

        $transaction = new Transaction();
        $transaction
            ->setDynamicGlobalProps($dynamicGlobalProperties)
            ->setOperations([$operation])
            ->setExtensions([])
            ->sign($authorPrivateKeyWif);

        $this->dcoreApi->requestWebsocket(NetworkBroadcast::class, new BroadcastTransactionWithCallback($transaction));
    }

    /**
     * @inheritdoc
     */
    public function downloadContent(string $consumer, string $uri, string $regionCodeFrom, bool $broadcast = false)
    {
        // TODO: Implement downloadContent() method.
    }

    /**
     * @inheritdoc
     */
    public function getDownloadStatus(string $consumer, string $uri)
    {
        // TODO: Implement getDownloadStatus() method.
    }

    /**
     * @inheritdoc
     */
    public function requestToBuy(
        ChainObject $consumer,
        string $uri,
        string $priceAssetName,
        string $priceAmount,
        int $regionCodeFrom = 1,
        string $authorPrivateKeyWif,
        bool $broadcast = true
    )
    {
        /** @var $dynamicGlobalProperties $dynamicGlobalProps */
        $dynamicGlobalProperties = $this->dcoreApi->getGeneralApi()->getDynamicGlobalProperties();

        $elGamalPublicKey = PrivateKey::fromWif($authorPrivateKeyWif)->toElGamalPublicKey();

        $operation = new RequestToBuy();
        $operation
            ->setConsumer($consumer)
            ->setUri($uri)
            ->setPrice((new AssetAmount())->setAmount($priceAmount)->setAssetId($priceAssetName))
            ->setRegionCodeFrom($regionCodeFrom)
            // TODO: Better Public Key Implementation
            ->setElGamalPublicKey((new PubKey())->setPubKey($elGamalPublicKey));

        /** @var AssetAmount[] $fees */
        $fees = $this->dcoreApi->requestWebsocket(Database::class, new GetRequiredFees([$operation], new ChainObject($priceAssetName)));
        $operation->setFee(clone reset($fees));

        $transaction = new Transaction();
        $transaction
            ->setDynamicGlobalProps($dynamicGlobalProperties)
            ->setOperations([$operation])
            ->setExtensions([])
            ->sign($authorPrivateKeyWif);

        $this->dcoreApi->requestWebsocket(NetworkBroadcast::class, new BroadcastTransactionWithCallback($transaction));
    }

    /**
     * @inheritdoc
     */
    public function leaveRatingAndComment(
        string $consumer,
        string $uri,
        int $rating,
        string $comment,
        bool $broadcast = false
    ): BaseOperation
    {
        // TODO: Implement leaveRatingAndComment() method.
    }

    /**
     * @inheritdoc
     */
    public function generateEncryptionKey()
    {
        // TODO: Implement generateEncryptionKey() method.
    }

    /**
     * @inheritdoc
     */
    public function searchUserContent(
        string $user,
        string $term,
        string $order,
        string $regionCode,
        string $id,
        string $type,
        int $count = 100
    ): array
    {
        // TODO: Implement searchUserContent() method.
    }

    /**
     * @inheritdoc
     */
    public function getAuthorAndCoAuthorsByUri(string $uri): array
    {
        // TODO: Implement getAuthorAndCoAuthorsByUri() method.
    }
}
