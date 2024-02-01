Rector works with all class names as fully qualified by default. That way it knows the exact types even on just changed nodes. In the most projects, that's not a desired behavior, because short version with `use` statement is easier to read:

```diff
+use App\Some\Namespace\SomeClass;

-$object = new \App\Some\Namespace\SomeClass();
+$object = new SomeClass();
```

<br>

To import FQN like these, configure `rector.php` with:

```php
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withImportNames();
```

<br>

Single short classes are imported too:

```diff
+use DateTime;
-$someClass = \DateTime();
+$someClass = DateTime();
```

<br>

To keep those:

```php
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withImportNames(importShortClasses: false);
```


## How to Remove Unused Imports?

```php
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withImportNames(removeUnusedImports: true);
```

<br>

That's it. Happy coding!
