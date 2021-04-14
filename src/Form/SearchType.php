<?php


namespace App\Form;


use App\Data\SearchData;
use App\Entity\Campus;
use App\Entity\Trip;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class SearchType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher'
                ]
            ])
        ->add('campus', EntityType::class, [
            'label' => false,
            'required' => false,
            'class' => 'App\Entity\Campus'
        ])
        ->add('dateMin', DateType::class, [
            'label' => 'Entre le',
            'required' => false,
            'format' => 'dd/MM/yyyy',
            'attr' => [
                'class' => 'date_picker'
            ]
        ])
        ->add('dateMax', DateType::class, [
            'label' => 'et le',
            'required' => false,
            'format' => 'dd/MM/yyyy'

        ])

        ->add('isOrganizer', CheckboxType::class, [
            'label' => 'Sorties dont je suis l\'organisateur/trice',
            'required' => false,
        ])
        ->add('subscribedTo', CheckboxType::class, [
            'label' => 'Sorties auxquelles je suis inscrit/e',
            'required' => false,
        ])
        ->add('insubscribedTo', CheckboxType::class, [
            'label' => 'Sorties auxquelles je ne suis pas inscrit/e',
            'required' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false

        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }


}