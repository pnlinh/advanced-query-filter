<?php

namespace FVSoft\QueryFilter;

trait Searchable
{
    /**
     * Get searchable columns.
     *
     * @return array
     */
    protected function searchable()
    {
        return property_exists($this, 'searchable') ? $this->searchable : [];
    }

    /**
     * Search filter.
     *
     * @param  string  $direction
     */
    public function search($keyword)
    {
        if (! $this->searchable() || !$keyword) {
            return;
        }

        $modifiedKeyword = $this->modifiedKeyword($keyword);

        if ($this->shouldSearchSpecificColumn()) {
            if ($this->shouldSpecificCOlumnMustExists()) {
                $this->getBuilder()->where($this->request->query('search_by'), 'like', $modifiedKeyword);
            }

            return;
        }

        $this->getBuilder()->where(function ($query) use ($modifiedKeyword) {
            foreach ($this->searchable() as $column) {
                $query->orWhere($column, 'like', $modifiedKeyword);
            }
        });
    }

    /**
     * Alias for "search" method.
     *
     * @param  string  $keyword
     */
    public function q($keyword)
    {
        $this->search($keyword);
    }

    /**
     * Convert keyword to sql like string.
     *
     * @param  string  $keyword
     * @return string
     */
    protected function modifiedKeyword($keyword)
    {
        $endSearch = starts_with($keyword, '*');
        $startSearch = ends_with($keyword, '*');

        if (!$endSearch && $startSearch){
            return '%'.$keyword.'%';
        }

        if ($endSearch) {
            return '%'.ltrim($keyword, '*');
        }

        if ($startSearch) {
            return rtrim($keyword, '*').'%';
        }

        return $keyword;
    }

    /**
     * Should search specific column.
     *
     * @return bool
     */
    protected function shouldSearchSpecificColumn()
    {
        return $this->request->filled('search_by');
    }

    /**
     * Ensure specific column must be exists.
     *
     * @return bool
     */
    protected function shouldSpecificCOlumnMustExists()
    {
        return  in_array($this->request->query('search_by'), $this->searchable());
    }
}
