---
id: 62
title: "How to Upgrade Zend Legacy Project"
perex: |
    Zend is the 2nd most requested project upgrade in our client group and online forums. How approach Zend upgrade, if you have no idea where to start? What are the criteria to consider? What are the alternatives?
---

Zend is one of the oldest PHP frameworks. Now it's dead and no longer active - we won't se any new project build on it in 2024.

There is a fresh Reddit thread called ["Who migrated codebase from Zend framework?"](https://www.reddit.com/r/PHP/comments/1cibspi/who_migrate_codebase_from_zend_framework/), where authors ask about other people's experience with Zend framework migration. One reply mentions 5+ years migration, that sounds like a terrible waste of resources.

We'd like to prevent such waste of resources in future Zend migration (done with us or on your own), so we'll share our approach to Zend and what turned out to be as important **criteria for successful upgrade from business point of view**.

## Main Criteria

There are 2 most important criteria to consider, if you're running on Zend and want to upgrade your stack.

* your Zend version
* your Zend-developer community you're hiring your PHP tallents from

Let's look at Zend version first.

## Zend 3

Congrats! This is the best Zend version you can have. Zend 3 itself was more or less only rebranded in 2018 to Laminass and continues its development. Here is how to [migrate from Zend to Laminas](https://docs.laminas.dev/migration/).

It's also worth reading [Zend Framework 3: A retrospective](https://getlaminas.org/blog/2020-03-09-transferring-zf-to-laminas.html) from 2020, to understand what's ahead of your after you migrate to Laminas.

<br>

## Zend 2

If you have Zend 2, the decision what to do next is more complex.

You have 2 options:

First, similar to above, upgrade your project to Zend 3. Once you're on Zend 3, then you can switch to Laminas. **Huge downside of this approach** is that there is no Zend 2 to 3 upgrade set. You'd have to do this manually or with Rector help by writing custom rules.

Zend 2 and 3 are quite different, with some packages removed and some new added - you read more about it in documentation - [Upgrading to Zend 3.0
](https://docs.zendframework.com/zend-mvc/migration/to-v3-0/).

We strongly recommend hiring a senior developer who's done such a migration before as consultant. In other case you'll easily waste a year of resources on discovering the differences between those 2 versions.

<br>

Second, you can switch to another framework like Symfony/Laravel. Surprisingly, such a switch is **cheaper to perform**, thanks to huge Symfony/Laravel ecosystem and well support in community.

Instead of 2 step migration, going Zend 2 to 3, then Laminas, this is only single step. The work is done in parallel, so feature [delivery keeps running](/blog/success-story-of-automated-framework-migration-from-fuelphp-to-laravel-of-400k-lines-application). The only work that needs to be done is to identify MVC patterns in your specific Zend 2 project. MVC patterns in Symfony/Laravel are very well documented and Zend 2 features are major subset of them.

## Zend 1

This is the worst case scenario. Zend 1 and 2 are almost complete rewrite and architecture has moved from magical autoloading to solid framework. But if its planned proppely, the upgrade can fit the same complexity as upgrade of Zend 2.

Again, still have 2 options.

Go from Zend 1 to Zend 2, go from Zend 2 to Zend 3 and then to Laminas. If you decide to go for this, hire a consultant and prepare for 3 year-budget. It's like upgrading from Windows 3.11 to 95, from 95 to 2000, from 200O to NT, then to XP and Windows 11. The difference are so huge, the A to Z jump is cheaper and more effective than going through all the versions.

The second option is to switch Zend 1 to Symfony/Laravel. It requires to identify MVC patterns in Zend 1, then writing custom rules to migrate to Symfony/Laravel. Based on our experience, Zend 1 has less features than Zend 2, so mapping to Symfony/Laravel is easier as you have less features to migrate.

## What is the community your Hire From

But, before you decide on project upgrade of framework migration, ask your PHP team and HR team very important but often missed question:

*What is the PHP community like around us?*

I came to Prague in 2014, looking for a PHP community. I only found "Zend meetup", so I went there to talk about PHP and other frameworks. It turned out, nobody there uses Zend anymore. It's only the old name of the meetup. Majority was using Symfony.

It didn't mean the Symfony was better framework per-se. It meant it's easier to find a Symfony developer in that city, in that year. **If you can hire developers that know your framework in your city**, maybe you can find talented 10x developers that will bring your project to your next level.

**If you struggle to hire any developer that knows the framework you use**, you'll have to spend resources to teach them. They'll most likely misuse the framework in non-standard way, so other developers that know the framework won't understand it. They'll make wrong design choices that will influence future, maintainability and costs of your project.

<br>

The PHP community around you is a pool of talent you can hire from. This tallent will shape future of your project in next 3-5 years.
If your pool is dry, consider changing to another pool that is active. If your pool is active, stick with it.

<br>

Happy coding!



