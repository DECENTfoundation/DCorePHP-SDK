<?php

namespace DCorePHP\Net;

use DCorePHP\Exception\InvalidApiCallException;
use DCorePHP\Net\Model\Request\BaseRequest;
use DCorePHP\Net\Model\Response\BaseResponse;
use WebSocket\Client;

class Websocket
{
    /** @var string */
    private $url;
    /** @var Client */
    private $client;
    /** @var int */
    private $requestId = 1;
    /** @var bool */
    private $debug;
    private $MAX_RETRIES = 5;

    /**
     * @param string $url
     * @param bool $debug
     */
    private function __construct(string $url, bool $debug = false)
    {
        $this->url = $url;
        $this->debug = $debug;
    }

    /**
     * @param string $url
     * @param bool $debug
     * @return Websocket
     */
    public static function getInstance(string $url, bool $debug = false): Websocket
    {
        static $instance = null;

        if ($instance === null) {
            $instance = new Websocket($url, $debug);
        }

        return $instance;
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        if (!$this->client instanceof Client) {
            $this->client = new Client($this->url, [
                'context' => stream_context_create([
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ]
                ]),
                // Extended timeout because of callbacks, 60 seconds based on KT implementation
                'timeout' => 60
            ]);
        }

        return $this->client;
    }

    /**
     * does a POST request using curl
     *
     * @param BaseRequest $request
     * @return mixed based on $request
     * @throws InvalidApiCallException
     * @throws \WebSocket\BadOpcodeException
     */
    public function send(BaseRequest $request)
    {
        $request->setId($this->requestId);

        if ($this->debug) {
            var_dump('request: ' . $request->toJson());
        }

        $client = $this->getClient();
        $client->send($request->toJson());

        if ($request->isWithCallback()) {
            do {
                $rawResponse = $client->receive();
                $response = new BaseResponse($rawResponse);
            } while ($response->getMethod() !== 'notice');
        } else {
            do {
                $rawResponse = $client->receive();
                $response = new BaseResponse($rawResponse);
            } while ($response->getId() !== $request->getId());
        }

        if ($this->debug) {
            var_dump('response: ' . $rawResponse);
        }

        if ($response->getError()) {
            throw new InvalidApiCallException($response->getError()->getMessage());
        }

        $this->requestId++;

        return $request->responseToModel($response);
    }
}
