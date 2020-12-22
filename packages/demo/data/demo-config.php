<?php

// rector.php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Generic\Tests\Rector\Property\AnnotatedPropertyInjectToConstructorInjectionRector\FixtureTypedProperty\TypedProperty;
use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $services = $containerConfigurator->services();

    // A. run whole set
    $parameters->set(Option::SETS, [SetList::DEAD_CODE]);

    // B. or single rule
    $services->set(TypedProperty::class);
};
