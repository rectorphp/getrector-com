You can define paths to process in the `rector.php` config:

```php
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src/SingleFile.php',
        __DIR__ . '/src/WholeDirectory',
    ]);
};
```

Or in the command line, in case you want to run Rector on single file right now:

```bash
vendor/bin/rector process src/SingleFile.php
```
