<?php

declare(strict_types=1);

namespace Rector\Website\Form;

use Rector\Set\SetProvider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

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
            'data' => "<?php\necho 'hi';'" . PHP_EOL,
            'attr' => [
                'rows' => 10,
                'cols' => 150,
            ],
        ]);

        $setProvider = new SetProvider();
        $sets = $setProvider->provide();
        $sets = array_flip($sets);

        $formBuilder->add('set', ChoiceType::class, [
            'label' => 'Set',
            'required' => true,
            'choices' => $sets,
        ]);

//        $rectorsFinder = new RectorsFinder();
//        $rectorRules = $rectorsFinder->findCoreRectorClasses();
//        sort($rectorRules);
//        $rectorRules = array_flip($rectorRules);
//
//        $formBuilder->add('rule', ChoiceType::class, [
//            'label' => 'Rule',
//            'required' => false,
//            'multiple' => true,
//            'choices' => $rectorRules
//        ]);

        $formBuilder->add('process', SubmitType::class, [
            'label' => 'Process File',
            'attr' => [
                'class' => 'btn btn-success',
            ],
        ]);
    }
}
