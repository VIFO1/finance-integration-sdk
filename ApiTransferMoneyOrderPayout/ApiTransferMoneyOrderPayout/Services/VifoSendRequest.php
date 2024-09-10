<?php

namespace ApiTransferMoneyOrderPayout\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class VifoSendRequest
{
    private $client;
    private $baseUrl;
    public function __construct($env = 'dev')
    {
        if ($env == 'dev') {
            $this->baseUrl = 'https://sapi.vifo.vn';
        } else if ($env == 'tsg') {
            $this->baseUrl = 'https://sapi.vifo.vn';
        } else {
            $this->baseUrl = 'https://api.vifo.vn';
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
    public function sendRequest($method, $endpoint, $headers, $body)
    {
        $baseUrl = $this->baseUrl . $endpoint;
        $result = []; 
        try {
            $response = $this->client->request($method, $baseUrl, [
                'headers' => $headers,
                'json' => $body
            ]);
            
            $json = json_decode($response->getBody()->getContents(), true);
            $result['status_code'] = $response->getStatusCode();
            $result['body'] = $json;
        } catch (RequestException $e) {
            $result['error'] = $e->getMessage();
            $result['body'] = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null;
        }
        return $result; 
    }
}
