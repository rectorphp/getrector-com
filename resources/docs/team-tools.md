The same way you're using multiple packages to run your project, Rector works best with company of other tools. Here are some of the most practical ones:

## 1. Coding Standard

Rector is using php-parser to print the code, so it might add extra space here and there. That's why it's important to have a coding standard tool running.

We **recommend [Easy Coding Standard](https://github.com/easy-coding-standard/easy-coding-standard)**, as fast, reliable and its configuration UX is very similar to Rector.

Ideally, first install a coding standard tool, get it to highest level possible and then move on to Rector.

## 2.Static Analysis

Rector uses PHPStan to understand types. What PHPStan can see, Rector can see too and vice versa. In case you're using a PHPStan baseline and ignore thousands of errors, you're making Rector blind. You can't rename a method call on class of `mixed` type.

**Add [PHPStan](https://github.com/phpstan/phpstan)** to your project, **remove baseline and increase level one by one**.

To get the best performance out of Rector, you should reach PHPStan level 3-4 without baseline before using.

<br>

There 2 more tools that help out with specific sets:

* [tomasvotruba/type-coverage](https://github.com/TomasVotruba/type-coverage) - works best with `withTypeCoverageLevel()`
* [tomasvotruba/class-leak](https://github.com/TomasVotruba/class-leak) - works best with `withDeadCodeLevel()`

## 3. Multi Tools

Last but not least, in 2024 we shipped a home made tool that can help you with various tasks. We use it every project and it's a great help:

* [rector/swiss-knife](https://github.com/rectorphp/swiss-knife)

It helps with:

* fixing PSR-4 namespace to match `composer.json` autoload
* finalizing all classes excepts parents, entities marked with docblock, attributes or YAML-defined
* detecting commented code or git conflicts

<br>

There is also [symplify/config-transformer](https://github.com/symplify/config-transformer) that helps with transforming YAML Symfony configs to PHP. There you can follow up with [Symfony rules for Rector](https://github.com/rectorphp/rector-symfony/blob/main/docs/rector_rules_overview.md#stringextensiontoconfigbuilderrector) to reach config builders.
