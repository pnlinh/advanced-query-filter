<?php

namespace FVSoft\QueryFilter;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class QueryFilter
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $queryBuilder;

    /**
     * Query filter constructor.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Apply filters.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Builder $query)
    {
        $this->queryBuilder = $query;

        foreach ($this->filters() as $filter => $value) {
            $filter = Str::camel($filter);

            if (method_exists($this, $filter)) {
                call_user_func([$this, $filter], $value);
            }
        }

        return $this->getBuilder();
    }

    /**
     * Get query builder.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getBuilder()
    {
        return $this->queryBuilder;
    }

    /**
     * Get all filters.
     *
     * @return array
     */
    protected function filters()
    {
        $filters = $this->request->query();

        ksort($filters);

        return $filters;
    }
}
