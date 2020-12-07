<?php

declare(strict_types=1);

namespace Rector\Website\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class ContactFormType extends AbstractType
{
    /**
     * @var array<string, int>
     */
    private const PHP_VERSION_CHOICES = [
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

    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
        $formBuilder->add('name', TextType::class, [
            'required' => true,
        ]);
        $formBuilder->add('email', TextType::class, [
            'required' => true,
        ]);

        $formBuilder->add('project_size', ChoiceType::class, [
            'label' => 'How big is your project?',
            'required' => true,
            'choices' => [
                'Smaller then 100 000 lines' => 100_000,
                '< 500 000 lines' => 500_000,
                '< 1 000 000 lines' => 1_500_000,
                'More than million lines' => 9_999_999,
            ],
        ]);

        $formBuilder->add('current_php_version', ChoiceType::class, [
            'label' => 'Current PHP version?',
            'placeholder' => 'If relevant...',
            'choices' => self::PHP_VERSION_CHOICES,
        ]);

        $formBuilder->add('goal_php_version', ChoiceType::class, [
            'label' => 'Goal PHP version?',
            'placeholder' => 'If relevant...',
            'choices' => self::PHP_VERSION_CHOICES,
        ]);
    }
}
