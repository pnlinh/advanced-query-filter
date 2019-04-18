<?php

namespace FVSoft\QueryFilter\Tests;

use FVSoft\QueryFilter\Filterable;
use FVSoft\QueryFilter\QueryFilter;
use FVSoft\QueryFilter\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class SortableTest extends TestCase
{
    /** @test */
    public function sortable_trait()
    {
        $fakeRequest = new Request(['sort_by' => 'id', 'sort' => 'desc']);

        $testModelFilters = new TestModelWithSortableFilters($fakeRequest);

        $testModel = TestModelWithSortable::applyFilters($testModelFilters)->toSql();

        $expected = TestModelWithSortable::orderBy('id', 'desc')->toSql();

        $this->assertEquals($testModel, $expected);
    }
}

class TestModelWithSortable extends Model
{
    use Filterable;
}

class TestModelWithSortableFilters extends QueryFilter
{
    use Sortable;

    protected $sortable = ['id'];
}
