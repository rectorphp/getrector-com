---
id: 30
title: "How to Migrate Legacy PHP Applications Without Stop Developing New Features"
perex: |
    Migrating legacy PHP applications is always a challenging task.

    Today I'll introduce one strategy to make these migrations easier by using the power of Rector. With this strategy, we were able to successfully migrate a legacy PHP application over a period of one year, **without stopping developing new features!**

---

## Difficulty of Migrating Legacy PHP Applications

Migrating requires high knowledge of PHP, deep understanding of the application, **and time** especially in applications running in production. Migration takes time and man-hours not only for code modification, but also for testing that the application works after the migration.

While migrating is going on, you have to stop implementing new features, otherwise you'll fall into the hell of conflict and outdated branches.
Stopping development for a certain time for the migration would be a difficult decision.

## How to solve it?

Let's see the concept first:

<img src="/assets/images/blog/2022/automated_migration_concept.png" alt="" style="max-width: 35em" class="img-thumbnail">

The most important thing was to avoid conflicts.
We were careful not to edit the same file in the “Development branch” and the “Migration branch”. We continued developing new features as usual in the “Development branch” directory for
example, `old/app`. Then we applied Rector rules to migrate `old/app`, and copied the whole result to `new/app` in the “Migration branch”, automated the process in CI.
We were free from conflicts, by always editing the app code in the “Development branch”, and only adding files in the migration branch. Also could make our “Migration branch” updated to the “Development branch” by just merging them.

You can easily run the new application by changing the namespace in composer.json if it's written in psr-4.

```diff
 "autoload": {
     "psr-4": {
-        "App\\": "old/app/"
+        "App\\": "new/app/"
      }
 }
```

With this approach, we could gain enough time to test and fix bugs of the new application, and keep on developing in different branches.

## Summary

In this article, I introduced one approach that can make migrating legacy PHP applications easier. We used this method and successfully migrated the framework of our large legacy PHP application. It can be useful for other situations such as, upgrading PHP versions or refactoring the codebase by Rector.

I'll talk about the full story of the migration in the next post, so stay tuned!
