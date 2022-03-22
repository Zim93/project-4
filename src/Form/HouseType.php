<?php

namespace App\Form;

use App\Entity\House;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class HouseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'label'=>'Nom'
            ])
            ->add('type',ChoiceType::class,[
                'label'=>'Type d\'habitation',
                'placeholder' => 'Choisissez un type d\'habitation',
                'choices'  => [
                    'Type1' => 'Type1',
                    'Type2' => 'Type2',
                    'Type3' => 'Type3',
                ]
            ])
            ->add('street_number', IntegerType::class,[
                'label'=>'Numéro de voie',
                'attr' => [
                    'min' => 1
                ]
            ])
            ->add('street_sub_number', TextType::class,[
                'label'=>'Complément de numéro de voie'
            ])
            ->add('street_label', TextType::class,[
                'label'=>'Libellé de voie'
            ])
            ->add('city_name', TextType::class,[
                'label'=>'Nom de ville'
            ])
            ->add('postal_code',TextType::class,[
                'label'=>'Code postal',
                'constraints'=>[
                    new Regex([
                        'pattern' => '/^(?:0[1-9]|[1-8]\d|9[0-8])\d{3}$/', 
                        'message' => 'Country code must be exactly 2 character long upper case letters.'
                        ])
                ]
            ])
            ->add('images', FileType::class,[
                'multiple'=>true
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
            ->add('nbr_rooms', IntegerType::class,[
                'label'=>'Nombre de chambres',
                'attr' => [
                    'min' => 1
                    ]
            ])
            ->add('nbr_showeroom',IntegerType::class,[
                'label'=>'Nombre de salle de bain',
                'attr' => [
                    'min' => 1
                    ]
            ])
            ->add('description', TextareaType::class)
            ->add('price',IntegerType::class,[
                'label'=>'Prix',
                'attr' => [
                    'min' => 1
                    ]
            ])
            ->add('equiments',ChoiceType::class,[
                'label'=>'Equipements disponible',
                'expanded' => true,
                'multiple'=>true,
                'choices'  => [
                    'Equipement1' => 'Equipement1',
                    'Equipement2' => 'Equipement2',
                    'Equipement3' => 'Equipement3',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => House::class,
        ]);
    }
}
