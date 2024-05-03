---
id: 64
title: "7 Traits of Successful Upgrade Companies"
perex: |
    For the past 10 years, we've been working with over 50 companies on legacy PHP upgrades. We've already written about our approach and technical process of upgrades.

    We've met many companies we helped turn their projects from legacy projects that were hard to work with to code that is full of joy, safety, and smoothness.

    Over time, we've noticed that those companies' traits often repeat and are shared with other similar companies. We'd like to share these observations so you can mimic them for your company and make your upgrade project successful.
---

## 1. Practical Approach

Companies that are successful with finished upgrades are highly practical. They minimize the number of shallow work, meetings, and paperwork.

We have a first meeting to learn about goals. Then, we do an intro analysis. This is followed by a second meeting to discuss results, and then we jump right in to start. The most successful companies contact us with a clear vision and secured budget. They know [how we work](https://getrector.com/hire-team#process), and they're ready to start.

We'll likely start hands-on upgrade work not even 40 days after first contact. We start with 10 hours/week. In the second month, when they see results, they ask to increase to 40-60 hours/week.

## 2. Space for Deep work focus

Some PHP projects become hard to work with because there is something deeply wrong with them. It's like a traumatized person who needs help out of depression. Their problems will not disappear after watching a few YouTube videos on self-help.

In the same way, legacy code will not be upgraded by reading a few articles about legacy upgrades. Otherwise, these projects would already be fixed by their own developers' team.

Successful companies understand this, and they're willing to invest and give space for deep work exploration. **They know that it takes time to understand the complexity of the problem** and that we need to think about the problem deeply before coming up with a solution.

For example, a couple of projects we worked on used synonymous testing tools to test the same logic—there was PHPUnit, PHPSpec, and Codeception at the same time—all using unit testing of the same code.

It's like having 3 cars to drive to work everyday. This makes tests hard to read, as you have to switch context.

We could quickly delete 2 of them or ignore them (worked so far, right?). None of these would be a sustainable solution, only postponing the problem.

Instead, we took time to think deeply and came up with solution that:

 * keeps the value of tests
 * and reduces complexity from 3 to 1

We migrated all tests to PHPUnit using custom Rector rules.

## 3. Trust

Companies that hire us and end up with successful upgrades are those that strongly trust us. This doesn't mean they believe everything without critical thinking, but they listen to us. When we agree on an upgrade plan, they give us confidence to execute it.

We don't have meetings to rethink, we don't discuss for weeks in PR, and they don't ask us about a line of code. To be honest, they accept 95 % of PRs with "Approve."

If we feel our clients trust us, we react responsibly and take projects seriously like our own. We come up with solutions that are harder for us to prepare and execute but will bring more value to the project in the future.

## 4. Fast merging

This is related to trust. If we trust our clients and our clients trust us, we also trust the code. It's our job to increase the trust in the code itself by anyone working with it. If a junior comes to the project, they have to be confident they can make a change and it will not break the project.

Successful clients know this, and they embrace the change by merging it as soon as possible. It doesn't mean we get merged withing an hour without review and hope for the best. It means we work on a responsible CI/CD pipeline that will tell us if something is wrong. **If CI passes, the PR can be merged in 1-2 days**.

If the average time to merge is over 1-2 weeks, this is usually a sign of trust issues that will result in an upgrade stale.

## 5. Focus on Long-term vision

In the intro analysis, we present the upgrade battle plan for the next 6-12 months. It's a fairly comprehensive report with 30-40 areas, but it's not the bullet points they discuss—it's the vision they're interested in.

Successful companies don't want only to improve their code quality. They want to attract talented developers, they want to deliver features fast. They want the **codebase to serve them, not the other way around**. They focus on long-term sustainability in the next 3-5 years.

## 6. Single Responsible Person

The successful companies we work with **have exactly one responsible person in charge of the upgrade**. When we have a meeting, the person is there. If we're proposing a change of direction, we mention the person. If we want to plan for next year, we speak with the person.

That way, we know there is no loss of information, and our client knows they're informed about everything.

## 7. Will to Radical Prune

Every gardener knows that if you want your apple tree to grow, you must prune branches yearly. You have to cut branches that are weak, growing upwards or downwards, or too close to each other. It takes courage to do so—if a tree is neglected for a couple of years, up to 80 % of its branches have to be cut. As a result, the tree will grow juicy, full apples rich in vitamins.

<blockquote class="blockquote mt-5 mb-5">
    Perfection is achieved not when there is nothing more to add. <br>
    but when there is nothing left to take away.
</blockquote>

Successful companies understand this principle. Remember the [Twitter firing 80 % of stuff](https://edition.cnn.com/2023/04/12/tech/elon-musk-bbc-interview-twitter-intl-hnk/index.html) to deliver more features than ever before.

 Legacy projects are similar to overgrown trees. In the first couple of months, we radically prune packages that bring marginal value to the project.

 For example, some projects we started with had PHPStan, SARB, Psalm, ppm,d, and a colossal baseline. That's 5 different approaches to static analysis with little extra value.

 It's like paying taxes in 5 countries for the same income. It takes a lot of pruning to get to a bare PHPStan setup with a single config. Successful companies are willing to do so and actually replicate this approaches in other places themselves.

<br>

Last but not least, **people from successful companies care about the company**. They want the best for the project in the future, not to fast-tick the KPI and bonus in a salary. If we look at the headlines of this post, it's like a recipe for good friendship.

Do you recognize yourself in some of those traits? Would you like to learn the other ones? [Let us know](https://getrector.com/contact); we're ready to help.

<br>

Happy upgrading!
