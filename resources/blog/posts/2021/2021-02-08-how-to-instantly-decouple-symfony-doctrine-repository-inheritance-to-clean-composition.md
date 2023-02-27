---
id: 17
title: "How to Instantly Decouple Symfony Doctrine Repository Inheritance to Clean Composition"
perex: |
    Do your Doctrine repositories extend a parent Symfony service? Do you use magic methods of parent `Doctrine\ORM\EntityRepository`?
    Would you like **switch to decoupled service design and use composition over inheritance**?

    If you're looking for "why", read [How to use Repository with Doctrine as Service in Symfony](https://tomasvotruba.com/blog/2017/10/16/how-to-use-repository-with-doctrine-as-service-in-symfony/).

    **If you know why and look for "how", keep reading this post.**
---

## The Single Class Fallacy of The Best Practise

It's always very simple to show an example of one polished class, with `final`, constructor injection, SOLID principles, design patterns and modern PHP 8.0 features. That's why it's easy to write such posts as the one above :)

**But what about real-life projects that have 50+ repositories?** Would you read a post about how someone refactored 50 repositories to services one by one? Probably not, because it would take dozens of hours just to write the post.

## Turn Fallacy to Pattern Refactoring with Rector

What if you could **change just 1 case and it would be promoted to the rest of your application**? From many cases, to just one. That's exactly what Rector help you with.

Let's see how it works. We'll use [the example from the original post](https://tomasvotruba.com/blog/2017/10/16/how-to-use-repository-with-doctrine-as-service-in-symfony/#how-to-make-this-better-with-symfony-3-3), where the goal is to [turn inheritance to composition](https://github.com/jupeter/clean-code-php#prefer-composition-over-inheritance) - one of SOLID principles.

<br>

**Instead of inheritance...**

```php
namespace App\Repository;

use App\Entity\Post;
use Doctrine\ORM\EntityRepository;

final class PostRepository extends EntityRepository
{
}
```

**...we use composition:**

```php
namespace App\Repository;

use App\Entity\Post;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

final class PostRepository
{
    private EntityRepository $repository;

    public function __construct(EntityManager $entityManager)
    {
        $this->repository = $entityManager->getRepository(Post::class);
    }
}
```


## 4 Steps to Instant Refactoring of All Repositories

### 1. Install Rector

```bash
composer install rector/rector --dev
```

### 2. Setup `rector.php`

```php
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Rector\Doctrine\Rector\MethodCall\ReplaceParentRepositoryCallsByRepositoryPropertyRector;
use Rector\Doctrine\Rector\Class_\MoveRepositoryFromParentToConstructorRector;

return function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    // order matters, this needs to be first to correctly detect parent repository

    // this will replace parent calls by "$this->repository" property
    $services->set(ReplaceParentRepositoryCallsByRepositoryPropertyRector::class);

    // this will move the repository from parent to constructor
    $services->set(MoveRepositoryFromParentToConstructorRector::class);
};
```

### 3. Run Rector on Your Code

Now the fun part:

```bash
vendor/bin/rector process /app
```

You will see diffs like:

```diff
 use App\Entity\Post;
 use Doctrine\ORM\EntityRepository;

-final class PostRepository extends EntityRepository
+final class PostRepository
 {
+    private \Doctrine\ORM\EntityRepository $repository;
+    public function __construct(\Doctrine\ORM\EntityManager $entityManager)
+    {
+        $this->repository = $entityManager->getRepository(\App\Entity\Post::class);
+    }
     /**
      * Our custom method
      *
      * @return Post[]
@@ -14,7 +22,7 @@
      */
     public function findPostsByAuthor(int $authorId): array
     {
-        return $this->findBy([
+        return $this->repository->findBy([
             'author' => $authorId
         ]);
     }
```

And your code is now both **refactored to more the cleanest version possible**. That's it!

<br><br>

Happy instant refactoring!
