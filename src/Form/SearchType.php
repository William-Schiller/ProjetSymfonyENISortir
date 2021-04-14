<?php


namespace App\Form;


use App\Data\SearchData;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
                'class' => 'App\Entity\Campus',
                'placeholder' => 'Séléctionnez un campus'
            ])
            ->add('dateMin', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('dateMax', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('isOrganizer', ChoiceType::class, [
                'choices' => [
                    ' Sorties dont je suis l\'organisateur/trice' => ' Sorties dont je suis l\'organisateur/trice',
                ],
                'placeholder' => false,
                'expanded' => true,
                'required' => false,

            ])
            ->add('subscribedTo', ChoiceType::class, [
                'choices' => [
                    'Sorties auxquelles je suis inscrit/e' => "1",
                    //'Sorties auxquelles je ne suis pas inscrit/e' => "2",
                ],
                'placeholder' => false,
                'expanded' => true,
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