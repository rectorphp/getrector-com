---
id: 1
title: "How to install Rector despite Composer Conflicts"
perex: |
    Rector is a composer package. If you install it, it has to meet install requirements conditions.

    **But how can you [upgrade your Symfony 2.8](https://www.tomasvotruba.com/blog/2019/02/28/how-to-upgrade-symfony-2-8-to-3-4/), when Rector needs at least Symfony 4.4?**

updated_at: '2021-05'
updated_message: |
    Since **Rector 0.11** the prefixed version is available in `rector/rector` out of the box.

    The `rector/rector-prefixed` is now deprecated.
---

Do you have the most modern code base on PHP 7.2 and Symfony 4.4? No?

Then you've probably experienced this before:

```bash
composer install rector/rector --dev
```

<img src="/assets/images/blog/2020/rector_install_fail.png" class="img-thumbnail mt-2 mb-3">

That's sad :(

<a href="https://github.com/rectorphp/rector/issues/2334">
    <img src="/assets/images/blog/2020/rector_install_issue.png" class="img-thumbnail mt-2 mb-3">
</a>

Rector needs the same access to your code as PHPStan, so they can use reflection to get metadata and vendor class analysis. **Classes must be unique and autoloaded**. Few alternatives might help with version install config.

## Alternative Solutions that Do Not Work

### 1. composer-bin-plugin

- [bamarni/composer-bin-plugin](https://github.com/bamarni/composer-bin-plugin)

This composer plugin will help install the dependency to own directory with own dependencies - e.g. `vendor-bin/rector/vendor/bin/rector` (instead of `vendor/bin/rector`). It will allow us to *install* Rector, but not to use it. Why?

There will be conflicts between same-named class in your project and the one in Rector's vendor:

```php
// version 2.8

class YourFavoriteFrameworkClass
{
    public function process($name)
    {
    }
}
```

vs

```php
// version 4.4

class YourFavoriteFrameworkClass
{
    public function process(string $name, $value)
    {
    }
}
```

Now it depends on luck - which classes get loaded first.

Often you run out of luck and get [incompatible class error](https://3v4l.org/Znrnq):

```bash
Warning: Declaration of Implementer::process($name, $newArgument) should be compatible with
YourFavoriteFrameworkClass44::process(string $name) in ...
```

Also, Rector now doesn't know, if you're using version 2.8 (yours) or 4.4 (its), and if what versions and types it should prefer.

### 2. Docker

Thanks to [Jan Mikes](https://janmikes.cz/) Rector also [runs in Docker](https://github.com/rectorphp/rector#run-rector-in-docker). Docker is promoted as *a tool to isolate dependencies*, right?

Well, the truth is somewhere half-way. Do you have PHP 5.6? Rector needs at least 7.1 at version 0.5 and 7.2 at version 0.6. Docker allows you **to run Rector on older PHP**. That's a good thing if you need to upgrade PHP.

But does it solve *the same-named classes* problem in your project and Rector? **No.**

<br>

## Super Hard but only Working Solution

Now we know, the real problem is *same-named classes*.

The question is, how can we **make name difference** between these 2 classes:

```php
// your code

namespace Symfony\Component\Console;

class Command
{

}
```

```php
// Rector code

namespace Symfony\Component\Console;

class Command
{

}
```

In short:

```bash
Symfony\Component\Console\Command
Symfony\Component\Console\Command
```

Any ideas?

<br>

Well, one of them probably... has to be **named differently**?

```bash
Symfony\Component\Console\Command
RectorsVendor\Symfony\Component\Console\Command
```

Yes. That's the only way (we know of), how to make your code autoloadable and Rector's code unique.
So every time Rector uses `Symfony\Component\Console\Command` internally, e.g. to call `ProcessCommand`, **it will actually use** prefixed `RectorsVendor\Symfony\Component\Console\Command`.

This way, there will never be two same-named classes anymore. **Problem solved... well, in theory.**

## Community Effort

How do we **prefix all these classes in Rector's vendor and replace their names in Rector's code**?

Luckily, there is a [humbug/php-scoper](https://github.com/humbug/php-scoper) tool to help us. It doesn't have much clear API and provides unclear error messages like "missing index.php", but if you find the magical combination, it does the right job.

The main pain point was the Symfony application. Symfony was never designed to be prefixed and downgraded. And we don't mean one Symfony class, but Symfony application with dependency injection, PSR-4 autodiscovery, YAML, configs, and local packages.

Thanks to friendly kick from [John Linhart from Mautic](https://johnlinhart.com/) and 3 co-work weekend on this issue only, **we got this working in the early December 2019**.

## Introducing `rector/rector` 0.11

After a month of testing and fixing bugs, we're proud to announce **prefixed and downgraded `rector/rector`**.

Rector is now prefixed and published as per-commit into [distributoin repository](https://github.com/rectorphp/rector) repository.

<br>

You can **install it** despite having conflicting packages:

```bash
composer require rector/rector --dev
```

<br>

This opens migrations of legacy projects to brand new opportunities.

**Now, even Symfony 1.4, Nette 0.9, or Laravel 3 on PHP 5.3 can be instantly upgraded**.
