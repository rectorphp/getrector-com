In 2024 we published an in-house tool that we used only internal for years.

It can help you with various everyday tasks that are outside Rector's scope, but are connected with upgrades and code quality.

### Install

```bash
composer require rector/swiss-knife --dev
```

It helps you to:

* **finalize all classes** except entities, mocked classes, classes with children, YAML-defined etc.
* **privatize class constant**, that are never used outside the class
* detect commented code creep
* **convert random namespaces to PSR-4** - updates `composer.json`, class names and use imports
* spot accidental git conflicts in CI
* turn any JSON file to **nice and human-readable format**
* search all PHP files with quick regex
* generate [Symfony 5.3](https://symfony.com/blog/new-in-symfony-5-3-config-builder-classes) configs
* split huge Symfony configs to per-package in `/packages/` folder
* spot fake-traits

...and more


<br>

See [Swiss knife Github repository](https://github.com/rectorphp/swiss-knife) for full documentation.


