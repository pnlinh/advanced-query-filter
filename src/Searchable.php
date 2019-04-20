<?php

namespace FVSoft\QueryFilter;

use Illuminate\Support\Str;

trait Searchable
{
    protected function searchable(): array
    {
        return property_exists($this, 'searchable') ? $this->searchable : [];
    }

    public function search(string $keyword)
    {
        if (!$this->searchable() || !$keyword) {
            return;
        }

        $modifiedKeyword = $this->modifiedKeyword($keyword);

        if ($this->shouldSearchSpecificColumn()) {
            if ($this->shouldSpecificColumnMustBeExists()) {
                $this->getBuilder()->where($this->getRequest()->query('search_by'), 'like', $modifiedKeyword);
            }

            return;
        }

        $this->getBuilder()->where(function ($query) use ($modifiedKeyword) {
            foreach ($this->searchable() as $column) {
                $query->orWhere($column, 'like', $modifiedKeyword);
            }
        });
    }

    public function q(string $keyword)
    {
        $this->search($keyword);
    }

    protected function modifiedKeyword(string $keyword): string
    {
        $endSearch = Str::startsWith($keyword, '*');
        $startSearch = Str::endsWith($keyword, '*');

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

    protected function shouldSearchSpecificColumn(): bool
    {
        return $this->getRequest()->filled('search_by');
    }

    protected function shouldSpecificColumnMustBeExists(): bool
    {
        return  in_array($this->getRequest()->query('search_by'), $this->searchable());
    }
}
