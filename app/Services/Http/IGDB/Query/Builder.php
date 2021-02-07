<?php

namespace App\Services\Http\IGDB\Query;

use App\Services\Http\IGDB\Client;
use App\Services\Http\IGDB\Exceptions\BuilderException;
use App\Services\Http\IGDB\Exceptions\IGDBException;
use App\Services\Http\IGDB\Query\Clauses\FieldClause;
use App\Services\Http\IGDB\Query\Clauses\SortClause;
use App\Services\Http\IGDB\Query\Concerns\HasWhereClausesTrait;
use Exception;
use Illuminate\Http\Client\Response;

class Builder
{
    use HasWhereClausesTrait;
    /**
     * @var FieldClause[]
     */
    protected $fields = [];

    /**
     * @var SortClause|null
     */
    protected $sort;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @param Client $client
     *
     * @return void
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

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
                $this->validateFieldName($param, 'fields');

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
        $this->validateFieldName($field, 'fields');

        $this->fields[] = new FieldClause($field);

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
        $this->validateFieldName($field, 'sort');
        $this->validateSortDirection($direction);

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
     * Executes the query and returns a Response object.
     *
     * @param string $endpoint
     *
     * @return Response
     *
     * @throws IGDBException
     * @throws Exception
     */
    public function execute(string $endpoint)
    {
        return $this
            ->client
            ->request($this, $endpoint);
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

    /**
     * @param string $direction
     *
     * @return void
     *
     * @throws BuilderException
     */
    protected function validateSortDirection(string $direction)
    {
        if (!\in_array($direction, ['asc', 'desc'])) {
            throw BuilderException::invalidSortDirection($direction);
        }
    }
}
