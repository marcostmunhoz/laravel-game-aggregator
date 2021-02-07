<?php

namespace App\Services\Http\IGDB\Query\Clauses;

use Stringable;

class SortClause implements Stringable
{
    /**
     * @var string
     */
    protected $field;

    /**
     * @var string
     */
    protected $direction;

    /**
     * @param string $field
     * @param string $direction
     *
     * @return void
     */
    public function __construct(string $field, string $direction)
    {
        $this->field = $field;
        $this->direction = $direction;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "sort {$this->field} {$this->direction};";
    }
}
