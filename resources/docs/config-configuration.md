## Provide Config

By default, Rector picks `rector.php` in your root directory as a configuration file. To change that, use `--config` option in CLI run:

```bash
vendor/bin/rector process --config rector-custom-config.php
```

## Spacing and Indents

By default, Rector prints code with 4 spaces indent and unix newline.
If you have other preference, change it via `indent()` method:

```php
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->indent(' ', 4);
};
```
