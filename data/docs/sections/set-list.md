@todo set list

@todo level set list


Are looking for the downgrade categories? There is the `DowngradeSetList`:

```php
use Rector\Set\ValueObject\DowngradeSetList;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([DowngradeSetList::PHP_70])

    $rectorConfig->paths([__DIR__ . '/src']);
};
```
