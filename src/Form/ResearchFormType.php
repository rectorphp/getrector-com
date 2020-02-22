<?php

declare(strict_types=1);

namespace Rector\Website\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

final class ResearchFormType extends AbstractType
{
    /**
     * @param mixed[] $options
     */
    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
        $formBuilder->add('php_version', TextType::class, [
            'label' => 'PHP version',
            'required' => true,
        ]);

        $formBuilder->add('framework', TextType::class, [
            'label' => 'Framework',
            'required' => true,
        ]);

        $formBuilder->add('using_composer', TextType::class, [
            'label' => 'Using composer?',
            'required' => true,
        ]);

        $formBuilder->add('composer_up_to_date', TextType::class, [
            'label' => 'If yes, is it up to date dependencies, when was your last update?',
            'required' => true,
        ]);

        $formBuilder->add('external_tools', TextType::class, [
            'label' => 'Do you use any external tools? Coding standards? Static analysis?',
            'required' => true,
        ]);

        $formBuilder->add('continuous_integration', TextType::class, [
            'label' => 'Do you have Continuous Integration after each push?',
            'required' => true,
        ]);

        $formBuilder->add('test_coverage', TextType::class, [
            'label' => 'Test coverage?',
            'required' => true,
        ]);

        $formBuilder->add('team_size', TextType::class, [
            'label' => 'Dev team size?',
            'required' => true,
        ]);

        $formBuilder->add('project_age', TextType::class, [
            'label' => 'Age of project? Months, years?',
            'required' => true,
        ]);

        $formBuilder->add('project_size', TextType::class, [
            'label' => 'Size of projects? Classes, lines of code?',
            'required' => true,
        ]);

        $formBuilder->add('frustration_level', TextType::class, [
            'label' => 'Frustration level? 10 = frustrated as hell, 0 = not frustrated at all',
            'required' => true,
        ]);

        $formBuilder->add('frustration_reasons', TextareaType::class, [
            'label' => 'What sucks the most in your application?',
            'required' => true,
        ]);

        $formBuilder->add('improvements_suggestions', TextareaType::class, [
            'label' => 'How would you decrease your frustration level if you could?',
            'required' => true,
        ]);

        $formBuilder->add('company_web', TextType::class, [
            'label' => 'Company website',
            'required' => true,
        ]);

        $formBuilder->add('contact_email', TextType::class, [
            'label' => 'Your email',
        ]);

        $formBuilder->add('contact_name', TextType::class, [
            'label' => 'Your name',
        ]);

        $formBuilder->add('submit', SubmitType::class, [
            'label' => 'Send',
            // 'attr' => [
            //    'class' => 'btn btn-lg btn-success m-auto btn-demo-submit',
            // ],
        ]);
    }
}
