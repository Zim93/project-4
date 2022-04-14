<?php

namespace App\Form;

use App\Entity\House;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class HouseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'label'=>'Nom'
            ])
            ->add('full_address', TextType::class,[
                'label'=>'Adresse compléte'
            ])
            ->add('street_number', IntegerType::class,[
                'label'=>'Numéro de voie',
                'attr' => [
                    'readonly'=>"readonly",
                    'min' => 1,
                ]
            ])
            ->add('street_sub_number', TextType::class,[
                'label'=>'Complément de numéro de voie',
                'required'=>false,
                'attr' => [
                    'readonly'=>"readonly",
                ]
            ])
            ->add('street_label', TextType::class,[
                'label'=>'Libellé de voie',
                'attr' => [
                    'readonly'=>"readonly",
                ]
            ])
            ->add('city_name', TextType::class,[
                'label'=>'Nom de ville',
                'attr' => [
                    'readonly'=>"readonly",
                ]
            ])
            ->add('postal_code',TextType::class,[
                'label'=>'Code postal',
                'attr' => [
                    'readonly'=>"readonly",
                ],
                'constraints'=>[
                    new Regex([
                        'pattern' => '/^(?:0[1-9]|[1-8]\d|9[0-8])\d{3}$/', 
                        'message' => 'Veuiller entrer un code postal correcte'
                        ])
                ]
            ])
            ->add('country', TextType::class,[
                'label'=>'Pays',
                'attr' => [
                    'readonly'=>"readonly",
                ]
            ])
            ->add('type',ChoiceType::class,[
                'label'=>'Type d\'habitation',
                'placeholder' => 'Choisissez un type d\'habitation',
                'expanded' => true,
                'choices'  => [
                    'Cabane dans les arbres' => 'Cabane dans les arbres',
                    'Cabane sur pilotis' => 'Cabane sur pilotis',
                    'Cabane sur l\'eau' => 'Cabane sur l\'eau',
                    'Cabane' => 'Cabane',
                    'Roulotte' => 'Roulotte',
                    'Yourte' => 'Yourte',
                    'Tente / Tipi' => 'Tente / Tipi',
                    'Bulle' => 'Bulle',
                    'Chalet' => 'Chalet',
                    'Autre' => 'Autre',
                ]
            ])
            ->add('nbr_accepted', IntegerType::class,[
                'label'=>'Nombre de voyageurs',
                'attr' => [
                    'min' => 1
                    ]
            ])
            ->add('nbr_beds', IntegerType::class,[
                'label'=>'Nombre de lits',
                'attr' => [
                    'min' => 1
                    ]
            ])
            ->add('house_area', IntegerType::class,[
                'label'=>'Surface du logement en m²'
            ])
            ->add('nbr_rooms', IntegerType::class,[
                'label'=>'Nombre de chambres',
                'attr' => [
                    'min' => 1
                    ]
            ])
            ->add('nbr_wc',IntegerType::class,[
                'label'=>'Nombre de WC',
                'attr' => [
                    'min' => 1
                    ]
            ])
            ->add('wc_type',ChoiceType::class,[
                'expanded'=>true,
                'choices'  => [
                    'WC dans le logement'=>'WC dans le logement',
                    'WC, hors du logement'=>'WC, hors du logement'
                ]
            ])           
            ->add('nbr_showeroom',IntegerType::class,[
                'label'=>'Nombre de salle de bain',
                'attr' => [
                    'min' => 1
                    ]
            ])
            ->add('shower_room_type',ChoiceType::class,[
                'expanded'=>true,
                'choices'  => [
                    'SDB dans le logement'=>'SDB dans le logement',
                    'SDB, hors du logement'=>'SDB, hors du logement'
                ]
            ])
            ->add('description', TextareaType::class,[
                'required'=>false,
            ])
            ->add('price',IntegerType::class,[
                'label'=>'Prix',
                'attr' => [
                    'min' => 1
                    ]
            ])
            ->add('equiments',ChoiceType::class,[
                'label'=>'Logement avec cuisine',
                'expanded' => true,
                'multiple'=>true,
                'choices'  => [
                    'Four'=>'Four',
                    'Plaque de cuission'=>'Plaque de cuission',
                    'Micro onde'=>'Micro onde',
                    'Réfrigérateur'=>'Réfrigérateur',
                    'Cafetiere'=>'Cafetiere',
                    'Bouilloire'=>'Bouilloire',
                    'Table et chaise'=> 'Table et chaise',
                    'Lave vaisselle'=>'Lave vaisselle',
                    'Vaisselle'=>'Vaisselle'
                ]                    
            ])
            ->add('outside',ChoiceType::class,[
                'label'=>'Extérieur',
                'expanded' => true,
                'multiple'=>true,
                'choices'  => [
                    'Terrasse'=>'Terrasse',
                    'Barbecue'=>'Barbecue',
                    'Balcon'=>'Balcon',
                    'Mobilier de jardin'=>'Mobilier de jardin',
                    'Chaise longues'=>'Chaise longues',
                ]
            ])
            ->add('confort',ChoiceType::class,[
                'label'=>'Confort',
                'expanded' => true,
                'multiple'=>true,
                'choices'  => [
                    'Piscine partagé'=>'Piscine partagé',
                    'Piscine privative'=>'Piscine privative',
                    'Spa partagé'=>'Spa partagé',
                    'Spa privatif'=>'Spa privatif',
                    'Sauna'=>'Sauna',
                    'Air conditionné'=>'Air conditionné',
                    'Wifi'=>'Wifi',
                    'TV'=>'TV',
                    'Chauffage'=>'Chauffage',
                    'Lit bébé'=>'Lit bébé',
                    'Chaise bébé'=>'Chaise bébé',
                    'Douche'=>'Douche',
                    'Baignoire'=>'Baignoire',
                    'Serviette fournis'=>'Serviette fournis',
                    'Lave linges'=>'Lave linges',
                    'Linge de lit'=>'Linge de lit',
                    'Linge de lit non fournis'=>'Linge de lit non fournis',
                    'Aspirateur'=>'Aspirateur',
                    'Parking gratuit'=>'Parking en supplément',
                    'Air de jeux'=>'Air de jeux',
                ]
            ])
            ->add('arrival_time', TimeType::class,[
                'label'=>'Horraire d\'arrivée mini',
            ])
            ->add('arrival_time_max', TimeType::class,[
                'label'=>'Horraire d\'arrivée maxi',
            ])
            ->add('departure_time', TimeType::class,[
                'label'=>'Horraire de départ maxi',
            ])
            ->add('breakfast_dispo', CheckboxType::class,[
                'label'=>'Petit déjeuné inclus',
                'required'=>false,
            ])
            ->add('strong_points',ChoiceType::class,[
                'label'=>'Point fort en évidence (max 5) :',
                'expanded' => true,
                'multiple'=>true,
                'choices'  => [
                    'Piscine'=> 'piscine',
                    'Spa'=> 'spa',
                    'Air conditionné'=> 'ac',
                    'Wifi'=> 'Wifi',
                    'TV'=> 'tv',
                    'Chauffage'=> 'chauffage',
                    'Cuisine'=> 'cuisine',
                    'Terasse'=> 'terasse',
                    'Accessibilité'=> 'acce',
                    'Petit déjeuner'=> 'petit-dej',
                    'Chauffage'=> 'chauffage',
                    'Restauration sur place'=> 'resto',
                    'Parking'=> 'parking'
                ],
                'attr' => [
                    'data-checked-nbr' => 0
                ],
                'choice_attr' => function($choice) {
                    return ['class' => 'strong_points'];
                },
            ])
            ->add('images', FileType::class, [
                'label' => 'Images',
    
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,
    
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
                'multiple' => true,
            ])
            ->add('lat',HiddenType::class)
            ->add('lng',HiddenType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => House::class,
        ]);
    }
}
