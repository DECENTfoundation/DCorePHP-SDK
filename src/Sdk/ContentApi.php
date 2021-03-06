<?php

namespace DCorePHP\Sdk;

use DCorePHP\Crypto\Credentials;
use DCorePHP\Crypto\PrivateKey;
use DCorePHP\Exception\ObjectAlreadyFoundException;
use DCorePHP\Exception\ObjectNotFoundException;
use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Content\ContentKeys;
use DCorePHP\Model\Content\ContentObject;
use DCorePHP\Model\Content\SubmitContent;
use DCorePHP\Model\Operation\ContentCancellationOperation;
use DCorePHP\Model\Operation\ContentSubmitOperation;
use DCorePHP\Model\Operation\PurchaseContentOperation;
use DCorePHP\Model\Operation\RequestToBuy;
use DCorePHP\Model\Operation\Transfer;
use DCorePHP\Model\PubKey;
use DCorePHP\Model\Transaction;
use DCorePHP\Model\TransactionConfirmation;
use DCorePHP\Net\Model\Request\BroadcastTransactionWithCallback;
use DCorePHP\Net\Model\Request\GenerateContentKeys;
use DCorePHP\Net\Model\Request\GetContentById;
use DCorePHP\Net\Model\Request\GetContentByURI;
use DCorePHP\Net\Model\Request\GetContentsById;
use DCorePHP\Net\Model\Request\ListPublishingManagers;
use DCorePHP\Net\Model\Request\RestoreEncryptionKey;
use DCorePHP\Net\Model\Request\SearchContent;
use Symfony\Component\Validator\Constraints\IdenticalTo;
use Symfony\Component\Validator\Validation;

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

    public function get(ChainObject $contentId): ContentObject
    {
        $contents = $this->dcoreApi->requestWebsocket(new GetContentById($contentId));

        return reset($contents) ?: null;
    }

    /**
     * @inheritdoc
     */
    public function getByURI(string $uri): ContentObject
    {
        $content = $this->dcoreApi->requestWebsocket(new GetContentByURI($uri));
        if ($content instanceof ContentObject) {
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
        } catch (\Exception $e) {
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
        string $term = '',
        string $order = SearchContent::ORDER_NONE,
        string $user = '',
        string $regionCode = '',
        string $id = '0.0.0',
        string $type = '1',
        int $count = 100
    ): array
    {
        return $this->dcoreApi->requestWebsocket(new SearchContent($term, $order, $user, $regionCode, $id, $type, $count)) ?: [];
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
     * @inheritdoc
     */
    public function createPurchaseOperation(Credentials $credentials, ChainObject $contentId): PurchaseContentOperation
    {
        return new PurchaseContentOperation($credentials, $this->get($contentId));
    }

    /**
     * @inheritdoc
     */
    public function createPurchaseOperationWithUri(Credentials $credentials, string $uri): PurchaseContentOperation
    {
        return new PurchaseContentOperation($credentials, $this->getByURI($uri));
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
     * @inheritDoc
     */
    public function deleteById(ChainObject $contentId, Credentials $author, AssetAmount $fee): ?TransactionConfirmation
    {
        return $this->deleteByUrl($this->get($contentId)->getURI(), $author, $fee);
    }

    /**
     * @inheritDoc
     */
    public function deleteByUrl(string $url, Credentials $author, AssetAmount $fee): ?TransactionConfirmation
    {
        $operation = new ContentCancellationOperation();
        $operation
            ->setAuthor($author->getAccount())
            ->setUri($url)
            ->setFee($fee);

        return $this->dcoreApi->getBroadcastApi()->broadcastOperationWithECKeyPairWithCallback(
            $author->getKeyPair(),
            $operation
        );
    }

    /**
     * @inheritDoc
     */
    public function deleteByRef($reference, Credentials $author, AssetAmount $fee): ?TransactionConfirmation
    {
        if ($reference instanceof ChainObject) {
            return $this->deleteById($reference, $author, $fee);
        }
        if (ContentObject::hasValid($reference)) {
            return $this->deleteByUrl($reference, $author, $fee);
        }
    }

    /**
     * @inheritdoc
     */
    public function update(SubmitContent $content, Credentials $author, AssetAmount $publishingFee, AssetAmount $fee): ?TransactionConfirmation
    {
        $foundContent = $this->getByURI($content->getUri()); // Also checks if exists and throws exception
        [$subject, $constraints] = [$foundContent->getExpiration()->format('c'), [
            new IdenticalTo([
                'value' => $content->getExpiration()->format('c'),
                'message' => 'Content expiration must be the same!'])]];
        if (($violations = Validation::createValidator()->validate($subject, $constraints))->count() > 0) {
            throw new ValidationException($violations);
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
     * @inheritdoc
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

        $operation = new ContentCancellationOperation();
        $operation
            ->setId($authorId)
            ->setUri($uri);

//        /** @var AssetAmount[] $fees */
//        $fees = $this->dcoreApi->requestWebsocket(new GetRequiredFees([$operation], $assetId));
//        $operation->setFee(clone reset($fees));

        $transaction = new Transaction();
        $transaction
            ->setDynamicGlobalProps($dynamicGlobalProperties)
            ->setOperations([$operation])
            ->setExtensions([])
            ->sign($authorPrivateKeyWif);

        $this->dcoreApi->requestWebsocket(new BroadcastTransactionWithCallback($transaction));
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

        $fee = $this->dcoreApi->getValidationApi()->getFee($operation, new ChainObject($priceAssetName));
        $operation->setFee($fee);

        $transaction = new Transaction();
        $transaction
            ->setDynamicGlobalProps($dynamicGlobalProperties)
            ->setOperations([$operation])
            ->setExtensions([])
            ->sign($authorPrivateKeyWif);

        $this->dcoreApi->requestWebsocket(new BroadcastTransactionWithCallback($transaction));
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
