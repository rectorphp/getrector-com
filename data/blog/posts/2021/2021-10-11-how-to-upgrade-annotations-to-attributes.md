---
id: 26
title: "How to Upgrade Annotations to Attributes"
perex: |
    We used `@annotations` in PHP 7.4 and below. Now we can use native `#[attributes]` in PHP 8. They have better support in PHPStan and Rector, thanks to their native language nature.
    <br><br>
    The Internet is full of questions ["How can I use PHP 8 attributes instead of annotations in Doctrine?"](https://stackoverflow.com/questions/66769981/how-can-i-use-php8-attributes-instead-of-annotations-in-doctrine) or ["Converting Annotations to Attributes"](https://www.reddit.com/r/symfony/comments/lbvmdx/converting_annotations_into_attributes/).
    <br><br>
    Do you want to know the answer? Rector has a simple solution for you.
---

One package that added support for attributes is Doctrine:

```diff
 use Doctrine\ORM\Mapping as ORM;

-/**
- * @ORM\Column(type="string")
- */
+#[ORM\Column(type: "string")]
```

## Upgrade from Annotations to Attributes in 3 Steps

### 1. Configure `rector.php` to include the packages you use:

```php
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\Symfony\Set\SymfonySetList;
use Rector\Symfony\Set\SensiolabsSetList;
use Rector\Nette\Set\NetteSetList;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(DoctrineSetList::ANNOTATIONS_TO_ATTRIBUTES);
    $containerConfigurator->import(SymfonySetList::ANNOTATIONS_TO_ATTRIBUTES);
    $containerConfigurator->import(NetteSetList::ANNOTATIONS_TO_ATTRIBUTES);
    $containerConfigurator->import(SensiolabsSetList::FRAMEWORK_EXTRA_61);
};
```

<br>

### 2. Run Rector to upgrade your code

```bash
vendor/bin/rector process
```

<br>

### 3. Handle Manual Steps

* Do you use `doctrine/orm`? Be sure to use [at least version 2.9](https://github.com/doctrine/orm/releases/tag/2.9.0), where attributes were released.

* Do you use Doctrine with Symfony? Update the Symfony bundle mapping parser in your config to read attributes:

```diff
 # config/packages/doctrine.yaml
 doctrine:
     orm:
         mappings:
             App:
-                type: annotation
```

This enables new Symfony [auto-detection feature](https://github.com/symfony/symfony/pull/42054).

And that's it! Now your codebase uses nice and shiny PHP 8 native attributes.

<br>

Happy coding!
