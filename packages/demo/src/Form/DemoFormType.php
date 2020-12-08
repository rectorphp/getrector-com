<?php

declare(strict_types=1);

namespace Rector\Website\Demo\Form;

use Rector\Website\Demo\ValueObject\DemoFormData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

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
            'empty_data' => '',
            'attr' => [
                'rows' => 30,
                'class' => 'codemirror_php',
            ],
            'constraints' => [new NotBlank()],
        ]);

        $formBuilder->add('config', TextareaType::class, [
            'label' => false,
            'required' => true,
            'empty_data' => '',
            'attr' => [
                'class' => 'codemirror_php',
            ],
            'constraints' => [new NotBlank()],
        ]);

        $formBuilder->add('process', SubmitType::class, [
            'label' => 'Process',
            'attr' => [
                'class' => 'btn btn-lg btn-success m-auto btn-demo-submit',
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults([
            'data_class' => DemoFormData::class,
        ]);
    }
}
