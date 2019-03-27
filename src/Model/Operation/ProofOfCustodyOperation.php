<?php

namespace DCorePHP\Model\Operation;

use DCorePHP\Model\BaseOperation;
use DCorePHP\Model\ChainObject;

class ProofOfCustodyOperation extends BaseOperation
{
    public const OPERATION_TYPE = 24;
    public const OPERATION_NAME = 'proof_of_custody';

    /** @var ChainObject */
    private $seeder;
    /** @var string */
    private $uri;
    /** @var Proof */
    private $proof;

    public function __construct()
    {
        parent::__construct();
        $this->proof = new Proof();
    }

    /**
     * @param array $rawOperation
     */
    public function hydrate(array $rawOperation): void
    {
        foreach (
            [
                '[fee][amount]' => 'fee.amount',
                '[fee][asset_id]' => 'fee.assetId',
                '[seeder]' => 'seeder',
                '[URI]' => 'uri',
                '[proof][reference_block]' => 'proof.referenceBlock',
                '[proof][seed]' => 'proof.seed',
                '[proof][mus]' => 'proof.mus',
                '[proof][sigma]' => 'proof.sigma',
            ] as $path => $modelPath
        ) {
            try {
                $value = $this->getPropertyAccessor()->getValue($rawOperation, $path);
                $this->getPropertyAccessor()->setValue($this, $modelPath, $value);
            } catch (\Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException $exception) {
                // skip
            }
        }
    }

    /**
     * @return ChainObject
     */
    public function getSeeder(): ?ChainObject
    {
        return $this->seeder;
    }

    /**
     * @param ChainObject|string $seeder
     * @return self
     * @throws \DCorePHP\Exception\ValidationException
     */
    public function setSeeder($seeder): self
    {
        if (is_string($seeder)) {
            $seeder = new ChainObject($seeder);
        }

        $this->seeder = $seeder;
        return $this;
    }

    /**
     * @return string
     */
    public function getUri(): ?string
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     * @return ProofOfCustodyOperation
     */
    public function setUri(string $uri): ProofOfCustodyOperation
    {
        $this->uri = $uri;
        return $this;
    }

    /**
     * @return Proof
     */
    public function getProof(): Proof
    {
        return $this->proof;
    }

    /**
     * @param Proof $proof
     * @return ProofOfCustodyOperation
     */
    public function setProof(Proof $proof): ProofOfCustodyOperation
    {
        $this->proof = $proof;
        return $this;
    }
}