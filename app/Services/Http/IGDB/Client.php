<?php

namespace App\Services\Http\IGDB;

use App\Services\Http\IGDB\Exceptions\IGDBException;
use App\Services\Http\IGDB\Query\Builder;
use Carbon\Carbon;
use Closure;
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
     * Creates a new Query Builder and, optionally, executes the query.
     * If both $closure and $endpoint are present, returns a Response object, otherwire, returns the builder.
     *
     * @param Closure|null $closure
     * @param string|null  $endpoint
     *
     * @return Response|Builder
     *
     * @throws IGDBException
     * @throws Exception
     */
    public function query(?Closure $closure = null, ?string $endpoint = null)
    {
        if ($closure && $endpoint) {
            $builder = new Builder($this);

            $closure($builder);

            return $this
                ->request(
                    $builder,
                    $endpoint
                );
        }

        return new Builder($this);
    }

    /**
     * Make a request to a given endpoint.
     *
     * @param Builder $query
     * @param string  $endpoint
     *
     * @return Response
     *
     * @throws IGDBException
     * @throws Exception
     */
    public function request($query, string $endpoint)
    {
        $content = null;

        if ($query instanceof Builder) {
            $content = $query->compile();
        } else {
            throw IGDBException::invalidPayload();
        }

        $response = $this->httpClient
            ->withBody(
                $content,
                'text/plain'
            )
            ->post($endpoint);

        if ($response->failed()) {
            throw IGDBException::unexpectedResponse($response);
        }

        return $response;
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
