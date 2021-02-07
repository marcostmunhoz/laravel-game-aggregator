<?php

namespace App\Services\Http\IGDB\Query\Concerns;

use App\Services\Http\IGDB\Exceptions\BuilderException;
use App\Services\Http\IGDB\Query\Clauses\NestedWhereClause;
use App\Services\Http\IGDB\Query\Clauses\WhereClause;
use Closure;
use Illuminate\Support\Str;

trait HasWhereClausesTrait
{
    /**
     * @var WhereClause[]
     */
    protected $wheres = [];

    /**
     * Add a filtering condition (where) to the query.
     *
     * @param string|Closure         $field
     * @param string|mixed|bool|null $operator
     * @param mixed|null             $value
     * @param bool                   $and
     *
     * @return static
     */
    public function where($field, $operator = null, $value = null, bool $and = true)
    {
        if (\is_string($field)) {
            $this->validateFieldName($field, 'where');
        }

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

        $this->validateOperator($operator);

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

    /**
     * Validates if the given field name is valid.
     *
     * @param string $field
     * @param string $clause
     *
     * @return void
     *
     * @throws BuilderException
     */
    protected function validateFieldName(string $field, string $clause)
    {
        $regex = '/^(([a-zA-Z0-9_]+(\.(([a-zA-Z0-9_]+)|\*))*)|\*)$/';
        if (!preg_match($regex, $field)) {
            throw BuilderException::invalidFieldName($field);
        }

        switch ($clause) {
            case 'where':
            case 'sort':
                if (Str::contains($field, '*')) {
                    throw BuilderException::wildcardDisallowed($clause);
                }
                break;
        }
    }

    /**
     * Validates if the given operator is valid.
     *
     * @param string $operator
     *
     * @return void
     *
     * @throws BuilderException
     */
    protected function validateOperator(string $operator)
    {
        $allowedOperators = [
            '=',
            '!=',
            '>',
            '<',
            '>=',
            '<=',
            '~',
        ];

        if (!\in_array($operator, $allowedOperators)) {
            throw BuilderException::invalidOperator($operator);
        }
    }
}
