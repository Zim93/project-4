<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationHostFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('civility', ChoiceType::class,[
                'label'=>'Civilité',
                'choices'=>[
                    'Madame'=>'madame',
                    'Monsieur'=>'Monsieur'
                ],
                'placeholder' => ''
            ])
            ->add('lastname', TextType::class, [
                'label' =>  'Nom'
            ])
            ->add('firstname', TextType::class, [
                'label' =>  'Prénom'
            ])
            ->add('status', ChoiceType::class,[
                'label'=>'Statut',
                'expanded'=> true,
                'choices'=>[
                    'Particulier'=>'Particulier',
                    'Professionnel'=>'Professionnel'
                ]
            ])
            ->add('fonction', TextType::class, [
                'label' =>  'Fonction',
                'required'=>false,
            ])
            ->add('company_name', TextType::class, [
                'label' =>  'Nom de l\'entreprise',
                'required'=>false,
            ])

            ->add('email',EmailType::class,[
                'label' =>  'E-mail',
            ])
            ->add('emailConfirmation',EmailType::class,[
                'label' =>  'Confirmation de l\'e-mail',
                'mapped' => false,
            ])  
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label' => 'Mot de passe',
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrer votre mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('confirmPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Confirmer votre mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('address',TextType::class,[
                'label' =>  'Adresse',
            ])
            ->add('postal_code',TextType::class,[
                'label' =>  'Code postal',
            ])
            ->add('city',TextType::class,[
                'label' =>  'Ville',
            ])
            ->add('country',ChoiceType::class,[
                'label' =>  'Pays',
                'placeholder' => '',
                'choices'  => [
                    "Autriche"=>"Autriche",
                    "Belgique"=>"Belgique",
                    "Bulgarie"=>"Bulgarie",
                    "Croatie"=>"Croatie",	
                    "Chypre"=>"Chypre",
                    "République Tchèque"=>"République Tchèque",
                    "Danemark"=>"Danemark",
                    "Estonie"=>"Estonie",	
                    "Finlande"=>"Finlande",	
                    "France"=>"France",	
                    "Gibraltar"=> "Gibraltar",	
                    "Allemagne"=> "Allemagne",	
                    "Grèce"=> "Grèce",	
                    "Hongrie"=> "Hongrie",
                    "Islande"=> "Islande",	
                    "Irlande"=> "Irlande",	
                    "Italie"=> "Italie",	
                    "Lettonie"=>"Lettonie",	
                    "Liechtenstein"=> "Liechtenstein",	
                    "Lituanie"=>	"Lituanie",	
                    "Luxembourg"=> "Luxembourg",
                    "Malte"=>	"Malte",
                    "Pays-Bas"=>	"Pays-Bas",
                    "Norvège"=>	"Norvège",
                    "Pologne"=>	"Pologne",
                    "Portugal"=>	"Portugal"	,
                    "Roumanie"=>	"Roumanie",
                    "Slovaquie"=> "Slovaquie",
                    "Slovénie"=>	"Slovénie",	
                    "Espagne"=>	"Espagne",
                    "Suède"=>	"Suède",
                    "Royaume-Uni"=>	"Royaume-Uni"
                ]
            ])
            ->add('phone',TextType::class,[
                'label' =>  'Numéro de téléphone',
            ])
            ->add('submit', SubmitType::class,[
                'label' => 'Faire une demande d\'inscription'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
