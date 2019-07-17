<?php

namespace DCorePHP\Sdk;

use DCorePHP\Crypto\Credentials;
use DCorePHP\DCoreApi;
use DCorePHP\Exception\ObjectNotFoundException;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\Memo;
use DCorePHP\Model\Nft;
use DCorePHP\Model\NftData;
use DCorePHP\Model\NftModel;
use DCorePHP\Model\NftOptions;
use DCorePHP\Model\Operation\NftCreateOperation;
use DCorePHP\Model\Operation\NftIssueOperation;
use DCorePHP\Model\Operation\NftTransferOperation;
use DCorePHP\Model\Operation\NftUpdateDataOperation;
use DCorePHP\Model\Operation\NftUpdateOperation;
use DCorePHP\Model\Proposal\Fee;
use DCorePHP\Model\TransactionConfirmation;
use DCorePHP\Net\Model\Request\GetNftCount;
use DCorePHP\Net\Model\Request\GetNftData;
use DCorePHP\Net\Model\Request\GetNftDataCount;
use DCorePHP\Net\Model\Request\GetNfts;
use DCorePHP\Net\Model\Request\GetNftsBalances;
use DCorePHP\Net\Model\Request\GetNftsBySymbol;
use DCorePHP\Net\Model\Request\ListNftData;
use DCorePHP\Net\Model\Request\ListNfts;
use DCorePHP\Net\Model\Request\SearchNftHistory;

class NftApi extends BaseApi implements NftApiInterface
{

    /**
     * @inheritDoc
     */
    public function get(string $idOrSymbol): Nft
    {
        if (ChainObject::isValid($idOrSymbol)) {
            return $this->getById(new ChainObject($idOrSymbol));
        }
        return $this->getBySymbol($idOrSymbol);
    }

    /**
     * @inheritDoc
     */
    public function getAll(array $ids): array
    {
        return $this->dcoreApi->requestWebsocket(new GetNfts($ids));
    }

    /**
     * @inheritDoc
     */
    public function getById(ChainObject $id): Nft
    {
        $nfts = $this->getAll([$id]);
        $nft = reset($nfts);

        if ($nft instanceof Nft) {
            return $nft;
        }

        throw new ObjectNotFoundException("Nft with id $id doesn't exist.");
    }

    /**
     * @inheritDoc
     */
    public function getAllBySymbol(array $symbols): array
    {
        return $this->dcoreApi->requestWebsocket(new GetNftsBySymbol($symbols));
    }

    /**
     * @inheritDoc
     */
    public function getBySymbol(string $symbol): Nft
    {
        $nfts = $this->getAllBySymbol([$symbol]);
        $nft = reset($nfts);

        if ($nft instanceof Nft) {
            return $nft;
        }

        throw new ObjectNotFoundException("Nft with symbol $symbol doesn't exist.");
    }

    /**
     * @inheritDoc
     */
    public function getAllData(array $ids): array
    {
        return $this->make($this->getAllDataRaw($ids));
    }

    /**
     * @inheritDoc
     */
    public function getAllDataRaw(array $ids): array
    {
        return $this->dcoreApi->requestWebsocket(new GetNftData($ids));
    }

    /**
     * @inheritDoc
     */
    public function getAllDataWithClass(array $ids, string $class): array
    {
        return $this->make($this->dcoreApi->requestWebsocket(new GetNftData($ids)), $class);
    }

    /**
     * @inheritDoc
     */
    public function getDataWithClass(ChainObject $id, string $class): NftData
    {
        $data = $this->getAllDataWithClass([$id], $class);
        $data = reset($data);
        if ($data instanceof NftData) {
            return $data;
        }
        throw new ObjectNotFoundException("Nft with symbol $id doesn't exist.");
    }

    /**
     * @inheritDoc
     */
    public function getData(ChainObject $id): NftData
    {
        $data = $this->getAllData([$id]);
        $data = reset($data);
        if ($data instanceof NftData) {
            return $data;
        }
        throw new ObjectNotFoundException("Nft with symbol $id doesn't exist.");
    }

    /**
     * @inheritDoc
     */
    public function getDataRaw(ChainObject $id): NftData
    {
        $data = $this->getAllDataRaw([$id]);
        $data = reset($data);
        if ($data instanceof NftData) {
            return $data;
        }
        throw new ObjectNotFoundException("Nft with symbol $id doesn't exist.");
    }

