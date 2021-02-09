<?php

namespace App\Services\Http\IGDB\Query\Clauses;

use Stringable;

class WhereClause implements Stringable
{
    /**
     * @var string
     */
    protected $field;

    /**
     * @var string
     */
    protected $operator;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var bool
     */
    protected $and;

    /**
     * @var bool
     */
    protected $firstClause = false;

    /**
     * @param string $field
     * @param string $operator
     * @param mixed  $value
     * @param bool   $and
     *
     * @return void
     */
    public function __construct(string $field, string $operator, $value, bool $and = true)
    {
        $this->field = $field;
        $this->operator = $operator;
        $this->value = $value;
        $this->and = $and;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            '%s%s %s %s',
            $this->compileAnd(),
            $this->field,
            $this->operator,
            $this->compileValue()
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

    /**
     * Compiles the value of the condition.
     *
     * @return string
     */
    protected function compileValue()
    {
        if (null === $this->value) {
            return 'null';
        }

        if (\is_array($this->value)) {
            return '('.implode(', ', array_map(
                [$this, 'quoteValue'],
                $this->value
            )).')';
        }

        return $this->quoteValue($this->value);
    }

    /**
     * Quotes the value, if needed.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    protected function quoteValue($value)
    {
        if (is_numeric($value)) {
            return $value;
        }

        if ('"' !== substr($value, 0, 1)) {
            $value = '"'.$value;
        }

        if ('"' !== substr($value, -1, 1)) {
            $value .= '"';
        }

        return $value;
    }
}
