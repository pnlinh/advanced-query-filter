# Laravel Advanced Query Filter

Laravel Advanced Query Filter for [Laravel](https://laravel.com/).

[![Build Status](https://api.travis-ci.org/fvsoft/laravel-advanced-query-filter.svg)](https://travis-ci.org/fvsoft/laravel-advanced-query-filter)
[![Total Downloads](https://poser.pugx.org/fvsoft/laravel-advanced-query-filter/d/total.svg)](https://packagist.org/packages/fvsoft/laravel-advanced-query-filter)
[![Latest Stable Version](https://poser.pugx.org/fvsoft/laravel-advanced-query-filter/v/stable.svg)](https://packagist.org/packages/fvsoft/laravel-advanced-query-filter)
[![License](https://poser.pugx.org/fvsoft/laravel-advanced-query-filter/license.svg)](https://packagist.org/packages/fvsoft/laravel-advanced-query-filter)

## Requirements

- PHP >= 7.1.5
- Laravel >= 5.6.*

## Installation

Require this package with composer.

```bash
composer require fvsoft/laravel-advanced-query-filter
```

## Basic usage

Add trait to model:

```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use FVSoft\QueryFilter\Filterable;

class Post extends Model
{
    use Filterable;
}
```

Create basic filter `app/Filters/PostFilters.php`:

```php
<?php

namespace App\Http\Filters;

use FVSoft\QueryFilter\QueryFilter;

class PostFilter extends QueryFilter
{
    /**
     * user_id filter.
     *
     * @param  int  $id
     */
    public function userId($id)
    {
        $this->getBuilder()->whereUserId($id);
    }
}
```

In `PostController`:

```php
<?php

namespace App\Http\Controllers;

use App\Http\Filters\PostFilter;
use App\Post;

class PostController extends Controller
{
    public function index(PostFilter $filter)
    {
        // Now apply filters to Post.
        $posts = Post::applyFilters($filter)->get();

        // Send it to view.
        return view('posts.index', compact('posts'));
    }
}
```

### Making a new filter

The package included an artisan command to create a new filter.

```bash
php artisan make:filter PostFilter
```

This filter will have the `App\Http\Filters` namespace and will be saved in `app/Http/Filters`.

or into a custom namespace, say, `App\Blog`

```bash
php artisan make:filter "Blog/PostFilter"
```

This filter will have the `App\Blog` namespace and will be saved in `app/Blog`.


## Available traits

### Sortable

Allow to order items, you must add `$sortable` property:

```php
<?php

namespace App\Http\Filters;

use FVSoft\QueryFilter\QueryFilter;
use FVSoft\QueryFilter\Sortable;

class PostFilter extends QueryFilter
{
    use Sortable;

    protected $sortable = [
        'id', 'title',
    ];
}
```

Demo:

```
// your-url?sort_by=id
// SELECT * FROM `posts` ORDER BY `id` ASC

// your-url?sort_by=id&sort=desc
// SELECT * FROM `posts` ORDER BY `id` DESC
```

### Searchable

Allow to search items, you must add `$searchable` property:

```php
<?php

namespace App\Http\Filters;

use FVSoft\QueryFilter\QueryFilter;
use FVSoft\QueryFilter\Searchable;

class PostFilter extends QueryFilter
{
    use Searchable;

    protected $searchable = [
        'id', 'title',
    ];
}
```

Demo:

```
// your-url?search=foo or your-url?q=foo
// SELECT * FROM `posts` WHERE (`id` LIKE '%foo%' OR `title` LIKE '%foo%')

// your-url?search=foo*
// SELECT * FROM `posts` WHERE (`id` LIKE 'foo%' OR `title` LIKE 'foo%')

// your-url?search=*foo
// SELECT * FROM `posts` WHERE (`id` LIKE '%foo' OR `title` LIKE '%foo')

// your-url?search=foo&search_by=title
// SELECT * FROM `posts` WHERE `title` LIKE '%foo%'
```

## Credits

- [Đinh Quốc Hân](https://github.com/dinhquochan)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
