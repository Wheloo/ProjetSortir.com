<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Participant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class GererProfilType extends AbstractType
{
    private $user;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->user = $tokenStorage->getToken()->getUser();
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('pseudo', TextType::class,[
                'label' => 'pseudo',
                'required' => false,
                'empty_data' => $this->user->getPseudo()
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prenom',
                'required' => false,
                'empty_data' => $this->user->getPrenom()
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'required' => false,
                'empty_data' => $this->user->getNom()
            ])
            ->add('telephone', TextType::class, [
                'label'=> 'Telephone',
                'required' => false,
                'empty_data' => $this->user->getTelephone()
            ])
            ->add('email', EmailType::class, [
                'label'=> 'Email',
                'required' => false,
                'empty_data' => $this->user->getEmail()
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'options' => ['attr' => ['class' => 'password-field mb-2']],
                'first_options'  => [
                    'label' => 'Mot de passe',
                    'empty_data' => $this->user->getPassword()],
                'second_options' => [
                    'label' => 'Repeter le mot de passe',
                    'empty_data' => $this->user->getPassword()],
                'label' => 'Password',
                'required' => false,
            ])
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'nom'
            ])
            /*Photo*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
