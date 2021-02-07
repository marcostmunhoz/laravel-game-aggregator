<?php

namespace App\Services\Http\IGDB\Exceptions;

use App\Services\Exceptions\ServiceException;
use Illuminate\Http\Client\Response;

class IGDBException extends ServiceException
{
    /**
     * Authentication errors.
     *
     * @param string $detail
     * @param int    $status
     *
     * @return static
     */
    public static function authenticationFailed(string $detail, int $status)
    {
        return new static(sprintf('There was an error during the authentication: %s', $detail, $status), 401);
    }

    /**
     * @return static
     */
    public static function invalidPayload()
    {
        return new static('The provided payload is invalid.', 400);
    }

    /**
     * Invalid API responses.
     *
     * @param Response $response
     *
     * @return static
     */
    public static function unexpectedResponse(Response $response)
    {
        $message = 'Unexpected response.';
        if ($title = $response->json('0.title')) {
            $message = $title;
        }

        if ($reason = $response->json('0.cause')) {
            $message .= ': '.$reason;
        }

        return new static(
            $message,
            $response->json('0.status', 400)
        );
    }
}
