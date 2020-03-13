<?php

declare(strict_types=1);

namespace Rector\Website\ForCompanies\Form;

use Rector\Website\ForCompanies\ValueObject\ProjectCalculationFormData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ProjectCalculationFormType extends AbstractType
{
    /**
     * @param mixed[] $options
     */
    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
        $formBuilder->add('in_house_months', NumberType::class);
        $formBuilder->add('in_house_monthly_costs', NumberType::class);
        $formBuilder->add('project_lines_of_code', NumberType::class);

        $formBuilder->add('submit', SubmitType::class, [
            'label' => 'Calculate',
            'attr' => [
                'class' => 'btn btn-lg btn-success m-auto',
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults([
            'data_class' => ProjectCalculationFormData::class,
        ]);
    }
}
