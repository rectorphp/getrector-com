Rector rules that implement `Rector\Contract\Rector\ConfigurableRectorInterface` can be configured.

Typical example is `Rector\Renaming\Rector\Name\RenameClassRector`:

```php
<?php

use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withConfiguredRule(RenameClassRector::class, [
        'App\SomeOldClass' => 'App\SomeNewClass',
    ]);
```
