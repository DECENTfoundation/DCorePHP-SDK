<?php

namespace DCorePHP\Model;

use DCorePHP\Crypto\ECKeyPair;
use DCorePHP\Crypto\PrivateKey;

class Transaction
{
    /** @var array */
    private $extensions = [];
    /** @var BaseOperation[] */
    private $operations = [];
    /** @var DynamicGlobalProps */
    private $dynamicGlobalProps;
    /** @var string */
    private $signature;

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
     * @return DynamicGlobalProps
     */
    public function getDynamicGlobalProps(): DynamicGlobalProps
    {
        return $this->dynamicGlobalProps;
    }

    /**
     * @param DynamicGlobalProps $dynamicGlobalProps
     * @return Transaction
     */
    public function setDynamicGlobalProps(DynamicGlobalProps $dynamicGlobalProps): Transaction
    {
        $this->dynamicGlobalProps = $dynamicGlobalProps;
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
     * @return Transaction
     */
    public function increment(): self
    {
        $this->getDynamicGlobalProps()->getExpiration()->modify('+1 second');

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
            'ref_block_num' => $this->getDynamicGlobalProps()->getRefBlockNum(),
            'ref_block_prefix' => $this->getDynamicGlobalProps()->getRefBlockPrefix(),
            'expiration' => $this->getDynamicGlobalProps()->getExpiration()->format('Y-m-d\TH:i:s'),
            'signatures' => $this->getSignature() ? [$this->getSignature()] : [],
        ];
    }

    /**
     * @return string
     */
    public function toBytes(): string
    {
        return implode('', [
            implode('', [ // block data bytes
                implode('', array_reverse(str_split(str_pad(dechex($this->getDynamicGlobalProps()->getRefBlockNum()), 4, '0', STR_PAD_LEFT), 2))),
                implode('', array_reverse(str_split(str_pad(dechex($this->getDynamicGlobalProps()->getRefBlockPrefix()), 8, '0', STR_PAD_LEFT), 2))),
                implode('', array_reverse(str_split(str_pad(dechex($this->getDynamicGlobalProps()->getExpiration()->format('U')), 8, '0', STR_PAD_LEFT), 2))),
            ]),
            str_pad(dechex(\count($this->getOperations())), 2, '0', STR_PAD_LEFT), // number of operations
            $this->getOperations() ? implode('', array_map(function (BaseOperation $transfer) { // operation bytes
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
        $ecKeyPair = new ECKeyPair(PrivateKey::fromWif($privateKeyWif));

        $signature = null;
        do {
            $this->increment();

            try {
                $signature = $ecKeyPair->signature($this->toBytes());
            } catch (\Exception $e) {
                // try again
            }
        } while (!$signature);

        $this->setSignature($signature);

        return $this;
    }
}
