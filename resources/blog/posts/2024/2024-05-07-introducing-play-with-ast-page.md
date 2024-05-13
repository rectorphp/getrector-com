---
id: 65
title: "Introducing Play with AST page"
perex: |
    Do you want to know what the AST structure from a PHP Source code? The getrector.com will help you with new interactive form.
---

Even for experienced Rector users, manual trial and error may be needed to decide which `Node` to use when creating a new Rector rule or refactoring an existing one. We are here to help you so that you can play before deciding which `Node` you want to use to refactor your code.

We created a new "Play with AST" page for you: [https://getrector.com/ast](https://getrector.com/ast), that you can:

* Insert a PHP source code
* Get All AST structure from the source code
* Get Only AST structure for part of the code (click a part of the PHP source code)
* Get node types can be used on `Rector::getNodeTypes()`

<br>

You can start with open [https://getrector.com/ast](https://getrector.com/ast), and insert a sample code, for example:

```php
<?php

function foo()
{
    return 1;
}
```

<br>

Then, you can click the `Show me` button, then you can get the AST structure:

<img src="https://github.com/rectorphp/getrector-com/assets/459648/1b33502f-bce4-4ba3-a32a-1d43efe311cb" class="img-thumbnail mt-2 mb-5">

<br>

The source code on the first block is clickable; for example, you can click the "return" part:

<img src="https://github.com/rectorphp/rector/assets/459648/67d6abad-aa17-4576-b64b-5d8e69f782af" class="img-thumbnail mt-2 mb-5">

Then, you will get the AST structure of return and node usage that can be used for creating custom rules:

<img src="https://github.com/rectorphp/rector/assets/459648/75a0b37e-1ed4-42c9-bf96-f179c4fccf9a" class="img-thumbnail mt-2 mb-5">

That's it!

<br>

Happy coding!
