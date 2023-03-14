<?php

namespace CViniciusSDias\GoogleCrawler\Proxy;

use CViniciusSDias\GoogleCrawler\Proxy\UrlParser\KProxyGoogleUrlParser;
use CViniciusSDias\GoogleCrawler\Proxy\HttpClient\KProxyGoogleHttpClient;
use CViniciusSDias\GoogleCrawler\Proxy\UrlParser\GoogleUrlParserInterface;
use CViniciusSDias\GoogleCrawler\Proxy\HttpClient\GoogleHttpClientInterface;
use CViniciusSDias\GoogleCrawler\Proxy\UrlParser\CommonProxyGoogleUrlParser;
use CViniciusSDias\GoogleCrawler\Proxy\HttpClient\CommonProxyGoogleHttpClient;

class CommonProxyAbstractFactory implements GoogleProxyAbstractFactory
{
    public function __construct(
        private string $endpoint
    ) {}

    public function createGoogleHttpClient(): GoogleHttpClientInterface
    {
        return new CommonProxyGoogleHttpClient($this->endpoint);
    }

    public function createGoogleUrlParser(): GoogleUrlParserInterface
    {
        return new CommonProxyGoogleUrlParser();
    }
}