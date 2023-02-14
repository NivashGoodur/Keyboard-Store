<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner un Nom'
                    ]),
                    new Length([
                        'min' => 1,
                        'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères',
                        'max' => 150,
                        'maxMessage' => 'Le nom doit contenir au maximum {{ limit }} caractères'
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
                        'min' => 1,
                        'minMessage' => 'Le Prénom doit contenir au moins {{ limit }} caractères',
                        'max' => 150,
                        'maxMessage' => 'Le Prénom doit contenir au maximum {{ limit }} caractères'
                    ]),
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner un Email'
                    ]),
                    new Length([
                        'min' => 1,
                        'minMessage' => 'L\'email doit contenir au moins {{ limit }} caractères',
                        'max' => 150,
                        'maxMessage' => 'L\'email doit contenir au maximum {{ limit }} caractères'
                    ]),
                ]
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner un message'
                    ]),
                    new Length([
                        'min' => 10,
                        'minMessage' => 'Le message doit contenir au moins {{ limit }} caractères',
                        'max' => 2000,
                        'maxMessage' => 'Le message doit contenir au maximum {{ limit }} caractères'
                    ]),
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => [
                    'class' => 'btn-block btn-success w-100'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
