<?php

namespace App\Util;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class HttpLookup
{
    protected HttpClientInterface $client;

    public function __construct()
    {
        $this->client = HttpClient::create();
    }
}
