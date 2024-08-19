---
id: 63
title: "Upgrade Legacy Framework or Change it for Another?"
perex: |
    Would you upgrade your Nokia 3310 to a newer Nokia or change it to an iPhone with USB-C? Would you upgrade your old Ford Fiesta to a newer Ford or change it to a Tesla Model 3? Would you upgrade your house's wooden windows for better wood or use plastic 3-layers?

    If you use any PHP framework, it doesn't mean you have to stick with it for the end of your project life. The upgrade or change can be both valid options, depending on your project state, PHP community in your country or version.
---

When we get reached by clients with "we need to upgrade framework X from version 1 to 2", we don't go for the upgrade immediately. Instead, we ask them questions about their particular context:

* How fast does framework X help you deliver business value?
* How easily can you hire enough developers with framework X knowledge?
* What is the most used framework by your dev team in their hobby projects?
* What meetups do your developers go to?

It might sound like we ask technical questions that have nothing to do with productivity and business value. After all, we upgrade mainly to get fast, deliver value, and quickly adapt to beat the competition.

## Newer Technology = Faster Learning

Would you rather have 10 developers with Windows 98 or 3 developers with MacBook M3?

**Tooling + technology + community = productivity**

Those 3 developers go to an IT meetup in your city every month. They share their know-how, attract other talented developers, and can join your team. This talent further improves the technical state of your project, making it faster than the competition and more tolerant of future challenges.

These new developers attend the meetup, share their great experiences with your company, and attract other talents. The circle continues.

This way, you can hire great developers without heavy HR investments by supporting your technical stack. We've seen many startups successfully using this approach, followed by a successful exit to a global fund.

## Active Hiring Pool

The goal of the upgrade is not to have a great codebase at the moment. **The goal is to be competitive in the next 3-5 years, prepare for any unexpected changes, and provide a superior experience for our customers. That's why they buy products or services from us, which keeps the company growing.

There is a lack of developers who bring value to your project. This is obvious for any growing company that needs to hire more developers. That's why focus on a hiring pool—a group of developers you can hire from. These developers meet your conditions, and you meet their payment expectations. Like a fish pool, this pool has to be growing and healthy.

What does this mean in practice? For example, your project uses the latest Symfony 7.x. But your job applicants know only Laravel. Of course, you can teach them different technology than they use daily, but they won't be productive. It's more likely your developer count will head to zero as your hiring pool is dry.

This applies vise versa: if you use the latest Laravel, but all you can hire from are Symfony developers, your project will be unable to grow. **So always consider your specific hiring pool first**.

## Upgrade or Change?

We can move to the technology once we consider your hiring pool.

* There is a group we call "dying frameworks". Those are frameworks that very few people would choose to build a new project in 2024.

* The other group is "living frameworks." Most PHP developers would choose one of them to build a new project—not because they have known it for 10+ years, but because they find it easy to use and fast to build the desired project. For example, we have known Nokia for 25 years, but it would not be the phone to buy as a gift to your friend.

If you're a business owner/CTO and not an active part of the online/offline PHP community, it can be hard to know which is which. Your company has used the *framework X* since the beginning, so it's only reasonable to keep using it.

**There is a quick way to guesstimate whether framework X belongs to the "dying" or "living" category** The download statistics show that if framework X is popular, most developers will know about it, use it, learn it, and improve it.

### How to find out downloads stat numbers?

* Go to https://packagist.org/
* Type your framework name into the search bar and press Enter
* Click on the first package
* Then click on "Installs" in the right column

<br>

For example, this year, we wrote about [CakePHP migration](/blog/what-to-expect-when-you-plan-to-migrate-away-from-cakephp-2). Let's see its [download stats](https://packagist.org/packages/cakephp/cakephp/stats):

<img src="/assets/images/blog/2024/cake-downloads.png" class="img-thumbnail" style="max-width: 40em">

<br>
<br>

We also wrote about [Phalcon framework](/blog/how-to-upgrace-phalcon-project). What are [the stats in](https://packagist.org/packages/phalcon/cphalcon/stats) there?

<img src="/assets/images/blog/2024/phalcon-downloads.png" class="img-thumbnail" style="max-width: 40em">

<br>
<br>

Once we have stats for your *framework X*, we need  **a stable reference to compare to**. We use Symfony or Laravel stats. Here, let's check [Laravel stats](https://packagist.org/packages/laravel/framework/stats):

<img src="/assets/images/blog/2024/laravel-downloads.png" class="img-thumbnail" style="max-width: 40em">

<br>
<br>

Now we have the data to compare. What to look at?

* **absolute daily/monthly downloads** - this will give you an idea if your framework is popular or not
* **trending line** - how healthy community is, is framework growing or not; look for past 1-2 years

<br>

## Examples of Decisions based on data

Now that we have data about the hiring pool and the dying or living framework, the last criterion is the framework's age.

**How old is the version your project uses?**

As we wrote in [How to Upgrade Zend Legacy Project](/blog/how-to-upgrade-zend-legacy-project), it's cheaper to completely change from an ancient framework like Zend 1 to the latest Laravel/Symfony. But if your project uses Zend 3, sticking with it and upgrading is generally cheaper.

Here, we'll share a few practical examples of how to evaluate these data so you can better decide whether to upgrade or change.

<br>
<br>

## Usecase A

* your framework is CakePHP 2
* the hiring pool is active with Symfony and Zend developers
* Symfony has more downloads than Zend
* your developers use Symfony or Laravel in their hobby projects

Upgrading CakePHP 2 to the CakePHP 4 doesn't make sense, as you need to have new CakePHP developers to hire from.

You can choose Symfony or Zend developers. To decide, we check the download stats. Symfony has more downloads than Zend. Last but not least, we verify with the team. They'd prefer any of Symfony or Laravel.

**Symfony is the best choice.**

<br>

## Usecase B

* your framework is Symfony 5.0
* the hiring pool is active with Symfony and Laravel
* Symfony and Laravel have quite similar downloads
* your developers use rather Laravel in their hobby projects

Symfony is a living framework we can stick with. Your hiring pool has Symfony developers, so we're suitable to grow. The latest Symfony version is 7, so we'd only have to upgrade 2 major versions = doable. Despite your developers prefer Laravel, **we'd stick with Symfony**.

<br>

## Usecase C

Same as above, but:

* your framework is Symfony 2.x

Here, we want to demonstrate when it might be **cheaper to change frameworks, depending on the age of your current version**. Symfony 2 was released in 2011 and has an entirely different architecture than the current Symfony 7. It's 13 years technology gap and 5 major versions to upgrade.

<br>

On the other hand, **your developers developers prefer Laravel**. That means your project could grow further with Laravel developers. The change from Symfony 2 to Laravel 11 would be only a single step:

* Symfony 2 → Laravel 11

Here, we'd choose a framework **change from Symfony to Laravel** as a cheaper and more beneficial solution.

<br>

This is how we discuss with our clients about the decision to upgrade or change the framework entirely.

<br>

Happy coding!
