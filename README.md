# Advanced Query Filter

Advanced Query Filter for [Laravel](https://laravel.com/).

[![Build Status](https://api.travis-ci.org/fvsort/advanced-query-filter.svg)](https://travis-ci.org/fvsort/advanced-query-filter)
[![Total Downloads](https://poser.pugx.org/fvsort/advanced-query-filter/d/total.svg)](https://packagist.org/packages/fvsort/advanced-query-filter)
[![Latest Stable Version](https://poser.pugx.org/fvsort/advanced-query-filter/v/stable.svg)](https://packagist.org/packages/fvsort/advanced-query-filter)
[![License](https://poser.pugx.org/fvsort/advanced-query-filter/license.svg)](https://packagist.org/packages/fvsort/advanced-query-filter)

## Requirements

- PHP >= 7.1.5
- Laravel >= 5.6.*

## Installation

Require this package with composer.

```bash
composer require fvsort/advanced-query-filter
```

## Basic usage

Add trait to model:

```php
use Illuminate\Database\Eloquent\Model;
use FVSort\QueryFilter\Filterable;

class Post extends Model
{
    use Filterable;
}
```

Create basic filter `app/Filters/PostFilters.php`:

```php
<?php

namespace App\Filters;

use FVSort\QueryFilter\QueryFilter;

class PostFilters extends QueryFilter
{
    /**
     * `userId` filter.
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
use App\Filters\PostFilters;
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

## Credits

- [Dinh Quoc Han](https://github.com/dinhquochan)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
