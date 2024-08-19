---
id: 70
title: "How to Migrate CodeIgniter to Symfony or Laravel"
perex: |
    CodeIgniter was created in 2006 and was one of the first MVC PHP frameworks. Yet it never gained traction and got stuck.

    Is your project running CodeIgniter, and do your developers want a change?

    We receive a few client requests a year for CodeIgniter project upgrades, so we'll share a few tips on migrating it to Symfony/Laravel.
---

## How Expensive is Framework Migration?

First, we have to ask the tough question: are you afraid framework migration will be expensive? It is often easier than a single framework upgrade. Let's look at an example where an upgrade of a single framework takes more steps than migration from one to another:

* Project A: Symfony 2, we make an upgrade to 3, 4, 5, 6, 7
* Project B: CodeIgniter 1/2/3 to Symfony 7

↓

* Project A has **5 steps**
* Project B has **a single step** - even if the step is quite complex, it's still only single step

<br>

## Step 1: Avoid Two Entry Points

Someone asked about ["Incremental migrate Codeigniter to Symfony" on Reddit](https://www.reddit.com/r/symfony/comments/gmldnk/incremental_migrate_codeigniter_to_symfony/) 4 years. The first suggested solution is introducing a few Symfony controllers and creating "a bridge".

**That's not a way to upgrade**. You'll end up with 2 frameworks mess instead, as one comment explains:

<blockquote class="blockquote mt-5 mb-5 text-medium" style="font-size: 1.1em">
"You're going to spend way more time getting the two frameworks to talk to each other than you think. Unless you have some very small, very targeted needs and are highly confident that won't change, setting up a way for them to communicate via events/messages is going to save you time in the long run.<br><br>I speak from experience having done something similar with multiple legacy code bases, including one that is Symfony wrapping CI."
</blockquote>

Instead, we always use a single framework. We migrate from one to another using [pattern migration](/blog/success-story-of-automated-framework-migration-from-fuelphp-to-laravel-of-400k-lines-application). This allows us to run business features in CodeIgniter while working custom Rector rules to flip to Symfony in parallel. **That way [business is growing](/blog/how-to-migrate-legacy-php-applications-without-stopping-development-of-new-features) and migration is being prepared at the same time**.

Let's dive into it.

## Step 2: Identify Patterns in both Frameworks

First, we have to identify patterns in CodeIgniter and find their equivalent in Symfony. This way, we can map them and create a migration plan.

**CodeIgniter** has:

* models to communicate with a database
* routes map that checks specific URL string, then calls a specific public method in a specific controller class
* controller classes that load services
* PHP and HTML templates to render data
* array configs to store configuration

<br>

**Symfony/Laravel** has:

* Doctrine repositories/Eloquent models to communicate with a database
* `@Route()` annotations or `routes.php` that mark specific controller methods to match URL string
* controllers using dependency injection to load services
* TWIG/Blade templates to render data
* PHP configs with fluent API to store configuration

<br>

## Step 3: From Models to Repositories

In most PHP frameworks, the database is not tightly coupled to the application. That's what *M* in MVC standards for—**model**. Symfony can work with CodeIgniter models, and CodeIgniter can work with Doctrine repositories. After all, it's only a group of arrays or simple objects.

That's why the database is low hanging fruit that we start with.

<br>

At first, we have to focus on basic principles - what do we need to replace?

* call data from the database
* return them in the form of arrays

Let's see how the model class looks in CodeIgniter:

```php
class Coupon_Model extends CI_Model
{
    /**
     * @return object
     */
    public function getCoupon($couponCode)
    {
        $this->db->where('coupon_code', $couponCode);
        $query = $this->db->get('coupons');

        return $query->row();
    }
}
```

There we can see model class name <=> table name convention. Doctrine has the same convention.

<br>

How would it look like in Doctrine?

```php
use Doctrine\ORM\EntityRepository;

class CouponRepository extends EntityRepository
{
    public function getCoupon($couponCode)
    {
         return $this->createQueryBuilder('c')
            ->andWhere('c.couponCode = :code')
            ->setParameter('code', $couponCode)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
```

<br>

At first, we focus only on getting data from the database. Result of both CodeIgniter and Symfony methods:

```php
public function getCoupon($couponCode)
```

**must be the same**. For now, it's essential to skip entities, objects, and collections and **focus only on a single pattern at a time**.

Once we've flipped the read model to repositories, we can examine other patterns, such as data storage, modification, and so on.

<br>

## Step 4: From file-routing to Controller Annotations

Controllers should be as slim as possible. Their primary function is to delegate request data to specific services and then render results.

Let's check the layers that convert URL to specific controller action - routing. CodeIgniter defines routes in `application/config/routes.php` as follows:

```php
$route['blog'] = "blog/overview";
```

The route "blog" leads to the `BlogController` class, with the `overview()` public method. Once we know the pattern, we create a custom Rector rule to read this file and generate Symfony controller annotations in the right place:

```php
/**
 * @Route("/blog", name="blog_overview")
 */
public function overview()
{
    // ...
}
```

<br>

## Step 5: Migrate Controller externals

Now that we have prepared the route and repository migration let's check the features used in controllers.

To give you a practical example, let's look at a typical CodeIgniter 1.0 controller:

```php
class Products extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // loads Product_model to "Product_model" magic property
        $this->load->model('Product_model');
    }

    public function index()
    {
        $data['products'] = $this->Product_model->get_all_products();

        // Loading the view with data
        $this->load->view('products/index', $data);
    }
}
```

We can extend rules from Step 3 to use our Doctrine repository service:

```diff
-$data['products'] = $this->Product_model->get_all_products();
+$data['products'] = $this->productRepository->get_all_products();
```

<br>

We can also see 2 more patterns we haven't covered yet:

* dependency injection

```php
public function __construct()
{
    parent::__construct();
    // loads Product_model to "Product_model" magic property
    $this->load->model('Product_model');
}
```

* template rendering

```php
// Loading the view with data
$this->load->view('products/index', $data);
```

Because our target is Symfony 7, we can work with constructor dependency injection. We create a custom Rector rule to move magic string-based dependencies to type-based dependencies:

```diff
+private ProductRepository $productRepository;

-public function __construct()
+public function __construct(ProductRepository $productRepository)
 {
     parent::__construct();
-    $this->load->model('Product_model');
+    $this->productRepository = $productRepository;
 }
```

Add similar migration for `$this->load->helper('...');` that looks like a service locator.

<br>

The next step is to add a custom rule for template rendering. We can use Symfony Twig templating engine:

```diff
-$this->load->view('products/index', $data);
+return $this->render('products/index', $data);
```

We respect the original pattern, but we use Symfony services instead.

<br>

## Step 6: Migrate Templates from PHP to Twig

Out of the box, CodeIgniter users bare PHP + HTML templates:

```php
<?php $this->load->view('some_header'); ?>

<div class="row">
    <div class="col-12">
        <h1>Product: <?= $product->title ?></h1>
    </div>
</div>
```

We can temporarily use PHP rendering in Symfony, or better finish the job and create PHP to Twig migration instead.

```diff
-<?php $this->load->view('some_header'); ?>
+{{ include('some_header') }}

<div class="row">
    <div class="col-12">
-        <h1>Product: <?= $product->title ?></h1>
+        <h1>Product: {{ $product->title }}</h1>
    </div>
</div>
```

<br>

## Step 7: Prepare for Configs

CodeIgniter has a straightforward way to configure your project:

```php
<?php

$hook['some_option'][] = [
    'key' => 'value'
];
```

Simple array. Using [Symfony PHP fluent API](/blog/modernize-symfony-configs), this should be easy to migrate.

Either to parameters or bundle configurations:

```php
// config/security.php
use Symfony\Config\SecurityConfig;

return static function (SecurityConfig $securityConfig): void {
    $securityConfig->enableAuthenticatorManager(true);
};
```

<br>

## Step 8: Migrate Controllers

By now, we have chipped everything we could off controllers:

* routing via annotations
* dependency injection via constructor
* template rendering via TWIG
* database calls via repository

If nothing else is left, we create a custom Rector rule to migrate CodeIgniter controllers to Symfony.


<br>

This is the gist of CodeIgniter to Symfony migration. Every project is strictly individual and requires custom work on Rector rules to cover all the patterns.

It's important to note that these rules must **dry-run in CI** on the fly so you can see the progress and fix edge cases. Do not make migration until all the known patterns are covered by Rector rules. That way, you save yourself from manual work and bugs.

<br>

Happy coding!
