<?php

if (! function_exists("webdriver")) {
    /**
     * @return App\Scrapers\Webdriver
     */
    function webdriver() {
        return resolve('App\Scrapers\Webdriver');
    }
}
