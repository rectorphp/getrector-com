---
id: 72
title: "Improving Rector Performance"
perex: |
    Today I want to talk about how I added an optimization that made Rector 20-30% faster!

author: carlos_granados
---

*This is a guest post by [Carlos Granados](https://twitter.com/carlos_granados), who uses Rector very frequently and has recently contributed several improvements to this tool.*

<br>

<blockquote class="twitter-tweet"><p lang="en" dir="ltr">This week we&#39;ve released <a href="https://twitter.com/rectorphp?ref_src=twsrc%5Etfw">@rectorphp</a> 1.2.5 <br>with huge performance improvement <br><br>by amazing <a href="https://twitter.com/carlos_granados?ref_src=twsrc%5Etfw">@carlos_granados</a> 👏❤️️<br><br>Rector is now 25-30 % faster🚀🚀🚀<br><br>Enjoy 🤗 <a href="https://t.co/uhT3eyaMks">pic.twitter.com/uhT3eyaMks</a></p>&mdash; Tomas Votruba (@VotrubaT) <a href="https://twitter.com/VotrubaT/status/1833053542988284294?ref_src=twsrc%5Etfw">September 9, 2024</a></blockquote>

## Always optimizing

I started my development career in the 80s, developing games for home computers like Spectrum, Amstrad or Commodore 64. These were very limited platforms, so you learned that every CPU cycle and every byte counted.

So even nowadays, whenever I am developing software, in the back of my mind I'm always thinking about ways in which things can be optimized so that they need to do less computing and use less CPU or memory. I always try to implement small optimizations, things like assigning the result of a function to a variable and reusing that instead of calling the function several times or moving the calculation of the end condition of a loop out of the loop so that we don't need to calculate the condition on every loop pass.

Most of these optimizations provide very small benefits but, as they say, "every little counts" and hundreds of these little optimizations end up making code that is faster and uses less resources.

There are many cases where you need to opt for code clarity or complexity instead of performance. For example, if you refactor a function to extract some code into another function, performance is going to be a tiny bit worse, but the gains in diminished complexity and improved reusability far outweight this small lose.

If you are really looking into heavily optimizing some software, my advice is: look for the code that is executed more often and concentrate your efforts there. If a function is called thousands of times during the execution of your program, even a tiny improvement there can lead to great gains in the overall performance of the product.

## Optimizing Rector

I was trying to debug a Rector rule, trying to fing out why it provided an incorrect result for some cases, when I realized something: Rector was trying to apply all rules to all AST nodes. But every rule can only work with a limited subset of node types. So the first thing that every rule did was to check if that rule could be applied to that particular kind of node. And for the majority of rules the answer would be "no". So we lost a lot of time checking if every rule could be applied to the particular node that we had in hand. And this got worse as we increased the number of rules that we were applying to our code.

I thought that instead of doing this, we could find out a list of the rules that applied to a particular kind of node and only call these rules when we were dealing with each of them. There was only one obstacle, the `NodeTraverser` class from the `PhpParser` library that Rector used to traverse the AST did not provide any mechanism to only call some visitors (rules) for each kind of node. So I had to patch this class to add this mechanism. This is how the class looks after this patching:

```php
class NodeTraverser implements NodeTraverserInterface
{
    ...
    protected function traverseNode(Node $node) : Node {
    ...
                $visitors = $this->getVisitorsForNode($subNode);
                foreach ($visitors as $visitorIndex => $visitor) {
    ...
    }
    ...
    /**
     * @return NodeVisitor[]
     */
    public function getVisitorsForNode(Node $node)
    {
        return $this->visitors;
    }
    ...
}

```

As you can see, now before looping for the visitors for each node, we call a function that can provide the list of visitors to apply to that node. The default implementation just returns all available visitors.

Then in our `RectorNodeTraverser` Rector class, which inherits from this base `NodeTraverser`, we implement the `getVisitorsForNode()` function like this:

```php
final class RectorNodeTraverser extends NodeTraverser
{
    /**
     * @var array<class-string<Node>,RectorInterface[]>
     */
    private array $visitorsPerNodeClass = [];
    ...
    /**
     * We return the list of visitors (rector rules) that can be applied to each node class
     * This list is cached so that we don't need to continually check if a rule can be applied to a node
     *
     * @return NodeVisitor[]
     */
    public function getVisitorsForNode(Node $node): array
    {
        $nodeClass = $node::class;
        if (! isset($this->visitorsPerNodeClass[$nodeClass])) {
            $this->visitorsPerNodeClass[$nodeClass] = [];
            foreach ($this->visitors as $visitor) {
                assert($visitor instanceof RectorInterface);
                foreach ($visitor->getNodeTypes() as $nodeType) {
                    if (is_a($nodeClass, $nodeType, true)) {
                        $this->visitorsPerNodeClass[$nodeClass][] = $visitor;
                        continue 2;
                    }
                }
            }
        }

        return $this->visitorsPerNodeClass[$nodeClass];
    }
    ...
}
```

As you can see, we don't pre-calculate the rules to be used for every single kind of node, instead we calculate this list of the fly for every type of node that we find. This allows us to avoid calculating this list for any kind of node that is not present in our code base. Also, we don't attempt to cache these lists in any way. Calculating them is quite fast and the extra complexity that would have been needed to create and use this cache is not worth the small extra performance gain that we could have obtained.

When I was working on this code, before I tested it I was hoping for a performance gain of 5%-10%, so I was really happy when my tests returned a performance gain of 20-25%. This was later confirmed by Tomas Votruba, Abdul Malik Ikhsan and Markus Staab who measured similar or even greater gains.

I am really proud to have been able to add this improvement to Rector. I have always said that this tool is the best thing that has happened to the PHP ecosystem in the most recent years and I am always happy to see ways to improve it. And now all Rector users will benefit from much shorter runs.

One part of this that makes me specially happy is to think about the carbon footprint implications of this change. Rector is a tool used by thousands of developers which must be run thousands of times a day. This means that this improvement will end up saving many millions of minutes of execution, which means helping to lower the carbon footprint of this tool in a very significant way.

If you like this improvement and would like to support my contributions to open source, please consider [sponsoring me](https://github.com/sponsors/carlos-granados). Thanks!!!
