Do you want to migrate your annotations to native PHP 8.0 attributes?

```diff
 use Doctrine\ORM\Mapping as ORM;

-/**
- * @ORM\Entity
- */
+#[ORM\Entity]
 class SomeEntity
 {
 }
```

Following method will automatically pick up attribute classes present in your `/vendor`, and upgrade annotations to their attribute equivalent:

```php
<?php

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withAttributesSets();
```

**If you're on a legacy project** and want to take it step by step, use named arguments to limit to specific groups:

```php
<?php

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withAttributesSets(symfony: true, doctrine: true);
```
