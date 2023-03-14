<?php

namespace CViniciusSDias\GoogleCrawler\Proxy\UrlParser;

use CViniciusSDias\GoogleCrawler\Exception\InvalidResultException;

class CommonProxyGoogleUrlParser implements GoogleUrlParserInterface
{

    /** {@inheritdoc} */
    public function parseUrl(string $googleUrl): string
    {
        $link = parse_url($googleUrl);
        parse_str($link['query'], $link);

        parse_str($link['u'], $link);
        $link = array_values($link);

        $url = filter_var($link[0], FILTER_VALIDATE_URL);
        // If this is not a valid URL, so the result is (probably) an image, news or video suggestion
        if (!$url) {
            throw new InvalidResultException();
        }

        return $url;
    }

}