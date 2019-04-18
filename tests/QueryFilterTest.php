<?php

namespace FVSoft\QueryFilter\Tests;

use FVSoft\QueryFilter\Filterable;
use FVSoft\QueryFilter\QueryFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class QueryFilterTest extends TestCase
{
    /** @test */
    public function scope_apply_filter()
    {
        $testModelFilters = new TestModelFilters(new Request());

        $testModel = TestModel::applyFilters($testModelFilters)->toSql();

        $expected = $testModelFilters->apply(TestModel::query())->toSql();

        $this->assertEquals($testModel, $expected);
    }
    /** @test */
    public function it_can_apply_query()
    {
        $testModelFilters = (new TestModelFilters(new Request()))->apply(TestModel::query())->toSql();

        $expected = TestModel::query()->toSql();

        $this->assertEquals($testModelFilters, $expected);
    }

    /** @test */
    public function it_apply_sample_filter()
    {
        $fakeRequest = new Request(['sample' => 'foo']);

        $testModelFilters = (new TestModelFilters($fakeRequest))->apply(TestModel::query())->toSql();

        $expected = TestModel::where('sample', 'foo')->toSql();

        $this->assertEquals($expected, $testModelFilters);
    }
}

class TestModel extends Model
{
    use Filterable;
}

class TestModelFilters extends QueryFilter
{
    public function sample($value)
    {
        $this->getBuilder()->where('sample', $value);
    }
}
