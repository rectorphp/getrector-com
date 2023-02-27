<?php

declare(strict_types=1);

return static function (\Symplify\EasyCI\Config\EasyCIConfig $easyCIConfig): void {
    $easyCIConfig->paths([__DIR__ . '/app', __DIR__ . '/src', __DIR__ . '/config']);

    $easyCIConfig->typesToSkip([

        \Illuminate\Routing\Controller::class,
        \Illuminate\Support\ServiceProvider::class,
        \Illuminate\Contracts\Console\Kernel::class,
        \Illuminate\Contracts\Http\Kernel::class,
    ]);
};
