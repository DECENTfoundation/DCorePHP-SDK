<?php

namespace DCorePHP\Model;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class NftModel
{

    private $values = [];
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
        $this->values();
        $dataTypes = [];
        foreach ($this->values as $value) {
            $dataTypes[] = $this->serializer->deserialize($value, NftDataType::class, 'json');
        }
        return $dataTypes;
    }
    /**
     * @throws ExceptionInterface
     */
    public function values(): array {
        if (!empty($this->values)) return $this->values;

        $normalizedArray = $this->serializer->normalize($this);
        foreach ($normalizedArray as $item) {
            $this->values[] = $this->serializer->encode($item, 'json');
        }
        return $this->values;
    }
}