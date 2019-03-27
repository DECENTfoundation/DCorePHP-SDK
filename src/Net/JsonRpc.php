<?php

namespace DCorePHP\Net;

use DCorePHP\Exception\InvalidApiCallException;
use DCorePHP\Net\Model\Request\BaseRequest;
use DCorePHP\Net\Model\Response\BaseResponse;

class JsonRpc
{
    /** @var string */
    private $url;
    /** @var object curl resource */
    private $client;
    /** @var int */
    private $requestId = 1;

    /**
     * @param string $url
     */
    private function __construct(string $url)
    {
        $this->url = $url;
    }

    public static function getInstance(string $url): JsonRpc
    {
        static $instance = null;

        if ($instance === null) {
            $instance = new JsonRpc($url);
        }

        return $instance;
    }

    private function getClient()
    {
        if (!$this->client) {
            $this->client = curl_init();

            curl_setopt($this->client, CURLOPT_TIMEOUT, 30);
            curl_setopt($this->client, CURLOPT_RETURNTRANSFER, true);
        }

        return $this->client;
    }

    public function post(BaseRequest $request)
    {
        $request->setId($this->requestId);

        $client = $this->getClient();

        curl_setopt($client, CURLOPT_URL, $this->url);
        curl_setopt($client, CURLOPT_POST, 1);
        curl_setopt($client, CURLOPT_POSTFIELDS, $request->toJson());
        curl_setopt($client, CURLOPT_HTTPHEADER, ['Content-type: application/json']);

        // disable ssl certificate issuer check
        // because the core ssl cert becomes randomly invalid and returns:
        // SSL certificate problem: unable to get local issuer certificate
        // curl_setopt($client, CURLOPT_SSL_VERIFYHOST, 0);
        // curl_setopt($client, CURLOPT_SSL_VERIFYPEER, 0);

        $rawResponse = curl_exec($client);
        if (curl_error($client)) {
            throw new InvalidApiCallException(curl_error($client));
        }

        $response = new BaseResponse($rawResponse);
        if ($response->getError()) {
            throw new InvalidApiCallException($response->getError()->getMessage());
        }

        $this->requestId++;

        return $response->getResult() ? $request->responseToModel($response) : null;
    }
}
