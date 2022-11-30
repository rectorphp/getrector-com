Rector works with all class names as fully qualified by default. That way it knows the exact types even on just changed nodes. In the most projects, that's not a desired behavior, because short version with `use` statement is easier to read:

```diff
+use App\Some\Namespace\SomeClass;

-$object = new \App\Some\Namespace\SomeClass();
+$object = new SomeClass();
```

<br>

To import FQN like these, configure `rector.php` with:

```php
$rectorConfig->importNames();
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
$rectorConfig->importShortClasses(false);
```

## How to Remove Unused Imports?

To remove imports, use [ECS](https://github.com/symplify/easy-coding-standard) with `NoUnusedImportsFixer`rule:

```php
// ecs.php
use PhpCsFixer\Fixer\Import\NoUnusedImportsFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->rule(NoUnusedImportsFixer::class);
};
```

<br>

Run it to remove unused imports:

```bash
vendor/bin/ecs check src --fix
```

<br>

That's it. Happy coding!
