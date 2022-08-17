---
id: 22
title: "Prefixed Rector by Default"
perex: |
    Today we're introducing a big step up in making Rector developer experience more smooth and intuitive. It will also ease development for Rector contributors. We won't have to think about dependencies in `composer.json` anymore.
    <br><br>
    Are these goals in contradiction? Quite the contrary.

since_rector: 0.11
---

*This move was inspired by [PHPStan 0.12 development and release repository setup](https://phpstan.org/blog/phpstan-0-12-released#phar-distribution-by-default).*

The prefixed version allows to use of Rector on an older version than Rector is being developed. E.g., if you need to refactor your project on 7.1.

If you have symfony/console 2.8 and wanted to install `rector/rector` on your project, it would fail:

```bash
composer require symfony/console:2.8
composer require rector/rector --dev
```

<em class="fas fa-fw fa-times text-danger fa-2x"></em>

That's where [prefixed version](/blog/2020/01/20/how-to-install-rector-despite-composer-conflicts) helps too.

```bash
composer require symfony/console:2.8
composer require rector/rector-prefixed --dev
```

<em class="fas fa-fw fa-check text-success fa-2x"></em>

The ultimate problem with this setup is a terrible user experience [with hidden knowledge](@todo memory lock post). As a user, I don't want to think about different names for the same package. Would you install `symfony/console` or `symfony/console-prefixed` based on conflicts on install? No.

## Single Distribution Package

We knew this must be a **single way** to install Rector:

```bash
composer require symfony/console:2.8
composer require rector/rector --dev
```

<em class="fas fa-fw fa-check text-success fa-2x"></em>

In April and May we've been working hard to make `rector/rector-prefixed` experience identical to `rector/rector`. It included:

- adding [static reflection](/blog/2021/03/15/legacy-refactoring-made-easy-with-static-reflection)
- adding [custom static annotation parser](/blog/from-doctrine-annotations-parser-to-static-reflection)
- [automate downgrade to PHP 7.1](/blog/2021/03/22/rector-010-released-with-php71-support)
- polishing user experience while writing own Rector rules
- working with tests
- installing Rector extensions

Last big change was a repository switch. The original `rector/rector` repository will become development only and will be replecated with distribution `rector/rector-prefixe` repository:

- `rector/rector-prefixed` → `rector/rector` - the **distribution repository**
- `rector/rector` → `rector/rector-src` - the **development repository**
- deprecate `rector/rector-prefixed` and suggest `rector/rector` as replacement

## How to Upgrade?

The next version is still in progress and will be released in May 2021. We're now testing the dev version to ensure there are no glitches when the stable tag is out.

There are 2 ways to upgrade, depending on which version you use.

For prefixed version:

```bash
composer remove rector/rector-prefixed
composer require rector/rector:^0.11 --dev
```

For normal version:

```bash
composer update rector/rector:^0.11 --dev
```

From now on, every next Rector release will be under `rector/rector` package name. One less thing to worry about before
you instantly upgrade your code.

<br>

Happy coding!
