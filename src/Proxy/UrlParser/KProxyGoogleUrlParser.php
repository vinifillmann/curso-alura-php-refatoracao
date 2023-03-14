<?php

namespace CViniciusSDias\GoogleCrawler\Proxy\UrlParser;

use CViniciusSDias\GoogleCrawler\Exception\InvalidResultException;

class KProxyGoogleUrlParser implements GoogleUrlParserInterface
{

        /** {@inheritdoc} */
        public function parseUrl(string $googleUrl): string
        {
            $parsedUrl = parse_url($googleUrl);
            parse_str($parsedUrl['query'], $link);
    
            if (!array_key_exists('q', $link)) {
                // Generally a book suggestion
                throw new InvalidResultException();
            }
    
            $url = filter_var($link['q'], FILTER_VALIDATE_URL);
            // If this is not a valid URL, so the result is (probably) an image, news or video suggestion
            if (!$url) {
                throw new InvalidResultException();
            }
    
            return $url;
        }

}