<?php

namespace FVSoft\QueryFilter\Providers;

use Illuminate\Support\ServiceProvider;
use FVSoft\QueryFilter\Console\FilterMakeCommand;

class AdvancedQueryFilterServiceProvider extends ServiceProvider
{
    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                FilterMakeCommand::class,
            ]);
        }
    }
}
