<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner un Email'
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'L\'email doit contenir au moins {{ limit }} caractères',
                        'max' => 150,
                        'maxMessage' => 'L\'email doit contenir au maximum {{ limit }} caractères'
                    ]),
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner un Prénom'
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Le Prénom doit contenir au moins {{ limit }} caractères',
                        'max' => 150,
                        'maxMessage' => 'Le Prénom doit contenir au maximum {{ limit }} caractères'
                    ]),
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner un Nom'
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Le Nom doit contenir au moins {{ limit }} caractères',
                        'max' => 150,
                        'maxMessage' => 'Le Nom doit contenir au maximum {{ limit }} caractères'
                    ]),
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'invalid_message' => 'Le mot de passe et la confirmation doivent être identique',
                'label' => 'Mot de passe',
                'first_options' => ['label'=> 'Mot de passe'],
                'second_options' => ['label'=> 'Confirmer le mot de passe']
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'S\'inscrire',
                'attr' => [
                    'class' => 'mt-4 d-block w-100 btn-info'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
