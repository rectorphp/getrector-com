---
id: 78
title: "How to Strangle your Project with Strangle Anti-Pattern"
perex: |
    Nearly half of the project we help upgrade have tried the upgrade before on their own.
    They've introduced infamous "strangle pattern". It's a way to upgrade project separating one part of the codebase from the rest at a time.

    Those companies reach us, because while the strangle pattern is great in theory, it actually strangles the project in practice. They're unable to move, they use now 2 frameworks instead of one and team has to work with complexity squared.

    Today we'd like to share why it's a terrible choice and what upgrade strategy to take instead.
---


## What is "Strangle Pattern"?

In programming, the strangler pattern is an architectural pattern that involves wrapping old code, with the intent of redirecting it to newer code or to log uses of the old code ([taken from Wikipedia](https://en.wikipedia.org/wiki/Strangler_fig_pattern)).

How does it look in practise?

Let's say we have an old CakePHP, CodeIgniter, Yii or plain PHP project and you want to upgrade to Symfony or Laravel. You start by isolating part of the project, e.g. invoicing, and wrap it with Symfony or Laravel controller.

It practise, it can look something like:

<div class="text-center mb-5">
    <img src="https://accesto.com/blog/static/d20bbd2696d22557b2048fb00c8fc598/3c492/code-refactoring.png" style="max-width: 30em" class="img-thumbnail">
    <br>
    <small class="text-secondary">
        From <a href="https://accesto.com/blog/monolith-to-microservices/">Monolith to Microservices</a>
    </small>
</div>

That way, we'll have soon the full invoicing in Symfony or Laravel, right?

<br>

No, in practise **this is a terrible idea**. Why?

## Just a single Controller

What is necessary to make to introduce a single Symfony/Laravel controller into an old project?

* we have to setup HTTP layer for response and request
* we have to setup routing
* we have to setup dependency injection container and interconnect it with old project
* we have to make sure the services used in controller are correctly autowired

To setup single invoicing controller, we have to setup the whole Symfony/Laravel HTTP, DI and Routing layers. This could be months of work without showing any value to the customer.

<br>

## The Mythical Man-Month

Have you heard of [The Mythical Man-Month](https://en.wikipedia.org/wiki/The_Mythical_Man-Month) book? Its central theme is that **adding manpower to a software project that is behind schedule delays it even longer**.

<blockquote class="blockquote text-center mt-5 mb-5">
Same way adding parallel framework<br>
will make upgrading the project even harder.
</blockquote>

If we could come to a project in a moment before they put 1 year into a strangle pattern upgrade, we could save them a lot of time and money. It usually takes 3-6 months just to untangle the project to original state.

<br>

Let's say we finished the upgrade of invoicing to Symfony/Laravel framework. Now we've done almost full framework migration path, but there are still 3 modules we have to upgrade. We have just quadrupled our budget. In our experience, here is around 15-30 modules in such PHP projects.

What is the result? We now have a legacy project with 2 frameworks instead of 1. To upgrade a PHP version, we have to now upgrade 2 frameworks, not just 1. To add a new layer, e.g. routing security, we have find a way how both frameworks can work together.

## Pattern Refactoring

Instead of chopping of project and doubling everything, we should focus on smallest steps possible to make [project upgrade a success](/blog/7-traits-of-successful-upgrade-companies). We use [pattern refactoring](https://tomasvotruba.com/blog/2019/04/15/pattern-refactoring) approach.

How does pattern refactoring look like? The gist of this approach is to refactor **single pattern in the whole project at a time**.
That means we always have one framework responsible for one part. There are never 2 frameworks handling the same area e.g. controllers, like in strangle pattern.

### Step by step

1. Find a pattern that is used in the whole project, e.g. configuration, routing or dependency injection container
2. Create fully automated strategy using Rector and symfony/finder + php-parser
3. Have a way to quickly verify if the migration works or not, e.g. using [smoke testing](https://tomasvotruba.com/blog/cost-effective-container-smoke-tests-every-symfony-project-must-have)
4. Would it take more than 2 weeks? Find a simpler pattern

That way we have always one framework responsible for one part of the project.

## Benefits of Pattern Refactoring

* the project is always in a working state
* the project is always improving - even if we decide to pause the upgrade process in 4 months, we'll have a better project than we started with
* the work is always finished - there are no stale pull-requests that take a month to review
* the pull-request as small, independent on each other and easy to review
* great use of CLI automation - 80 % of patterns are repeating in the most of projects

<br>

We use this approach since one of our first upgrades in 2017. We only improve the CLI tooling and automation around it. If we discover a new pattern, we add it to Rector/tools and use it instantly in the next project.

<br>

### Case Study

This way, you can migrate whole project to a Laravel under a year - [see this Rector case study](/blog/success-story-of-automated-framework-migration-from-fuelphp-to-laravel-of-400k-lines-application).


<br>

The pattern refactoring is not just for very complex legacy-framework to Symfony/Laravel migrations. They're also suitable for long framework upgrades like [Symfony 2.8 to 7.2](https://tomasvotruba.com/blog/off-the-beaten-path-to-upgrade-symfony-28-to-72). Once you learn it, you can re-use and benefit from it in many projects.

<br>

Happy coding!
