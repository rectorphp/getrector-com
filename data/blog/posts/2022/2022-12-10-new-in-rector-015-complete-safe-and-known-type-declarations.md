---
id: 45
title: "New in Rector 0.15: Complete Safe and Known Type Declarations"
perex: |
    Rector is helping PHP upgrades and framework migrations. It also helps to [rise the type coverage](https://tomasvotruba.com/blog/how-to-measure-your-type-coverage/) of your project.

since_rector: "0.15.0"
---

Rector completes the type declarations for parameters, returns and properties, one Rector rule per each case. The only problem was, **these rules use PHPStan type inference that relies on docblocks**. That means it could complete strict types that are not actually true. You burn once and then you ignore these rules forever.

### We want you to Feel 100 % Safe

Our goal is to make Rector reliable, so it does not guess and does not requires your attention on every change. Instead it must work without flaws and be 100 % reliable.

That's why [we slowly refactored away from these rules](/blog/how-to-automatically-add-return-type-declarations-without-breaking-your-code), **split them into dozens small ones that work only with strictly known types**.

<br>

<blockquote class="twitter-tweet"><p lang="en" dir="ltr">Next release brings huge improvement in type infering 💪<br><br>We moved many rules from unreliable docblocks <br>to 100 % sure type declarations ↓<br><br>Every project will get the most type coverage they can with guaranteed safety 😎<a href="https://t.co/kAuyIbaK48">https://t.co/kAuyIbaK48</a> <a href="https://t.co/xQlyxf6vTz">pic.twitter.com/xQlyxf6vTz</a></p>&mdash; Rector (@rectorphp) <a href="https://twitter.com/rectorphp/status/1599001416718163968?ref_src=twsrc%5Etfw">December 3, 2022</a></blockquote>

<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>



<br>

## Known and Safe Types ~~First~~ Only

Before, Rector would make this change:

```diff
 class Project
 {
-    private $name;
+    private string $name;

     /**
      * @param string $name
      */
     public function __construct($name)
     {
         $this->name = $name;
     }
}
```

<br>

Then we create the `Project` based on API call:

```php
$projectName = $this->apiCaller->getProjectName(123);
$project = new Project($projectName);
```

and get crash on `null` being passed to `Project` constructor. We don't want that.

From Rector 0.15, the `ParamTypeDeclarationRector`, `ReturnTypeDeclarationRector`, `PropertyTypeDeclarationRector` and their array alternatives are removed and this will not happen.

<br>

Now, only **strict type declarations are taken into an account**:

```diff
 class Project
 {
-    private $name;
+    private string $name;

     public function __construct(string $name)
     {
         $this->name = $name;
     }
}
```

or

```diff
 class Project
 {
-    public function getSize()
+    public function getSize(): int
     {
        return strlen($this->name);
     }
}
```

## How to Upgrade to Rector 0.15?

The `TYPE_DECLARATION_STRICT` set was removed and reliable rules are moved to the `TYPE_DECLARATION`. Now you can use single set to handle the types.

```diff
 use Rector\Config\RectorConfig;
 use Rector\Set\ValueObject\SetList;

 return static function (RectorConfig $rectorConfig): void {
     $rectorConfig->sets([
         SetList::TYPE_DECLARATION,
-        SetList::TYPE_DECLARATION_STRICT,
    ]);
 };
```

<br>

## Protip: One by one

Take a single rule from `TYPE_DECLARATION` set, one by one. Apply the rules slowly on your code base and create a pull-request per rule. Soon the strict type declarations will be everywhere they can.

Start with return type declaration rules first. They're the most easy to apply and tolerated thanks to [return type covariance](https://www.php.net/manual/en/language.oop5.variance.php).
