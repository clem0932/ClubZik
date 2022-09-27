<?php

namespace App\Controller\Admin;

use App\Entity\Local;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class LocalCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Local::class;
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
