## Spacing and Indents

By default, Rector prints code with 4 spaces indent and unix newline.
If you have other preference, change it via `indent()` method:

```php
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->indent(' ', 4);
};
```
