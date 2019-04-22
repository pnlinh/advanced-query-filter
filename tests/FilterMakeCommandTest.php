<?php

namespace FVSoft\QueryFilter\Tests;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class FilterMakeCommandTest extends TestCase
{
    /** @test */
    public function it_can_create_a_filter()
    {
        $exitCode = Artisan::call('make:filter', [
            'name' => 'PostFilter',
            '--force' => true,
        ]);

        $this->assertEquals(0, $exitCode);

        $this->assertContains('Filter created successfully.', Artisan::output());

        $shouldOutputFilePath = $this->app['path'].'/Http/Filters/PostFilter.php';

        $this->assertTrue(File::exists($shouldOutputFilePath), 'File exists in default app/Http/Filters folder');

        $contents = File::get($shouldOutputFilePath);

        $this->assertContains('namespace App\Http\Filters;', $contents);

        $this->assertContains('class PostFilter extends QueryFilter', $contents);
    }

    /** @test */
    public function it_can_create_a_filter_with_a_custom_namespace()
    {
        $exitCode = Artisan::call('make:filter', [
            'name' => 'Blog/CategoryFilter',
            '--force' => true,
        ]);

        $this->assertEquals(0, $exitCode);

        $this->assertContains('Filter created successfully.', Artisan::output());

        $shouldOutputFilePath = $this->app['path'].'/Blog/CategoryFilter.php';

        $this->assertTrue(File::exists($shouldOutputFilePath), 'File exists in custom app/Blog folder');

        $contents = File::get($shouldOutputFilePath);

        $this->assertContains('namespace App\Blog;', $contents);

        $this->assertContains('class CategoryFilter extends QueryFilter', $contents);
    }
}
