# Laravel Cache Attr
This is package of attributes for Laravel.
Supported attributes are here.

* [Cacheable](./src/Attributes/Cacheable.php)

See also [example](./example/).

## Install

```bash
composer require morimorim/laravel-cache-attr
```

## Usage

Specify attributes like following.

```php
use Laravel\Cache\Attribute\Cacheable;

class ExampleService
{
    #[Cacheable(name: 'ExampleService#heavyProcess', ttl_seconds: 60)]
    public function heavyProcess(int $sleep)
    {
        sleep($sleep);
        return Carbon::now();
    }
}
```