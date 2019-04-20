<?php

namespace FVSoft\QueryFilter;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class QueryFilter
{
    /** @var \Illuminate\Http\Request */
    protected $request;

    /** @var \Illuminate\Database\Eloquent\Builder */
    protected $builder;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        foreach ($this->getFilters() as $filter => $value) {
            $filter = Str::camel($filter);

            if (method_exists($this, $filter)) {
                call_user_func([$this, $filter], $value);
            }
        }

        return $this->getBuilder();
    }

    public function getBuilder(): Builder
    {
        return $this->builder;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    protected function getFilters(): array
    {
        $filters = $this->getRequest()->query();

        ksort($filters);

        return $filters;
    }
}
