---
id: 30
title: "New in Rector 0.12 - The Latest PHP in a Single Import"
perex: |
    The most used feature of Rector is to keep you updated with the latest PHP. PHP 8.1 was released almost a month ago, so many projects started to use Rector to upgrade to PHP 8.1. There is a new import in your `rector.php` with every new version.
    <br><br>
    Soon, your config is cluttered with a list of imports. How can we reduce this complexity to a single line? How can we handle your-favorite-framework upgrade in second?

updated_since: '2022-04'
updated_message: |
    Since **Rector 0.12** a new `RectorConfig` is available with simpler and easier to use config methods.

since_rector: 0.12
---

How do you upgrade to PHP 8.1 in 2 steps?

Register new PHP 8.1 set in `rector.php`:

```diff
 use Rector\Set\ValueObject\SetList;
 use Rector\Config\RectorConfig;

 return function (RectorConfig $rectorConfig): void {
     $rectorConfig->import(SetList::PHP_55);
     $rectorConfig->import(SetList::PHP_56);
     $rectorConfig->import(SetList::PHP_70);
     $rectorConfig->import(SetList::PHP_71);
     $rectorConfig->import(SetList::PHP_73);
     $rectorConfig->import(SetList::PHP_74);
     $rectorConfig->import(SetList::PHP_80);
+    $rectorConfig->import(SetList::PHP_81);
};
```

<br>

And run Rector:

```bash
vendor/bin/rector
```

That's it! Yet, there is a code smell lurking.

## Error-Prone Complexity

High complexity leads to a high error rate. What could go wrong with such a configuration?

* we can accidentally skip one of the versions - what version do we miss? 7.2
* we skip lower versions - the PHP 5.3 and 5.4 contain many valuable rules, but our code will still run the old syntax
* the more versions we want to upgrade, the longer config gets

## Config made Simple with Level Set

We wanted to lower complexity and remove these errors. That's why we add a new feature in Rector 0.12 - *Level sets*.

<br>

Now instead of gazillion lines with set imports, you can use just **the single latest level**:

```php
use Rector\Set\ValueObject\LevelSetList;
use Rector\Config\RectorConfig;

return function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(LevelSetList::UP_TO_PHP_81);
};
```

That's it! No more place for mistakes.

The `LevelSetList` also includes the PHP target version parameter to ensure all rules are applied.

<br>

Next time you'll need to upgrade PHP, you can change only 1 line:

```diff
 use Rector\Set\ValueObject\LevelSetList;
 use Rector\Config\RectorConfig;

 return function (RectorConfig $rectorConfig): void {
-    $rectorConfig->import(LevelSetList::UP_TO_PHP_81);
+    $rectorConfig->import(LevelSetList::UP_TO_PHP_82);
 };
```

## Frameworks or Downgrades?

You might think that's a very nice improvement for PHP, but do we support something similar for the framework packages?

See for yourself:

```php
use Rector\Symfony\Set\SymfonyLevelSetList;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Config\RectorConfig;

return function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(LevelSetList::UP_TO_PHP_82);
    $rectorConfig->import(SymfonyLevelSetList::UP_TO_SYMFONY_60);
};
```

There are also `Rector\Set\ValueObject\DowngradeSetList` configs that make sure you downgrade with ease too!

<br>

Happy coding!


