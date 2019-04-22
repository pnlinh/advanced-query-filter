<?php

namespace FVSoft\QueryFilter\Tests;

use FVSoft\QueryFilter\Providers\AdvancedQueryFilterServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [AdvancedQueryFilterServiceProvider::class];
    }
}
