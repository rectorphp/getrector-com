---
id: 45
title: "New in Rector 0.15: Complete Safe and Known Type Declarations"
perex: |
    Rector is helping with PHP upgrades and framework migrations. It also helps to [rise the type coverage](https://tomasvotruba.com/blog/how-to-measure-your-type-coverage/) of your project.

since_rector: "0.15.0"
---

Rector completes the type declarations for parameters, returns, and properties, with one Rector rule per each case. The only problem was, **these rules use PHPStan type inference that relies on docblocks**, and it could complete strict types that are not true. You burn once, and then you ignore these rules forever.

### We want you to Feel 100 % Safe

Our goal is to make Rector reliable, so it does not guess and does not require your attention on every change. Instead, it must work without flaws and be 100 % reliable.

That's why [we slowly refactored away from these rules](/blog/how-to-automatically-add-return-type-declarations-without-breaking-your-code), **split them into dozens of small ones that work only with strictly known types**.

<br>

<blockquote class="twitter-tweet"><p lang="en" dir="ltr">Next release brings huge improvement in type inferring 💪<br><br>We moved many rules from unreliable docblocks <br>to 100 % sure type declarations ↓<br><br>Every project will get the most type coverage they can with guaranteed safety 😎<a href="https://t.co/kAuyIbaK48">https://t.co/kAuyIbaK48</a> <a href="https://t.co/xQlyxf6vTz">pic.twitter.com/xQlyxf6vTz</a></p>&mdash; Rector (@rectorphp) <a href="https://twitter.com/rectorphp/status/1599001416718163968?ref_src=twsrc%5Etfw">December 3, 2022</a></blockquote>

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

Then we create the `Project` based on the API call:

```php
$projectName = $this->apiCaller->getProjectName(123);
$project = new Project($projectName);
```

and get a crash as pass a `null` to the `Project` constructor. **We don't want that.**

From Rector 0.15, we removed the `ParamTypeDeclarationRector`, `ReturnTypeDeclarationRector`, and `PropertyTypeDeclarationRector` and their array alternatives, so this will not happen.

<br>

Now, Rector takes into account **only strict type declarations**:

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

We removed the `TYPE_DECLARATION_STRICT` set and moved reliable rules to the `TYPE_DECLARATION` set. Now you can use a single set to handle the type declarations.

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

Take a single rule from the `TYPE_DECLARATION` set, one by one. Apply the rules slowly on your code base and create a pull request per rule. Soon the strict type declarations will be everywhere they can.

Start with return type declaration rules first. They're the easiest to apply and tolerated thanks to [return type covariance](https://www.php.net/manual/en/language.oop5.variance.php).
