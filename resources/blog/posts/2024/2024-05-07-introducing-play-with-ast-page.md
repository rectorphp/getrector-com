---
id: 65
title: "Introducing Play with AST page"
perex: |
    Do you want to know what the AST structure from a PHP Source code?, The getrector.com will help you.
---

Even on non-starter Rector user, it may be need manual try and error to decide which `Node` to be used when creating new Rector rule or refactor existing one. We are here to help you, that you can play before decide which `Node` you want to use to refactor your code.

We created new "Play with AST" page for you: [https://getrector.com/ast](https://getrector.com/ast), that you can:

* Insert a PHP source code
* Get All AST structure from source code
* Get Only AST structure for part of the code (click part of PHP source code)
* Get node types can be used on `Rector::getNodeTypes()`


You can start with open [https://getrector.com/ast](https://getrector.com/ast), and insert a sample code, for example:

```php
<?php

function foo()
{
    if (\rand(0, 1)) {
        return 1;
    }
    return 2;
}
```

Then, you can click the `Show me` button, then you can get the AST structure:

![Screenshot 2024-05-07 at 18 01 51](https://github.com/rectorphp/getrector-com/assets/459648/1b33502f-bce4-4ba3-a32a-1d43efe311cb)

The source code on first block is clickable, for example, you can click the "return" part:

![Screenshot 2024-05-07 at 18 03 19](https://github.com/rectorphp/rector/assets/459648/67d6abad-aa17-4576-b64b-5d8e69f782af)

then, you will get the AST stucture of return, and node usage that can be used for creating custom rule:

![Screenshot 2024-05-07 at 18 14 06](https://github.com/rectorphp/rector/assets/459648/75a0b37e-1ed4-42c9-bf96-f179c4fccf9a)

That's it!

<br>

Happy coding!
