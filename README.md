# Advanced Query Filter

Advanced Query Filter for [Laravel](https://laravel.com/).

[![Build Status](https://api.travis-ci.org/fvsoft/advanced-query-filter.svg)](https://travis-ci.org/fvsoft/advanced-query-filter)
[![Total Downloads](https://poser.pugx.org/fvsoft/advanced-query-filter/d/total.svg)](https://packagist.org/packages/fvsoft/advanced-query-filter)
[![Latest Stable Version](https://poser.pugx.org/fvsoft/advanced-query-filter/v/stable.svg)](https://packagist.org/packages/fvsoft/advanced-query-filter)
[![License](https://poser.pugx.org/fvsoft/advanced-query-filter/license.svg)](https://packagist.org/packages/fvsoft/advanced-query-filter)

## Requirements

- PHP >= 7.1.5
- Laravel >= 5.6.*

## Installation

Require this package with composer.

```bash
composer require fvsoft/advanced-query-filter
```

## Basic usage

Add trait to model:

```php
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

class PostFilters extends QueryFilter
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
use App\Http\Filters\PostFilters;
use App\Post;

class PostController extends Controller
{
    public function index(PostFilters $filters)
    {
        // Now apply filters to Post.
        $posts = Post::applyFilters($filters)->get();

        // Send it to view.
        return view('posts.index', compact('posts'));
    }
}
```

## Avaiable traits

### Sortable

Allow to order items, you must add `$sortable` property:

```php
use FVSoft\QueryFilter\Sortable;

class PostFilters extends QueryFilter
{
    use Sortable;

    protected $sortable = [
        'id', 'title',
    ];
}
```

## Credits

- [Dinh Quoc Han](https://github.com/dinhquochan)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
