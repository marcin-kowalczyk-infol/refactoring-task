<?php

namespace App\Util;

use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;

class BinlistLookup extends HttpLookup
{
    private const API_URL = 'https://lookup.binlist.net/';

    public function __construct()
    {
        parent::__construct();
        $this->client = $this->client->withOptions([
            'headers' => ["Accept-Version" => 3],
        ]);
    }

    public function fetchCardInformation(string $bin): array
    {
        $url = self::API_URL . $bin;
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
