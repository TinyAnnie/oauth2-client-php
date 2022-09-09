<?php

namespace App\Providers;

use App;
use View;
use Illuminate\Support\ServiceProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\GenericProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $provider = new GenericProvider([
            'clientId'                => config('auth.oauth2_client_id'),
            'clientSecret'            => config('auth.oauth2_client_secret'),
            'urlAuthorize'            => config('auth.oauth2_client_authorize_url'),
            'urlAccessToken'          => config('auth.oauth2_client_access_token_url'),
            'urlResourceOwnerDetails' => '',
        ]);

        try {
            $accessToken = $provider->getAccessToken('client_credentials');
            $header = $provider->getHeaders($accessToken);
            App::singleton('oauth_client_header', function () use ($header) {
                return $header;
            });

            View::share('oauth_client_header', app('oauth_client_header'));

        } catch (IdentityProviderException $exception) {
            \Log::info("Request not authenticated");
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
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
