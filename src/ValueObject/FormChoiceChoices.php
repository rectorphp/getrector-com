<?php

declare(strict_types=1);

namespace Rector\Website\ValueObject;

final class FormChoiceChoices
{
    /**
     * @var array<string, int>
     */
    public const PHP_VERSION_CHOICES = [
        '5.3' => 50300,
        '5.4' => 50400,
        '5.5' => 50500,
        '5.6' => 50600,
        '7.0' => 70000,
        '7.1' => 70100,
        '7.2' => 70200,
        '7.3' => 70300,
        '7.4' => 70400,
        '8.0' => 80000,
    ];

    /**
     * @var array<string, string>
     */
    public const CURRENT_FRAMEWORK_CHOICES = [
        'Symfony' => 'symfony',
        'Laravel' => 'Laravel',
        'Nette' => 'nette',
        'CakePHP' => 'cake_php',
        'Zend' => 'zend',
        'Phalcon' => 'phalcon',
        'CodeIgniter' => 'code_igniter',
        'Yii' => 'yii',
        'Custom framework' => 'custom_framework',
        'spaghetti code' => 'spaghetti_code',
    ];

    /**
     * @var array<string, string>
     */
    public const TARGET_FRAMEWORK_CHOICES = [
        'Symfony' => 'symfony',
    ];
}
