<?php

namespace CViniciusSDias\GoogleCrawler;

use CViniciusSDias\GoogleCrawler\Exception\InvalidGoogleHtmlException;
use CViniciusSDias\GoogleCrawler\Exception\InvalidResultException;
use CViniciusSDias\GoogleCrawler\Proxy\{
    GoogleProxyInterface,
    NoProxy
};
use CViniciusSDias\GoogleCrawler\Proxy\HttpClient\GoogleHttpClientInterface;
use CViniciusSDias\GoogleCrawler\Proxy\UrlParser\GoogleUrlParserInterface;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;
use Symfony\Component\DomCrawler\Link;
use DOMElement;

/**
 * Google Crawler
 *
 * @package CViniciusSDias\GoogleCrawler
 * @author Vinicius Dias
 */
class Crawler
{

    public function __construct(
        private GoogleUrlParserInterface $parser,
        private GoogleHttpClientInterface $httpClient
    ) {}

    /**
     * Returns the 100 first found results for the specified search term
     *
     * @return ResultList
     * @throws \GuzzleHttp\Exception\ServerException If the proxy was overused
     * @throws \GuzzleHttp\Exception\ConnectException If the proxy is unavailable or $countrySpecificSuffix is invalid
     */
    public function getResults(SearchTermInterface $searchTerm, string $googleDomain = "google.com", string $countryCode = ""): ResultList
    {
        if (stripos($googleDomain, 'google.') === false || stripos($googleDomain, 'http') === 0) {
            throw new \InvalidArgumentException('Invalid google domain');
        }

        $googleUrl = "https://$googleDomain/search?q={$searchTerm}&num=100";
        if (!empty($countryCode)) {
            $googleUrl .= "&gl={$countryCode}";
        }
        
        $response = $this->httpClient->getHttpResponse($googleUrl);
        $stringResponse = (string) $response->getBody();

        $domCrawler = new DomCrawler($stringResponse);
        $googleResultList = $this->createGoogleResultList($domCrawler);

        $resultList = new ResultList($googleResultList->count());

        $domElementParser = new DomElementParser($this->parser);
        foreach ($googleResultList as $googleResultElement) {
            $parsedResultMaybe = $domElementParser->parse($googleResultElement);
            $parsedResultMaybe->select(fn (Result $parsedResult) => $resultList->addResult($parsedResult));
        }
        return $resultList;
    }

    private function createGoogleResultList(DomCrawler $domCrawler): DomCrawler
    {
        $googleResultList = $domCrawler->filterXPath('//div[@class="Gx5Zad fP1Qef xpd EtOod pkphOe"]');
        if ($googleResultList->count() === 0) {
            throw new InvalidGoogleHtmlException('No parsable element found');
        }
        return $googleResultList;
    }
}
