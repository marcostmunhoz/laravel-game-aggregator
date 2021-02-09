<?php

namespace Tests\Unit\Services\Http\IGDB\Query;

use App\Services\Http\IGDB\Client;
use App\Services\Http\IGDB\Exceptions\BuilderException;
use App\Services\Http\IGDB\Query\Builder;
use App\Services\Http\IGDB\Query\Clauses\NestedWhereClause;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BuilderTest extends TestCase
{
    use WithFaker;

    /**
     * @var Builder
     */
    protected $builder;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createBuilder();
    }

    public function test_builder_can_select_single_column()
    {
        $column = $this->faker->word;

        $this->builder->select($column);

        $this->assertEquals(
            "fields $column;",
            $this->builder->compile()
        );
    }

    public function test_builder_can_select_multiple_columns()
    {
        $columns = $this->faker->words($this->faker->numberBetween(1, 10));

        $this->builder->select(...$columns);

        $this->assertEquals(
            'fields '.implode(', ', $columns).';',
            $this->builder->compile()
        );
    }

    public function test_calling_select_twice_overwrites_the_previous_selection()
    {
        $oldColumn = $this->faker->words($this->faker->numberBetween(1, 10));

        $this->builder->select(...$oldColumn);

        $newColumn = $this->faker->words($this->faker->numberBetween(1, 10));

        $this->builder->select(...$newColumn);

        $this->assertEquals(
            'fields '.implode(', ', $newColumn).';',
            $this->builder->compile()
        );
    }

    public function test_field_name_should_be_valid()
    {
        $column = $this->faker->sentence;

        $this->expectExceptionObject(BuilderException::invalidFieldName($column));

        $this->builder->select($column);
    }

    public function test_builder_can_make_simple_filters()
    {
        $andColumn = $this->faker->word;
        $andValue = $this->faker->word;
        $orColumn = $this->faker->word;
        $orValue = $this->faker->word;

        $this->builder->where($andColumn, $andValue)
            ->where($orColumn, '!=', $orValue, false);

        $this->assertEquals(
            "where $andColumn = $andValue | $orColumn != $orValue;",
            $this->builder->compile()
        );
    }

    public function test_builder_can_make_nested_filters()
    {
        $level1Column1 = $this->faker->word;
        $level1Value1 = $this->faker->word;
        $level1Column2 = $this->faker->word;
        $level1Value2 = $this->faker->word;
        $level2Column1 = $this->faker->word;
        $level2Value1 = $this->faker->word;
        $level2Column2 = $this->faker->word;
        $level2Value2 = $this->faker->word;

        $this->builder->where($level1Column1, $level1Value1)
            ->where($level1Column2, '!=', $level1Value2, false)
            ->where(function (NestedWhereClause $clause) use ($level2Column1, $level2Value1, $level2Column2, $level2Value2) {
                $clause->where($level2Column1, $level2Value1)
                    ->where($level2Column2, '!=', $level2Value2, false);
            });

        $this->assertEquals(
            "where $level1Column1 = $level1Value1 | $level1Column2 != $level1Value2 & ($level2Column1 = $level2Value1 | $level2Column2 != $level2Value2);",
            $this->builder->compile()
        );
    }

    public function test_filter_field_name_should_be_valid()
    {
        $column = $this->faker->sentence;
        $value = $this->faker->word;

        $this->expectExceptionObject(BuilderException::invalidFieldName($column));

        $this->builder->where($column, $value);
    }

    public function test_filter_operator_should_be_valid()
    {
        $column = $this->faker->word;
        $value = $this->faker->word;
        $operator = 'invalid operator';

        $this->expectExceptionObject(BuilderException::invalidOperator($operator));

        $this->builder->where($column, $operator, $value);
    }

    public function test_builder_can_sort_results()
    {
        $column = $this->faker->word;
        $direction = $this->faker->randomElement(['asc', 'desc']);

        $this->builder->sortBy($column, $direction);

        $this->assertEquals(
            "sort $column $direction;",
            $this->builder->compile()
        );
    }

    public function test_calling_sort_twice_overwrites_the_previous_sorting()
    {
        $oldColumn = $this->faker->word;
        $oldDirection = $this->faker->randomElement(['asc', 'desc']);

        $this->builder->sortBy($oldColumn, $oldDirection);

        $newColumn = $this->faker->word;
        $newDirection = $this->faker->randomElement(['asc', 'desc']);

        $this->builder->sortBy($newColumn, $newDirection);

        $this->assertEquals(
            "sort $newColumn $newDirection;",
            $this->builder->compile()
        );
    }

    public function test_sort_direction_should_be_valid()
    {
        $column = $this->faker->word;
        $direction = $this->faker->sentence;

        $this->expectExceptionObject(BuilderException::invalidSortDirection($direction));

        $this->builder->sortBy($column, $direction);
    }

    public function test_sort_field_name_should_be_valid()
    {
        $column = $this->faker->sentence;
        $direction = $this->faker->word;

        $this->expectExceptionObject(BuilderException::invalidFieldName($column));

        $this->builder->sortBy($column, $direction);
    }

    public function test_builder_can_limit_results()
    {
        $limit = $this->faker->numberBetween(1);
        $offset = $this->faker->numberBetween(1);

        $this->builder->limit($limit, $offset);

        $this->assertEquals(
            "limit $limit;\noffset $offset;",
            $this->builder->compile()
        );
    }

    public function test_calling_limit_twice_overwrites_the_previous_limiting()
    {
        $oldLimit = $this->faker->numberBetween(1);
        $oldOffset = $this->faker->numberBetween(1);

        $this->builder->limit($oldLimit, $oldOffset);

        $newLimit = $this->faker->numberBetween(1);
        $newOffset = $this->faker->numberBetween(1);

        $this->builder->limit($newLimit, $newOffset);

        $this->assertEquals(
            "limit $newLimit;\noffset $newOffset;",
            $this->builder->compile()
        );
    }

    public function test_builder_can_make_a_complex_query()
    {
        // select
        $selectColumns = $this->faker->words($this->faker->numberBetween(1, 10));
        $this->builder->select(...$selectColumns);
        $selectCompiled = 'fields '.implode(', ', $selectColumns).';';

        // where
        $whereLevel1Column1 = $this->faker->word;
        $whereLevel1Value1 = $this->faker->word;
        $whereLevel1Column2 = $this->faker->word;
        $whereLevel1Value2 = $this->faker->word;
        $whereLevel2Column1 = $this->faker->word;
        $whereLevel2Value1 = $this->faker->word;
        $whereLevel2Column2 = $this->faker->word;
        $whereLevel2Value2 = $this->faker->word;
        $this->builder->where($whereLevel1Column1, $whereLevel1Value1)
            ->where($whereLevel1Column2, '!=', $whereLevel1Value2, false)
            ->where(function (NestedWhereClause $clause) use ($whereLevel2Column1, $whereLevel2Value1, $whereLevel2Column2, $whereLevel2Value2) {
                $clause->where($whereLevel2Column1, $whereLevel2Value1)
                    ->where($whereLevel2Column2, '!=', $whereLevel2Value2, false);
            });
        $whereCompiled = "where $whereLevel1Column1 = $whereLevel1Value1 | $whereLevel1Column2 != $whereLevel1Value2 & ($whereLevel2Column1 = $whereLevel2Value1 | $whereLevel2Column2 != $whereLevel2Value2);";

        // sort
        $sortColumn = $this->faker->word;
        $sortDirection = $this->faker->randomElement(['asc', 'desc']);
        $this->builder->sortBy($sortColumn, $sortDirection);
        $sortCompiled = "sort $sortColumn $sortDirection;";

        // limit
        $limit = $this->faker->numberBetween(1);
        $offset = $this->faker->numberBetween(1);
        $this->builder->limit($limit, $offset);
        $limitCompiled = "limit $limit;\noffset $offset;";

        $this->assertEquals(
            implode(\PHP_EOL, [$selectCompiled, $whereCompiled, $sortCompiled, $limitCompiled]),
            $this->builder->compile()
        );
    }

    protected function createBuilder()
    {
        $this->builder = new Builder(
            $this->createMock(Client::class)
        );
    }
}
