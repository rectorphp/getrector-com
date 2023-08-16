---
id: 50
title: "Rector 0.18 - Refocus on PHP"
perex: |
    Before going to Rector 1.0, we want to put focus solely on PHP files. In this release we're leaving not-so-well known feature that could handle some changes configs and templates.
---

Rector as any other tools like ECS, PHPStan, PHP-CS-Fixer, PHPUnit, Pest etc. focuses on working with PHP files.
Yet, in some cases it could change YAML, TWIG or files too. While using PHP classes in templates is a bad practise, it cannot be avoided like in Symfony configs.

That's why added a "special file processor" that was able to handle some non-php files too. This is not-so-known feature, that worked in quite magic way.

* those files are processed only if passed into the explicit paths, e.g.

```bash
# this will process them
bin/rector config src

# this will not
bin/rector src
```

* it only renames class names matching FQN regex pattern

```bash
# this is skipped
use App\SomeType;

class: SomeType
```

The problem is, Rector is build on AST to work with any form of PHP class naming - it's 100 % reliable and can handle whatever aliases.

The above mentioned magic was hidden deep and it lead to un-expected cases - some class are renamed, but are not.

Instead these files should be skipped completely and **Rector handle PHP files only**. This will show same behavior as other CLI tools mention above and make you handle non-PHP files yourself in a consistent and aware way.

## Potential for Standalone tool

What are important changes?

* In Rector 0.17.3 we removed `NonPhpFileProcessor` and `NonPhpRectorInterface` - see [PR 4761](https://github.com/rectorphp/rector-src/pull/4761).

* The `FileProcessorInterface` that is designed to support these magic changes is now deprecated and should be moved away from.

<br>

On the other hand, this shows potential for a standalone tool. The same way PHPStan core does not handle Blade templates but only pure PHP syntax, but there is an [wrapper package](https://github.com/TomasVotruba/bladestan), the Rector will handle pure PHP and allow extension to grow.

<br>

Happy coding!
