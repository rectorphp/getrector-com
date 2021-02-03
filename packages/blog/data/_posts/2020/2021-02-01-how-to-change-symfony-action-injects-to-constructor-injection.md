---
id: 16
title: "How to Instantly Refactor Symfony Action Injects to Constructor Injection"
perex: |
    Action Injections are much fun a first, but they turn your fresh project into legacy code very fast. With PHP 8 and promoted properties, there is no reason to pollute method arguments with services.
    <br><br>
    How to **refactor out of the legacy back to constructor injection** today?
---

*Action Injection* or *Method Injection* is [Laravel](https://laravel.com/docs/8.x/controllers#method-injection) and [Symfony feature](https://symfony.com/doc/3.4/service_container/3.3-di-changes.html#controllers-are-registered-as-services), that turns Controller action method to injected constructor:

```php
final class SomeController
{
    public function actionDetail(int $id, User $user, PostRepository $postRepository)
    {
        $post = $postRepository->get($id);
        if (! $user->hasAccess($post)) {
            // ...
        }

        // ...
    }
}
```

<br>

It looks sexy and fun at first, but in few months, it will reveal its true face [as an ugly code smell](https://tomasvotruba.om/blog/2018/04/23/how-to-slowly-turn-your-symfony-project-to-legacy-with-action-injection/):

<br>

<blockquote class="blockquote">
    Action injection makes it confusing whether an object is treated stateful or stateless - a very grey area with, e.g., the Session.
    <footer class="blockquote-footer text-right">Iltar van der Berg</footer>
</blockquote>

<blockquote class="blockquote">
    I'm a Symfony trainer, and I'm told to teach people how to use Symfony and talk about this injection pattern. Sob.
    <footer class="blockquote-footer text-right">Alex Rock</footer>
</blockquote>

<blockquote class="blockquote">
    I work on a project that uses action injection, and I hate it. The whole idea about action injection is broken. Development with this pattern is a total nightmare.
    <footer class="blockquote-footer text-right">A</footer>
</blockquote>

<br>

It's natural to **try new patterns with an open heart** and validate them in practice, but **what if** you find this way as not ideal and want to go to constructor injection instead?

How would you change all your 50 controllers with action injections...

```php
<?php

namespace App\Controller;

final class SomeController
{
    public function detail(int $id, Request $request, ProductRepository $productRepository)
    {
        $this->validateRequest($request);
        $product = $productRepository->find($id);
        // ...
    }
}
```

**...to the constructor injection:**

```php
<?php

namespace App\Controller;

final class SomeController
{
    public function __construct(
        private ProductRepository $productRepository
    ) {
    }

    public function detail(int $id, Request $request)
    {
        $this->validateRequest($request);
        $product = $this->productRepository->find($id);
        // ...
    }
}
```


## How to Waste a Week in one Team

- 50 controllers, four action methods per each → 200 services
- some of them are duplicated
- identify services, [`Request` objects](https://symfony.com/doc/current/controller.html#controller-request-argument) and [Argument Resolver objects](https://symfony.com/doc/current/controller/argument_value_resolver.html)
- code-reviews and discussions that might take up to 5-10 days
- and rebase on new merged PRs... well, you have 4-10 hours of team-work wasted ahead of you.

<br>

**We find the time of our client's team very precious**, don't you? So we Let Rector do the work.

## 3 Steps to Instant Refactoring

### 1. Install Rector

```bash
composer install rector/rector --dev
```

### 2. Prepare Config

Add the `action-injection-to-constructor-injection` set and configure your Kernel class name.

```php
<?php

// rector.php


declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    $parameters->set(Option::SETS, [
        SetList::ACTION_INJECTION_TO_CONSTRUCTOR_INJECTION
    ]);

    // the default value
    $parameters->set('kernel_class', 'App\Kernel');
};
```

### 3. Run Rector on Your Code

```bash
vendor/bin/rector process /app
```

<br>

You will see diffs like:

```diff
 <?php

 namespace App\Controller;

 final class SomeController
 {
+    public function __construct(
+         private ProductRepository $productRepository
+    ) {
+    }
+
-    public function detail(int $id, Request $request, ProductRepository $productRepository)
+    public function detail(int $id, Request $request)
     {
         $this->validateRequest($request);
-        $product = $productRepository->find($id);
+        $product = $this->productRepository->find($id);
         // ...
     }
 }
```

<br>

And your code is now both **refactored and clean**. That's it!

<br><br>

Happy instant refactoring!
