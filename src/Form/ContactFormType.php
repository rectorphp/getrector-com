<?php

declare(strict_types=1);

namespace Rector\Website\Form;

use Rector\Website\ValueObject\ContactFormData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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

    /**
     * @var string
     */
    private const PICK_ONE_PLACEHOLDER = 'Pick one...';

    /**
     * @param mixed[] $options
     */
    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
        $formBuilder->add('project_size', ChoiceType::class, [
            'label' => 'Project size',
            'placeholder' => self::PICK_ONE_PLACEHOLDER,
            'required' => true,
            'choices' => [
                'Smaller then 100 000 lines' => 100_000,
                '< 250 000 lines' => 250_000,
                '< 500 000 lines' => 500_000,
                '< 1 000 000 lines' => 1_500_000,
                'More than million lines' => 9_999_999,
            ],
        ]);

        $formBuilder->add('framework', TextType::class, [
            'label' => 'Used PHP framework',
            'attr' => [
                'placeholder' => 'E.g. Symfony 2.8',
            ],
        ]);

        $formBuilder->add('current_php_version', ChoiceType::class, [
            'label' => 'Current PHP version',
            'placeholder' => self::PICK_ONE_PLACEHOLDER,
            'choices' => self::PHP_VERSION_CHOICES,
        ]);

        $formBuilder->add('target_php_version', ChoiceType::class, [
            'label' => 'Goal PHP version',
            'placeholder' => 'If relevant...',
            'choices' => self::PHP_VERSION_CHOICES,
            'required' => false,
        ]);

        $formBuilder->add('message', TextareaType::class, [
            'label' => 'What do you need help you with?',
            'attr' => [
                'rows' => 5,
            ],
        ]);

        $formBuilder->add('name', TextType::class, [
            'label' => 'What is your name?',
            'required' => true,
        ]);

        $formBuilder->add('email', TextType::class, [
            'label' => 'What is your email?',
            'required' => true,
        ]);

        $formBuilder->add('submit', SubmitType::class, [
            'label' => 'Send Message',
            'attr' => [
                'class' => 'btn btn-success',
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults([
            'data_class' => ContactFormData::class,
        ]);
    }
}
