---
id: 6
title: "How to make Rector Contribute Your Pull Requests Every Day"
perex: |
    Rector can upgrade legacy code to a modern one. But in reality, that's ~5 % of usage. On the other hand, **more than [300 projects](https://packagist.org/packages/rector/rector/dependents?order_by=downloads) use Rector daily**, on every commit in Github Actions, Travis, and Gitlab CI.
    <br>
    <br>
    And that's only open-source projects. The number of private projects using Rector would be much higher.
---

Using Rector in CI is a huge help to each member of the team. Rector reports weak parts and suggests better code.
But Rector's primary goal is not to give you more work and steal your attention. Rector handles repeated, and mundane work for you and **let you focus on essential problems**.

Actually, Rector is [pushing commits to itself on GitHub](http://github.com/rectorphp/rector) since [March 2020](https://github.com/rectorphp/rector/pull/3013/files) when [Jan Mikes](https://github.com/JanMikes) added it. What does it mean? If you contribute a rule that belongs, e.g., to *code quality* set that is part of `rector-ci.php`, its change will propagate to all Rector's code automatically before it gets merged. **You don't have to do anything - maintenance zero.**

When does that happen?

## Make part Rector of your Code Review

We all know a passive code review. It's a classic code review, with comments from colleagues, reports from failed test cases, invalid coding standards, or maybe call on missing class. It only tells us something is wrong, but we have to fix it. It's passive feedback that only complains and adds us extra work.

That is not sustainable. The bigger code-base you have, the more features you add, the more passive code-review you get from all your colleagues and tools from CI. That isn't very pleasant, and it's clear why most developers don't like code-reviews. I don't mean those who give feedback, but those who need to invest the energy to resolve the feedback.

## From Passive to Active Code Review

You already know coding standard tools like [ECS](https://github.com/symplify/easy-coding-standard) that fix code for you. It fixes all the code style rules your team agreed on. E.g., each file must contain `declare(strict_types=1);`. If a new developer joins the team, he or she don't have to handle it, the coding standard tool will do it for them.

The same works for more complicated cases, like making every class final, using service constructor injection, and using [repository service](https://tomasvotruba.com/blog/2017/10/16/how-to-use-repository-with-doctrine-as-service-in-symfony/).

**Do you enjoy making code-reviews with hundreds of rules in your head and adding extra work to the pull-request author?**

We don't, so we let Rector for us in **active code review**.

## How to make Rector Active Contributor in GitHub Actions

We've been testing this workflow for the last 7 months and that saving work and attention is addictive.

The workflow is simple:

- you commit to a new branch
- Rector runs through the code, changes it
- changes are committed to your pull-request
- then you can either merge it or continue pushing (with force to override it, because Rector will change the new code again - *he's restless*)

**Let's talk real code now.**

We have a [dedicated GitHub action](https://github.com/symplify/symplify/blob/master/.github/workflows/rector_ci.yaml) to handle this process.

**Do you want to try it?** Copy it, create [new access token](https://github.com/settings/tokens), add `ACCESS_TOKEN` env variable to [repository Secrets](https://github.com/symplify/symplify/settings/secrets), and you're ready to make your first actively reviewed pull-request.

```yaml
# .github/workflows/rector_ci.yaml
name: Rector CI

on:
    pull_request: null

jobs:
    rector-ci:
        runs-on: ubuntu-latest
        # run only on commits on main repository, not on forks
        if: github.event.pull_request.head.repo.full_name == github.repository
        steps:
            -
                uses: actions/checkout@v2
                with:
                    # Solves the not "You are not currently on a branch" problem, see https://github.com/actions/checkout/issues/124#issuecomment-586664611
                    ref: ${{ github.event.pull_request.head.ref }}
                    # Must be used to trigger workflow after push
                    token: ${{ secrets.ACCESS_TOKEN }}

            -
                uses: shivammathur/setup-php@v1
                with:
                    php-version: 7.4
                    coverage: none

            -   run: composer install --no-progress --ansi

            ## First run Rector without --dry-run, it would stop the process with exit 1 here
            -   run: vendor/bin/rector process --config rector-ci.php --ansi

            -
                name: Check for Rector modified files
                id: rector-git-check
                run: echo ::set-output name=modified::$(if git diff --exit-code --no-patch; then echo "false"; else echo "true"; fi)

            -   name: Git config
                if: steps.rector-git-check.outputs.modified == 'true'
                run: |
                    git config --global user.name 'rector-bot'
                    git config --global user.email 'your-name@your-domain.com'
                    echo ::set-env name=COMMIT_MESSAGE::$(git log -1 --pretty=format:"%s")

            -   name: Commit Rector changes
                if: steps.rector-git-check.outputs.modified == 'true'
                run: git commit -am "[rector] ${COMMIT_MESSAGE}"
```

<br>

Then, the coding standard fixes all design nuances that Rector made:

```yaml
            ## Now, there might be coding standard issues after running Rector
            -
                if: steps.rector-git-check.outputs.modified == 'true'
                run: vendor/bin/ecs check src --fix

            -
                name: Check for CS modified files
                if: steps.rector-git-check.outputs.modified == 'true'
                id: cs-git-check
                run: echo ::set-output name=modified::$(if git diff --exit-code --no-patch; then echo "false"; else echo "true"; fi)

            -   name: Commit CS changes
                if: steps.cs-git-check.outputs.modified == 'true'
                run: git commit -am "[cs] ${COMMIT_MESSAGE}"
```

<br>

Last, we push the commits into the branch:

```yaml
            -   name: Push changes
                if: steps.rector-git-check.outputs.modified == 'true'
                run: git push
```


Congrats! Now you delegate active code-reviews to Rector.

## Make the most of Rector CI

To make the most of it, notice the most repeated comments in passive code-reviews and make Rector rules out of it.
**You'll save time, work and code-reviews become more joyful and lighter**. As a side effect, you can now focus on topics that computers can't automate (yet) - architecture and design.

That's all, folks - now go and try out [the Github Action for yourself](https://github.com/symplify/symplify/blob/master/.github/workflows/rector_ci.yaml).

<br>

Happy coding!
