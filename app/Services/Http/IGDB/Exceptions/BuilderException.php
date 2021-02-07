<?php

namespace App\Services\Http\IGDB\Exceptions;

use App\Services\Exceptions\ServiceException;

class BuilderException extends ServiceException
{
    /**
     * @param string $name
     *
     * @return static
     */
    public static function invalidFieldName(string $name)
    {
        return new static(
            sprintf('The value \'%s\' is not a valid field name.', $name),
            400
        );
    }

    /**
     * @param string $operator
     *
     * @return static
     */
    public static function invalidOperator(string $operator)
    {
        return new static(
            sprintf('The operator \'%s\' is not valid.', $operator),
            400
        );
    }

    /**
     * @param string $direction
     *
     * @return static
     */
    public static function invalidSortDirection(string $direction)
    {
        return new static(
            sprintf('The sort direction \'%s\' is not valid.', $direction),
            400
        );
    }

    /**
     * @param string $clause
     *
     * @return static
     */
    public static function wildcardDisallowed(string $clause)
    {
        return new static(
            sprintf('Wildcards are not allowed in %s clause.', $clause),
            400
        );
    }
}
