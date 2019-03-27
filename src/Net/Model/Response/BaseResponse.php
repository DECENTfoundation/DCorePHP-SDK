<?php

namespace DCorePHP\Net\Model\Response;

use Symfony\Component\PropertyAccess\PropertyAccess;

class BaseResponse
{
    /** @var int */
    private $id;
    /** @var Error|null */
    private $error;
    /** @var mixed */
    private $result;

    public function __construct(string $rawResponse)
    {
        $this->setError(new Error());

        $decodedResponse = json_decode($rawResponse, true);
        if ($decodedResponse === null) {
            throw new \RuntimeException('Invalid data');
        }

        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        foreach (
            [
                '[id]' => 'id',
                '[error][code]' => 'error.code',
                '[error][message]' => 'error.message',
                '[error][data]' => 'error.data',
                '[result]' => 'result',
            ] as $path => $modelPath
        ) {
            $value = $propertyAccessor->getValue($decodedResponse, $path);
            $propertyAccessor->setValue($this, $modelPath, $value);
        }

        if ($this->getError()->getCode() === null) {
            $this->setError(null);
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id): BaseResponse
    {
        $this->id = $id;

        return $this;
    }

    public function getError(): ?Error
    {
        return $this->error;
    }

    public function setError(?Error $error): BaseResponse
    {
        $this->error = $error;

        return $this;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function setResult($result): BaseResponse
    {
        $this->result = $result;

        return $this;
    }
}
