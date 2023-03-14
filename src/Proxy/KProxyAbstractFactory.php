<?php

namespace CViniciusSDias\GoogleCrawler\Proxy;

use CViniciusSDias\GoogleCrawler\Proxy\UrlParser\KProxyGoogleUrlParser;
use CViniciusSDias\GoogleCrawler\Proxy\HttpClient\KProxyGoogleHttpClient;
use CViniciusSDias\GoogleCrawler\Proxy\UrlParser\GoogleUrlParserInterface;
use CViniciusSDias\GoogleCrawler\Proxy\HttpClient\GoogleHttpClientInterface;

class KProxyAbstractFactory implements GoogleProxyAbstractFactory
{
    public function __construct(
        private int $serverNumber
    ) {}

    public function createGoogleHttpClient(): GoogleHttpClientInterface
    {
        return new KProxyGoogleHttpClient($this->serverNumber);
    }

    public function createGoogleUrlParser(): GoogleUrlParserInterface
    {
        return new KProxyGoogleUrlParser();
    }
}