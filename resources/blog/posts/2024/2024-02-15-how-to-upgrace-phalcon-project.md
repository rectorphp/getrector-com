---
id: 61
title: "How to Upgrade Phalcon project"
perex: |
    Phalcon is a PHP framework that is written in C and is known for its speed. It was created in 2012 and it has own PHP-like language - Zephir. After CakePHP, this is the most requested framework to handle. We though we'll share your options if you want to upgrade your project running on Phalcon.
---

<img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Phalcon-hires.png/120px-Phalcon-hires.png" style="float: right">

In 2010-2015 there was a boom of PHP frameworks - FuelPHP, CodeIgniter, CakePHP or Yii. While you can still download these packages over composer, they are no longer active nor growing. Phalcon belongs to this group.

## Finished Upgrade != Successful Upgrade

We did a huge Phalcon upgrade to from version 4 in 2019, and we used to provide [upgrade set in Rector](https://github.com/rectorphp/rector/pull/2437). It was quite a challenge, because Phalcon project is not written in PHP, but in slightly different syntax. We had to create a custom parser for it.

Yes, we helped our client to use newer version... but:

<blockquote class="blockquote text-center mt-5 mb-5">
We've completed the upgrade task as we agreed,<br>
but they still struggled with the same issues as before.
</blockquote>

Our mission is not to upgrade project, but also make the project **cheaper and easier to maintain in the future**, and help our clients grow in the long term.

They **could not hire new developers that use Phalcon**, because they were so rare they were extremely expensive.

If they did hire a junior developer, they could not teach them new technologies like Vue, Redis, RabbitMQ, notifications or sockets. They were stuck in the past, only with newer version of the same framework.

<br>

## Why Phalcon is Not a Good Choice in 2024

Why was that? The Phalcon framework - as many others - did not get enough **traction to create an active community**. In 2020 there was [announced Phalcon 6](https://en.wikipedia.org/wiki/Phalcon_(framework)) as a native PHP framework like every other, but it's still not released. The composer package [has 77 download over past 3 years](https://packagist.org/packages/phalcon/phalcon/stats).

<br>

Same goes for for cphalcon package, that has on average [~ 400 daily downloads](https://packagist.org/packages/phalcon/cphalcon/stats):

<img src="https://private-user-images.githubusercontent.com/924196/303916132-57a7e11a-f241-460e-9d1c-10d88cda2837.png?jwt=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJnaXRodWIuY29tIiwiYXVkIjoicmF3LmdpdGh1YnVzZXJjb250ZW50LmNvbSIsImtleSI6ImtleTUiLCJleHAiOjE3MDc2NTI1MDYsIm5iZiI6MTcwNzY1MjIwNiwicGF0aCI6Ii85MjQxOTYvMzAzOTE2MTMyLTU3YTdlMTFhLWYyNDEtNDYwZS05ZDFjLTEwZDg4Y2RhMjgzNy5wbmc_WC1BbXotQWxnb3JpdGhtPUFXUzQtSE1BQy1TSEEyNTYmWC1BbXotQ3JlZGVudGlhbD1BS0lBVkNPRFlMU0E1M1BRSzRaQSUyRjIwMjQwMjExJTJGdXMtZWFzdC0xJTJGczMlMkZhd3M0X3JlcXVlc3QmWC1BbXotRGF0ZT0yMDI0MDIxMVQxMTUwMDZaJlgtQW16LUV4cGlyZXM9MzAwJlgtQW16LVNpZ25hdHVyZT1hNTVhMTkxN2QzNjZiZDg2NjllNTJjNTkzZGI0NTBjMDIzOTMxOWVhMTRkYmQ4Y2M2YzEyZjBlY2ZhNzBjOTMzJlgtQW16LVNpZ25lZEhlYWRlcnM9aG9zdCZhY3Rvcl9pZD0wJmtleV9pZD0wJnJlcG9faWQ9MCJ9.XmiwsZs-dcPh6qrjOtXQPOKWIkWnAFy86RDrfzcsIW0" class="img-thumbnail">

<br>

That means even if you upgrade to the latest Phalcon 5 version, your next upgrade will be even harder - because of flip from C extension to native PHP code.

<blockquote class="blockquote text-center mt-5 mb-5">
It's like getting a mortgage with following deal:<br>
when you finally pay it off,<br>
we'll reset it and you'll start paying again.
</blockquote>

We don't mean to sound negative, but we want to help you make the best decision for future of your project. It's better to work with realistic data and make informed decision.

<div class="clearfix"></div>

## What is a Successful Upgrade?

Would you buy a house, that has beautiful facade, but the inside is still from 1950s? No internet, poor electricity, and coal heating.  Probably not. The same goes for software. The facade is the framework, the inside is the code. If you upgrade the facade, but not the inside, you're not getting any extra value.

Our priority is to **get our clients to sustainable and maintainable codebase**, that will accelerate the business growth. Even after we're long done, **your code should work for you**, so your company can:

* hire new developers at reasonable price
* have free resources to educate them - videos, articles or book
* have a framework that attracts new developers with talent
* have cheap and fast adoption of new technologies

<br>

This often depends on the community around the framework. If your developers can't got to a meetup or a conference of your framework, they can't learn new effective way to use it and they become stuck in the past. So will your project.

## Should we Rewrite the project?

Saying that, the only options seems to rewrite the project into a modern framework like Symfony or Laravel, right? Not necessarily. Much cheaper than [known "rewrite from scratch" mistake](https://www.joelonsoftware.com/2000/04/06/things-you-should-never-do-part-i/), is to switch the framework.

Various PHP MVC frameworks are sometimes more similar, than version 2 or 3 of the same framework. That means it's easier to switch from Phalcon to Symfony/Laravel, than from Phalcon 4 to Phalcon 5.


<blockquote class="blockquote text-center mt-5 mb-5">
The reason is that we migration framework X to Symfony/Laravel so often,<br>
it's getting easier and cheaper every year.

<br>
<br>
While upgrade of non-active framework X to X+1 is done once in a blue moon,
and is always a challenge from scratch, often costly.<br>
</blockquote>

<br>

## Migrate to Symfony/Laravel

The migration to Symfony/Laravel are requested so often, we have a boilerplate ready and we fill in edge cases specific for your project.

We mostly handle these migrations behind NDA, but we can share some stories:

* In 2019, we've done a middle-size production project [Nette to Symfony migration](https://tomasvotruba.com/blog/2019/08/26/how-we-migrated-54-357-lines-of-code-nette-to-symfony-in-2-people-under-80-hours) under 80 hours - see [Rector ruleset](https://github.com/deprecated-packages/rector-nette-to-symfony)
* In 2022, Rajyan shared a story o [400 000 lines project migration from FuelPHP to Laravel](https://getrector.com/blog/success-story-of-automated-framework-migration-from-fuelphp-to-laravel-of-400k-lines-application)
* In 2023, we created ruleset to migrate [Symfony to Laravel](https://github.com/TomasVotruba/laravelize), templates included

<br>

Both Symfony and Laravel communities are active with 4-6 conferences every year:

* [Laravel conferences](https://laravel-news.com/events)
* [Symfony conferences](https://live.symfony.com/)

<br>

## Migrate your Project to Success

Despite your fist initial choice to "only upgrade" single version, the framework migration is cheaper and faster solution that brings you long-term success.

That way your both facade and inside will be modern and attractive for new developers that will be happy to take care of your project.

<img src="https://www.pngitem.com/pimgs/b/613-6131352_model-3-tesla-tesla-model-3-png-transparent.png" style="max-width: 30em" class="mt-4 mb-4">


<br>
<br>

Happy coding!
