<?php

declare(strict_types=1);

namespace App\Service;

use GuzzleHttp\Client;

class CoinDeskClient
{
    /** @var string  */
    private $apiUrl = 'https://api.coindesk.com/v1';

    /** @var Client */
    private $client;

    /**
     * @return Client
     */
    private function getClient(): Client
    {
        if ($this->client === null) {
            $this->client = new Client();
        }

        return $this->client;
    }

    /**
     * @param string $uri
     * @param string $method
     *
     * @return array|null
     */
    private function sendRequest(string $uri, string $method = 'GET'): ?array
    {
        try {
            $response = $this->getClient()->request($method, $uri);
            $data = $response->getBody()->getContents();

            return json_decode($data, true);

        } catch (\Throwable $exception) {
            // Log
        }

        return null;
    }

    /**
     * @param string $code
     *
     * @return array|null
     */
    private function getCurrencyData(string $code): ?array
    {
        return $this->sendRequest($this->apiUrl . '/bpi/currentprice/'.$code.'.json');
    }

    /**
     * @return float|null
     */
    public function getBitcoinRateValue(): ?float
    {
        $data = $this->getCurrencyData('BTC');
        if ($data === null) {
            return null;
        }

        return $data['bpi']['USD']['rate_float'] ?? null;
    }
}
