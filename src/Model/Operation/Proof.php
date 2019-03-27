<?php

namespace DCorePHP\Model\Operation;

class Proof
{
    /** @var int */
    private $referenceBlock;
    /** @var string */
    private $seed;
    /** @var array */
    private $mus = [];
    /** @var string */
    private $sigma;

    /**
     * @return int
     */
    public function getReferenceBlock(): ?int
    {
        return $this->referenceBlock;
    }

    /**
     * @param int $referenceBlock
     * @return Proof
     */
    public function setReferenceBlock(int $referenceBlock): Proof
    {
        $this->referenceBlock = $referenceBlock;
        return $this;
    }

    /**
     * @return string
     */
    public function getSeed(): ?string
    {
        return $this->seed;
    }

    /**
     * @param string $seed
     * @return Proof
     */
    public function setSeed(string $seed): Proof
    {
        $this->seed = $seed;
        return $this;
    }

    /**
     * @return array
     */
    public function getMus(): array
    {
        return $this->mus;
    }

    /**
     * @param array $mus
     * @return Proof
     */
    public function setMus(array $mus): Proof
    {
        $this->mus = $mus;
        return $this;
    }

    /**
     * @return string
     */
    public function getSigma(): ?string
    {
        return $this->sigma;
    }

    /**
     * @param string $sigma
     * @return Proof
     */
    public function setSigma(string $sigma): Proof
    {
        $this->sigma = $sigma;
        return $this;
    }
}