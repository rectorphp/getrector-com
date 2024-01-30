Rector is using static reflection to load code without running it since version 0.10. That means your classes are found **without composer autoload and without running them**. Rector will find them and work with them as you have PSR-4 autoload properly setup. This comes very useful in legacy projects or projects with custom autoload.

Do you want to know more about it? Continue here:

- [From Doctrine Annotations Parser to Static Reflection](https://getrector.com/blog/from-doctrine-annotations-parser-to-static-reflection)
- [Legacy Refactoring made Easy with Static Reflection](https://getrector.com/blog/2021/03/15/legacy-refactoring-made-easy-with-static-reflection)
- [Zero Config Analysis with Static Reflection](https://phpstan.org/blog/zero-config-analysis-with-static-reflection) - from PHPStan

<br>

```php
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withAutoloadPaths([
        // discover specific file
        __DIR__ . '/file-with-functions.php',
        // or full directory
        __DIR__ . '/project-without-composer',
    ]);
```

## Include Files

Do you need to include constants, class aliases or custom autoloader? Use bootstrap files:


```php
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withBootstrapFiles([
        __DIR__ . '/constants.php',
        __DIR__ . '/project/special/autoload.php',
    ]);
```

Listed files will be executed like:

```php
include $filePath;
```
