<?php

namespace App\Services\Http\IGDB\Query;

use App\Services\Http\IGDB\Query\Clauses\FieldClause;
use App\Services\Http\IGDB\Query\Clauses\SortClause;
use App\Services\Http\IGDB\Query\Clauses\WhereClause;

class Builder
{
    /**
     * @var FieldClause[]
     */
    protected $fields = [];

    /**
     * @var SortClause|null
     */
    protected $sort;

    /**
     * @var WhereClause[]
     */
    protected $wheres = [];

    /**
     * Selects one or more fields.
     * If there's already selected fields, they'll be overwritten.
     *
     * @param string[] ...$params
     *
     * @return $this
     */
    public function select(string ...$params)
    {
        $this->fields = array_map(
            function ($param) {
                return new FieldClause($param);
            },
            $params
        );

        return $this;
    }

    /**
     * Add a field to the query without overwriting the existing ones.
     *
     * @param string $field
     *
     * @return $this
     */
    public function addField(string $field)
    {
        $this->fields[] = new FieldClause($field);

        return $this;
    }

    /**
     * Add a filtering condition (where) to the query.
     *
     * @param string $field
     * @param string $operator
     * @param mixed  $value
     * @param bool   $and
     *
     * @return void
     */
    public function where($field, $operator = null, $value = null, bool $and = true)
    {
        $count = \func_num_args();

        // we assume the operator as =
        if (2 === $count) {
            return $this->where($field, '=', $operator);
        }

        $clause = new WhereClause($field, $operator, $value, $and);

        if (empty($this->wheres)) {
            $clause->isFirstClause(true);
        }

        $this->wheres[] = $clause;

        return $this;
    }

    /**
     * Add sorting to the query result.
     *
     * @param string $field
     * @param string $direction
     *
     * @return $this
     */
    public function sortBy(string $field, string $direction = 'asc')
    {
        $this->sort = new SortClause($field, $direction);

        return $this;
    }

    /**
     * Compiles the whole query (fields, where, order).
     *
     * @return string
     */
    public function compile()
    {
        return implode(
            \PHP_EOL,
            array_filter([
                $this->compileFields(),
                $this->compileWheres(),
                $this->compileSort(),
            ])
        );
    }

    /**
     * Compiles the fields clause.
     *
     * @return string|null
     */
    protected function compileFields()
    {
        if (!$this->fields) {
            return null;
        }

        $fields = implode(', ', $this->fields);

        return "fields $fields;";
    }

    /**
     * Compiles the where clause;.
     *
     * @return string|null
     */
    protected function compileWheres()
    {
        if (!$this->wheres) {
            return null;
        }

        $wheres = implode('', $this->wheres);

        return "where $wheres;";
    }

    /**
     * Compiles the sort clause.
     *
     * @return string|null
     */
    protected function compileSort()
    {
        return $this->sort
            ? (string) $this->sort
            : null;
    }
}
