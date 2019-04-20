<?php

namespace FVSoft\QueryFilter;

use Illuminate\Database\Eloquent\Builder;

/**
 * Filterable Trait.
 *
 * @method \Illuminate\Database\Eloquent\Builder applyFilters(\FVSoft\QueryFilter\QueryFilter $filter)
 */
trait Filterable
{
    public function scopeApplyFilters(Builder $builder, QueryFilter $filter): Builder
    {
        return $filter->apply($builder);
    }
}
