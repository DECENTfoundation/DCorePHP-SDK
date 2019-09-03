<?php

namespace DCorePHP\Model\Operation;

use DateTime;
use DCorePHP\Exception\ValidationException;
use DCorePHP\Model\Asset\AssetAmount;
use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;
use DCorePHP\Model\CoAuthors;
use DCorePHP\Model\Content\CustodyData;
use DCorePHP\Model\RegionalPrice;
use DCorePHP\Utils\Math;
use DCorePHP\Utils\VarInt;
use Exception;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;

class AddOrUpdateContentOperation extends BaseOperation
{
    public const OPERATION_TYPE = 20;
    public const OPERATION_NAME = 'content_submit';

    /** @var string */
    private $size = '1';
    /** @var ChainObject */
    private $author;
    /** @var CoAuthors */
    private $coauthors;
    /** @var string */
    private $uri;
    /** @var int */
    private $quorum = 0;
    /** @var RegionalPrice[] */
    private $price;
    /** @var string */
    private $hash;
    /** @var ChainObject[] */
    private $seeders = [];
    /** @var KeyParts[] */
    private $keyParts = [];
    /** @var DateTime */
    private $expiration;
    /** @var AssetAmount */
    private $publishingFee;
    /** @var mixed */
    private $synopsis;
    /** @var CustodyData */
    private $custodyData;

    public function __construct()
    {
        parent::__construct();
        $this->publishingFee = new AssetAmount();
        $this->coauthors = new CoAuthors();
    }

    public function hydrate(array $rawOperation): void
    {
        foreach (
            [
                '[fee][amount]' => 'fee.amount',
                '[fee][asset_id]' => 'fee.assetId',
                '[size]' => 'size',
                '[author]' => 'author',
                '[URI]' => 'uri',
                '[quorum]' => 'quorum',
                '[hash]' => 'hash',
                //TODO: Hydrate seeders [Unknown structure]
//                '[seeders]' => 'seeders',
                //TODO: Hydrate key_parts [Unknown structure]
//                '[key_parts]' => 'keyParts',
                '[expiration]' => 'expiration',
                '[publishing_fee][amount]' => 'publishingFee.amount',
                '[publishing_fee][asset_id]' => 'publishingFee.asset_id',
                '[synopsis]' => 'synopsis'
            ] as $path => $modelPath
        ) {
            try {
                $value = $this->getPropertyAccessor()->getValue($rawOperation, $path);
                $this->getPropertyAccessor()->setValue($this, $modelPath, $value);
            } catch (NoSuchPropertyException $exception) {
                // skip
            }
        }

        $rawCoAuthors = $rawOperation['co_authors'];
        $coAuthors = [];
        foreach ($rawCoAuthors as [$id, $weight]) {
            $coAuthors[] = [new ChainObject($id), $weight];
        }
        $this->setCoauthors((new CoAuthors())->setAuthors($coAuthors));

        $rawPrices = $rawOperation['price'];

        $regionalPrices = [];
        foreach ($rawPrices as $rawPrice) {
            $regionalPrice = new RegionalPrice();
            foreach (
                [
                    '[region]' => 'region',
                    '[price][amount]' => 'price.amount',
                    '[price][asset_id]' => 'price.assetId',
                ] as $path => $modelPath
            ) {
                try {
                    $value = $this->getPropertyAccessor()->getValue($rawPrice, $path);
                    $this->getPropertyAccessor()->setValue($regionalPrice, $modelPath, $value);
                } catch (NoSuchPropertyException $exception) {
                    // skip
                }
            }
            $regionalPrices[] = $regionalPrice;
        }
        $this->setPrice($regionalPrices);
    }

    /**
     * @return string
     */
    public function getSize(): string
    {
        return $this->size;
    }

    /**
     * @param string $size
     *
     * @return AddOrUpdateContentOperation
     */
    public function setSize(string $size): AddOrUpdateContentOperation
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return CoAuthors
     */
    public function getCoauthors(): CoAuthors
    {
        return $this->coauthors;
    }

    /**
     * @param CoAuthors $coauthors
     *
     * @return AddOrUpdateContentOperation
     */
    public function setCoauthors(CoAuthors $coauthors): AddOrUpdateContentOperation
    {
        $this->coauthors = $coauthors;

        return $this;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     *
     * @return AddOrUpdateContentOperation
     */
    public function setUri(string $uri): AddOrUpdateContentOperation
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * @return int
     */
    public function getQuorum(): int
    {
        return $this->quorum;
    }

    /**
     * @param int $quorum
     *
     * @return AddOrUpdateContentOperation
     */
    public function setQuorum(int $quorum): AddOrUpdateContentOperation
    {
        $this->quorum = $quorum;

        return $this;
    }

    /**
     * @return RegionalPrice[]
     */
    public function getPrice(): array
    {
        return $this->price;
    }

    /**
     * @param RegionalPrice[] $price
     *
     * @return AddOrUpdateContentOperation
     */
    public function setPrice(array $price): AddOrUpdateContentOperation
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        if ($this->hash === null || $this->hash === '') {
            $this->hash = hash('ripemd160', $this->getUri());
        }
        return $this->hash;
    }

    /**
     * @param string $hash
     *
     * @return AddOrUpdateContentOperation
     */
    public function setHash(string $hash): AddOrUpdateContentOperation
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * @return ChainObject[]
     */
    public function getSeeders(): array
    {
        return $this->seeders;
    }

