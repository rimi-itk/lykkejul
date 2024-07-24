<?php

namespace App\Controller\Admin;

use App\Entity\Win;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use Symfony\Component\Translation\TranslatableMessage;

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
        yield AssociationField::new('player');
        yield DateField::new('createdAt')
            ->setFormat('yyyy-MM-dd')
            ->setLabel(new TranslatableMessage('Date'));
        yield BooleanField::new('prizeCollected');
    }
}
