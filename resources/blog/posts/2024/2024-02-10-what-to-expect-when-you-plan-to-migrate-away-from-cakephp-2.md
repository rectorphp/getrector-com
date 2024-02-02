---
id: 58
title: "What to expect when you plan to Migrate Away from CakePHP 2"
perex: |
    What is the most requested project we get from our clients? PHP upgrade, Symfony upgrade, framework switch... yes, these belong to the most common ones. But one of the requests is far beyond the most requested one. From CakePHP 2 to Symfony/Laravel.
---

*Disclaimer: This post is no rant about any framework. It's about the process of migration that our clients often request.*

Most companies can handle PHP, Laravel, or Symfony upgrades themselves by using bare Rector. But the CakePHP 2 migration is a different story. It's a framework feature-wise similar to Symfony/Laravel, so it's an obvious target to migrate to one of them. We get, on average, 3 requests a year and can only handle some of them. We thought we'd share the process with you so you can start yourself.

<br>

So what is the problem with CakePHP 2 then?

## The PHP 5.2-feature-lock

The CakePHP 2 was written around the era of Zend 1, when standardized autoload was not a thing. Some of you remember the `Under_score` approach that was pre-step to namespace separator `\\`. Finally, PHP 5.3 first introduced namespaces which was released in 2009. A lot of frameworks went for a back-ward compatibility approach and didn't use namespaces for a long time.

That's why a typical CakePHP 2 class looks like this:

```php
App::uses('Controller', 'Framework');

class ChatGPTController extends Controller
{
}
```

It's full of `App::uses()` calls.

As I write in [8 Steps You Can Make Before Huge Upgrade to Make it Faster, Cheaper and More Stable](https://tomasvotruba.com/blog/2019/12/16/8-steps-you-can-make-before-huge-upgrade-to-make-it-faster-cheaper-and-more-stable/), the first prerequisite before starting any upgrade is to have classes autoloaded with PSR-4. In some projects, this is a matter of weeks, using [smart tooling](https://github.com/symplify/easy-ci).

<br>

In the case of a CakePHP 2, it's a real challenge. We love challenges in the Rector team, so let's dive into it.

What is happening here?

```php
App::uses('Controller', 'Framework');

class ChatGPTController extends Controller
{
}
```

We created a controller class that extends some Controller classes. There is some kind of PHP 5.3 use-import-like call. How would such code look written in PHP 5.3?

```php
use Framework\Controller;

class ChatGPTController extends Controller
{
}
```

The `Framework\Controller` class does not exist; it is not autoloadable and thus [invisible to PHPStan](https://github.com/phpstan/phpstan/discussions/5257) and static analysis. There is a [PHPStan extension](https://github.com/sidz/phpstan-cakephp2) that can help you, but it's not a long-term solution.

## Step 1: Make Classes Autoloadable

The first step is to make all classes autoloadable with PSR-4. -+

Create 2 [custom rules](https://getrector.com/documentation/custom-rule) to handle `App::uses()` and `App::imports()` calls:

```diff
-App::uses('Controller', 'Framework');
+use Framework\Controller;

class ChatGPTController extends Controller
{
}
```

This looks simple enough, right? There is a catch. Now we must find the `Controller` class in a `Framework` "namespace" and actually add the namespace to the class:

```php
// src/Framework/Controller.php
class Controller
{
}
```

CakePHP 2 uses paths to assume the namespace. So, the class `Controller` located in the `Framework` folder is considered to be in the `Framework` namespace. The same class located in the `Admin` directory would have an `Admin` namespace.

Now we create a 3rd rule, that autocompletes the namespace:

```diff
+namespace Framework;

 // src/Framework/Controller.php
 class Controller
 {
 }
```

Now we use


## Step 2: Move away from CakePHP 2 autoloader

To make the PSR-4 autoloader work, we have to get rid of the magic CakePHP autoloader that could give us a false sense of security. The  CakePHP 2 `App::load()` method states following:

```php
class App
{
    /**
     * Method to handle the automatic class loading. It will look for each class' package
     * defined using App::uses() and with this information, it will resolve the package name to a full path
     * to load the class from. The file name for each class should follow the class name. For instance,
     * if a class is named `MyCustomClass` the file name should be `MyCustomClass.php`
     *
     * @param string $className the name of the class to load
     * @return bool
     */
    public static function load($className)
    {
        // ...
    }
}
```

We want to ensure all our classes are loaded with PSR-4, so we have to cut off any autoloader that string classes could fall back to. We can do that by removing the places where CakePHP registers the autoload. This can be located in various places, depending on the project.

**But we must be careful here**, as removing such code could break something else in CakePHP internals that still depends on the magic autoload.

## Step 3: Make sure CakePHP 2 is Part of Your Project

This is where we come to the next important step. We might have removed the old CakePHP 2 code that fakes the `use` and `namespace` calls, but the internal use of the framework still depends on it.

We may have to visit the internal working of the CakePHP 2 codebase and refactor it.

To enable that, make sure the `CakePHP 2` library is not a dependency in your `composer.json`, but located directly in `/library/cakephp2` directory. This way, we can easily refactor the codebase without breaking the framework.

<br>

Look for `ClassRegistry` static calls, and beware the [special `Model` type](https://github.com/sidz/phpstan-cakephp2/blob/fa4edd9fc56b81a28576342b753316b2431a8253/src/ClassRegistryInitExtension.php#L61-L67).

```php
ClassRegistry::init()
```

This piece of work is very individual and depends on the project. It depends on what parts of the framework you use and how you use them. Creating a custom rule would be overly complex and not satisfy the needs of every project.

Take a deep breath and start refactoring. Give special care to plugins.

## Step 4: Prepare Rector Migration rules

We've done much hard work - all classes autoloaded with PSR-4, and the CakePHP 2 autoloader was removed. The class names are unique and use PHP 5.3 namespaces. It's time to prepare custom framework migration rules from CakePHP 2 to Symfony/Laravel.

Depending on your project, this may include a custom rule:

* to refactor the controller to Symfony/Laravel
* to refactor the model to Doctrine/Eloquent
* to refactor validation to Symfony/Laravel validation attributes and rules
* to refactor views to Twig/Blade

Again, this part is strictly individual and depends on the project. It's a good idea to start with a small, isolated model in the project and see how it goes.

We plan to extend this post as we learn more about the process. If you have any experience with this, please share it with us at [@rectorphp](https://twitter.com/rectorphp).

<br>

Happy coding!
