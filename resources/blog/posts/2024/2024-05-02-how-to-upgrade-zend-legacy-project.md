---
id: 62
title: "How to Upgrade Zend Legacy Project"
perex: |
    Zend is the second most requested project upgrade in our client group and online forums. If you have no idea where to start, how should you approach the Zend upgrade? What criteria should you consider? What are the alternatives?
---

Zend is one of the oldest PHP frameworks. It's dead and no longer active—in 2024, no new projects will be built on it.

There is a fresh Reddit thread called ["Who migrated codebase from Zend framework?"](https://www.reddit.com/r/PHP/comments/1cibspi/who_migrate_codebase_from_zend_framework/), where authors ask about other people's experience with Zend framework migration. One reply mentions a 5+ year migration, which sounds like a terrible waste of resources.

We want to prevent such a waste of resources in future Zend migration (done with us or on your own), so we'll share our approach to Zend and what turned out to be an important **criteria for a successful upgrade from a business point of view**.

## Main Criteria

If you're running on Zend and want to upgrade your stack, there are 2 most important criteria to consider.

* your Zend version
* your Zend-developer community you're hiring your PHP talents from

Let's look at the Zend version first.

## Zend 3

Congrats! This is the best Zend version you can have. Zend 3 itself was more or less only rebranded in 2018 to Laminass and continues its development. Here is how to [migrate from Zend to Laminas](https://docs.laminas.dev/migration/).

It's also worth reading [Zend Framework 3: A retrospective](https://getlaminas.org/blog/2020-03-09-transferring-zf-to-laminas.html) from 2020 to understand what's ahead of you after you migrate to Laminas.

## Zend 2

If you have Zend 2, deciding what to do next is more complex.

You have 2 options:

First, similar to the above, upgrade your project to Zend 3. Once you're on Zend 3, you can switch to Laminas. **The huge downside of this approach** is that there is no Zend 2 to 3 upgrade set. You'd have to do this manually or with Rector's help by writing custom rules.

Zend 2 and 3 are quite different, with some packages removed and some newly added - you can read more about it in the documentation - [Upgrading to Zend 3.0
](https://docs.zendframework.com/zend-mvc/migration/to-v3-0/).

We strongly recommend hiring a senior developer who's done such a migration as a consultant. Otherwise, you'll easily waste a year of resources discovering the differences between those 2 versions.

<br>

Second, you can switch to another framework like Symfony/Laravel. Surprisingly, such a switch is **cheaper to perform**, thanks to the vast Symfony/Laravel ecosystem and well community support.

Instead of a 2-step migration, going Zend 2 to 3, then Laminas, this is only a single step. The work is done in parallel, so feature [delivery keeps running](/blog/success-story-of-automated-framework-migration-from-fuelphp-to-laravel-of-400k-lines-application). The only work that needs to be done is to identify MVC patterns in your specific Zend 2 project. MVC patterns in Symfony/Laravel are very well documented, and Zend 2 features are a major subset of them.

## Zend 1

Zend 1 is the worst-case scenario. Zend 2 is an almost complete rewrite compared to Zend 1, and the architecture has moved from magical autoloading to a solid framework. But if it's planned properly, the upgrade can be the same complexity as the upgrade of Zend 2.

Again, you still have 2 options.

Go from Zend 1 to Zend 2, Zend 2 to Zend 3, and then to Laminas. If you decide to go for this, hire a consultant and prepare a three-year budget.

It's like upgrading from Windows 3.11 to 95, from 95 to 2000, from 200O to NT, then to XP and Windows 11. The difference is so huge that the A to Z jump is cheaper and more effective than going through all the versions.

The second option is to switch Zend 1 to Symfony/Laravel. This requires identifying MVC patterns in Zend 1 and then writing custom rules to migrate to Symfony/Laravel. Based on our experience, Zend 1 has fewer features than Zend 2, so mapping to Symfony/Laravel is easier as there are fewer features to migrate.

## What is the community you Hire From

But, before you decide on a project upgrade of framework migration, ask your PHP team and HR team essential but often missed questions:

What is the PHP community like around us?
What developers can we hire at a reasonable price?
What PHP framework is well supported in our city if you want your developer to grow?

I came to Prague in 2014, looking for a PHP community. I only found "Zend meetup", so I went there to talk about PHP and other frameworks. It turned out that nobody there uses Zend anymore. It's only the old name of the meetup. The majority was using Symfony.

It didn't mean the Symfony was a better framework per se. It meant it was easier to find a Symfony developer in that city in that year.

* **If you can hire developers who know your framework in your city**, maybe you can find talented 10x developers who will take your project to the next level.

* **If you struggle to hire a developer who knows the framework you use**, you'll have to spend resources to teach them. They'll most likely misuse the framework in a non-standard way, so other developers who know the framework won't understand it. They'll make wrong design choices that will influence the future, maintainability, and costs of your project.

<br>

The PHP community around you is a pool of talent you can hire from. This talent will shape the future of your project in the next 3-5 years.
If your pool is dry, consider changing to another active pool. If your pool is active, stick with it.

<br>

Happy coding!
