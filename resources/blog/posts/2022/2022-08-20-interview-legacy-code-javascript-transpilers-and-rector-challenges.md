---
id: 40
title: "Interview: Legacy Code, Javascript Transpilers and Rector Challenges"
perex: |
    I'll be speaking there in October at Paris on [Forum PHP 2022](https://event.afup.org/) about Rector.
    I was asked for a simple interview to warmup the talk topic. The 3 questions - each looking at different angle, but going deep.
    <br><br>
    Contrary to mostly technical content on this blog, this post will give you behind the scenes insights on wider Rector vision.
---


## 1) Your talk is about legacy code. What made you interested in that subject, and do you think it is an important topic for developers?

What made me interested in legacy code? That's a good question :) During my first 10 years of coding, I strictly avoided legacy codebases. I've heard only traumatic stories from older friends, and I wanted to work only with fresh new technologies. That was the most fun, right? As I grew, I realized it's easy to work with new technology and... also boring. I started to look for a challenge and got hired by the largest Czech Pharmacy company to get a 10-year-old spaghetti code to modern MVC with high-quality standards.

That was a great experience that got me closer to automated tools. Compared to my peers, I've always been extremely lazy and had a weakness for clean code. That got me thinking, "this company is probably not the only one in the world having this problem". If I work all my life, I can help max. 20 companies this way. That's not much :)

I came across automated tools like Code Sniffer, and the idea of "automated upgrades for legacy code" started. If one person writes a rule to add a "void" type where it belongs, anyone in the world can use it for free. The whole world runs on a new version of PHP in a few seconds.

I realized people around me might hate legacy code, but they also love to work with complex systems and improve them. That's where the biggest problem of the PHP community and the solution clicked.

<br>

## 2) In the JS ecosystem, downgrading and transpiling code is the norm. Do you think it is the future of the PHP ecosystem too?

When I started working on Rector in 2016, I noticed other languages have similar tools. Yet their focus was rather dialect-based than single-lined. JS ecosystem is rich but also has too many variations. Those variations are easily spread, burn out in a few years, and they are hard to get rid of or change.

I mean, a new JS framework has just been released during writing these answers :). There is no ReactJS <-> Angular migration tool, nor Angular 2 to 4 upgrade (fun factory: I was hired once for that too).

The PHP is very concise in this matter. There is PHP 5.4, then 5.5, then 5.6. There is no 5.6-beta-only-with-this-feature with brand-new syntax. That means the language is deterministic. Every version has strictly defined behavior. You can do an "A → B upgrade" with a computer algorithm. There is no better language than PHP to spark these automated tools, whether coding standard tools, static analyzers, or Rector. There are already discussions on Reddit that Rector should become part of PHP official RFCs.

It will be fantastic. Imagine there is RFC that adds a new "read-only" keyword to the class. The implementation and tests are part of the RFC already. But now, there will also be a Rector upgrade set, tested on top 1000 composer packages.

There is no "it will be so hard to upgrade, don't give us more work, please" discussion. It's zero work for us developers to upgrade. We just run "composer update" and enjoy the new PHP version without effort :)

<br>

## 3) There are many rules to maintain on a project like Rector. What's the main difficulty of working on this project?

Thank you for your question about this challenge. I'm proud to say the Rector community gives the tool propper battle testing. By the nature of the tool, Rector faces the worst legacy code there is. Its job is to upgrade the "impossible" legacy projects, after all :). The people then report edge cases that Rector missed.

Now comes the most significant challenge: people send a failing test case to particular Rector rule, but they think the fix is too hard to try. Then we try to explain to them that they can do it. Most often than not, the fix usually adds an "instanceof" check here or changes the bool return. When they try it, they're honestly surprised by how easy it is. The next issue usually comes up with a fix included.

At last, I want to encourage you. For anyone working with a legacy codebase or with 3+ years old project, give this technology a try. Whether Rector or abstract syntax tree, **it will give you incredible power to change "impossible-level problems" in your project in a matter of days**.

If you need a heads-up start, get a [Rector book](https://leanpub.com/rector-the-power-of-automated-refactoring) that explains this from the very first steps. It will show you that anything is possible with your code, whether 1000 lines or 10 000 000 lines.

Good luck and happy coding!

Tomas
