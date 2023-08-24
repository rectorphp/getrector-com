---
id: 53
title: "Rector 0.18 - From Symfony Container to Laravel and How to Upgrade your Extensions"
perex: |
    Since the first Rector version, we used Symfony container to inject the services. It worked very well. The new PHP 8.0 came with attributes, and Symfony started to use them extensively.

    We're downgrading Rector down to PHP 7.2, and this forced us to lock with unmaintained Symfony 6.1. We needed a hacky patch to make Rector config work...

    This made us think: Is there a better way?
---

The container started to cost more maintenance than the features it provided, so we tried an experimental switch to Laravel. To our surprise, this helped us to make **[our tests run 7x faster](/blog/rector-018-how-we-made-tests-seven-times-faster)**.

<br>

## It's all about the Downgrade

How is this possible? Apart from the 3 changes we mentioned in the article regarding downgrading and scoping - **the size matters**.

<img src="https://sergeyzhuk.me/assets/images/posts/code-review/review-mem.jpeg" class="img-thumbnail mt-3 mb-4" style="max-width: 25em">

<br>

During downgrade and scoping, all the `/vendor` is shipped with the Rector project. That means every dependency is scoped and downgraded - line by line.

<br>

What about features? Rector is a CLI app, and it needs a simple dependency injection container:

* autowire service constructor,
* pass tagged services,
* callback to avoid circular dependencies.

If two packages are compatible, the more lines mean only more features we will never use.

<br>

**Downgrading is a challenging process** and sometimes it's a dead end - e.g., downgrading PHP code that uses PHP 8.0 attributes and doesn't provide a fallback feature on PHP 7.2 cannot be automated. Fewer lines mean fewer pitfalls to worry about.

<br>

## Smaller is Better

Let's compare container package sizes without knowing the package name.

Guess which is which ↓

```bash
Filesystem                                         count
Directories ......................................... 14
Files .............................................. 186

Lines of code                           count / relative
Code ................................... 17 694 /   79 %
Comments ................................ 4 698 /   21 %
Total .................................. 22 392 /  100 %
```

* **17 694 lines of code**

<br>

Then the other:

```bash
Filesystem                                         count
Directories .......................................... 0
Files ................................................ 6

Lines of code                           count / relative
Code .................................... 1 091 / 56.1 %
Comments .................................. 855 / 43.9 %
Total ................................... 1 946 /  100 %
```

* **1 091 lines of code**

<br>

That means one is **16 x size greater** than the other.

<br>

Results revealed:

* the symfony/dependency-injection 6.3 ..... **17 694 lines**
* the illuminate/container 10.20 ..................... **1 081 lines**

<br>

*We also used symfony/http-kernel for the kernel test case to run, but we skipped transitional packages for the sake of comparison simplicity.*

<br>


## How to Upgrade your Extensions?

Rector 0.18 is the first release with a Laravel container. **Now, we're testing in the wild**.

<br>

If you've used [bare `RectorConfig` class](/blog/new-in-rector-012-much-simpler-and-safer-rule-configuration) to set up your configuration, **no upgrade is needed**.

Few changes are required if you've used Symfony internal methods or maintain a custom Rector extension.

<br>

**The way services are registered**:

* Before, you had to register every single service, even if fully autowired.
* Now, you only register the services requiring a scalar parameter, factory, or tagged services.

```diff
 use Rector\Config\RectorConfig;

 return static function (RectorConfig $rectorConfig): void {
-    $services = $rectorConfig->services();
-    $services->set(App\SomeService::class);

+    $rectorConfig->singleton(App\SomeService::class, function () {
+        return new SomeService('some parameter');
+    });
};
```

<br>

Tag service:

```diff
 use Rector\Config\RectorConfig;

 return static function (RectorConfig $rectorConfig): void {
-    $services = $rectorConfig->services();
-    $services->set(App\SomeService::class)
-        ->tag(SomeInterface::class);

+    $rectorConfig->singleton(SomeService::class);
+    $rectorConfig->tag(SomeService::class, SomeInterface::class);
};
```

<br>

You can drop the `$services->defaults()` calls ultimately, as this is now included in the core:

```diff
-    $services = $rectorConfig->services();
-    $services->defaults()
-        ->public()
-        ->autowire()
-        ->autoctonfigure();
```

<br>

Also, PSR-4 autodiscovery is not needed anymore, as services are created for you:

```diff
-    $services->load('Rector\\', __DIR__ . '/../packages')
-        ->exclude([...]);
```

<br>

That's it! Find more in [Laravel documentation](https://laravel.com/docs/10.x/container) and [pull-request with container switch in Rector](https://github.com/rectorphp/rector-src/pull/4698).

Is there something missing? Let us know to update this post. Thank you!



<br>

Happy coding!
