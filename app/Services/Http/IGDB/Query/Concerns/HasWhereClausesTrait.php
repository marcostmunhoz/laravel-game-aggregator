<?php

namespace App\Services\Http\IGDB\Query\Concerns;

use App\Services\Http\IGDB\Query\Clauses\NestedWhereClause;
use App\Services\Http\IGDB\Query\Clauses\WhereClause;
use Closure;

trait HasWhereClausesTrait
{
    /**
     * @var WhereClause[]
     */
    protected $wheres = [];

    /**
     * Add a filtering condition (where) to the query.
     *
     * @param string $field
     * @param string $operator
     * @param mixed  $value
     * @param bool   $and
     *
     * @return static
     */
    public function where($field, $operator = null, $value = null, bool $and = true)
    {
        $count = \func_num_args();

        // if first parameters is a closure, we assume that's a nested where clause
        if ($field instanceof Closure) {
            return $this->nestedWhere(
                $field,
                null === $operator ? true : (bool) $operator
            );
        }

        // otherwise, if there's only two parameters, we assume
        // the operator as =
        if (2 === $count) {
            return $this->where($field, '=', $operator);
        }

        $clause = new WhereClause($field, $operator, $value, $and);

        // if there's no other where clause in the array, set it as first
        if (empty($this->wheres)) {
            $clause->isFirstClause(true);
        }

        $this->wheres[] = $clause;

        return $this;
    }

    /**
     * Add a nested filtering condition to the query.
     *
     * @param Closure $closure
     * @param bool    $and
     *
     * @return static
     */
    public function nestedWhere(Closure $closure, bool $and = true)
    {
        $clause = new NestedWhereClause($and);

        // if there's no other where clause in the array, set it as first
        if (empty($this->wheres)) {
            $clause->isFirstClause(true);
        }

        // pass the clause as parameter to the closure
        $closure($clause);

        $this->wheres[] = $clause;

        return $this;
    }

    /**
     * Compiles the where clause.
     *
     * @return string|null
     */
    protected function compileWheres()
    {
        if (!$this->wheres) {
            return null;
        }

        $wheres = implode('', $this->wheres);

        if ($this instanceof NestedWhereClause) {
            return $wheres;
        }

        return "where $wheres;";
    }
}
