<?php

namespace CViniciusSDias\GoogleCrawler;

use CViniciusSDias\GoogleCrawler\Exception\InvalidGoogleHtmlException;
use CViniciusSDias\GoogleCrawler\Proxy\{
    GoogleProxyAbstractFactory,
    NoProxyAbstractFactory
};
use CViniciusSDias\GoogleCrawler\Proxy\HttpClient\GoogleHttpClientInterface;
use CViniciusSDias\GoogleCrawler\Proxy\UrlParser\GoogleUrlParserInterface;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

/**
 * Google Crawler
 *
 * @package CViniciusSDias\GoogleCrawler
 * @author Vinicius Dias
 */
class Crawler
{

    private GoogleHttpClientInterface $httpClient;
    private GoogleUrlParserInterface $urlParser;
    public function __construct(
        GoogleProxyAbstractFactory $factory = null
    ) {
        if (is_null($factory)) {
            $factory = new NoProxyAbstractFactory();
        }

        $this->httpClient = $factory->createGoogleHttpClient();
        $this->urlParser = $factory->createGoogleUrlParser();
    }

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

        $domElementParser = new DomElementParser($this->urlParser);
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
