## Prepared Sets

You can use particular single rules, or whole list of rules, called "set lists":

```php
<?php

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        codingStyle: true,
        naming: true,
        privatization: true,
        typeDeclarations: true,
        rectorPreset: true,
        // ...
    );
```

That way you can include group of rules that focus on certain topic, e.g. in this case on dead detection. It makes config small and clear.

Try autocomplete in your IDE to see all available prepared sets.

## PHP Sets

The best practise is to use PHP version defined in `composer.json`. Rector will automatically pick it up with empty `->withPhpSets()` method:

```php
<?php

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPhpSets();
```

<br>

**Are you on a legacy project** and want to upgrade set by set first? Use one by one from `PHP_53` up to `PHP_74`:

```php
<?php

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;

return RectorConfig::configure()
    ->withSets([SetList::PHP_73]);
```

> If you're on PHP 8+, you can use `->withPhpSets()` with named arguments:
>
> ```diff
> -   ->withSets([SetList::PHP_74]);
> +   ->withPhpSets(php80: true);
> ```

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

Following method will automatically pick up attribute classes present in your `/vendor`, and upgrade annotations to their attribute equivalent:

```php
<?php

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withAttributesSets();
```

**If you're on a legacy project** and want to take it step by step, use named arguments to limit to specific groups:

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
        __DIR__.'/config/rector-custom-set.php'
    ]);
```
