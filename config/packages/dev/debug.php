<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symplify\Amnesia\Functions\env;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('debug', [
        'dump_destination' => 'tcp://' . env('VAR_DUMPER_SERVER'),
    ]);
};
