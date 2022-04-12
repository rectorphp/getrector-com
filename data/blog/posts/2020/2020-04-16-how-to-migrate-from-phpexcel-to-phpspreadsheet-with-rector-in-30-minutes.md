---
id: 3
title: "How to Migrate From PHPExcel to PHPSpreadsheet with Rector in 30 minutes"
perex: |
    [PHPExcel](https://github.com/PHPOffice/PHPExcel) is a package for working with Excel files in PHP. The last version was released in 2015, and it was **deprecated in 2017**. Still, it has over **27 000 daily downloads** - that's tons of legacy code.
    <br>
    <br>
    Do you use it too? Do you want to switch to [PHPSpreadsheet](https://github.com/PHPOffice/PhpSpreadsheet)? You can do it today.
---

PHPSpreadsheet is **direct follower** of PHPExcel with the same maintainers, just with dozens of BC breaks. There is the official [migration tutorial](https://github.com/PHPOffice/PhpSpreadsheet/blob/master/docs/topics/migration-from-PHPExcel.md) that describes step by step **24 different changes** that you need to do.

 Only 1 of those changes - class renames - is automated with [`preg_replace()`](https://github.com/PHPOffice/PhpSpreadsheet/blob/87f71e1930b497b36e3b9b1522117dfa87096d2b/src/PhpSpreadsheet/Helper/Migrator.php#L329). Regular expressions are not the best thing to use to modify code, rather contrary.

<br>

But there are also extra method calls:

```diff
-$worksheet->getDefaultStyle();
+$worksheet->getParent()->getDefaultStyle();
```

Method call renames:

```diff
-$worksheet->setSharedStyle($sharedStyle, $range);
+$worksheet->duplicateStyle($sharedStyle, $range);
```

Argument switch and extra method calls:

```diff
-$cell = $worksheet->setCellValue('A1', 'value', true);
+$cell = $worksheet->getCell('A1')->setValue('value');
```

Method move to another class:

```diff
-PHPExcel_Cell::absoluteCoordinate()
+PhpOffice\PhpSpreadsheet\Cell\Coordinate::absoluteCoordinate()
```

and [so on](https://github.com/PHPOffice/PhpSpreadsheet/blob/50d78ce7898ee3a540cefd9693085b3636e578e6/docs/topics/migration-from-PHPExcel.md).

<br>

Most people use PHPExcel in the past, and **didn't touch the code ever since**. If it works, why touch it, right?

That why for last 3 years the download rate decreased just very poorly - from ~25 000 daily downlaod to ~20 000 daily downloads:

<div class="row">
    <div class="col-12 col-sm-6">
        <a href="https://packagist.org/packages/phpoffice/phpexcel/stats">
            <img src="/assets/images/blog/2020/sheets_phpexcel_stats.png" class="img-thumbnail mt-3 mb-3">
        </a>
    </div>

    <div class="col-12 col-sm-6">
        <a href="https://packagist.org/packages/phpoffice/phpspreadsheet">
            <img src="/assets/images/blog/2020/sheets_phpspreadsheet_stats.png" class="img-thumbnail mt-3 mb-3">
        </a>
    </div>
</div>

## We need Migration - Fast

We got into a project that used PHPExcel and wanted to switch to PHP 7.4 and Symfony 5. PHP upgrade and framework migration is half of the work. **The other half are these *small* packages, that vendor locks your project to old PHP or another old dependency**.

To get rid of old PHPExcel, we prepare a set `phpexcel-to-phpspreadsheet` to help us.

It took 3 days to make and now has [**230 lines**](https://github.com/rectorphp/rector/blob/master/config/set/php-office/phpexcel-to-phpspreadsheet.yaml) of configuration and **17 rules**.

## How to Migrate?

1. Install Rector

```bash
composer require rector/rector --dev
```

2. Create `rector.php` config

```bash
vendor/bin/rector init

# on Windows?
vendor\bin\rector init
```

3. Add your set to the `rector.php` config:

```php
use Rector\PHPOffice\Set\PHPOfficeSetList;
use Rector\Config\RectorConfig;

return function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(PHPOfficeSetList::PHPEXCEL_TO_PHPSPREADSHEET);
};
```

4. Dry run Rector to see what *would be* changed

```bash
vendor/bin/rector process src --dry-run
```

5. Make the changes happen:

```bash
vendor/bin/rector process src
```

<br>

That's it! Rector just migrated your code from PHPExcel to PHPSpreadsheet.

<br>

If you have any issues, look at [official migration tutorial](https://github.com/PHPOffice/PhpSpreadsheet/blob/50d78ce7898ee3a540cefd9693085b3636e578e6/docs/topics/migration-from-PHPExcel.md) or [let us know on GitHub](https://github.com/rectorphp/rector/issues).

<br>

Happy coding!
