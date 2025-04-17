<?php

namespace App\Controller\Admin;

use App\Entity\JobApplication;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;

class JobApplicationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return JobApplication::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
                IdField::new('id')->hideOnForm(),
                EmailField::new('email'),
                TextField::new('fullname'),
                TextField::new('subject'),
                TextEditorField::new('message'),
                TextEditorField::new('coverLetter'),
        ];
    }
    
}
