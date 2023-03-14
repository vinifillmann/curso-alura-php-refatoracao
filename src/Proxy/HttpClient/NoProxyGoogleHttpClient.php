<?php

namespace CViniciusSDias\GoogleCrawler\Proxy\HttpClient;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use CViniciusSDias\GoogleCrawler\Exception\InvalidUrlException;

class NoProxyGoogleHttpClient implements GoogleHttpClientInterface
{
        /** {@inheritdoc} */
        public function getHttpResponse(string $url): ResponseInterface
        {
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                throw new InvalidUrlException("Invalid Google URL: $url");
            }
    
            return (new Client())->request('GET', $url);
        }
}