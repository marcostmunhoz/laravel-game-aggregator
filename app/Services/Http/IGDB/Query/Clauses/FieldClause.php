<?php

namespace App\Services\Http\IGDB\Query\Clauses;

use Stringable;

class FieldClause implements Stringable
{
    /**
     * @var string
     */
    protected $field;

    /**
     * @param string $field
     *
     * @return void
     */
    public function __construct($field)
    {
        $this->field = $field;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->field;
    }
}
