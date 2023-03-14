<?php

namespace CViniciusSDias\GoogleCrawler\Proxy;

use CViniciusSDias\GoogleCrawler\Proxy\HttpClient\GoogleHttpClientInterface;
use CViniciusSDias\GoogleCrawler\Proxy\UrlParser\GoogleUrlParserInterface;

interface GoogleProxyAbstractFactory
{
    public function createGoogleHttpClient(): GoogleHttpClientInterface;
    public function createGoogleUrlParser(): GoogleUrlParserInterface;
}