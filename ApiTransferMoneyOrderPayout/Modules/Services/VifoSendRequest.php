<?php

namespace Modules\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Modules\Interfaces\VifoSendRequestInterface;

class VifoSendRequest implements VifoSendRequestInterface
{
    private $client;
    private $baseUrl;
    public function __construct($env = 'dev')
    {
        if ($env == 'dev') {
            $this->baseUrl = 'https://sapi.vifo.vn';
        } else if ($env == 'stg') {
            $this->baseUrl = 'https://sapi.vifo.vn';
        } else if ($env == 'production') {
            $this->baseUrl = 'https://api.vifo.vn';
        } else {
            throw new \InvalidArgumentException("Invalid environment: $env");
        }

        $this->client = new Client();
    }

    /**
     * Send an HTTP request.
     *
     * @param string $method The HTTP method (GET, POST....).
     * @param string $endpoint The endpoint URL.
     * @param array $headers The request headers.
     * @param array $body The request body.
     * @return array An array containing the status code and body of the response, or error information.
     */
    public function sendRequest(string $method, string $endpoint, array $headers, array $body): array
    {

        $baseUrl = $this->baseUrl . $endpoint;

        try {
            $response = $this->client->request($method, $baseUrl, [
                'headers' => $headers,
                'json' => $body,
            ]);
            $json = json_decode($response->getBody()->getContents(), true);
            return [
                'status_code' => $response->getStatusCode(),
                'body' => $json,
            ];
        } catch (RequestException $e) {
            return [
                'errors' => 'Request Exception: ' . $e->getMessage(),
                'status_code' => $e->hasResponse() ? $e->getResponse()->getStatusCode() : 500,
                'body' => $e->hasResponse() ? json_decode($e->getResponse()->getBody()->getContents(), true) : null
            ];
        }
    }
}
