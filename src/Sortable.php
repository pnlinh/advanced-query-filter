<?php

namespace FVSoft\QueryFilter;

trait Sortable
{
    /**
     * Sort direction.
     *
     * @var string
     */
    protected $sortDirection = 'asc';

    /**
     * Get sortable column.
     * @return array
     */
    protected function sortable()
    {
        return property_exists($this, 'sortable') ? $this->sortable : [];
    }

    /**
     * Sort direction filter.
     *
     * @param  string  $direction
     */
    public function sort($direction = 'asc')
    {
        $this->sortDirection = ($direction === 'asc') ? 'asc' : 'desc';
    }

    /**
     * Sort by filter.
     *
     * @param  string  $column
     */
    public function sortBy($column)
    {
        if (in_array($column, $this->sortable())) {
            $this->getBuilder()->orderBy($column, $this->sortDirection);
        }
    }
}
