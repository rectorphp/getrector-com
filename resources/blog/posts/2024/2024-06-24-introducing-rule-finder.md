---
id: 69
title: "Introducing Rule Finder"
perex: |
    To this day, Rector provides **over 535 rules spread in 4 repositories** - core, PHPUnit, Symfony, and Doctrine. If you are looking for a rule that does a specific job, you'd have to go through 4 markdown files, find it on a page, and hope to get it right. That is frustrating, especially when you look for a "constant," but rules have "const" in their name.

    We heard your feedback and worked on a **single place to search rules past couple of months**. We're proud to share the final page.
---

Right to the point: next time you look for a rule that does "this or that", open [getrector.com/find-rule](/find-rule).

There are 3 ways you can narrow your search.

## 1. Search by name and description

We have prepared 5 queries for you to try. Click on any of them to see the result.

<img src="/assets/images/blog/2024/search-first.gif" class="img-thumbnail mb-4" style="max-width: 100%">

Click on the rule class input to select it. Then, you can copy it and paste it into your `rector.php` setup.

The search works word by word, so you can type them in any order. You can look for "type constant," and rules with "const" will also match.

You can also see if the rule is *configurable* = used in many sets with specific configurations.

<br>

## 2. Filter by node

Are you curious about rules that modify class methods? Do you look for PHP 8 attributes? What about types of closures?

Pick your node in the select:

<img src="/assets/images/blog/2024/search-second.gif" class="img-thumbnail mb-4" style="max-width: 100%">

<br>

## 3. Filter by set

Did you know there are over 30 prepared sets in Rector? Not just PHP or framework upgrades, but with a focus on dead code, PHPUnit code quality, or Doctrine improvements.

It would be wild to turn them on unthinkingly. Now you can **see all the rules by a single set** - pick it from the select box:

<img src="/assets/images/blog/2024/search-third.gif" class="img-thumbnail mb-4" style="max-width: 100%">

<br>

<br>

This is part of our mission to make learning Rector and AST more fun. Like we did with ["Play with AST" page](/blog/introducing-play-with-ast-page). Try it and let us know, [how you like it](https://github.com/rectorphp/getrector-com/issues).

In the future, we **plan to add community packages like Laravel, Sulu, Drupal...** [and more](https://github.com/rectorphp/rector#empowered-by-community-heart).

<br>

Now start searching [your following rule](https://getrector.com/find-rule).

<br>

Happy coding!
