<?php

namespace App\Controller\Admin;

use App\Entity\Win;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;

class WinCrudController extends AbstractCrudController
{
    #[\Override]
    public static function getEntityFqcn(): string
    {
        return Win::class;
    }

    #[\Override]
    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('player'),
            DateField::new('createdAt'),
            BooleanField::new('prizeCollected'),
        ];
    }
}
