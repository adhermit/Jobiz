<?php

namespace App\Controller\Admin;

use App\Entity\Job;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use Vich\UploaderBundle\Form\Type\VichImageType;


class JobCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Job::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id') ->hideOnForm(),
            TextField::new('title'),
            TextEditorField::new('description'),
            TextField::new('city'),
            TextField::new('country'),
            ChoiceField::new('type') ->setChoices([
                'Full Time' => 'Full Time',
                'Part Time' => 'Part Time',
                'Internship' => 'Internship',
                'Freelance' => 'Freelance',
            ]),
            TextField::new('company'),
            AssociationField::new('category')
                ->setFormTypeOption('choice_label', 'type'),
        ];
    }
    
}
