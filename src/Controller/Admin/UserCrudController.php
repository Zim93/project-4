<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            EmailField::new('email','E-mail'),
            Field::new('password')
                ->hideOnIndex()
                ->hideOnForm(),
            Field::new('firstname','Prénom'),
            Field::new('lastname', 'Nom'),
            ChoiceField::new('roles')
                ->setChoices(
                    ['Administrateur' => 'ROLE_ADMIN',
                    'Utilisateur' => 'ROLE_USER',
                    'Hote' => 'ROLE_HOST'])
                ->allowMultipleChoices(),
            Field::new('created_at','Créer')
            ->hideOnForm(),
        ];
    }
}
