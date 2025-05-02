In 2024 we shipped an in-house tool that we originally used only internally to help with upgrades.

It can help you with various everyday tasks that are outside Rector's scope, but are connected with upgrades and code quality.

### Install

```bash
composer require rector/swiss-knife --dev
```

It helps with:

* fixing PSR-4 namespace to match `composer.json` autoload
* finalizing all classes excepts parents, entities marked with docblock, attributes or YAML-defined
* detecting commented code or git conflicts

<br>

See [Swiss knife Github repository](https://github.com/rectorphp/swiss-knife) for full documentation.


