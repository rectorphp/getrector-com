<?php

declare(strict_types=1);

namespace Rector\Website\Form;

use Rector\Website\Entity\ContactMessage;
use Rector\Website\ValueObject\FormChoices;
use Rector\Website\ValueObject\FormPlaceholder;
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
     * @param mixed[] $options
     */
    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
        $formBuilder->add('framework', TextType::class, [
            'label' => 'Used PHP framework',
            'attr' => [
                'placeholder' => 'E.g. Symfony 2.8',
            ],
        ]);

        $formBuilder->add('currentPhpVersion', ChoiceType::class, [
            'label' => 'Current PHP version',
            'choices' => FormChoices::CURRENT_PHP_VERSION,
            'placeholder' => FormPlaceholder::PICK_ONE,
        ]);

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
            'attr' => [
                'class' => 'btn btn-success btn-lg',
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults([
            'data_class' => ContactMessage::class,
        ]);
    }
}
