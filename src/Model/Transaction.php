<?php

namespace DCorePHP\Model;

use DCorePHP\Crypto\ECKeyPair;
use DCorePHP\Crypto\PrivateKey;
use DCorePHP\DCorePHPException;
use DCorePHP\Utils\Math;

class Transaction
{
    private const MAX_SIGN_LIMIT = 20;
    /** @var array */
    private $extensions = [];
    /** @var BaseOperation[] */
    private $operations = [];
    /** @var BlockData */
    private $blockData;
    /** @var string */
    private $signature;
    /** @var string */
    private $chainId = '';

    /**
     * @return array
     */
    public function getExtensions(): array
    {
        return $this->extensions;
    }

    /**
     * @param array $extensions
     * @return Transaction
     */
    public function setExtensions(array $extensions): Transaction
    {
        $this->extensions = $extensions;
        return $this;
    }

    /**
     * @return BaseOperation[]
     */
    public function getOperations(): array
    {
        return $this->operations;
    }

    /**
     * @param BaseOperation[] $operations
     * @return Transaction
     */
    public function setOperations(array $operations): Transaction
    {
        $this->operations = $operations;
        return $this;
    }

    /**
     * @return string
     */
    public function getSignature(): ?string
    {
        return $this->signature;
    }

    /**
     * @param string $signature
     * @return Transaction
     */
    public function setSignature(string $signature): Transaction
    {
        $this->signature = $signature;
        return $this;
    }

    /**
     * @return BlockData
     */
    public function getBlockData(): BlockData
    {
        return $this->blockData;
    }

    /**
     * @param BlockData $blockData
     * @return Transaction
     */
    public function setBlockData(BlockData $blockData): Transaction
    {
        $this->blockData = $blockData;

        return $this;
    }

    /**
     * @return string
     */
    public function getChainId(): string
    {
        return $this->chainId;
    }

    /**
     * @param string $chainId
     * @return Transaction
     */
    public function setChainId(string $chainId): Transaction
    {
        $this->chainId = $chainId;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'extensions' => $this->getExtensions(),
            'operations' => array_map(function (BaseOperation $operation) {
                return $operation->toArray();
            }, $this->getOperations()),
            'ref_block_num' => $this->getBlockData()->getRefBlockNum(),
            'ref_block_prefix' => $this->getBlockData()->getRefBlockPrefix(),
            'expiration' => $this->getBlockData()->getExpiration()->format('Y-m-d\TH:i:s'),
            'signatures' => $this->getSignature() ? [$this->getSignature()] : [],
        ];
    }

    /**
     * @return string
     */
    public function toBytes(): string
    {
        return implode('', [
            $this->getBlockData()->toBytes(),
            str_pad(Math::gmpDecHex(\count($this->getOperations())), 2, '0', STR_PAD_LEFT), // number of operations
            $this->getOperations() ? implode('', array_map(static function (BaseOperation $transfer) { // operation bytes
                return $transfer->toBytes();
            }, $this->getOperations())) : '00',
            '00', // extensions
        ]);
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return substr(hash('sha256', hex2bin($this->toBytes())), 0, 40);
    }

    /**
     * @param string $privateKeyWif
     * @return Transaction
     * @throws \Exception
     */
    public function sign(string $privateKeyWif): self
    {
        $ecKeyPair = ECKeyPair::fromBase58($privateKeyWif);

        $signature = null;
        $attempt = 0;
        do {
            try {
                $signature = $ecKeyPair->signature($this->toBytes(), $this->getChainId());
            } catch (\Exception $e) {
                // try again
            }

            if (!$signature) {
                $this->getBlockData()->increment();
            }
            if ($attempt === self::MAX_SIGN_LIMIT) {
                throw new DCorePHPException('Reached MAX signing limit, unable to create a signature, possible problem with input data.');
            }
            $attempt++;
        } while (!$signature);

        $this->setSignature($signature);

        return $this;
    }
}
