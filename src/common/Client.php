<?php

namespace unapi\fssp\common;

class Client extends \GuzzleHttp\Client
{
    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $config['base_uri'] = 'http://fssprus.ru';
        $config['cookies'] = true;

        parent::__construct($config);
    }
}