<?php

namespace App\Services\Http\IGDB\Query\Clauses;

use App\Services\Http\IGDB\Query\Concerns\HasWhereClausesTrait;
use Stringable;

class NestedWhereClause implements Stringable
{
    use HasWhereClausesTrait;

    /**
     * @var bool
     */
    protected $and;

    /**
     * @var bool
     */
    protected $firstClause = false;

    /**
     * @param bool $and
     *
     * @return void
     */
    public function __construct(bool $and = true)
    {
        $this->and = $and;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            '%s(%s)',
            $this->compileAnd(),
            $this->compileWheres()
        );
    }

    /**
     * Sets whether the clause is the first of a given group.
     *
     * @param bool $first
     *
     * @return void
     */
    public function isFirstClause(bool $first = true)
    {
        $this->firstClause = $first;
    }

    /**
     * Compiles the connection between two where clauses (and/or).
     *
     * @return string
     */
    protected function compileAnd()
    {
        if ($this->firstClause) {
            return '';
        }

        $operator = $this->and ? ' & ' : ' | ';

        return $operator;
    }
}
