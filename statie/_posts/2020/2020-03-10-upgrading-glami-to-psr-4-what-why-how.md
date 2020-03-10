---
id: 2
title: "Upgrading Glami to PSR-4, part 1: What and why?"
perex: |
    In april 2019 we upgraded [Glami](https://glami.cz)'s big codebase to follow PSR-4.
    <br>
    <strong>It was a great success! In this part we will go through what PSR-4 is and it's benefits.</strong>
tweet: "New post on #rectorphp blog: Upgraded Glami to PSR-4, part 1: What and why?"
---

## What is PSR-4?

PSR-4 is [standard for autoloading classes](https://www.php-fig.org/psr/psr-4/) in PHP world, supported for example by composer or by [PHPStorm since 2014](https://blog.jetbrains.com/phpstorm/2014/04/psr-0-psr-4-and-sourcetest-root-support-in-phpstorm-8-eap/).

In short, you must register namespace prefix to a directory and every class fully qualified name must follow certain standards. As well only having single class per file is allowed and it must match (without the .php extension).

Example is worth 1000 words here:

<table class="table table-bordered table-condensed table-striped">
  <thead>
    <tr>
      <th>Fully Qualified Class Name</th>
      <th>Namespace Prefix</th>
      <th>Base Directory</th>
      <th>Resulting File Path</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Acme\Log\Writer\File_Writer</td>
      <td>Acme\Log\Writer</td>
      <td>./acme-log-writer/lib/</td>
      <td>./acme-log-writer/lib/File_Writer.php</td>
    </tr>
    <tr>
      <td>Symfony\Core\Request</td>
      <td>Symfony\Core</td>
      <td>./vendor/Symfony/Core/</td>
      <td>./vendor/Symfony/Core/Request.php</td>
    </tr>
    <tr>
      <td>Rector\Website\Controller\DemoController</td>
      <td>Rector\Website</td>
      <td>./src</td>
      <td>./src/Controller/DemoController.php</td>
    </tr>
  </tbody>
</table>

Following PSR-4 + using composer for autoloading allows you to rapidly create classes and use them instantly, without bothering about loading them manually.

It is really simple, just check [autoloading definition in `composer.json` of this website](https://github.com/rectorphp/getrector.org/blob/master/composer.json):
```json
{
  "autoload": {
        "psr-4": {
            "Rector\\Website\\": "src",
            "Rector\\Website\\Blog\\": "packages/Blog/src"
        }
    }
}
```

## Why should you want it?

Without PSR-4 **it's a mess**!

Most of the popular tools (like Rector, PHPStan or Psalm) expects you to have solved question of autoloading already or they most likely will not be able to process your code.

In Glami, for autoloading classes they were using combination of [Nette RobotLoader](https://github.com/nette/robot-loader) (great tool for autoloading, if you do not mind about PSR-4 at all) and on some places, for performance reasons, including the files manually.

<div class="text-center">
    <img style="max-width: 460px;" src="/assets/images/blog/glami-psr-4/robotloader-usage.png" class="img-thumbnail mt-3 mb-1">
    &nbsp;
    <img style="max-width: 460px;" src="/assets/images/blog/glami-psr-4/manual-includes.png" class="img-thumbnail mt-1 mb-3">
</div>

Having 5 classes in same file was common thing, making the need of finding specific class for developer much more difficult.

We found ["dead" PHPUnit test cases](https://twitter.com/mikes_honza/status/1224818282143809537?s=20) - not running, because of not following the standard!
For example class `MySomeClassTest` in file `MySomeClass.php` and because of the default `*Test.php` file filter in PHPUnit, these tests were simply ignored.

Glami is **very focused on performance**. Because there is no publicly documented performance outcomes of switching to PSR-4 autoloading, we did not know what to expect.

**And we were quite surprised after first tests!**
**Glami was globally faster by 2-4ms**!

In one of the most business critical parts, response time lowered from **8ms to 6ms**. That is **incredible 25%** performance gain for this specific scenario just by following a PSR-4 standard!

<div class="text-center">
    <img style="max-width: 260px;" src="/assets/images/blog/glami-psr-4/performance-boost.png" class="img-thumbnail mt-3 mb-3">
</div>

---

In next part, we will look more into migrated codebase and migration process itself.

Don't you have PSR-4 compatible application yet? [Let us help you](https://getrector.org/contact) achieve this great success too!
