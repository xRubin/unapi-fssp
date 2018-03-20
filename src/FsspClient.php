<?php

namespace unapi\fssp;

use GuzzleHttp\Client;

class FsspClient extends Client
{
    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $config['base_uri'] = 'http://fssprus.ru/iss/ip/';
        $config['cookies'] = true;

        parent::__construct($config);
    }

    public function getAjaxUrl(): string
    {
        return 'http://is.fssprus.ru/ajax_search';
    }
}