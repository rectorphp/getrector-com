Rector is working with PHP features of your project and uses only features, compatible with your code. That means it will not add attributes, unless you're at least on PHP 8.

The best practise is to let pickup the PHP version from `composer.json`:

```json
{
    "require": {
        "php": "^7.4"
    }
}
```

<br>

If it's not there, Rector look into other places:

* PHP version defined in `rector.php`
* `composer.json` require of PHP
* `composer.json` config platform of PHP
* the current PHP version runtime

<br>

If you want to force different PHP version than your codebase, you can do it at your own risk in `rector.php`:

```php
<?php

use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
    ->withPhpVersion(PhpVersion::PHP_80);
```
