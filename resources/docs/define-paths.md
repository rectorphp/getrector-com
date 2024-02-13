You can define paths to process in the `rector.php` config:

```php
<?php

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src/SingleFile.php',
        __DIR__ . '/src/WholeDirectory',
    ]);
```

Or in the command line, in case you want to run Rector on single file right now:

```bash
vendor/bin/rector process src/SingleFile.php
```

<br>

Do you have root files like `ecs.php`, `scoper.php`, `rector.php` etc. and do you want to check them too?

```php
<?php

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRootFiles();
```

## File Suffixes

Rector is looking for `*.php` files only out of the box. If you want to process other file suffixes, use `fileExtensions` config:

```php
<?php

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withFileExtensions(['php', 'phtml']);
```
