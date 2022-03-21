<?php

namespace App\Form;

use App\Entity\House;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HouseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('type')
            ->add('full_address')
            ->add('street_number')
            ->add('street_sub_number')
            ->add('street_label')
            ->add('city_name')
            ->add('postal_code')
            ->add('images')
            ->add('nbr_accepted')
            ->add('nbr_beds')
            ->add('nbr_rooms')
            ->add('nbr_showeroom')
            ->add('description')
            ->add('price')
            ->add('calendar_id')
            ->add('equiments')
            ->add('host')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => House::class,
        ]);
    }
}
