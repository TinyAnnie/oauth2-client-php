<?php

namespace App\Http\Clients;

use App\Http\Cache\OAuth2Store;
use GuzzleHttp\Client;

class Oauth2Client extends Client
{
    public function __construct(array $config = [])
    {
        $oauth2Store = new OAuth2Store();
        $defaults = [
            'headers' => $oauth2Store->GetOAuthHeaders(),
        ];

        $config = array_merge_recursive($defaults, $config);
        parent::__construct($config);
    }
}
