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
        $testModelFiters = (new TestModelFiters(new Request()));
        $testModel = TestModel::applyFilters($testModelFiters)->toSql();
        $expected = $testModelFiters->apply(TestModel::query())->toSql();

        $this->assertEquals($testModel, $expected);
    }
    /** @test */
    public function it_can_apply_query()
    {
        $testModelFiters = (new TestModelFiters(new Request()))->apply(TestModel::query())->toSql();
        $expected = TestModel::query()->toSql();

        $this->assertEquals($testModelFiters, $expected);
    }

    /** @test */
    public function it_apply_sample_filter()
    {
        $fakeRequest = new Request(['sample' => 'foo']);
        $testModelFiters = (new TestModelFiters($fakeRequest))->apply(TestModel::query())->toSql();
        $expected = TestModel::where('sample', 'foo')->toSql();

        $this->assertEquals($expected, $testModelFiters);
    }
}

class TestModel extends Model
{
    use Filterable;
}

class TestModelFiters extends QueryFilter
{
    public function sample($value)
    {
        $this->getBuilder()->where('sample', $value);
    }
}
