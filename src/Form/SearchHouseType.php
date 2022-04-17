<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class SearchHouseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('address', TextType::class,[
                'label' => 'Destination',
            ])
            ->add('nbr_voyagers', IntegerType::class,[
                'label' => 'Nombre de voyageurs',
                'empty_data' => 'Default value',
                'attr'=>[
                    'min'=>1,
                    'value'=>1,
                ]
            ])
            ->add('type',ChoiceType::class,[
                'label'=>'Type d\'habitation',
                'placeholder' => 'Type d\'hébergement',
                'choices'  => [
                    'Cabane dans les arbres' => 'Cabane dans les arbres',
                    'Cabane sur pilotis' => 'Cabane sur pilotis',
                    'Cabane sur l\'eau' => 'Cabane sur l\'eau',
                    'Cabane' => 'Cabane',
                    'Roulotte' => 'Roulotte',
                    'Yourte' => 'Yourte',
                    'Tente / Tipi' => 'Tente / Tipi',
                    'Bulle' => 'Bulle',
                    'Chalet' => 'Chalet'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
