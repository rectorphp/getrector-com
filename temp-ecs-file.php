<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Property\NestedAnnotationToAttributeRector;
use Rector\Php80\ValueObject\AnnotationPropertyToAttributeClass;
use Rector\Php80\ValueObject\NestedAnnotationToAttribute;

return RectorConfig::configure()->withConfiguredRule(
    NestedAnnotationToAttributeRector::class,
    [new NestedAnnotationToAttribute('Doctrine\\ORM\\Mapping\\JoinTable', [
        new AnnotationPropertyToAttributeClass('Doctrine\\ORM\\Mapping\\JoinColumn', 'joinColumns'),
        new AnnotationPropertyToAttributeClass('Doctrine\\ORM\\Mapping\\InverseJoinColumn', 'inverseJoinColumns'),
    ])]
);
