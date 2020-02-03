<?php

if (! function_exists("webdriver")) {
    /**
     * @return App\Scrapers\Webdriver
     */
    function webdriver() {
        return resolve('App\Scrapers\Webdriver');
    }
}

if (! function_exists('api')) {
    /**
     * @return \App\ApiCRUDProvider
     */
    function api() {
        return resolve(\App\ApiCRUDProvider::class);
    }
}