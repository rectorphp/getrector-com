---
id: 13
title: "Switch Symfony String Route Names to Constants"
perex: |
    Last December, we started to use PHP 8.0 and Symfony 5.2. This exact combination opens [many cool tricks](https://tomasvotruba.com/blog/2020/12/21/5-new-combos-opened-by-symfony-52-and-php-80/) we could never use before.
    One of those tricks is using constants for route name in `#[Route]` attribute.

updated_at: '2022-04'
updated_message: |
    Since **Rector 0.12** a new `RectorConfig` is available with simpler and easier to use config methods.
---

If you're not on PHP 8, [switch with Rector today](https://getrector.com/blog/2020/11/30/smooth-upgrade-to-php-8-in-diffs).

Ready now? Good.

We've all been there. Our memory is full of repeated strings with typos, and we have to type very slowly and carefully.
IDE and CI is going the other directory - they automate our coding process, so we only type the **bare minimum, so the program understands our intention**. IDE autocompletes full class structures, method names, and pre-defined live templates.

Back to the strings. The only **way to avoid typos is not to write at all**. We're not there yet, so we go for the next minimum. Type them just once.
In programming, we call it "constants".

```php
class CompanyInfo
{
    public const COMPANY_NAME = 'Edukai, s. r. o.';

    public const VAT_IT = 'CZ07237626';

    public const CARD_NUMBER = '0239583290850928';
}
```

In this way, We can avoid using an incorrect card number.

## Symfony `#[Route]` Reborn

Now back to Symfony. With Symfony 5.2, we have a new option use Routes. Instead of the old ~~comment~~ annotation way:

```php
use Symfony\Component\Routing\Annotation\Route;

final class MissionController
{
    /**
     * @Route(path="archive", name="mission")
     */
    public function __invoke()
    {
        // ...
    }
}
```

We can use PHP-native attribute:

```php
use Symfony\Component\Routing\Annotation\Route;

final class MissionController
{
    #[Route(path: 'mission', name: 'mission')]
    public function __invoke()
    {
        // ...
    }
}
```

What's the advantage of the route attribute?

- It's native PHP
- ECS can apply all PHP rules, Rector and PHPStan too
- it won't break as wobbly PHP comments do

## Use Constants in Attributes

Now that we've stated the benefits of constants and attributes, we should be ready to spot the problem they bring together:

```php
use Symfony\Component\Routing\Annotation\Route;

final class MissionController
{
    #[Route(path: 'mission', name: 'mission')]
    public function __invoke()
    {
        // ...
    }
}
```

```php
use Symfony\Component\Routing\Annotation\Route;

final class ContactController
{
    #[Route(path: 'contact', name: 'contact')]
    public function __invoke()
    {
        // ...
        return $this->redirectToRoute('mision');
    }
}
```

Can you see it? The route used has a "mission".

You're probably thinking, the IDE plugin would handle this, right? Well, you might be right, but on Symfony 5.2, it's broken and does not collect routes.

<blockquote class="blockquote text-center mt-4 mb-4">
    IDE plugin should only compensate missing PHP features,<br>
    not duplicate them with code smell approach.
</blockquote>

When we see a string, we assume it's a unique string we can change without affecting anything else, like an error message or headline title.

So here is the suggestion - what if we constants used instead of strings for route names?

```diff
 use Symfony\Component\Routing\Annotation\Route;
+use App\ValueObject\Routing\RouteName;

 final class MissionController
 {
-    #[Route(path: 'mission', name: 'mission')]
+    #[Route(path: 'mission', name: RouteName::MISSION)]
    public function __invoke()
    {
        // ...
    }
}
```

```diff
 use Symfony\Component\Routing\Annotation\Route;
+use App\ValueObject\Routing\RouteName;

 final class ContactController
 {
-    #[Route(path: 'contact', name: 'contact')]
+    #[Route(path: 'contact', name: RouteName::CONTACT)]
     public function __invoke()
     {
         // ...
-        return $this->redirectToRoute('mision');
+        return $this->redirectToRoute(RouteName::MISSION);
     }
 }
```

Looks nice, right? But applying this to your 200 routes... well, not so lovely.

## 2 Steps to Convert Route Names to Constants

It is very nice to Rector because it will:

- replace string routes with constants in your attributes
- **generate `RouteName` value object** with all the route constants
- tidy up the constant class import to short ones


Update your `rector.php`:

```php
use Rector\SymfonyCodeQuality\Rector\Attribute\ExtractAttributeRouteNameConstantsRector;
use Rector\Config\RectorConfig;

return function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(ExtractAttributeRouteNameConstantsRector::class);

    $rectorConfig->importNames();
 };
```

Run Rector:

```bash
vendor/bin/rector process
```

That's it!

<br>

We run this rule on our website while writing this post. How did it go? [See for yourself](https://github.com/rectorphp/getrector.org/pull/235/files).

<br>

## The Future Scope

With invokable controllers, we might get even to this:

```php
use Symfony\Component\Routing\Annotation\Route;

final class ContactController
{
    #[Route(path: 'contact', name: ContactController::class)]
    public function __invoke()
    {
        return $this->redirectToRoute(MissionController::class);
    }
}
```

<br>

Happy coding!
