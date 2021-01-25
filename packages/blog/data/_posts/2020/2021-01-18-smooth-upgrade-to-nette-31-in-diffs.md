---
id: 14
title: "Smooth Upgrade to Nette 3.1 in Diffs"
perex: |
    Nette 3.1 was released almost a month ago. Packages using it had enough time to give support to small BC breaks and now it's ready to run on your project.
    Let's look at what has changed and how to upgrade today.

contributor: "lulco"
pull_request_id: 5195
---

If you're not on Nette 3.1, hurry up. Why? There is a security issue in 3.0.x version:

<img src="/assets/images/blog/2021/nette_31_security.png" class="img-thumbnail mt-3 mb-3">

<br>

But that's not the only reason to upgrade. Nette is **rising to more active half** of PHP frameworks and is right behind Laravel. Give Nette core developers your appreciation by upgrading to the new version as soon as possible.

<a href="https://tomasvotruba.com/php-framework-trends">
<img src="/assets/images/blog/2021/nette_31_trends.png" class="img-thumbnail mt-3 mb-3">
</a>

<br>

## What has Changed in Nette 3.1?

### 1. Minimal PHP Version Bumped to PHP 7.2

```diff
 {
     "require": {
-        "php": "^7.1"
+        "php": "^7.2"
     }
 }
```

<br>

### 2. All Interfaces lost their `I` Prefix

This is the most significant change of Nette 3.1. Removing [visual clues](https://sensible.com/dont-make-me-think/) makes it harder to separate classes from interfaces, and it affects over 30 class names:

```diff
-use Nette\Application\UI\ITemplate;
+use Nette\Application\UI\Template;

-final class SomeTemplate implements ITemplate
+final class SomeTemplate implements Template
 {
    // ...
 }
```

```diff
 foreach ($rows as $row) {
-    /** @var \Nette\Database\IRow $row */
+    /** @var \Nette\Database\Row $row */
     $row->...()
 }
```

```diff
-use Nette\Localization\ITranslator;
+use Nette\Localization\Translator;
```

<br>

### Watch Out For Name Conflicts

This will create a **naming conflict with already existing class short names**, so be careful about "found & replace" upgrades:

```diff
-use Nette\Forms\IControl;
+use Nette\Forms\Control;
 use Nette\Applicatdion\UI\Control;
```

```diff
-use Nette\Application\UI\ITemplate;
+use Nette\Application\UI\Template;
 use Nette\Bridges\ApplicationLatte\Template;
```

<br>

Don't forget about configs:

```diff
 services:
     nette.mailer:
-        class: Nette\Mail\IMailer
+        class: Nette\Mail\Mailer
```

<br>

### 3. From Magical `RouteList`  to `addRoute()` Method

```diff
 $routeList = new RouteList();
-$routeList[] = new Route('<presenter>/<action>[/<id>]', 'Homepage:default');
+$routeList->addRoute('<presenter>/<action>[/<id>]', 'Homepage:default');

 return $routeList;
```

<br>

### 4. Form `addImage()` to `addImageButton()`

```diff
 $form = new Form;
-$input = $form->addImage('image');
+$input = $form->addImageButton('image');
```

<br>

### 5. New `addStaticParameters()` Method

Now its more clear what is a static parameter and what [dynamic one](https://doc.nette.org/en/3.0/bootstrap#toc-dynamic-parameters):

```diff
 // bootstrap.php
 $configurator = new Nette\Configurator();

-$configurator->addParameters([
+$configurator->addStaticParameters([
    // ...
]);

 $configurator->addDynamicParameters([
     // ...
 ]);
```

<br>

### 6. Renamed `DefaultTemplate` class

The ~~i~~nterface rename sometimes took already existing class names.
That's why some classes had to be renamed too:

```diff
-use Nette\Bridges\ApplicationLatte\Template;
+use Nette\Bridges\ApplicationLatte\DefaultTemplate;
```

<br>

### 7. New Param in `sendTemplate()` Method

```diff
 use Nette\Application\UI\Template;

 final class SomePresenter extends Presenter
 {
-    public function sendTemplate(): void
+    public function sendTemplate(?Template $template = null): void
     {
         // ...
     }
 }
```

<br>

### 8. Cache `save()` with Callable is Deprecated

[After this change you have to](https://github.com/nette/caching/commit/5ffe263752af5ccf3866a28305e7b2669ab4da82#diff-c3abbeb8a7d04b072c4f38e38e10d763123f5503f1719f852ec21e1602c4c4db) resolve data first, then pass it to cache `save()` method:

```diff
-$result = $this->cache->save($key, function () {
-    return $this->getSomeHeavyResult();
-});
+$result = $this->getSomeHeavyResult();
+$this->cache->save($key, $result);
```

<br>

### 9. Cookie now send Lax by default

```diff
 session:
     autoStart: false
-    cookieSamesite: Lax
```

```diff
-$this->response->setCookie($key, $data, $expire, null, null, $this->request->isSecured(), false, 'Lax');
+$this->response->setCookie($key, $data, $expire, null, null, $this->request->isSecured(), false);

-$this->response->setCookie($key, $data, $expire, null, null, $this->request->isSecured(), true, 'Lax');
+$this->response->setCookie($key, $data, $expire, null, null, $this->request->isSecured());
```

<br>

You can read more [detailed post about changes](https://forum.nette.org/cs/34080-novinky-v-nette-3-1-je-venku) on the Czech forum.

<br>


## 2 Steps to Make your Project Nette 3.1

Eager to try it on your project? This time try it without any `composer.json` modification - Rector will handle it too.

1. Add `NETTE_31` set your `rector.php`

```php
use Rector\Core\Configuration\Option;
use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::SETS, [SetList::NETTE_31]);
 };
```

2. Run Rector:

```bash
vendor/bin/rector process
```

That's it!

<br>

Have we missed something? [Create an issue](https://github.com/rectorphp/rector/issues/new?assignees=&labels=feature&template=2_Feature_request.md) so we can complete the set for other developers.

<br>

Happy coding!
