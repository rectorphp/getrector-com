<?php

declare(strict_types=1);

namespace Rector\Website\CleaningLadyList\Form;

use Rector\Website\CleaningLadyList\Entity\Project;
use Rector\Website\ValueObject\FormChoiceChoices;
use Rector\Website\ValueObject\FormPlaceholder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ProjectFormType extends AbstractType
{
    /**
     * @param mixed[] $options
     */
    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
        $formBuilder->add('name', TextType::class, [
            'label' => 'Project Name',
        ]);

        $formBuilder->add('currentPhpVersion', ChoiceType::class, [
            'label' => 'Current PHP version',
            'placeholder' => FormPlaceholder::PICK_ONE,
            'choices' => FormChoiceChoices::PHP_VERSION_CHOICES,
        ]);

        $formBuilder->add('targetPhpVersion', ChoiceType::class, [
            'label' => 'Target PHP version',
            'placeholder' => FormPlaceholder::PICK_ONE,
            'choices' => FormChoiceChoices::PHP_VERSION_CHOICES,
        ]);

        $formBuilder->add('currentFramework', ChoiceType::class, [
            'label' => 'Current Framework',
            'placeholder' => FormPlaceholder::PICK_ONE,
            'choices' => FormChoiceChoices::CURRENT_FRAMEWORK_CHOICES,
        ]);

        $formBuilder->add('targetFramework', ChoiceType::class, [
            'label' => 'Target Framework',
            'placeholder' => FormPlaceholder::PICK_ONE,
            'choices' => FormChoiceChoices::TARGET_FRAMEWORK_CHOICES,
        ]);

        $formBuilder->add('save', SubmitType::class, [
            'label' => 'Create',
            'attr' => [
                'class' => 'btn btn-success btn-lg mt-3',
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
