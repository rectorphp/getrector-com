---
id: 50
title: "Rector 0.18 - Refocus on PHP"
perex: |
    Before going to Rector 1.0, we need to refocus solely on PHP files. In this release, we're leaving a not-so-well-known feature that could handle some changes in configs and templates.
---

Like any other tools like ECS, PHPStan, PHP-CS-Fixer, PHPUnit, Pest, etc., Rector focuses on working with PHP files.
Yet, in some cases, it could change YAML, TWIG, or files too. While using PHP classes in templates is a bad practice, it cannot be avoided like in Symfony configs.

That's why I added a "special file processor" that could handle some non-php files too. This is a not-so-known feature that worked in quite a magic way.

* those files are processed only if passed into the explicit paths, e.g.

```bash
# this could process non-PHP files
bin/rector config src

# this could not
bin/rector src
```

* it only renames class names matching the FQN regex pattern

```bash
# This is skipped
use App\SomeType;

class: SomeType
```

The problem is Rector is built on AST to work with any form of PHP class naming - it's 100 % reliable and can handle whatever aliases.

The magic mentioned above was hidden deep, leading to unexpected cases - some classes are renamed but are not.

Instead, these files should be skipped entirely, and **Rector handles PHP files only**. This will show the same behavior as other CLI tools mention above and make you handle non-PHP files yourself in a consistent and aware way.

## What are the essential changes?

* In Rector 0.17.3 we removed `NonPhpFileProcessor` and `NonPhpRectorInterface` - see [PR 4761](https://github.com/rectorphp/rector-src/pull/4761).

* The `FileProcessorInterface` designed to support these magic changes is now deprecated and should be moved away from.

The community usage is very rare - as far as we know, there is only single package using those. But we want to plan ahead and give you time to adjust.

<br>

## Potential for a Standalone tool

On the other hand, this shows potential for a standalone tool. In the same way PHPStan core does not handle Blade templates but only pure PHP syntax, but there is an [wrapper package](https://github.com/TomasVotruba/bladestan), the Rector will handle pure PHP and allow reliable extensions to grow.

<br>

Happy coding!
