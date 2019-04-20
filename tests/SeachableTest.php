<?php

namespace FVSoft\QueryFilter\Tests;

use FVSoft\QueryFilter\Filterable;
use FVSoft\QueryFilter\QueryFilter;
use FVSoft\QueryFilter\Searchable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class SearchableTest extends TestCase
{
    /** @test */
    public function searchable_basic()
    {
        $fakeRequest = new Request(['search' => 'foo']);

        $testModelFilters = new TestModelWithSearchableFilters($fakeRequest);

        $testModel = TestModelWithSearchable::query()->applyFilters($testModelFilters)->toSql();

        $expected = TestModelWithSearchable::query()->where(function (Builder $query) {
            $query->orWhere('title', 'like', '%foo%');

            $query->orWhere('tags', 'like', '%foo%');
        })->toSql();

        $this->assertEquals($testModel, $expected);

        // Alias
        $fakeRequestAlias = new Request(['q' => 'foo']);

        $testModelFiltersAlias = new TestModelWithSearchableFilters($fakeRequest);

        $testModelAlias = TestModelWithSearchable::query()->applyFilters($testModelFiltersAlias)->toSql();

        $this->assertEquals($testModelAlias, $expected);
    }

    /** @test */
    public function it_searchable_starts_with()
    {
        $fakeRequest = new Request(['search' => 'foo*']);

        $testModelFilters = new TestModelWithSearchableFilters($fakeRequest);

        $testModel = TestModelWithSearchable::query()->applyFilters($testModelFilters)->toSql();

        $expected = TestModelWithSearchable::query()->where(function (Builder $query) {
            $query->orWhere('title', 'like', 'foo%');

            $query->orWhere('tags', 'like', 'foo%');
        })->toSql();

        $this->assertEquals($testModel, $expected);
    }

    /** @test */
    public function it_searchable_ends_with()
    {
        $fakeRequest = new Request(['search' => '*foo']);

        $testModelFilters = new TestModelWithSearchableFilters($fakeRequest);

        $testModel = TestModelWithSearchable::query()->applyFilters($testModelFilters)->toSql();

        $expected = TestModelWithSearchable::query()->where(function (Builder $query) {
            $query->orWhere('title', 'like', '%foo');

            $query->orWhere('tags', 'like', '%foo');
        })->toSql();

        $this->assertEquals($testModel, $expected);
    }

    /** @test */
    public function it_searchable_specific_column()
    {
        $fakeRequest = new Request(['search' => 'foo', 'search_by' => 'title']);

        $testModelFilters = new TestModelWithSearchableFilters($fakeRequest);

        $testModel = TestModelWithSearchable::query()->applyFilters($testModelFilters)->toSql();

        $expected = TestModelWithSearchable::query()->where('title', 'like', '%foo%')->toSql();

        $this->assertEquals($testModel, $expected);
    }

    /** @test */
    public function it_can_not_searchable_not_exists_column()
    {
        $fakeRequest = new Request(['search' => 'foo', 'search_by' => 'bar']);

        $testModelFilters = new TestModelWithSearchableFilters($fakeRequest);

        $testModel = TestModelWithSearchable::query()->applyFilters($testModelFilters)->toSql();

        $expected = TestModelWithSearchable::query()->toSql();

        $this->assertEquals($testModel, $expected);
    }
}

class TestModelWithSearchable extends Model
{
    use Filterable;
}

class TestModelWithSearchableFilters extends QueryFilter
{
    use Searchable;

    protected $searchable = ['title', 'tags'];
}
