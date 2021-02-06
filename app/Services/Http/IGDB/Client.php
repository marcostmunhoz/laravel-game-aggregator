<?php

namespace App\Services\Http\IGDB;

use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Client
{
    /**
     * @var PendingRequest
     */
    protected $httpClient;

    /**
     * @return void
     *
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $this->httpClient = Http
            ::baseUrl(config('services.igdb.base_url'))
            ->withHeaders(['Client-ID' => config('services.igdb.client_id')])
            ->withToken($this->getAccessToken());
    }

    /**
     * Returns the current access token, optionally authenticating if not set/expired.
     *
     * @return string
     *
     * @throws BindingResolutionException
     * @throws Exception
     * @throws IGDBException
     */
    protected function getAccessToken()
    {
        $accessToken = Cache::get('igdb:access-token');

        // if token is not set or is expired
        // I'm not using Cache::remember cause the ttl varies for each auth request
        if (!$accessToken) {
            logger('[igdb service] issuing new access code');
            // makes an authentication request
            $response = Http
                ::withOptions([
                    'query' => [
                        'client_id' => config('services.igdb.client_id'),
                        'client_secret' => config('services.igdb.client_secret'),
                        'grant_type' => 'client_credentials',
                    ],
                ])
                ->post(config('services.igdb.auth_url'));

            // checks if the response is valid, otherwise throws an exception
            if ($response->failed()) {
                $this->handleAuthenticationErrors($response);
            }

            // sets the cache
            $accessToken = $response->json('access_token');
            $ttl = $response->json('expires_in');

            logger(sprintf(
                '[igdb service] access code issued: %s | expiration: %s',
                $accessToken,
                Carbon::now()->addSeconds($ttl)
            ));

            Cache::put('igdb:access-token', $accessToken, $ttl);
        }

        return $accessToken;
    }

    /**
     * Handles authentication API errors.
     *
     * @param Response $response
     *
     * @return exit
     *
     * @throws IGDBException
     */
    protected function handleAuthenticationErrors(Response $response)
    {
        throw IGDBException::authenticationFailed($response->json('message'), $response->json('status'));
    }
}
