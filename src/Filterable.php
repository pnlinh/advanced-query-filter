<?php

namespace FVSort\QueryFilter;

use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    /**
     * Apply filters scope.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \FVSort\QueryFilter\QueryFilter $filter
     * @return mixed
     */
    public function scopeApplyFilters(Builder $query, QueryFilter $filter)
    {
        return $filter->apply($query);
    }
}
