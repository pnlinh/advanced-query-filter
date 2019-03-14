<?php

namespace FVSoft\QueryFilter\Tests;

use FVSoft\QueryFilter\Filterable;
use FVSoft\QueryFilter\QueryFilter;
use FVSoft\QueryFilter\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class SearchableTest extends TestCase
{
    /** @test */
    public function searchable_basic()
    {
        $fakeRequest = new Request(['search' => 'foo']);

        $testModelFiters = new TestModelWithSearchableFiters($fakeRequest);

        $testModel = TestModelWithSearchable::applyFilters($testModelFiters)->toSql();


        $expected = TestModelWithSearchable::where(function ($query) {
            $query->orWhere('title', 'like', '%foo%');

            $query->orWhere('tags', 'like', '%foo%');
        })->toSql();

        $this->assertEquals($testModel, $expected);

        // Alias
        $fakeRequestAlias = new Request(['q' => 'foo']);

        $testModelFitersAlias = new TestModelWithSearchableFiters($fakeRequest);

        $testModelAlias = TestModelWithSearchable::applyFilters($testModelFitersAlias)->toSql();

        $this->assertEquals($testModelAlias, $expected);
    }

    /** @test */
    public function it_searchable_starts_with()
    {
        $fakeRequest = new Request(['search' => 'foo*']);

        $testModelFiters = new TestModelWithSearchableFiters($fakeRequest);

        $testModel = TestModelWithSearchable::applyFilters($testModelFiters)->toSql();

        $expected = TestModelWithSearchable::where(function ($query) {
            $query->orWhere('title', 'like', 'foo%');

            $query->orWhere('tags', 'like', 'foo%');
        })->toSql();

        $this->assertEquals($testModel, $expected);
    }

    /** @test */
    public function it_searchable_ends_with()
    {
        $fakeRequest = new Request(['search' => '*foo']);

        $testModelFiters = new TestModelWithSearchableFiters($fakeRequest);

        $testModel = TestModelWithSearchable::applyFilters($testModelFiters)->toSql();

        $expected = TestModelWithSearchable::where(function ($query) {
            $query->orWhere('title', 'like', '%foo');

            $query->orWhere('tags', 'like', '%foo');
        })->toSql();

        $this->assertEquals($testModel, $expected);
    }

    /** @test */
    public function it_searchable_specific_column()
    {
        $fakeRequest = new Request(['search' => 'foo', 'search_by' => 'title']);

        $testModelFiters = new TestModelWithSearchableFiters($fakeRequest);

        $testModel = TestModelWithSearchable::applyFilters($testModelFiters)->toSql();

        $expected = TestModelWithSearchable::where('title', 'like', '%foo%')->toSql();

        $this->assertEquals($testModel, $expected);
    }

    /** @test */
    public function it_can_not_searchable_not_exists_column()
    {
        $fakeRequest = new Request(['search' => 'foo', 'search_by' => 'bar']);

        $testModelFiters = new TestModelWithSearchableFiters($fakeRequest);

        $testModel = TestModelWithSearchable::applyFilters($testModelFiters)->toSql();

        $expected = TestModelWithSearchable::query()->toSql();

        $this->assertEquals($testModel, $expected);
    }
}

class TestModelWithSearchable extends Model
{
    use Filterable;
}

class TestModelWithSearchableFiters extends QueryFilter
{
    use Searchable;

    protected $searchable = ['title', 'tags'];
}
