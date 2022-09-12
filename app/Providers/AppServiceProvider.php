<?php

namespace App\Providers;

use App;
use Illuminate\Support\ServiceProvider;
use View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
//        $provider = new GenericProvider([
//            'clientId'                => config('auth.oauth2_client_id'),
//            'clientSecret'            => config('auth.oauth2_client_secret'),
//            'urlAuthorize'            => config('auth.oauth2_client_authorize_url'),
//            'urlAccessToken'          => config('auth.oauth2_client_access_token_url'),
//            'urlResourceOwnerDetails' => '',
//        ]);
//
//        try {
//            $accessToken = $provider->getAccessToken('client_credentials');
//            $header = $provider->getHeaders($accessToken);
//            App::singleton('oauth_client_header', function () use ($header) {
//                return $header;
//            });
//
//            View::share('oauth_client_header', app('oauth_client_header'));
//
//        } catch (IdentityProviderException $exception) {
//            \Log::info("Request not authenticated");
//        }
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
}
