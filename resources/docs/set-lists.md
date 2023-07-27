You can use particular single rules, or whole list of rules, called "set lists":

```php
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([SetList::DEAD_CODE]);

    $rectorConfig->paths([__DIR__ . '/src']);
};
```

That way you can include group of rules that focus on certain topic, e.g. in this case on dead detection. It makes config small and clear.

<br>

## Level Sets

How can you upgrade to the PHP 7.3?

```php
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([SetList::PHP_73]);

    $rectorConfig->paths([__DIR__ . '/src']);
};
```

That's one of way, but what about all previous PHP versions like PHP 7.2, 7.1, 7.0 etc.?

Instead of listing all the PHP version sets, you can use "level sets":

```php
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([LevelSetList::UP_TO_PHP_73]);

    $rectorConfig->paths([__DIR__ . '/src']);
};
```

That way you can use all the sets that are needed to upgrade your code to the desired PHP version in single line.
