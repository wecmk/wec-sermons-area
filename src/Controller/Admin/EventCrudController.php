<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EventCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Event::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['date', 'series.name', 'reading', 'title', 'speaker', 'shortId', 'legacyId', 'tags'])
            ->setDefaultSort(['date' => 'DESC', 'apm' => 'DESC'])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm()->hideOnIndex()->hideOnDetail(),
            Field::new('shortId')->setFormTypeOption('disabled', 'disabled'),
            Field::new('date')->setHelp('Date of the recorded event'),
            TextField::new('uuid')->hideOnIndex()->hideOnForm(),
            Field::new('apm')->setHelp('One of AM,PM,MON,TUE,WED,THU,FRI,SAT'),
            AssociationField::new('series')->autocomplete(),
            TextField::new('reading')->setRequired(false),
            TextField::new('secondReading')->setRequired(false)->hideOnIndex(),
            TextField::new('title')->setRequired(false),
            Field::new('speaker')->setRequired(false),
            Field::new('youTubeLink')->hideOnIndex()->setLabel('YouTube Link'),
            Field::new('corrupt'),
            Field::new('isPublic'),
            TextareaField::new('tags')->setRequired(false)->hideOnIndex(),
            TextareaField::new('publicComments')->setRequired(false)->hideOnIndex(),
            TextareaField::new('privateComments')->setRequired(false)->hideOnIndex(),
        ];
    }
}
