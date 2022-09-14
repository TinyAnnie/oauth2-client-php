<?php

namespace App\Http\Cache;

use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Cache;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Token\AccessToken;

class OAuth2Store
{
    public function GetOAuthHeaders(): array
    {
        $cacheKey = 'guzzle-oauth2-token';
        $provider = new GenericProvider([
            'clientId' => config('auth.oauth2_client_id'),
            'clientSecret' => config('auth.oauth2_client_secret'),
            'urlAuthorize' => config('auth.oauth2_client_authorize_url'),
            'urlAccessToken' => config('auth.oauth2_client_access_token_url'),
            'urlResourceOwnerDetails' => '',
        ]);

        /** @var AccessToken $accessToken */
        $accessToken = Cache::get($cacheKey);
        if ($accessToken == null) {
            try {
                \Log::info("Get new token");
                $accessToken = $provider->getAccessToken('client_credentials');
                Cache::put(
                    $cacheKey,
                    $accessToken,
                    (new DateTime())->setTimestamp($accessToken->getExpires() - 1)
                );
            } catch (IdentityProviderException $exception) {
                \Log::info("Request not authenticated");
            }
        }

        return $provider->getHeaders($accessToken);
    }
}
