---
id: 12
title: "7 Valuable Lessons We Learned from our Clients in 2020"
perex: |
    2020 was a big year for us. We had 4 large projects with only tests in CI. Adding ECS with 10 basic sets, PHPStan to level 8, PSR-4 to all classes. In the end, we successfully upgraded Nette 2.2 to 3.0, Symfony 2.7 to 3.4 and Laravel 5.5 to 5.8, to Symfony, and from PHP 5.6 to 7.4. Oh, we also migrated Phalcon to Symfony.

    **The secret of a successful migration is speed and fast merges**. During these 8 months of intense work, sometimes even 200 hours a month, **we failed a lot**. We try to learn from our mistakes.

    Today we want to share what we've learned from our clients in 2020.
---

<blockquote class="blockquote text-center">
    "Hindsight Is 20/20."
</blockquote>

...moreover, in 2020. We want to share our failures so that you can prepare better prepared for your own.

## 1. Every Client is Different

Our focus groups are CTOs of projects that have decided to make a giant leap forward. That's a common trait that defines a good client. From that onward, it's subjective. Each person is different. While one client wants to be part of the migration and knows about each change, another client is happy for CI green checkbox in the CI. While one client wants to outsource the whole project to an external company, another client wants to contribute to your pull-requests.

Ask, communicate, set boundaries, and respect them mutually.

## 2. Communicate Possible Temporary Hacks

Nothing is at it seems at first sight. Developers use available framework features to their profit, regardless of what documentation or everyday use case it. E.g., the project has `composer.json` with a couple of internal packages:

```json
{
    "require": {
        "company-name/some-package": "^1.1",
        "company-name/another-package": "^1.2"
    }
}
```

These are internal private packages that are only available to the project. The developer usually creates internal packages to extract a package that is used by many company projects. E.g., an agency creates a package to work with payments. To make it simpler, they move it to their repository, and every new project is using it.

When you upgrade the main project from PHP 5.6 to 7.0, you have to upgrade the "another-name/some-package". It takes time, testing, tagging, and fixing bugs found during this cycle. So we started this cycle and worked on it for 5 days.

Guess what. The package "another-name/some-package" is used **only by the main project** and it was decoupled because "it seemed like a good idea". There is no actual reason to keep it separated. We find this out too late when our client suggested, "we can move this package to a local vendor. It's used only by this project". We've just wasted 5 days of work.

Of course, the client knows better, as we don't have access to their repositories, **but we should have asked**:

<blockquote class="blockquote text-center pt-4 PB-4">
    "Why is this package in a standalone repository?
    <br>
    How many other repositories does it use?
    <br>
    Just one? Can we inline it here?
    <br>
    It would speed up the migration and save a lot of work."
</blockquote>

