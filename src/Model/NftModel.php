<?php

namespace DCorePHP\Model;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class NftModel
{

    private $json = [];
    /** @var Serializer */
    private $serializer;

    /**
     * NftModel constructor.
     */
    public function __construct()
    {
        $this->serializer = new Serializer(
            [new ObjectNormalizer()],
            [new JsonEncoder()]
        );
    }

    /**
     * @return NftDataType[]
     * @throws ExceptionInterface
     */
    public function createDefinitions(): array {
        $this->json();
        $dataTypes = [];
        foreach ($this->json as $value) {
            $dataTypes[] = $this->serializer->deserialize($value, NftDataType::class, 'json');
        }
        return $dataTypes;
    }
    /**
     * @throws ExceptionInterface
     */
    public function json(): array {
        if (!empty($this->json)) return $this->json;

        $normalizedArray = $this->serializer->normalize($this);
        foreach ($normalizedArray as $item) {
            $this->json[] = $this->serializer->encode($item, 'json');
        }
        return $this->json;
    }

    /**
     * @return array
     * @throws ExceptionInterface
     */
    public function values(): array {
        $definitions = $this->createDefinitions();
        $values = [];
        foreach ($definitions as $definition) {
            $values[] = new Variant($definition->getType(), $definition->getValue());
        }
        return $values;
    }
}