<?php

namespace DCorePHP\Sdk;

use DCorePHP\Crypto\Credentials;
use DCorePHP\DCoreApi;
use DCorePHP\Exception\InvalidApiCallException;
use DCorePHP\Exception\ObjectNotFoundException;
use DCorePHP\Exception\ValidationException;
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
use Exception;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use WebSocket\BadOpcodeException;

interface NftApiInterface
{
    /**
     * Get NFT by id or symbol
     *
     * @param string $idOrSymbol
     *
     * @return Nft
     *
     * @throws ValidationException
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     * @throws ObjectNotFoundException
     */
    public function get(string $idOrSymbol): Nft;

    /**
     * Get NFTs by id
     *
     * @param ChainObject[] $ids
     *
     * @return array
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function getAll(array $ids): array;

    /**
     * Get NFT by id
     *
     * @param ChainObject $id
     * @return Nft
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     * @throws ObjectNotFoundException
     */
    public function getById(ChainObject $id): Nft;

    /**
     * Get NFTs by symbol
     *
     * @param array $symbols
     *
     * @return array
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function getAllBySymbol(array $symbols): array;

    /**
     * Get NFT by symbol
     *
     * @param string $symbol
     *
     * @return Nft
     *
     * @throws ObjectNotFoundException
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function getBySymbol(string $symbol): Nft;

    /**
     * Get NFT data instances with parsed model
     *
     * @param array $ids
     * @return array
     */
    public function getAllData(array $ids): array;

    /**
     * Get NFT data instances with raw model
     *
     * @param array $ids
     *
     * @return array
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function getAllDataRaw(array $ids): array;

    /**
     * Get NFT data instances with registered model, use [DCoreApi.registerNfts] to register nft model by object id,
     * if the model is not registered, [RawNft] will be used
     *
     * @param ChainObject $id
     *
     * @return NftData
     */
    public function getData(ChainObject $id): NftData;

    /**
     * Get NFT data instance with raw model
     *
     * @param ChainObject $id
     *
     * @return NftData
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function getDataRaw(ChainObject $id): NftData;

    /**
     * Count all NFTs
     *
     * @return string
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function countAll(): string;

    /**
     * Count all NFT data instances
     *
     * @return string
     *
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function countAllData(): string;

    /**
     * Get NFT balances per account with raw model
     *
     * @param ChainObject $account
     * @param array $nftIds
     *
     * @return array NFT data instances with raw model
     */
    public function getNftBalancesRaw(ChainObject $account, array $nftIds = []): array;

    /**
     * Get NFT balances per account with registered model, use [DCoreApi.registerNfts] to register nft model by object id,
     * if the model is not registered, [RawNft] will be used
     *
     * @param ChainObject $account
     * @param array $nftIds
     *
     * @return array
     */
    public function getNftBalances(ChainObject $account, array $nftIds = []): array;

    /**
     * Get NFTs alphabetically by symbol name
     *
     * @param string $lowerBound of symbol names to retrieve
     * @param int $limit maximum number of NFTs to fetch (must not exceed 100)
     *
     * @return array NFTs found
     */
    public function listAllRelative(string $lowerBound = '', int $limit = DCoreApi::REQ_LIMIT_MAX): array;

    /**
     * @param ChainObject $nftId
     * @return array
     */
    public function listDataByNft(ChainObject $nftId): array;

    /**
     * @param ChainObject $nftId
     * @return array
     */
    public function listDataByNftRaw(ChainObject $nftId): array;

    /**
     * @param ChainObject $nftDataId
     * @return array
     */
    public function searchNftHistory(ChainObject $nftDataId): array;

    /**
     * @param string $symbol
     * @param NftOptions $options
     * @param NftModel $model
     * @param bool $transferable
     * @param null $fee
     *
     * @return NftCreateOperation
     *
     * @throws ValidationException
     * @throws ExceptionInterface
     */
    public function createNftCreateOperation(string $symbol, NftOptions $options, NftModel $model, bool $transferable, $fee = null): NftCreateOperation;

