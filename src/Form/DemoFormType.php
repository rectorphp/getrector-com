<?php

declare(strict_types=1);

namespace Rector\Website\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class DemoFormType extends AbstractType
{
    /**
     * @param mixed[] $options
     */
    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
        $formBuilder->add('content', TextareaType::class, [
            // no label needed
            'label' => false,
            'required' => true,
            'attr' => [
                'rows' => 30,
                'class' => 'codemirror_php'
            ],
        ]);

        $formBuilder->add('config', TextareaType::class, [
            'label' => 'Rector config',
            'required' => true,
            'attr' => [
                'class' => 'codemirror_yaml'
            ],
        ]);

        $formBuilder->add('process', SubmitType::class, [
            'label' => 'Process!',
            'attr' => [
                'class' => 'btn btn-lg btn-success m-auto',
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults([
            'data_class' => RectorRunFormData::class,
        ]);
    }
}
