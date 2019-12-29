<?php

return [

    /**
     * The list of selenium nodes
     */
    'nodes' => [
        'http://selenium-node:4444/wd/hub'
    ],

    'connection_timeout' => 5000, // in ms = 5 sec
    'request_timeout' => 45000 // in ms = 45 sec
];
