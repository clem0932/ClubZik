<?php

namespace App\Controller\Admin;

use App\Entity\Instrument;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class InstrumentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Instrument::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
