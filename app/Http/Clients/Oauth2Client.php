<?php

namespace App\Http\Clients;

use GuzzleHttp\Client;

class Oauth2Client extends Client
{
    public function __construct(array $config = [])
    {
        $defaults = [
            'headers' => app('oauth_client_header'),
        ];

        $config = array_merge_recursive($defaults, $config);
        parent::__construct($config);
    }
}
