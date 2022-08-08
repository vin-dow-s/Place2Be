<?php

namespace App\Form;

use App\Data\SearchData;
use App\Entity\Campus;
use App\Entity\Participant;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('campus', EntityType::class, [
                'class'=> Campus::class,
                'label' => false,
                'required' => false,
                'attr' => [
                    'style' => 'width: 69em'
                ]
//                'multiple' => true,
//                'expanded' => true,
//                'empty_data' => Campus::class,

            ])
            ->add('q', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher',
                    'style' => 'width: 69em'
                ]
            ])
            ->add('dateMin', DateTimeType::class, [
                'label' => false,
                'widget' => 'single_text',
                'html5' => true,
                'required' => false,
                'attr' => [
                    'style' => 'width: 10em'
                ]
            ])
            ->add('dateMax', DateTimeType::class, [
                'label' => false,
                'widget' => 'single_text',
                'html5' => true,
                'required' => false,
                'attr' => [
                    'style' => 'width: 10em'
                ]

            ])
            ->add('isOrganisateur', CheckboxType::class, [
//                'class'=> Participant::class,
                'value' => Participant::class,
//                'data' => $builder.Participant::class,
                'mapped' => false,
                'label' => false,
//                'label' => 'Sorties dont je suis organisateur',
                'required' => false,
                'attr' => [
                    'style' => 'width: 1em'
                ]
            ])


//            TODO: Ajouter les checkboxtypes
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver -> setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
