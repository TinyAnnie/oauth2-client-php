<?php

namespace App\Http\Clients;

use App\Http\Helpers\FileTokenPersistence;
use App\Http\Helpers\TokenPersistence;
use GuzzleHttp\Client;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Token\AccessToken;

class Oauth2Client extends Client
{
    /** @var TokenPersistence */
    private $tokenPersistence;

    public function __construct(array $config = [])
    {
        $this->tokenPersistence = new FileTokenPersistence('/tmp/token.txt');
        $defaults = [
            'headers' => $this->getOAuthHeaders(),
        ];

        $config = array_merge_recursive($defaults, $config);
        parent::__construct($config);
    }

    private function getOAuthHeaders(): array
    {
        $provider = new GenericProvider([
            'clientId'                => config('auth.oauth2_client_id'),
            'clientSecret'            => config('auth.oauth2_client_secret'),
            'urlAuthorize'            => config('auth.oauth2_client_authorize_url'),
            'urlAccessToken'          => config('auth.oauth2_client_access_token_url'),
            'urlResourceOwnerDetails' => '',
        ]);

        if ($this->tokenPersistence->hasToken()) {
            $accessToken = $this->tokenPersistence->restoreToken();
            if (!$accessToken->hasExpired()) {
                \Log::info("Token valid");
                return $provider->getHeaders($accessToken);
            }
            \Log::info("Token expired");
        }
        try {
            \Log::info("Get new token");
            $accessToken = $provider->getAccessToken('client_credentials');
            $this->tokenPersistence->saveToken($accessToken);
            return $provider->getHeaders($accessToken);
        } catch (IdentityProviderException $exception) {
            \Log::info("Request not authenticated");
            return [];
        }
    }
}
