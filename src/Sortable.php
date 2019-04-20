<?php

namespace FVSoft\QueryFilter;

trait Sortable
{
    /** @var string */
    protected $sortDirection = 'asc';

    protected function sortable(): array
    {
        return property_exists($this, 'sortable') ? $this->sortable : [];
    }

    public function sort(string $direction = 'asc')
    {
        $this->sortDirection = ($direction === 'asc') ? 'asc' : 'desc';
    }

    public function sortBy(string $column)
    {
        if (in_array($column, $this->sortable())) {
            $this->getBuilder()->orderBy($column, $this->sortDirection);
        }
    }
}
