<?php

namespace CViniciusSDias\GoogleCrawler\Proxy\UrlParser;

use CViniciusSDias\GoogleCrawler\Exception\InvalidResultException;

class NoProxyGoogleUrlParser implements GoogleUrlParserInterface
{
        /** {@inheritdoc} */
        public function parseUrl(string $googleUrl): string
        {
            $urlParts = parse_url($googleUrl);
            parse_str($urlParts['query'], $queryStringParams);
    
            if (!$resultUrl = filter_var($queryStringParams['q'], FILTER_VALIDATE_URL)) {
                throw new InvalidResultException();
            }
    
            return $resultUrl;
        }
}