<?php

declare(strict_types=1);

namespace Rector\Website\Admin\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Rector\Website\CleaningLadyList\Entity\Checkbox;
use Rector\Website\CleaningLadyList\Entity\CleaningList;

final class CleaningListCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CleaningList::class;
    }

    /**
     * @return FieldInterface[]
     */
    public function configureFields(string $pageName): array
    {
        return [
            TextField::new('name'),
        ];
    }
}
