You can use particular single rules, or whole list of rules, called "set lists":

```php
<?php

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPreparedSets(deadCode: true, codeQuality: true);
```

That way you can include group of rules that focus on certain topic, e.g. in this case on dead detection. It makes config small and clear.

## PHP Sets

How can you upgrade to the PHP 7.3?

```php
<?php

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPhpSets(php73: true);
```

That way you can use all the sets that are needed to upgrade your code to the desired PHP version in single line.

## PHP 8.0 Attributes

Do you want to migrate your annotations to native PHP 8.0 attributes?

```diff
 use Doctrine\ORM\Mapping as ORM;

-/**
- * @ORM\Entity
- */
+#[ORM\Entity]
 class SomeEntity
 {
 }
```

You can pick the groups you need with `withAttributesSets()` method:

```php
<?php

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withAttributesSets(symfony: true, doctrine: true);
```

## Community or External Sets

How can I use Rector with community sets or my custom one?

```php
<?php

use Rector\Config\RectorConfig;
use Rector\Doctrine\Set\DoctrineSetList;

return RectorConfig::configure()
    ->withSets([
        DoctrineSetList::DOCTRINE_CODE_QUALITY,
        __DIR__ '/config/rector-custom-set.php'
    ]);
```
