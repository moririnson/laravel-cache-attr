<?php

namespace Laravel\Cache;

use Laravel\Cache\Attribute\Cacheable;
use Laravel\Cache\Factories\WrapperFactory;
use Laravel\Cache\Wrappers\CacheableWrapper;
use RecursiveIteratorIterator;
use ReflectionClass;

class LaravelCacheAttrServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected $priority = PHP_INT_MAX;

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/laravel-cache-attr.php',
            'line-bot'
        );
        $this->app->bind(WrapperFactory::class, fn () => new WrapperFactory());
        $this->bindWrappers();
    }

    private function bindWrappers()
    {
        $recursive_directory_iterator = new \RecursiveDirectoryIterator(
            \app_path(),
            \FilesystemIterator::SKIP_DOTS 
            | \FilesystemIterator::KEY_AS_PATHNAME
            | \FilesystemIterator::CURRENT_AS_FILEINFO
        );
        $iterator = new RecursiveIteratorIterator($recursive_directory_iterator, RecursiveIteratorIterator::LEAVES_ONLY);
        foreach ($iterator as $filename) {
            if (!str_ends_with($filename, '.php')) {
                continue;
            }
            
            $this->bindWrapper($filename, Cacheable::class);
        }
    }

    private function bindWrapper(string $filename, string $attribute_class)
    {
        $namespace = str_replace('/', '\\', 
            str_replace('.php', '', 
                str_replace(\app_path(), 'App',
                    $filename,
                ),
            ),
        );
        $reflection_class = new ReflectionClass('\\' . $namespace);
        $methods = array_filter($reflection_class->getMethods(), fn ($method) => count($method->getAttributes($attribute_class)) > 0);
        if (empty($methods)) {
            return;
        }
        $this->app->bind($namespace, function ($app) use ($namespace, $attribute_class) {
            $reflection = new ReflectionClass('\\' . $namespace);
            $constructor = $reflection->getConstructor();
            $params = isset($constructor) ? $constructor->getParameters() : [];
            $instances = [];
            foreach ($params as $param) {
                $instances[] = $app->make($param->getClass()->name);
            }
            $original = new $namespace(...$instances);
            return $app->make(WrapperFactory::class)->create($reflection, $attribute_class, $original);
        });
    }
}