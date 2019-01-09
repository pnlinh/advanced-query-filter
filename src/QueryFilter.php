<?php

namespace FVSoft\QueryFilter;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class QueryFilter
{
    protected $request;

    protected $queryBuilder;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $query)
    {
        $this->queryBuilder = $query;

        foreach ($this->filters() as $filter => $value) {
            if (method_exists($this, $filter)) {
                call_user_func([$this, $filter], $value);
            }
        }

        return $this->getBuilder();
    }

    public function getBuilder()
    {
        return $this->queryBuilder;
    }

    protected function filters()
    {
        return $this->request->query();
    }
}