    /**
     * @inheritDoc
     */
    public function countAll(): string
    {
        return $this->dcoreApi->requestWebsocket(new GetNftCount());
    }

    /**
     * @inheritDoc
     */
    public function countAllData(): string
    {
        return $this->dcoreApi->requestWebsocket(new GetNftDataCount());
    }

    /**
     * @inheritDoc
     */
    public function getNftBalancesRaw(ChainObject $account, array $nftIds = []): array
    {
        return $this->dcoreApi->requestWebsocket(new GetNftsBalances($account, $nftIds));
    }

    /**
     * @inheritDoc
     */
    public function getNftBalances(ChainObject $account, array $nftIds = []): array
    {
        return $this->make($this->getNftBalancesRaw($account, $nftIds));
    }

    /**
     * @inheritDoc
     */
    public function getNftBalancesWithClass(ChainObject $account, ChainObject $nftId, string $class): array
    {
        return $this->make($this->getNftBalancesRaw($account, [$nftId]), $class);
    }

    /**
     * @inheritDoc
     */
    public function listAllRelative(string $lowerBound = '', int $limit = DCoreApi::REQ_LIMIT_MAX): array
    {
        return $this->dcoreApi->requestWebsocket(new ListNfts($lowerBound, $limit));
    }

    /**
     * @inheritDoc
     */
    public function listDataByNftWithClass(ChainObject $nftId, string $class): array
    {
        return $this->make($this->listDataByNftRaw($nftId), $class);
    }

    /**
     * @inheritDoc
     */
    public function listDataByNft(ChainObject $nftId): array
    {
        return $this->make($this->listDataByNftRaw($nftId));
    }

    /**
     * @inheritDoc
     */
    public function listDataByNftRaw(ChainObject $nftId): array
    {
        return $this->dcoreApi->requestWebsocket(new ListNftData($nftId));
    }

    /**
     * @inheritDoc
     */
    public function searchNftHistory(ChainObject $nftDataId): array
    {
        return $this->dcoreApi->requestWebsocket(new SearchNftHistory($nftDataId));
    }

    /**
     * @inheritDoc
     */
    public function createNftCreateOperation(
        string $symbol,
        NftOptions $options,
        $model,
        bool $transferable,
        $fee = null
    ): NftCreateOperation {
        $operation = new NftCreateOperation();
        $operation
            ->setSymbol($symbol)
            ->setOptions($options)
            ->setDefinitions(NftModel::createDefinitions($model))
            ->setTransferable($transferable)
            ->setFee($fee);
        return $operation;
    }

    /**
     * @inheritDoc
     */
    public function create(
        Credentials $credentials,
        string $symbol,
        string $maxSupply,
        bool $fixedMaxSupply,
        string $description,
        $model,
        bool $transferable,
        $fee = null
    ): TransactionConfirmation {
        $fee = $fee ?: new AssetAmount();
        $options = new NftOptions();
        $options
            ->setIssuer($credentials->getAccount())
            ->setMaxSupply($maxSupply)
            ->setFixedMaxSupply($fixedMaxSupply)
            ->setDescription($description);

        return $this->dcoreApi->getBroadcastApi()->broadcastOperationWithECKeyPairWithCallback(
            $credentials->getKeyPair(),
            $this->createNftCreateOperation($symbol, $options, $model, $transferable, $fee)
        );
    }

    /**
     * @inheritDoc
     */
    public function createUpdateOperation(string $idOrSymbol, $fee = null): NftUpdateOperation
    {
        $nft = $this->get($idOrSymbol);
        $operation = new NftUpdateOperation();
        $operation
            ->setIssuer($nft->getOptions()->getIssuer())
            ->setId($nft->getId())
            ->setOptions($nft->getOptions())
            ->setFee($fee);
        return $operation;
    }

    /**
     * @inheritDoc
     */
    public function update(
        Credentials $credentials,
        string $idOrSymbol,
        string $maxSupply = null,
        bool $fixedMaxSupply = null,
        string $description = null,
        $fee = null
    ): TransactionConfirmation {
        $fee = $fee ?: new AssetAmount();

        $operation = $this->createUpdateOperation($idOrSymbol, $fee);
        $operation->getOptions()->update($maxSupply, $fixedMaxSupply, $description);

        return $this->dcoreApi->getBroadcastApi()->broadcastOperationWithECKeyPairWithCallback(
            $credentials->getKeyPair(),
            $operation
        );
    }

