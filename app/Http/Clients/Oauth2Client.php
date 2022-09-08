<?php

namespace App\Http\Clients;

use GuzzleHttp\Client;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Token\AccessTokenInterface;
use function config;

class Oauth2Client extends Client
{
    /** @var AccessTokenInterface */
    private $accessToken;

    /** @var GenericProvider */
    private $provider;

    public function __construct(array $config = [])
    {
        $this->provider = new GenericProvider([
            'clientId'                => config('auth.oauth2_client_id'),
            'clientSecret'            => config('auth.oauth2_client_secret'),
            'urlAuthorize'            => config('auth.oauth2_client_authorize_url'),
            'urlAccessToken'          => config('auth.oauth2_client_access_token_url'),
            'urlResourceOwnerDetails' => '',
        ]);
        $defaults = [
            'headers' => $this->getHeaders(),
        ];

        $config = array_merge_recursive($defaults, $config);
        parent::__construct($config);
    }

    private function getHeaders(): array
    {
        if ($this->accessToken === null) {
            try {
                $this->accessToken = $this->provider->getAccessToken('client_credentials');
            } catch (IdentityProviderException $exception) {
                return [];
            }
        }

        return $this->provider->getHeaders($this->accessToken);
    }
}
