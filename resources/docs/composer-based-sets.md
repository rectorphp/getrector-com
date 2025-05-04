Projects like Symfony, Doctrine, Twig or Laravel have lots of versions. Instead of adding dozens of sets for each of those, you can make use of composer-based set resolution:

```php
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withComposerBased(twig: true, doctrine: true, phpunit: true, symfony: true);
```

* Rector should look into installed `composer.json` version of `twig/twig`, `doctrine/*`, `phpunit/phpunit` and `symfony/*`
* then it picks all sets that are relevant to your specific installed versions
* and run those

If you upgrade to Doctrine 4, Twig 4, or Symfony 10 later, Rector will pick up sets for you.