    /**
     * @inheritDoc
     */
    public function createIssueOperation(
        ChainObject $issuer,
        string $idOrSymbol,
        ChainObject $to,
        NftModel $data = null,
        Memo $memo = null,
        $fee = null
    ): NftIssueOperation {
        $nft = $this->get($idOrSymbol);
        $dataArray = $data ? $data->values() : [];
        $operation = new NftIssueOperation();
        $operation
            ->setIssuer($issuer)
            ->setId($nft->getId())
            ->setTo($to)
            ->setData($dataArray)
            ->setMemo($memo)
            ->setFee($fee);
        return $operation;
    }

    /**
     * @inheritDoc
     */
    public function issue(
        Credentials $credentials,
        string $idOrSymbol,
        ChainObject $to,
        NftModel $data = null,
        Memo $memo = null,
        $fee = null
    ): TransactionConfirmation {
        $fee = $fee ?: new AssetAmount();

        return $this->dcoreApi->getBroadcastApi()->broadcastOperationWithECKeyPairWithCallback(
            $credentials->getKeyPair(),
            $this->createIssueOperation($credentials->getAccount(), $idOrSymbol, $to, $data, $memo, $fee)
        );
    }

    /**
     * @inheritDoc
     */
    public function createTransferOperation(
        ChainObject $from,
        ChainObject $to,
        ChainObject $id,
        Memo $memo = null,
        $fee = null
    ): NftTransferOperation {
        $operation = new NftTransferOperation();
        $operation
            ->setFrom($from)
            ->setTo($to)
            ->setId($id)
            ->setMemo($memo)
            ->setFee($fee);
        return $operation;
    }

    /**
     * @inheritDoc
     */
    public function transfer(
        Credentials $credentials,
        ChainObject $to,
        ChainObject $id,
        Memo $memo = null,
        $fee = null
    ): TransactionConfirmation {
        $fee = $fee ?: new AssetAmount();
        return $this->dcoreApi->getBroadcastApi()->broadcastOperationWithECKeyPairWithCallback(
            $credentials->getKeyPair(),
            $this->createTransferOperation($credentials->getAccount(), $to, $id, $memo, $fee)
        );
    }

    /**
     * @inheritDoc
     */
    public function createUpdateDataOperation(
        ChainObject $modifier,
        ChainObject $id,
        NftModel $data,
        $fee = null
    ): NftUpdateDataOperation {
        $operation = new NftUpdateDataOperation();
        $operation
            ->setModifier($modifier)
            ->setId($id)
            ->setData($data->createUpdate())
            ->setFee($fee);
        return $operation;
    }

    /**
     * @inheritDoc
     */
    public function createUpdateDataOperationWithNewData(
        ChainObject $modifier,
        ChainObject $id,
        $newData,
        Fee $fee = null
    ): NftUpdateDataOperation {
        // TODO: Implement createUpdateDataOperationWithNewData() method.
    }

    /**
     * @inheritDoc
     */
    public function updateData(
        Credentials $credentials,
        ChainObject $id,
        NftModel $values,
        $fee = null
    ): TransactionConfirmation {
        $fee = $fee ?: new AssetAmount();
        return $this->dcoreApi->getBroadcastApi()->broadcastOperationWithECKeyPairWithCallback(
            $credentials->getKeyPair(),
            $this->createUpdateDataOperation($credentials->getAccount(), $id, $values, $fee)
        );
    }

    /**
     * @inheritDoc
     */
    public function updateDataWithNewData(
        Credentials $credentials,
        ChainObject $id,
        $newData,
        Fee $fee = null
    ): TransactionConfirmation {
        // TODO: Implement updateDataWithNewData() method.
    }

    private function make(array $data, string $clazz = null) {
        return array_map(function (NftData $nft) use ($clazz) {
            $class = $clazz ?? $this->dcoreApi->isRegistered($nft->getNftId()->getId());
            if ($class) {
                return NftData::init($nft, $class);
            }
            return $nft;
        }, $data);
    }
}