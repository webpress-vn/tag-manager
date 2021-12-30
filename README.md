# Tag Manager Package for Laravel

- [Tag Manager Package for Laravel](#tag-manager-package-for-laravel)
    - [Installation](#installation)
        - [Composer](#composer)
    - [Configuration](#configuration)
        - [Model and Transformer](#model-and-transformer)
        - [Auth middleware](#auth-middleware)
    - [Query functions provide](#query-functions-provide)
        - [List of query functions](#list-of-query-functions)
        - [Use](#use)
        - [For example](#for-example)
    - [Routes](#routes)

## Installation

### Composer

To include the package in your project, Please run following command.

```
composer require webpress/tag-manager
```


## Configuration

### Model and Transformer

You can use your own model and transformer class by modifying the configuration file `config\tag.php`

```php
'models'          => [
    'tag' => App\Entities\Tag::class,
],

'transformers'    => [
    'tag' => App\Transformers\TagTransformer::class,
],
```
Your `Tag` model class must implements `VCComponent\Laravel\Tag\Contracts\TagSchema` and `VCComponent\Laravel\Tag\Contracts\TagManagement`

### Auth middleware

Configure auth middleware in configuration file `config\tag.php`

```php
'auth_middleware' => [
        'admin'    => [
            'middleware' => 'jwt.auth',
            'except'     => ['index'],
        ],
        'frontend' => [
            'middleware' => 'jwt.auth',
            'except'     => ['index'],
        ],
],
```

## Query functions provide

### List of query functions

Scope a query to only include published tags.
```php
public function scopeIsPublished($query)
```

Scope a query to sort tags by name column.
```php
public function scopeSortByName($query, $order = 'asc')
```

Scope a query to sort tags by usage time. From hight to low.
```php
public function scopeMostUsed($query, $tagable_type = null) 
```
Scope a query to sort tags by usage time. From low to hight.
```php
public function scopeLeastUsed($query, $taggable_type = null)
```


### Use

Use Trait.
```php
namespace App\Model;

use VCComponent\Laravel\Tag\Traits\TagQueryTrait;

class Tag 
{
    use TagQueryTrait;
    \\
}
```

Extend `VCComponent\Laravel\Tag\Entities\Tag` Entity.
```php
namespace App\Model;

use VCComponent\Laravel\Tag\Entities\Tag as BaseTag;

class Tag extends BaseTag
{
    \\
}
```

### For example

```php
$category = Tag::isPublished()->mostUsed()->get();
```

## Routes

The api endpoint should have these format:
| Verb   | URI                                            |
| ------ | ---------------------------------------------- |
| GET    | /api/admin/tags                                |
| GET    | /api/admin/tags/all                            |
| GET    | /api/admin/tags/{id}                           |
| POST   | /api/admin/tags                                |
| PUT    | /api/admin/tags/{id}                           |
| DELETE | /api/admin/tags/{id}                           |
| PUT    | /api/admin/tags/status/bulk                    |
| PUT    | /api/admin/tags/status/{id}                    |
| ----   | ----                                           |
| GET    | /api/tags/all                                  |
| GET    | /api/tags/{id}                                 |
| POST   | /api/tags                                      |
| PUT    | /api/tags/{id}                                 |
| DELETE | /api/tags/{id}                                 |