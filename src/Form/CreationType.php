<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Repository\LieuRepository;
use App\Repository\VilleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreationType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',null,['label' => 'Titre de la sortie'])
            ->add('dateHeureDebut',DateTimeType::class,
                ['label' => 'Date et heure de la sortie',
                    'widget' => 'single_text',
                ])
            ->add('dateLimiteInscription',DateType::class,
                ['label' => 'Date limite inscription',
                    'widget' => 'single_text',
                ])
            ->add('nbInscriptionsMax',IntegerType::class, ['label' => 'Nombre de places'])
            ->add('duree',IntegerType::class, ['label' => 'Durée'])
            ->add('infosSortie',null,['label' => 'Description et infos'])
            ->add('latitude',NumberType::class,[
                'label' => 'Latitude',
                'mapped' => false,
                'required' => false,
             ])
            ->add('longitude',NumberType::class,[
                'label' => 'Longitude',
                 'mapped' => false,
                'required' => false,
            ])
            ->add('ville', EntityType::class, [
                'class' => Ville::class,
                'choice_label' => 'nom',
                'label' => 'Ville',
                'mapped'=>false,
                'placeholder'=> '',
            ])
            ->add('lieu', EntityType::class,[
                'class' => Lieu::class,
                'choice_label' => 'nom',
                'label' => 'Lieux',
                'placeholder'=> ''
            ])
            ->add("Enregistrer", SubmitType::class)
            ->add("Publier", SubmitType::class)

        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
            'method' => 'POST',
        ]);
    }

}
