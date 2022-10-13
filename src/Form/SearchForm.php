<?php

namespace App\Form;

use App\Data\SearchData;
use App\Entity\Campus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'nom',
                'label' => 'Campus'
            ])
            ->add('search', TextType::class, [
                'label'=> 'Le nom de la sortie contient',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher une sortie'
                ]
            ])
            ->add('dateDebut', DateType::class, [
                'widget'=> "single_text",
                'required' => false
                ])
            ->add('dateFin', DateType::class, [
                'widget'=> "single_text",
                'required' => false
            ])

          ->add('organisateur', CheckboxType::class, [
                'label' => "Sorties dont je suis l'organisateur/trice",
                'required' => false,
            ])

            ->add('inscrit', CheckboxType::class, [
            'label' => 'Sorties auxquelles je suis inscrit/e',
            'required' => false,
            ])

            ->add('non_inscrit', CheckboxType::class, [
                'label' => 'Sorties auxquelles je ne suis pas inscrit',
                'required' => false
            ])
            ->add('passees', CheckboxType::class, [
            'label' => 'Sorties passées',
            'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class'=> SearchData::class,
           'method' => 'GET',
           'csrf_protection' => false
        ]);
    }


}