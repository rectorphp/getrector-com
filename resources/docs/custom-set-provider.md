Core sets like [Twig, Symfony, or Doctrine](/documentation/composer-based-sets) are based on specific versions of installed packages in `/vendor`:

```php
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withComposerBased(twig: true);
```

If you use Twig 2, Rector will load sets for Twig 2. Once you bump to Twig 3 in `composer.json`, Rector will automatically load new rules for Twig 3.

These sets are configured via classes that implement `SetProviderInterface`. Let's say you want to create a custom rule set for your Laravel community. Once set up, let us know, and we can add it to the Rector `withComposerBased()` method.

Let's take it step by step.


## 1. Create a custom `LaravelSetProvider` class that implements `SetProviderInterface`

```php
use Rector\Set\Contract\SetInterface;
use Rector\Set\Contract\SetProviderInterface;
use Rector\Set\ValueObject\Set;

final class LaravelSetProvider implements SetProviderInterface
{
    /**
     * @return SetInterface[]
     */
    public function provide(): array
    {
        return [];
    }
}
```

## 2. Add your `ComposerTriggeredSet` objects to the `provide()` method

What is the `ComposerTriggeredSet` object for? It connects to a specific package + version with a path to the rule set:

```php
namespace Rector\Set\ValueObject;

use Rector\Set\Contract\SetInterface

final class ComposerTriggeredSet implements SetInterface
{
    public function __construct(
        private string $groupName,
        private string $packageName,
        private string $version,
        private string $setFilePath
    ) {
    }

    // ...
}
```

* The `$groupName` is key in `->withComposerBased()`:
* The `$packageName` is the composer package name.
* `$version` is the minimal version to trigger the set
* and `$setFilePath` is the path to the Rector config with rules as we know it

Let's add objects for Laravel 10 and 11:

```php
use Rector\Set\Contract\SetInterface;
use Rector\Set\Contract\SetProviderInterface;
use Rector\Set\ValueObject\ComposerTriggeredSet;
use Rector\Set\ValueObject\Set;

final class LaravelSetProvider implements SetProviderInterface
{
    /**
     * @return SetInterface[]
     */
    public function provide(): array
    {
        return [
            new ComposerTriggeredSet(
                'laravel',
                'laravel/framework',
                '10.0',
                __DIR__ . '/../../config/sets/laravel10.php'
            ),
            new ComposerTriggeredSet(
                'laravel',
                'laravel/framework',
                '11.0',
                __DIR__ . '/../../config/sets/laravel11.php'
            ),
            // ...
        ];
    }
}
```

## 3. Enable the custom set provider in `rector.php`

```php
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withComposerBased(laravel: true);
```

If your parameter is missing, send us [a PR to enable it](https://github.com/rectorphp/rector-src/blob/main/src/Config/RectorConfig.php).

<br>

And we're done. Now, Rector will load the `laravel10.php` rules for Laravel 10 and `laravel11.php` rules for Laravel 11.

You can be creative with `SetListProvider` and define a rule for more specific packages like `illuminate/container`. That way, we handle complexity for Rector end-users, and all they have to do is add a single parameter to the `rector.php` config.
