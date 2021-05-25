<?php

declare(strict_types=1);

namespace Rector\Website\Form;

use Rector\Website\Entity\ContactMessage;
use Rector\Website\ValueObject\FormChoices;
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
     * @var string
     */
    private const PICK_ONE_PLACEHOLDER = 'Pick one...';

    /**
     * @param mixed[] $options
     */
    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
        $forCompanies = $options['for_companies'];

        if ($forCompanies === false) {
            $formBuilder->add('framework', TextType::class, [
                'label' => 'Used PHP framework',
                'attr' => [
                    'placeholder' => 'E.g. Symfony 2.8',
                ],
            ]);

            $formBuilder->add('currentPhpVersion', ChoiceType::class, [
                'label' => 'Current PHP version',
                'placeholder' => self::PICK_ONE_PLACEHOLDER,
                'choices' => FormChoices::CURRENT_PHP_VERSION,
            ]);
        }

        $formBuilder->add('message', TextareaType::class, [
            'label' => 'What do you need help with?',
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
            // see https://developers.google.com/recaptcha/docs/v3
            'attr' => [
                'class' => 'btn btn-success btn-lg g-recaptcha',
                // see https://www.google.com/recaptcha/admin/create - v3
                'data-sitekey' => '6LdXOu4aAAAAAOjxa3tfd3PGtI_A1L7g9f-FM4tr',
                'data-callback' => 'onSubmit',
                'data-action' => 'submit',
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults([
            'data_class' => ContactMessage::class,
            // @see https://stackoverflow.com/questions/48864637/symfony-4-multiple-forms-of-same-type-with-dynamic-display-fields
            'for_companies' => false,
            'attr' => [
                // for captcha form detection
                'id' => 'contact_form',
            ],
        ]);
    }
}
