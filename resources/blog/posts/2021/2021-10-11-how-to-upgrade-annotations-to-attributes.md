---
id: 26
title: "How to Upgrade Annotations to Attributes"
perex: |
    We used `@annotations` in PHP 7.4 and below. Now we can use native `#[attributes]` in PHP 8. They have better support in PHPStan and Rector, thanks to their native language nature.

    The Internet is full of questions ["How can I use PHP 8 attributes instead of annotations in Doctrine?"](https://stackoverflow.com/questions/66769981/how-can-i-use-php8-attributes-instead-of-annotations-in-doctrine) or ["Converting Annotations to Attributes"](https://www.reddit.com/r/symfony/comments/lbvmdx/converting_annotations_into_attributes/).

    Do you want to know the answer? Rector has a simple solution for you.

updated_at: '2025-03'
updated_message: |
    Since **Rector 2.0** a new `withAttributesSets()` configuration method is available.
---

One package that added support for attributes is Doctrine:

```diff
 use Doctrine\ORM\Mapping as ORM;

-/**
- * @ORM\Column(type="string")
- */
+#[ORM\Column(type: "string")]
```

Now, let's go to upgrade itself. It's effortless.

## Upgrade from Annotations to Attributes in 3 Steps

### 1. Configure `rector.php` to include all available attributes:

```php
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withAttributesSets();
```

Or limit to specific group - this option is better when you're addin Rector to a new project:

```php
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withAttributesSets(symfony: true);
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
+                type: attribute
```

This enables new Symfony [auto-detection feature](https://github.com/symfony/symfony/pull/42054).

That's it! Now your codebase uses nice and shiny PHP 8 native attributes.

<br>

Happy coding!
