<?php

namespace DCorePHP\Net\Model\Response;

class Error
{
    /** @var int */
    private $code;
    /** @var string */
    private $message;
    /** @var array */
    private $data;

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function setCode(?int $code): Error
    {
        $this->code = $code;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): Error
    {
        $this->message = $message;

        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(?array $data): ?Error
    {
        $this->data = $data;

        return $this;
    }
}
