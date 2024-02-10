Rector requires PHP 7.2 as min version. But what if you want to upgrade much older project, like PHP 7.0 or even 5.3?

The trick is in the separate Rector installation in own directory.

## 1. Install Rector

Create a directory parallel to your project, and install Rector there:

```bash
mkdir rector-standalone
cd rector-standalone
composer require rector/rector --dev
```


Now you have following file structure:

```bash
/your-project
/rector-standalone
```

## 2. Setup PHP

Make sure you're using PHP 7.2+, so you can run Rector. It doesn't matter your project can handle only PHP 5.3, as Rector uses static parsing of code and never runs your project.

```bash
php --version
# PHP 7.2
```

## 3. Run Rector

Now move to `/your-project` and run Rector in it:

```bash
cd /your-project
php ../rector-standalone/vendor/bin/rector
```

It will create a `rector.php` config in your project directory:

```bash
/your-project/rector.php
```

Tune the config to fit your needs. We typically add the current project PHP version set, e.g.:

```php
<?php

# rector.php
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPhpSets(php53: true);
```

Now run Rector and see the diffs it suggests:

```bash
php ../rector-standalone/vendor/bin/rector --dry-run
```

Ready to go? Let's run Rector to refactor your code:

```bash
php ../rector-standalone/vendor/bin/rector
```


That's it!

Next step would be pull-request, merge and then bump to `PHP_54` set.
