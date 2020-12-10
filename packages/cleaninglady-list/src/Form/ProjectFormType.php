<?php

declare(strict_types=1);

namespace Rector\Website\CleaningLadyList\Form;

use Rector\Website\CleaningLadyList\Entity\Project;
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
        $formBuilder->add('title', TextType::class, [
            'label' => false,
            'attr' => [
                'placeholder' => 'Project Title',
            ],
        ]);
        $formBuilder->add('currentFramework', ChoiceType::class, [
            'label' => false,
            'placeholder' => 'Framework',
            'choices' => [
                'Symfony' => 'Symfony',
                'CodeIgniter' => 'CodeIgniter',
                'Laravel' => 'Laravel',
                'Zend' => 'Zend',
                'Phalcon' => 'Phalcon',
                'CakePHP' => 'CakePHP',
                'Yii' => 'Yii',
            ],
        ]);
        $formBuilder->add('currentPhpVersion', ChoiceType::class, [
            'label' => false,
            'placeholder' => 'PHP version',
            'choices' => [
                '5.6' => '5.6',
                '7.0' => '7.0',
                '7.1' => '7.1',
                '7.2' => '7.2',
                '7.3' => '7.3',
                '7.4' => '7.4',
            ],
        ]);
        $formBuilder->add('desiredFramework', ChoiceType::class, [
            'label' => false,
            'placeholder' => 'Framework',
            'choices' => [
                'Symfony' => 'Symfony',
            ],
        ]);
        $formBuilder->add('desiredPhpVersion', ChoiceType::class, [
            'label' => false,
            'placeholder' => 'PHP version',
            'choices' => [
                '7.4' => '7.4',
            ],
        ]);
        $formBuilder->add('save', SubmitType::class, [
            'label' => 'create',
            'attr' => [
                'class' => 'btn btn-success btn-block',
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
