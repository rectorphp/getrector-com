---
id: 64
title: "7 Traits of Successful Upgrade Companies"
perex: |
    During past 10 years we've been working with over 50 companies on legacy PHP upgrades. We already wrote about our approach and technical process of upgrades.

    We've met many companies we helped to turn their projects from legacy that was hard to work with to code full of joy, safety and smoothness.

    In time, we've noticed those companies traits often repeat and are shared with other similar companies. We'd like to share these observations so you can mimic them to your company and make your upgrade project successful.
---

## 1. Practical Approach

Companies that are successful with finished upgrade are highly practical. They limit number of shallow work, meetings, paperwork to minimum.

We have first meeting to learn about goals, then we do an intro analysis. Followed by 2nd meeting to discuss results and then we jump right to start. The most successful companies contact us with clear vision, secured budget, they know [how we work](https://getrector.com/hire-team#process) and they're ready to start.

It's common we start hands on upgrade work not even 40 days after first contact. We start with 10 hours/week. On 2nd month when they see results, they asking to increase to 40-60 hours/week.

## 2. Space for Deep work focus

Some PHP projects become hard to work with, because there is something deeply wrong with them. It's like traumatized person, who needs help out of depression. There problems will disappear after seeing few Youtube videos on self-help. The same way legacy code will not be upgraded by reading few documentations pages. Otherwise these project would be already fixed by their own developers team.

Successful companies understand this and they're willing to invest and give space for deep work exploration. They know **it takes time to understand the complexity** of the problem and that **we need to think about problem deeply before coming up with solution**.

E.g., couple of projects we worked with used synonymous testing tools to test the same logic - there was PHPUnit, phpspec, and Codeception at the same time. All using unit testing of the same code.

This makes tests hard to read, as you have to switch context and distract. We could easily delete 2 of them or we could ignore it (worked so far, right?). None of these would be a sustainable solution, only postponing the problem. Instead we though about it and came up with migration of all tests to PHPUnit using custom Rector rules. Value in tests is kept and we have 1 tool instead of 3.

## 3. Trust

Companies who hire us and end up with successful upgrade are those who strongly trust us. It doesn't mean believe everything without critical thinking, but they listen to us. When we agree on an upgrade plan, they give us confidence to execute it.

We don't have meeting to rethink, we don't discuss for weeks in PR, they don't ask us about line of code. To be honest, they accept 95 % PRs with "Approve".

If we feel our clients trust us, we react with great responsibly and take project seriously like our own. We come up with solutions that are harder for us prepare and execute, but bring more value to the project in the future.

## 4. Fast merging

This is related to trust. If we trust our client and our clients trust us, we also trust the code. It's our job to increase trust in the code itself by anyone who's working with it. If a junior comes to the project, they have to be confident they can make a change and it will not break the project.

Successful clients know this and they embrace the change by merging it as soon as possible. It doesn't mean we get merged withing an hour without review and hope for the best. It means we work on responsible CI/CD pipeline that will tell us if something is wrong. **If CI is passing, the PR can be merged in 1-2 days**.

If average time to merge is over 1-2 weeks, this is usually sign of trust issues that will result in upgrade stale.

## 5. Focus on Long-term vision

In the intro analysis we present the upgrade battle plan for the next 6-12 months. It's quite report with 30-40 areas, but its not the bullet points they discuss - its the vision they're interested in.

Successful companies don't want to only improve their code quality. They want to attract talented developers, they want to deliver features fast. They want to the **codebase to serve them, no the other way around**. They focus on long-term sustainability in next 3-5 years.

## 6. Single Responsible Person

The successful companies we work with **have exactly single responsible person that is in charge of the upgrade**. When we have meeting, the person is there. If we're proposing a change of direction, we mention the person. If we want to plan for next year, we speak with the person.

That way we know there is no loss of information and our client knows they're informed about everything.

## 7. Will to Radical Prune

Every gardener knows that if you want your apple tree to grow, you have to prune branches yearly. You have to cut branches that are weak, that are growing upwards or downwards, that are too close to each other. It takes courage to do so - if a tree is neglected for couple of years, up to 80 % of its branches has to be cut. As a result, the tree will grow juicy full apples rich for vitamins.

<blockquote class="blockquote mt-5 mb-5">
    Perfection is achieved, not when there is nothing more to add,<br>
    but when there is nothing left to take away.
</blockquote>

Successful companies understand this principle. Remember the [Twitter firing 80 % of stuff](https://edition.cnn.com/2023/04/12/tech/elon-musk-bbc-interview-twitter-intl-hnk/index.html) to deliver more features than ever before.

 Legacy projects are similar to overgrown trees. First couple months prune packages, that brings little value but cost maintenance. E.g. some project we started with had PHPStan, SARB, Psalm, phpmd and huge baseline. That's 5 various approaches to static analysis with little extra value. It's like paying taxes in 5 countries from the same income. It takes lot of pruning to get to bare PHPStan setup with single config. Successful companies are willing to do so, and actually replicate similar approach in other places.


<br>

Last but not least, people from successful companies really care about the company. In a way, they want the best for the project in the future, not for fast ticking the KPI and bonus in a salary. If we look at the headlines, we could say it's kind of deep friendship, right?

Do you recognize yourself in some of those traits? Would you like grow to learn the other ones? [Let us know](https://getrector.com/contact), we're ready to help.

<br>

Happy upgrading!
