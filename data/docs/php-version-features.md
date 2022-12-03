Rector is working with PHP features of your project. That means it will not add attributes, unless you're at least on PHP 8.

It looks for PHP version in multiple places. If it does not find the version in first place, it looks for in the next one:

* PHP version defined in `rector.php`
* `composer.json` require of PHP
* `composer.json` config platform of PHP
* the current PHP version runtime

The highest priority is the current PHP version you define in `rector.php`:

```php
use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersion;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->phpVersion(PhpVersion::PHP_80);
};
```

Use `PhpVersion` enum to define the value there.
