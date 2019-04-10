<?php

namespace DCorePHP\Model;

class ProcessedTransaction
{

    /** @var array */
    private $signatures;
    /** @var array */
    private $extensions;
    /** @var BaseOperation[] */
    private $operations;
    /** @var \DateTime */
    private $expiration;
    /** @var int */
    private $refBlockNum;
    /** @var string */
    private $refBlockPrefix;
    // TODO: array / null -> Dcore error ?
    /** @var array */
    private $opResults;

    /**
     * @return array
     */
    public function getSignatures(): array
    {
        return $this->signatures;
    }

    /**
     * @param array $signatures
     * @return ProcessedTransaction
     */
    public function setSignatures(array $signatures): ProcessedTransaction
    {
        $this->signatures = $signatures;

        return $this;
    }

    /**
     * @return array
     */
    public function getExtensions(): array
    {
        return $this->extensions;
    }

    /**
     * @param array $extensions
     * @return ProcessedTransaction
     */
    public function setExtensions(array $extensions): ProcessedTransaction
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
     * @return ProcessedTransaction
     */
    public function setOperations(array $operations): ProcessedTransaction
    {
        $this->operations = $operations;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getExpiration(): \DateTime
    {
        return $this->expiration;
    }

    /**
     * @param \DateTime|string $expiration
     * @return ProcessedTransaction
     * @throws \Exception
     */
    public function setExpiration($expiration): ProcessedTransaction
    {
        $this->expiration = $expiration instanceof \DateTime ? $expiration : new \DateTime($expiration);

        return $this;
    }

    /**
     * @return int
     */
    public function getRefBlockNum(): int
    {
        return $this->refBlockNum;
    }

    /**
     * @param int $refBlockNum
     * @return ProcessedTransaction
     */
    public function setRefBlockNum(int $refBlockNum): ProcessedTransaction
    {
        $this->refBlockNum = $refBlockNum;

        return $this;
    }

    /**
     * @return string
     */
    public function getRefBlockPrefix(): string
    {
        return $this->refBlockPrefix;
    }

    /**
     * @param string $refBlockPrefix
     * @return ProcessedTransaction
     */
    public function setRefBlockPrefix(string $refBlockPrefix): ProcessedTransaction
    {
        $this->refBlockPrefix = $refBlockPrefix;

        return $this;
    }

    /**
     * @return array
     */
    public function getOpResults(): array
    {
        return $this->opResults;
    }

    /**
     * @param array|null $opResults
     * @return ProcessedTransaction
     */
    public function setOpResults($opResults): ProcessedTransaction
    {
        $this->opResults = $opResults;

        return $this;
    }

}