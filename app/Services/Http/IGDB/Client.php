<?php

namespace App\Services\Http\IGDB;

use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class Client
{
    /**
     * @var PendingRequest
     */
    protected $httpClient;

    /**
     * @var string|null
     */
    protected $accessToken;

    /**
     * @return void
     *
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $this->httpClient = Http::baseUrl(config('services.igdb.base_url'));
    }

    /**
     * Authenticates using the client id and secret.
     *
     * @return $this
     *
     * @throws BindingResolutionException
     * @throws Exception
     * @throws IGDBException
     */
    public function authenticate()
    {
        $response = Http
            ::withOptions([
                'query' => [
                    'client_id' => config('services.igdb.client_id'),
                    'client_secret' => config('services.igdb.client_secret'),
                    'grant_type' => 'client_credentials',
                ],
            ])
            ->post(config('services.igdb.auth_url'));

        if (!$response->successful()) {
            $this->handleAuthenticationErrors($response);
        }

        $this->accessToken = $response->json('access_token');

        return $this;
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
