<?php

namespace Tests\Unit\Services\Http\IGDB\Query;

use App\Services\Http\IGDB\Exceptions\BuilderException;
use App\Services\Http\IGDB\Query\Builder;
use App\Services\Http\IGDB\Query\Clauses\NestedWhereClause;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BuilderTest extends TestCase
{
    use WithFaker;

    public function test_builder_can_select_single_column()
    {
        $builder = new Builder();
        $column = $this->faker->word;

        $builder->select($column);

        $this->assertEquals(
            "fields $column;",
            $builder->compile()
        );
    }

    public function test_builder_can_select_multiple_columns()
    {
        $builder = new Builder();
        $columns = $this->faker->words($this->faker->numberBetween(1, 10));

        $builder->select(...$columns);

        $this->assertEquals(
            'fields '.implode(', ', $columns).';',
            $builder->compile()
        );
    }

    public function test_calling_select_twice_overwrites_the_previous_selection()
    {
        $builder = new Builder();
        $oldColumn = $this->faker->words($this->faker->numberBetween(1, 10));

        $builder->select(...$oldColumn);

        $newColumn = $this->faker->words($this->faker->numberBetween(1, 10));

        $builder->select(...$newColumn);

        $this->assertEquals(
            'fields '.implode(', ', $newColumn).';',
            $builder->compile()
        );
    }

    public function test_field_name_should_be_valid()
    {
        $builder = new Builder();
        $column = $this->faker->sentence;

        $this->expectExceptionObject(BuilderException::invalidFieldName($column));

        $builder->select($column);
    }

    public function test_builder_can_make_simple_filters()
    {
        $builder = new Builder();
        $andColumn = $this->faker->word;
        $andValue = $this->faker->word;
        $orColumn = $this->faker->word;
        $orValue = $this->faker->word;

        $builder->where($andColumn, $andValue)
            ->where($orColumn, '!=', $orValue, false);

        $this->assertEquals(
            "where $andColumn = $andValue | $orColumn != $orValue;",
            $builder->compile()
        );
    }

    public function test_builder_can_make_nested_filters()
    {
        $builder = new Builder();
        $level1Column1 = $this->faker->word;
        $level1Value1 = $this->faker->word;
        $level1Column2 = $this->faker->word;
        $level1Value2 = $this->faker->word;
        $level2Column1 = $this->faker->word;
        $level2Value1 = $this->faker->word;
        $level2Column2 = $this->faker->word;
        $level2Value2 = $this->faker->word;

        $builder->where($level1Column1, $level1Value1)
            ->where($level1Column2, '!=', $level1Value2, false)
            ->where(function (NestedWhereClause $clause) use ($level2Column1, $level2Value1, $level2Column2, $level2Value2) {
                $clause->where($level2Column1, $level2Value1)
                    ->where($level2Column2, '!=', $level2Value2, false);
            });

        $this->assertEquals(
            "where $level1Column1 = $level1Value1 | $level1Column2 != $level1Value2 & ($level2Column1 = $level2Value1 | $level2Column2 != $level2Value2);",
            $builder->compile()
        );
    }

    public function test_filter_field_name_should_be_valid()
    {
        $builder = new Builder();
        $column = $this->faker->sentence;
        $value = $this->faker->word;

        $this->expectExceptionObject(BuilderException::invalidFieldName($column));

        $builder->where($column, $value);
    }

    public function test_filter_operator_should_be_valid()
    {
        $builder = new Builder();
        $column = $this->faker->word;
        $value = $this->faker->word;
        $operator = 'invalid operator';

        $this->expectExceptionObject(BuilderException::invalidOperator($operator));

        $builder->where($column, $operator, $value);
    }

    public function test_builder_can_sort_results()
    {
        $builder = new Builder();
        $column = $this->faker->word;
        $direction = $this->faker->randomElement(['asc', 'desc']);

        $builder->sortBy($column, $direction);

        $this->assertEquals(
            "sort $column $direction;",
            $builder->compile()
        );
    }

    public function test_calling_sort_twice_overwrites_the_previous_sorting()
    {
        $builder = new Builder();
        $oldColumn = $this->faker->word;
        $oldDirection = $this->faker->randomElement(['asc', 'desc']);

        $builder->sortBy($oldColumn, $oldDirection);

        $newColumn = $this->faker->word;
        $newDirection = $this->faker->randomElement(['asc', 'desc']);

        $builder->sortBy($newColumn, $newDirection);

        $this->assertEquals(
            "sort $newColumn $newDirection;",
            $builder->compile()
        );
    }

    public function test_sort_direction_should_be_valid()
    {
        $builder = new Builder();
        $column = $this->faker->word;
        $direction = $this->faker->sentence;

        $this->expectExceptionObject(BuilderException::invalidSortDirection($direction));

        $builder->sortBy($column, $direction);
    }

    public function test_sort_field_name_should_be_valid()
    {
        $builder = new Builder();
        $column = $this->faker->sentence;
        $direction = $this->faker->word;

        $this->expectExceptionObject(BuilderException::invalidFieldName($column));

        $builder->sortBy($column, $direction);
    }

    public function test_builder_can_make_a_complex_query()
    {
        $builder = new Builder();

        // select
        $selectColumns = $this->faker->words($this->faker->numberBetween(1, 10));
        $builder->select(...$selectColumns);
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
        $builder->where($whereLevel1Column1, $whereLevel1Value1)
            ->where($whereLevel1Column2, '!=', $whereLevel1Value2, false)
            ->where(function (NestedWhereClause $clause) use ($whereLevel2Column1, $whereLevel2Value1, $whereLevel2Column2, $whereLevel2Value2) {
                $clause->where($whereLevel2Column1, $whereLevel2Value1)
                    ->where($whereLevel2Column2, '!=', $whereLevel2Value2, false);
            });
        $whereCompiled = "where $whereLevel1Column1 = $whereLevel1Value1 | $whereLevel1Column2 != $whereLevel1Value2 & ($whereLevel2Column1 = $whereLevel2Value1 | $whereLevel2Column2 != $whereLevel2Value2);";

        // sort
        $sortColumn = $this->faker->word;
        $sortDirection = $this->faker->randomElement(['asc', 'desc']);
        $builder->sortBy($sortColumn, $sortDirection);
        $sortCompiled = "sort $sortColumn $sortDirection;";

        $this->assertEquals(
            implode(\PHP_EOL, [$selectCompiled, $whereCompiled, $sortCompiled]),
            $builder->compile()
        );
    }
}
