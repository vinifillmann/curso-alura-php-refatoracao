<?php

namespace CViniciusSDias\GoogleCrawler\Proxy;

use CViniciusSDias\GoogleCrawler\Proxy\UrlParser\NoProxyGoogleUrlParser;
use CViniciusSDias\GoogleCrawler\Proxy\HttpClient\NoProxyGoogleHttpClient;
use CViniciusSDias\GoogleCrawler\Proxy\UrlParser\GoogleUrlParserInterface;
use CViniciusSDias\GoogleCrawler\Proxy\HttpClient\GoogleHttpClientInterface;

class NoProxyAbstractFactory implements GoogleProxyAbstractFactory
{
    public function createGoogleHttpClient(): GoogleHttpClientInterface
    {
        return new NoProxyGoogleHttpClient();
    }

    public function createGoogleUrlParser(): GoogleUrlParserInterface
    {
        return new NoProxyGoogleUrlParser();
    }
}