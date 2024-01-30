Rector requires PHP 7.2+. It can work with PHP 5.x and 8.x code.
It works best with OOP, typed code with separated PHP and template layers.

## Install

```bash
composer require rector/rector --dev
```

You can run it from your bin directory:

```bash
vendor/bin/rector
```

## First Run

Rector works with `rector.php` config file. You can create it manually, or Rector handle it for you:

```bash
vendor/bin/rector

 No "rector.php" config found. Should we generate it for you? [yes]:
 > yes


 [OK] The config is added now. Re-run command to make Rector do the work!
```

In `rector.php` you can define paths, rules and sets you want to run on your code:

```php
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withPreparedSets(deadCode: true);
};
```

To see preview of suggested changed, run `process` command with `--dry-run` option:

```bash
vendor/bin/rector process --dry-run
```

To make changes happen, run bare command:

```bash
vendor/bin/rector process
```
