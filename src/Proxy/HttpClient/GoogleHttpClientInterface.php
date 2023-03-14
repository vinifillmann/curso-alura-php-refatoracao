<?php

namespace CViniciusSDias\GoogleCrawler\Proxy\HttpClient;

use Psr\Http\Message\ResponseInterface;

interface GoogleHttpClientInterface
{
        /**
     * Gets the ResponseInterface for the informed URL based on all the information that
     * the proxy service needs
     *
     * @param string $url
     * @return ResponseInterface
     */
    public function getHttpResponse(string $url): ResponseInterface;
}