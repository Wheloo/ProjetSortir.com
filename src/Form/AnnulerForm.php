<?php

namespace App\Form;


use App\Entity\Annuler;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnulerForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('motif', TextareaType::class)
            ->add('Enregistrer', SubmitType::class)
            ->add('Annuler', SubmitType::class);
            }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class'=>Annuler::class,
           'method' => 'GET',
           'csrf_protection' => false
        ]);
    }


}