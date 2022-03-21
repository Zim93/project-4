<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nbr_voyagers')
            ->add('start_date')
            ->add('end_date')
            ->add('total')
            ->add('nbr_nights')
            ->add('created_at')
            ->add('updated_at')
            ->add('google_event_id')
            ->add('guest')
            ->add('house')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
