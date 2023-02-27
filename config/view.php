<?php

declare(strict_types=1);

use TomasVotruba\PunchCard\ViewConfig;

return ViewConfig::make()
<<<<<<< HEAD
    ->paths([__DIR__ . '/../resources/views'])
=======
    ->paths([
        __DIR__ . '/../resources/views'
    ])
>>>>>>> 94a7240 (add view config for Laravel)
    ->compiled(__DIR__ . '/../storage/framework/views')
    ->toArray();
