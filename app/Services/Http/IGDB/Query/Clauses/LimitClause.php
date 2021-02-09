<?php

namespace App\Services\Http\IGDB\Query\Clauses;

use Stringable;

class LimitClause implements Stringable
{
    /**
     * @var int
     */
    protected $limit;

    /**
     * @var int|null
     */
    protected $offset;

    /**
     * @param int      $limit
     * @param int|null $offset
     *
     * @return void
     */
    public function __construct(int $limit, ?int $offset = null)
    {
        $this->limit = $limit;
        $this->offset = $offset;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $limit = "limit {$this->limit};";

        if ($this->offset) {
            $limit .= \PHP_EOL."offset {$this->offset};";
        }

        return $limit;
    }
}
