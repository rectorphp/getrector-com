<?php

declare(strict_types=1);

namespace Rector\Website\Form;

use Rector\Set\SetProvider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class RectorRunFormType extends AbstractType
{
    /**
     * @param mixed[] $options
     */
    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
        $formBuilder->add('content', TextareaType::class, [
            'label' => 'PHP File content',
            'required' => true,
            'attr' => [
                'rows' => 10,
                'cols' => 150,
            ],
        ]);

        $setProvider = new SetProvider();
        $sets = $setProvider->provide();
        $sets = array_combine($sets, $sets);

        $formBuilder->add('set_name', ChoiceType::class, [
            'label' => 'Set',
            'required' => true,
            'choices' => $sets,
        ]);

        $formBuilder->add('process', SubmitType::class, [
            'label' => 'Process File',
            'attr' => [
                'class' => 'btn btn-success m-auto',
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
