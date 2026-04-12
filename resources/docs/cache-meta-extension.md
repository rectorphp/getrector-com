Rector caches processed files and skips them on subsequent runs if nothing has changed. By default, cache invalidation is based on file content, Rector configuration, registered rules, and Rector version.

But what if your rule depends on **external state** — a database schema, an API response, a config file outside `rector.php`, or an environment variable? Rector has no way of knowing that state changed, so it serves stale cache results.

The `CacheMetaExtensionInterface` solves this. It lets you provide custom metadata that Rector includes in its cache key computation. When your metadata hash changes, Rector re-processes all files.

## 1. Implement the Interface

Create a class that implements `CacheMetaExtensionInterface`:

```php
use Rector\Caching\Contract\CacheMetaExtensionInterface;

final class DatabaseSchemaCacheExtension implements CacheMetaExtensionInterface
{
    public function getKey(): string
    {
        return 'database-schema';
    }

    public function getHash(): string
    {
        // return a hash that represents the current state
        return sha1(file_get_contents(__DIR__ . '/schema.sql'));
    }
}
```

The `getKey()` method returns a unique identifier for this metadata source. The `getHash()` method returns a value representing the current state — when it changes, the cache is invalidated.

## 2. Register in `rector.php`

```php
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([...])
    ->withCacheMetaExtension(DatabaseSchemaCacheExtension::class);
```

<br>

## Use Cases

Here are some practical examples of when to use a cache meta extension:

* **External config files** — hash a YAML/JSON config that your rule reads
* **Database schema** — hash migration files or a schema dump
* **Environment variables** — hash environment values that affect rule behavior
* **API versioning** — hash an API version string that your rule depends on
