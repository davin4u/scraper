<?php

namespace App\Crawler\Clients;

use App\Crawler\Interfaces\ClientInterface;
use App\Crawler\Interfaces\ClientResponseInterface;
use App\Crawler\Webdriver;
use App\Crawler\WebdriverResponse;
use App\Exceptions\ScrapingTerminatedException;

/**
 * Class WebdriverClient
 * @package App\Crawler\Clients
 */
class WebdriverClient implements ClientInterface
{
    /**
     * @var Webdriver
     */
    protected $client;

    /**
     * WebdriverClient constructor.
     */
    public function __construct()
    {
        $this->client = new Webdriver();
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $options
     * @return ClientResponseInterface
     */
    public function request(string $method, string $url, array $options = []): ClientResponseInterface
    {
        try {
            $pageSource = $this->client->init()->open($url)->getPageSource();

            $this->client->quit();

            return new WebdriverResponse($pageSource);
        }
        catch (ScrapingTerminatedException $e) {
            return new WebdriverResponse(null);
        }
        catch (\Exception $e) {
            return new WebdriverResponse(null);
        }
    }
}