    /**
     * @param ChainObject[] $seeders
     *
     * @return AddOrUpdateContentOperation
     */
    public function setSeeders(array $seeders): AddOrUpdateContentOperation
    {
        $this->seeders = $seeders;

        return $this;
    }

    /**
     * @return KeyParts[]
     */
    public function getKeyParts(): array
    {
        return $this->keyParts;
    }

    /**
     * @param KeyParts[] $keyParts
     *
     * @return AddOrUpdateContentOperation
     */
    public function setKeyParts(array $keyParts): AddOrUpdateContentOperation
    {
        $this->keyParts = $keyParts;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getExpiration(): DateTime
    {
        return $this->expiration;
    }

    /**
     * @param DateTime|string $expiration
     *
     * @return AddOrUpdateContentOperation
     * @throws Exception
     */
    public function setExpiration($expiration): AddOrUpdateContentOperation
    {
        $this->expiration = $expiration instanceof DateTime ? $expiration : new DateTime($expiration);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSynopsis()
    {
        return $this->synopsis;
    }

    /**
     * @return mixed
     */
    public function getSynopsisDecoded()
    {
        return json_decode($this->synopsis, true) ?? $this->synopsis;
    }

    /**
     * @param mixed $synopsis
     *
     * @return AddOrUpdateContentOperation
     */
    public function setSynopsis($synopsis): AddOrUpdateContentOperation
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    /**
     * @return CustodyData
     */
    public function getCustodyData(): ?CustodyData
    {
        return $this->custodyData;
    }

    /**
     * @param CustodyData $custodyData
     *
     * @return AddOrUpdateContentOperation
     */
    public function setCustodyData(?CustodyData $custodyData): AddOrUpdateContentOperation
    {
        $this->custodyData = $custodyData;

        return $this;
    }

    /**
     * @return ChainObject
     */
    public function getAuthor(): ChainObject
    {
        return $this->author;
    }

    /**
     * @param ChainObject|string $author
     *
     * @return AddOrUpdateContentOperation
     * @throws ValidationException
     */
    public function setAuthor($author): AddOrUpdateContentOperation
    {
        if (is_string($author)) {
            $author = new ChainObject($author);
        }
        $this->author = $author;
        return $this;
    }

    /**
     * @return AssetAmount
     */
    public function getPublishingFee(): AssetAmount
    {
        return $this->publishingFee;
    }

    /**
     * @param AssetAmount $publishingFee
     *
     * @return AddOrUpdateContentOperation
     */
    public function setPublishingFee(AssetAmount $publishingFee): AddOrUpdateContentOperation
    {
        $this->publishingFee = $publishingFee;
        return $this;
    }

    public function toArray(): array
    {
        return [
            self::OPERATION_TYPE,
            [
                'size' => $this->getSize(),
                'author' => $this->getAuthor()->getId(),
                'co_authors' => $this->getCoauthors()->toArray(),
                'URI' => $this->getUri(),
                'quorum' => $this->getQuorum(),
                'price' => array_map(
                    static function (RegionalPrice $regionalPrice) {
                        return $regionalPrice->toArray();
                    }, $this->getPrice()
                ),
                'hash' => $this->getHash(),
                'seeders' => $this->getSeeders(),
                'key_parts' => array_map(
                    static function (KeyParts $keyParts) {
                        return $keyParts->toArray();
                    }, $this->getKeyParts()
                ),
                'expiration' => $this->getExpiration()->format('Y-m-d\TH:i:s'),
                'publishing_fee' => $this->getPublishingFee()->toArray(),
                'synopsis' => $this->getSynopsis(),
                'fee' => $this->getFee()->toArray()
            ],
        ];
    }

    public function toBytes(): string
    {
        return
            implode('', [
                $this->getTypeBytes(),
                $this->getFee()->toBytes(),
                Math::getInt64($this->getSize()),
                $this->getAuthor()->toBytes(),
                $this->getCoauthors()->toBytes(),
                VarInt::encodeDecToHex(sizeof(Math::stringToByteArray($this->getUri()))),
                Math::byteArrayToHex(Math::stringToByteArray($this->getUri())),
                Math::getInt32($this->getQuorum()),
                VarInt::encodeDecToHex(sizeof($this->getPrice())),
                $this->getPrice() ? implode('', array_map(static function (RegionalPrice $regionalPrice) { // operation bytes
                    return $regionalPrice->toBytes();
                }, $this->getPrice())) : '00',
                $this->getHash(),
                VarInt::encodeDecToHex(sizeof($this->getSeeders())),
                $this->getSeeders() ? implode('', array_map(static function (ChainObject $seeder) { // operation bytes
                    return $seeder->toBytes();
                }, $this->getSeeders())) : '',
                VarInt::encodeDecToHex(sizeof($this->getKeyParts())),
                implode('', array_map(static function (ChainObject $seeder) { // operation bytes
                    return $seeder->toBytes();
                }, $this->getKeyParts())),
                Math::getInt32Reversed($this->getExpiration()->format('U')),
                $this->getPublishingFee()->toBytes(),
                VarInt::encodeDecToHex(sizeof(Math::stringToByteArray($this->getSynopsis()))),
                Math::byteArrayToHex(Math::stringToByteArray($this->getSynopsis())),
                $this->getCustodyData() ? $this->getCustodyData()->toBytes() : '00'
            ]);
    }
}
