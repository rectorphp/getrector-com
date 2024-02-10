Do you want to skip whole directory or just single rule? Use `skip()` method:

```php
<?php

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withSkip([
        __DIR__ . '/src/SingleFile.php',
        __DIR__ . '/src/WholeDirectory',
        // or use fnmatch
        __DIR__ . '/src/*/Tests/*',
    ]);
```

Do you want to skip Rector rule?

```php
<?php

use Rector\Config\RectorConfig;
use Rector\CodeQuality\Rector\If_\SimplifyIfReturnBoolRector;

return RectorConfig::configure()
    ->withSkip([
        SimplifyIfReturnBoolRector::class,
    ]);
```

Do you want to skip specific rule only in a specific file?

```php
<?php

use Rector\Config\RectorConfig;
use Rector\CodeQuality\Rector\If_\SimplifyIfReturnBoolRector;

return RectorConfig::configure()
    ->withSkip([
        SimplifyIfReturnBoolRector::class => [
            __DIR__ . '/src/ComplicatedFile.php',
        ],
    ]);
```
