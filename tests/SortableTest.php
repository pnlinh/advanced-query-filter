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

        $testModelFiters = new TestModelWithSortableFiters($fakeRequest);

        $testModel = TestModelWithSortable::applyFilters($testModelFiters)->toSql();

        $expected = TestModelWithSortable::orderBy('id', 'desc')->toSql();

        $this->assertEquals($testModel, $expected);
    }
}

class TestModelWithSortable extends Model
{
    use Filterable;
}

class TestModelWithSortableFiters extends QueryFilter
{
    use Sortable;

    protected $sortable = ['id'];
}
