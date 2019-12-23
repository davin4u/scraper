<?php

if (! function_exists("webdriver")) {
    function webdriver() {
        return resolve('App\Scrapers\Webdriver');
    }
}
