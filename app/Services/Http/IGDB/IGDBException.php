<?php

namespace App\Services\Http\IGDB;

use App\Services\Exceptions\ServiceException;

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
        return new static(sprintf('There was an error during the authentication: %s', $detail, $status));
    }
}
