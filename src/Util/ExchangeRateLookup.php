<?php

namespace App\Util;

use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;

class ExchangeRateLookup extends HttpLookup
{
    private const API_URL = 'https://api.apilayer.com/exchangerates_data/';

    public function __construct()
    {
        parent::__construct();
        $this->client = $this->client->withOptions([
            'headers' => [
                'Content-Type' => 'text/plain',
                'apikey' => '1tWnUbJ7Z2c3w2zjIuv7ldWNBPF4d0Va',
            ],
        ]);
    }

    public function fetchRate(string $fromCurrency, string $toCurrency, float $amount): array
    {
        $url = self::API_URL . "convert?to={$toCurrency}&from={$fromCurrency}&amount={$amount}";

        try {
            $response = $this->client->request(
                'GET',
                $url,
            );

            if ($response->getStatusCode() !== 200) {
                return [];
            }

            return $response->toArray();
        } catch (ExceptionInterface) {
            return [];
        }
    }
}
