<?php

namespace App\Scrapers;

use App\Exceptions\ScrapingTerminatedException;
use App\Exceptions\WebdriverPageNotReachableException;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Illuminate\Support\Facades\Log;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;

/**
 * Class Webdriver
 * @package App\Scrapers
 */
class Webdriver
{
    /**
     * @var array|\Illuminate\Config\Repository|mixed
     */
    private $nodes = [];

    /**
     * @var array
     */
    private $windows = [];

    /**
     * @var RemoteWebDriver
     */
    private $driver = null;

    /**
     * Webdriver constructor.
     */
    public function __construct()
    {
        $this->nodes = config('selenium.nodes');

        if (empty($this->nodes)) {
            throw new \InvalidArgumentException("Selenium nodes are not specified");
        }
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function init()
    {
        try {
            $this->driver = RemoteWebDriver::create(
                $this->nodes[0], $this->getCapabilities(),
                config('selenium.connection_timeout'),
                config('selenium.request_timeout')
            );

            Log::debug("[NEW SESSION ID]: " . $this->driver->getSessionID());
        }
        catch (\Exception $e) {
            Log::error("[SCRAPER] Webdriver init error: " . $e->getMessage());

            throw new \Exception($e->getMessage());
        }

        return $this;
    }

    /**
     * Delay beetwen operations
     * @param  integer $time delay duration in seconds
     * @return $this
     */
    public function wait($time = 1)
    {
        sleep($time);

        return $this;
    }

    /**
     * Wait with a condition
     * @param  array  $condition wait condition, for example ['title' => 'Hello i am a title']
     * @param  integer $time      duration
     * @return $this
     */
    public function waitFor($condition, $time = 1)
    {
        // @TODO: wait for some action, for example, title is 'Hello i'm a title'

        return $this;
    }

    /**
     * @param null $url
     * @return $this
     * @throws ScrapingTerminatedException
     */
    public function open($url = null)
    {
        if (is_null($url)) {
            throw new \InvalidArgumentException("Url can't be null.");
        }

        Log::debug("Open url: $url");
        Log::debug("With session: " . $this->driver->getSessionID());

        try {
            $this->driver->get($url);
        }
        catch (\Exception $e) {
            Log::error("[WEBDRIVER] Page $url is not reachable: " . $this->driver->getSessionID());
            Log::error("[WEBDRIVER] Page $url is not reachable: " . $e->getMessage());

            //$this->close(3);

            $this->quit();

            throw new ScrapingTerminatedException("Webdriver open() exception.");
        }

        return $this;
    }

    /**
     * @param int $maxRecursive
     * @return $this
     * @throws ScrapingTerminatedException
     */
    public function close($maxRecursive = 1)
    {
        if ($maxRecursive <= 0) {
            return $this;
        }

        if (! is_null($this->driver)) {
            try {
                $this->driver->close();

                // $this->driver->quit();
            }
            catch (\Exception $e) {
                Log::error("[WEBDRIVER] can't close a session: " . $e->getMessage());

                if ($maxRecursive > 1) {
                    $this->close(--$maxRecursive);
                }
                else {
                    throw new ScrapingTerminatedException($e->getMessage());
                }
            }
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function quit()
    {
        if (! is_null($this->driver)) {
            Log::debug("Quit session: " . $this->driver->getSessionID());

            try {
                $this->driver->quit();
            }
            catch (\Exception $e) {
                Log::error("[WEBDRIVER] can't quit a browser: " . $e->getMessage());
            }
        }

        return $this;
    }

    /**
     * @param $mode
     * @return $this
     * @throws \Exception
     */
    public function window($mode)
    {
        if (is_null($this->driver)) {
            throw new \Exception("Webdriver instance is not initialized");
        }

        $this->windows = $this->driver->getWindowHandles();

        $handle = null;

        if ($mode === 'close') {
            $key = array_search($this->driver->getWindowHandle(), $this->windows);

            if (is_numeric($key)) {
                if (isset($this->windows[$key - 1])) {
                    $handle = $this->windows[$key - 1];
                }
                else if (isset($this->windows[$key + 1])) {
                    $handle = $this->windows[$key + 1];
                }
            }

            $this->driver->close();
        }

        if ($mode === 'next') {
            $key = array_search($this->driver->getWindowHandle(), $this->windows);

            if (is_numeric($key)) {
                if (isset($this->windows[$key + 1])){
                    $handle = $this->windows[$key + 1];
                }
            }
        }

        if ($mode === 'prev') {
            $key = array_search($this->driver->getWindowHandle(), $this->windows);

            if (is_numeric($key)) {
                if (isset($this->windows[$key - 1])){
                    $handle = $this->windows[$key - 1];
                }
            }
        }

        if (! is_null($handle)) {
            $this->driver->switchTo()->window($handle);
        }

        return $this;
    }

    /**
     * Get source code of the page
     * @return string Source code of the page
     */
    public function getPageSource()
    {
        Log::debug("Get page source with session id: " . $this->driver->getSessionID());

        return $this->driver->getPageSource();
    }

    /**
     * @param string $selector
     * @return \Facebook\WebDriver\Remote\RemoteWebElement
     * @throws \Exception
     */
    public function element(string $selector)
    {
        if (is_null($selector)) {
            throw new \InvalidArgumentException("Element selector can't be null.");
        }

        return $this->driver->findElement($this->recognizeElementSelector($selector));
    }

    /**
     * @param string $selector
     * @return \Facebook\WebDriver\Remote\RemoteWebElement[]
     * @throws \Exception
     */
    public function elements(string $selector)
    {
        if (is_null($selector)) {
            throw new \InvalidArgumentException("Element selector can't be null.");
        }

        return $this->driver->findElements($this->recognizeElementSelector($selector));
    }

    /**
     * @param string $selector
     * @param array $fields
     * @return \Facebook\WebDriver\Remote\RemoteWebElement
     * @throws \Exception
     */
    public function form(string $selector, $fields = [])
    {
        if (is_null($selector)) {
            throw new \Exception("Selector can't be null");
        }

        $form = $this->driver->findElement($this->recognizeElementSelector($selector));

        if (!empty($fields)) {
            foreach ($fields as $fselector => $fvalue) {
                $input = $form->findElement($this->recognizeElementSelector($fselector));

                if ($input != null) {
                    $input->sendKeys($fvalue);
                }
            }
        }

        return $form;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function back()
    {
        if (is_null($this->driver)) {
            throw new \Exception("Webdriver is not initialized");
        }

        $this->driver->navigate()->back();

        return $this;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getCurrentUrl()
    {
        if (is_null($this->driver)) {
            throw new \Exception("Webdriver is not initialized");
        }

        return $this->driver->getCurrentUrl();
    }

    /**
     * @param $path
     * @return $this
     * @throws \Exception
     */
    public function screenshot($path)
    {
        if (is_null($this->driver)) {
            throw new \Exception("Webdriver is not initialized");
        }

        $this->driver->takeScreenshot($path . '/' . time() . '.png');

        return $this;
    }

    /**
     * @param string $method
     * @param null $url
     * @param array $data
     * @return array|mixed
     * @throws \Exception
     */
    public function ajax($method = 'GET', $url = null, $data = [])
    {
        $method = strtoupper($method);

        $data = json_encode($data);

        if (is_null($url)) {
            throw new \InvalidArgumentException("Url can't be null");
        }

        if (! in_array($method, ['GET', 'POST'])) {
            throw new \InvalidArgumentException("Given method is not allowed");
        }

        $code = '';

        switch ($method) {
            case 'GET':
                $code = "var callback = arguments[arguments.length - 1]; window.jQuery.ajax({url: '$url', type: 'GET', dataType: 'json', success: function(response) {callback(response);}});";

                break;
            case 'POST':
                $code = "var callback = arguments[arguments.length - 1]; window.jQuery.ajax({url: '$url', type: 'POST', dataType: 'json', data: $data, success: function(response) {callback(response);}});";

                break;
        }

        try {
            $response = $this->driver->executeAsyncScript($code);
        }
        catch (\Exception $e) {
            Log::info("webdriver ajax() - ");
            Log::info($e->getMessage());

            $this->close();

            throw new \Exception("Webdriver ajax() exception.");
        }

        return $response;
    }

    /**
     * @param string $method
     * @param null $url
     * @param string $json_data
     * @return array|mixed
     * @throws \Exception
     */
    public function xmlHttpRequest($method = 'GET', $url = null, $json_data = '')
    {
        if (is_null($url)) {
            throw new \InvalidArgumentException("Url can't be null");
        }

        $method = strtoupper($method);

        if ($method == 'GET') {
            $code = "
                var callback = arguments[arguments.length - 1];
                var xhr = new XMLHttpRequest();
                xhr.open('GET', '$url', false);
                xhr.send();
                if (xhr.status != 200) {
                    callback({status: xhr.status, message: xhr.statusText});
                } else {
                    callback({status: xhr.status, data: xhr.responseText});
                }
            ";
        }
        else if ($method == 'POST') {
            $code = <<<EON
                var callback = arguments[arguments.length - 1];

                var boundary = String(Math.random()).slice(2);
                var boundaryMiddle = '--' + boundary + '\\r\\n';
                var boundaryLast = '--' + boundary + '--\\r\\n';

                var body = ['\\r\\n'];

                var data = JSON.parse('$json_data');

                for (var key in data) {
                    body.push('Content-Disposition: form-data; name="' + key + '"\\r\\n\\r\\n' + data[key] + '\\r\\n');
                }

                body = body.join(boundaryMiddle) + boundaryLast;

                var xhr = new XMLHttpRequest();

                xhr.open('POST', '$url', true);
                xhr.setRequestHeader('Content-Type', 'multipart/form-data; boundary=' + boundary);
                xhr.onreadystatechange = function() {
                    if (this.status != 200) {
                        callback({status: this.status, message: this.statusText});
                    } else {
                        callback({status: this.status, data: this.responseText});
                    }
                }
                xhr.send(body);
EON;
        }

        try {
            $response = $this->driver->executeAsyncScript($code);
        }
        catch (\Exception $e) {
            Log::info("webdriver xmlHttpRequest() - " . $e->getMessage());

            $this->close();

            throw new \Exception("Webdriver xmlHttpRequest() exception.");
        }

        return $response;
    }

    /**
     * @param $selector
     * @return WebDriverBy
     * @throws \Exception
     */
    private function recognizeElementSelector($selector)
    {
        if (preg_match('/^\w+$/si', $selector)) {
            return WebDriverBy::tagName($selector);
        }

        if (preg_match('/^\#[a-z0-9-_]+$/si', $selector)) {
            return WebDriverBy::id(str_replace('#', '', $selector));
        }

        if (preg_match('/^\.[a-z0-9-_]+$/si', $selector)) {
            return WebDriverBy::className(str_replace('.', '', $selector));
        }

        if (preg_match('/.*\..*|[ >]|\[.+\]/si', $selector)) {
            return WebDriverBy::cssSelector($selector);
        }

        throw new \Exception("Unknown element selector.");
    }

    /**
     * @return DesiredCapabilities
     */
    private function getCapabilities()
    {
        $capabilities = DesiredCapabilities::chrome();

        $options = new ChromeOptions();
        $options->addArguments(['--no-sandbox']);

        $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);

        /*
        if ($this->use_proxy && $this->proxy) {
            $options = new ChromeOptions();

            $options->addArguments(array(
                '--proxy-server=' . $this->proxy,
            ));

            $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);
        }*/

        return $capabilities;
    }

    /*
    private function updateInstance()
    {
        $this->close();

        $this->updateTorProxy();

        $this->driver = RemoteWebDriver::create($this->nodes[$this->active_driver_instance - 1], $this->getCapabilities(), 5000);

        $this->requests = 0;

        return $this;
    }

    private function updateTorProxy()
    {
        $tc = new TorControl(
            array(
                'hostname' => 'tor-node',
                'port'     => env('TOR_CONTROL_PORT'),
                'password' => env('TOR_CONTROL_PORT_PASSWORD'),
                'authmethod' => 1
            )
        );

        $tc->connect();

        $tc->authenticate();

        $res = $tc->executeCommand('SIGNAL NEWNYM');

        $tc->quit();

        return $this;
    }

    private function checkTorConnection()
    {
        try {
            $this->driver->get('https://2ip.ru/');
        }
        catch (\Exception $e) {
            Log::info('TOR connection FAILED');

            if ($this->task != null) {
                event(new TaskSkiped($this->task));
            }

            $this->close();

            die();
        };

        $this->screenshot();

        $ip = '';

        try {
            $ip = $this->driver->findElement(WebDriverBy::cssSelector('#d_clip_button'));
        }
        catch(\Exception $e) {
            if ($this->task != null) {
                event(new TaskSkiped($this->task));
            }

            $this->close();

            die();
        }

        $ip = trim($ip->getText());

        if ($ip && $ip != env('APP_IP')) {
            $this->ip = $ip;

            return;
        }

        event(new TaskSkiped($this->task));

        $this->close();

        throw new \Exception("IP is not hidden. Process was closed.");
    }

    private function checkProxyAvailability($url_to_check)
    {
        $available_proxy = false;

        do {
            $this->driver->get($url_to_check);

            $title = $this->driver->getTitle();

            if (strpos($title, 'Proxy error') !== FALSE) {
                Log::info('PROXY [ERROR]');

                try {
                    $this->close();

                    $this->proxy = $this->proxies->get();

                    $this->driver = RemoteWebDriver::create($this->nodes[0], $this->getCapabilities(), 5000, 999999);

                    $available_proxy = false;
                }
                catch(\Exception $e) {}
            }
            else {
                Log::info('PROXY [Available] ' . $this->getIp());

                $available_proxy = true;
            }
        } while(!$available_proxy);
    }

    public function getIp()
    {
        return $this->ip;
    }*/
}
