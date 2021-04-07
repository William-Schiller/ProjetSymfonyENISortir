<?php

namespace App\Form;

use App\Entity\Trip;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateTripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $dateNow = new \DateTime('now');

        $builder
            ->add('name')
            ->add('dateStart', DateTimeType::class, [
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'data' => new \DateTime('now +48 hours')
            ])
            ->add('duration')
            ->add('dateLimitInscription', DateTimeType::class, [
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'data' => new \DateTime('now +24 hours')
            ])
            ->add('nbMaxRegistration')
            ->add('informationTrip')
            ->add('campus')
            ->add('adress')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
        ]);
    }
}