    /**
     * Create NFT
     *
     * @param Credentials $credentials
     * @param string $symbol
     * @param string $maxSupply
     * @param bool $fixedMaxSupply
     * @param string $description
     * @param NftModel $model
     * @param bool $transferable
     * @param null $fee
     *
     * @return TransactionConfirmation
     *
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function create(Credentials $credentials, string $symbol, string $maxSupply, bool $fixedMaxSupply, string $description, NftModel $model, bool $transferable, $fee = null): TransactionConfirmation;

    /**
     * Create NFT update operation. Fills model with actual values.
     *
     * @param string $idOrSymbol
     * @param null $fee
     *
     * @return NftUpdateOperation
     *
     * @throws ObjectNotFoundException
     * @throws ValidationException
     * @throws InvalidApiCallException
     * @throws BadOpcodeException
     */
    public function createUpdateOperation(string $idOrSymbol, $fee = null): NftUpdateOperation;

    /**
     * Update NFT
     *
     * @param Credentials $credentials
     * @param string $idOrSymbol
     * @param string|null $maxSupply
     * @param bool|null $fixedMaxSupply
     * @param string|null $description
     * @param null $fee
     *
     * @return TransactionConfirmation
     *
     * @throws Exception
     */
    public function update(Credentials $credentials, string $idOrSymbol, string $maxSupply = null, bool $fixedMaxSupply = null, string $description = null, $fee = null): TransactionConfirmation;

    /**
     * Create NFT issue operation. Creates a NFT data instance.
     *
     * @param ChainObject $issuer
     * @param string $idOrSymbol
     * @param ChainObject $to
     * @param null $data
     * @param Memo|null $memo
     * @param null $fee
     *
     * @return NftIssueOperation
     */
    public function createIssueOperation(ChainObject $issuer, string $idOrSymbol, ChainObject $to, $data = null, Memo $memo = null, $fee = null): NftIssueOperation;

    /**
     * Issue NFT. Creates a NFT data instance.
     *
     * @param Credentials $credentials
     * @param string $idOrSymbol
     * @param ChainObject $to
     * @param null $data
     * @param Memo|null $memo
     * @param Fee|null $fee
     *
     * @return TransactionConfirmation
     */
    public function issue(Credentials $credentials, string $idOrSymbol, ChainObject $to, $data = null, Memo $memo = null, Fee $fee = null): TransactionConfirmation;

    /**
     * Create NFT data instance transfer operation
     *
     * @param ChainObject $from
     * @param ChainObject $to
     * @param ChainObject $id
     * @param Memo|null $memo
     * @param Fee|null $fee
     *
     * @return NftTransferOperation
     */
    public function createTransferOperation(ChainObject $from, ChainObject $to, ChainObject $id, Memo $memo = null, Fee $fee = null): NftTransferOperation;

    /**
     * Transfer NFT data instance
     *
     * @param Credentials $credentials
     * @param ChainObject $to
     * @param ChainObject $from
     * @param Memo|null $memo
     * @param Fee|null $fee
     *
     * @return TransactionConfirmation
     */
    public function transfer(Credentials $credentials, ChainObject $to, ChainObject $from, Memo $memo = null, Fee $fee = null): TransactionConfirmation;

    /**
     * Create NFT data instance update operation
     *
     * @param ChainObject $modifier
     * @param ChainObject $id
     * @param Fee|null $fee
     *
     * @return NftUpdateDataOperation
     */
    public function createUpdateDataOperation(ChainObject $modifier, ChainObject $id, Fee $fee = null): NftUpdateDataOperation;

    /**
     * Create NFT data instance update operation
     *
     * @param ChainObject $modifier
     * @param ChainObject $id
     * @param $newData
     * @param Fee|null $fee
     *
     * @return NftUpdateDataOperation
     */
    public function createUpdateDataOperationWithNewData(ChainObject $modifier, ChainObject $id, $newData, Fee $fee = null): NftUpdateDataOperation;

    /**
     * Update NFT data instance
     *
     * @param Credentials $credentials
     * @param ChainObject $id
     * @param array $values
     * @param Fee|null $fee
     *
     * @return TransactionConfirmation
     */
    public function updateData(Credentials $credentials, ChainObject $id, array $values, Fee $fee = null): TransactionConfirmation;

    /**
     * Update NFT data instance
     *
     * @param Credentials $credentials
     * @param ChainObject $id
     * @param $newData
     * @param Fee|null $fee
     *
     * @return TransactionConfirmation
     */
    public function updateDataWithNewData(Credentials $credentials, ChainObject $id, $newData, Fee $fee = null): TransactionConfirmation;
}