Now we know. The correct approach for a package that people tend to refactor to their repository, but it is used only by the project they're trying to refactor it from is... local package. But it's not [very known information](https://tomasvotruba.com/blog/2018/11/19/when-you-should-use-monorepo-and-when-local-packages/), so instead of using the local package, a new repository with huge maintenance cost is added.

We were afraid to doubt our client about a lack of this knowledge. Next time we will politely ask to save both sides the troubles.

## 3. Create probe PRs

Sometimes it's to upgrade than it seems. Honestly, it rarely is, but it's worth giving a "blitzkrieg" push a try. What does that mean? E.g., change `composer.json`:

```diff
 {
     "reqiure": {
-        "nette/application": "^2.4",
+        "nette/application": "^3.0"
     }
 }
```

Run:

```bash
composer update
```

Then try to run an application and try to process as many exceptions as possible. One by one in a specific time frame, e.g., 2-4 hours. In the end, the project could be upgraded (not likely), or you'll end up with **un-mergeable broken pull-request**.

**What now?** Revert and give up? Continue with the frustration of end out of sight?

<br>

Don't worry. This work has its value, just not in being merged.
### 3 Valuable Takeaways

- some of these commits **can be applied even to older version**
- some of these commits can be **turned into Rector rule** and automated next time
- some of these commits can be automated in some other way, e.g. [Latte to Twig converter](https://github.com/symplify/latte-to-twig-converter)
- some of these commits have to be done manually - we take note of them so we don't forget them

Now, we automated steps 1, 2, and 3. When we're done, we'll check out from the `master` branch and repeat the same probe process. But now we have 1, 2, and 3 automated, so we have a lot of extra time and work for manual-only work. We'll get much further in every iteration.

In the end, it might take 2-5 of such iterations. **In the end, we have 30 commits before the upgrade even started, 10 new Rector rules, 2 new packages. And the project is migrated.**

## 4. Go for as small PRs a Possible

This is very important, very. It's easy to create a big pull-request with 30 commits. The power is in small, independent pull-request.

- If PR can be split, we have to do it
- If this commit can be cherry-picked to the pre-migration phase, we have to do it
- If one of 5 commits is not passing it, drop it and make pull-request pass

**The confidence is the key here. The project owner has to feel they're in control, the pull-request will not break anything, and that CI is passing**. If these conditions are met, pull-request can be merged quickly, and you can have a fast feedback loop. We managed to create such a feedback loop with one of our clients - then it was a standard to create 7-8 PRs a day, and they've merged within an hour. After a week of such cooperation we pushed the project further than we could do in the last month.

## 5. Have a Regular Calls with Face to Face

It was quite a challenge to start 4 new cooperations during times of corona. It was forbidden to meet, everyone worked from home with their spouse, dogs, cats, and families, and chaos was everywhere. That's why we often met in random cases. We had to agree on time each week or two. The time was different for each meeting, once 11 AM, then 3 PM. Sometimes we had a call over the phone, sometimes just emails.

This worked when everything went by expectations. But where there were frictions, the lack of communication was a problem. We got stuck over a problem with one client that we could've solved in a short call. But it would mean to organize a meeting, settle on a date that might be next week, and we both wanted to solve it fast. It created confusion on both sides, which was purely organizational.

We're very grateful that our clients were understanding and open to corrections. After this mistake, we decided to have a **week-call based on a specific time, as short as 15 minutes**, to catch up and have a space for trouble management.

Even if there was 0 work done, we knew we could talk to each other, see each other and mainly share updates out of our control, like 3rd party client requests, upcoming vacations, hot fixing server failure priority, or budget/time changes.

## 6. Get CI First, Even if it takes a Long Time

How do you know your project works? And how do you know the project you've just opened for the first time works?
For us, it always a first time, and [fast feedback](https://tomasvotruba.com/blog/2020/01/13/why-is-first-instant-feedback-crucial-to-developers/) is crucial. When we run Rector upgrade that changes 10 000 lines (which we do a lot), the fast feedback loop is essential also to our clients.

With the first project, we usually tried to deliver the visual upgrade first. That means something changed in `composer.json` and a lot of changed files. But without proper CI setup, this ends up with a couple of bugs that we wasted days or weeks on.

After this mistake, **we started to CI setup first before any migration**. First 4-6 weeks, there is no upgrade. Even though we risk losing a client because "they only want the upgrade", we stand behind this priority. We only focus on CI and [preparation steps that make the rest of migration cheaper and faster](https://tomasvotruba.com/blog/2019/12/16/8-steps-you-can-make-before-huge-upgrade-to-make-it-faster-cheaper-and-more-stable/). Consider it a year training program before going to war. Would you go to was right away or get training first?

We apply the same to coding. After we:

- setup ECS with 10 basic sets
- switch classes completely to PSR-4
- add PHPStan to max level
- run [class-existence checks](https://github.com/symplify/class-presence)
- automated tests running on CI

The migration is ready to go. Then it's fast as there are just little pitfalls to overcome.

## 7. Scoped and Downgraded Rector is Must Have

It's easy to upgrade a project running PHP 7.4 to 8.0. I bet we could upgrade any such project under a day (if we exclude dependency PHP vendor locks). **But the lower PHP version is, the more difficult it gets**. Rector 0.9 itself requires at least PHP 7.3. What if your project has PHP 7.1 or 7.2 or even 5.6? Then we have to switch to Rector in a Docker.

Developers can handle elementary upgrades without our help, so most of our clients can be found between PHP 5.6 and 7.1.
Docker is very problematic to set up because projects that are legacy and need our help are legacy for a reason.

That's why we started a focus on downgrades in the Autumn 2020. The PHP 7.1 version is [almost ready](https://github.com/rectorphp/rector/pull/4447). Then we plan to continue to PHP 7.0 and PHP 5.6. The goal is to allow more accessible  composer-like installation even on lower PHP:

```bash
composer require rector/rector-php56 --dev
```

This way, **even the oldest project could upgrade from PHP 5.6 to 7.2 only with composer**.


<br>

The year 2020 was a challenge for all of us. We learned a lot, thanks to our client and their patience with our upgrade process.

Without mistake, there is no learning. We hope you've learned a lot from our failures that are not related to migrations only. Good luck with yours!

<br>

Happy new year 2021!
