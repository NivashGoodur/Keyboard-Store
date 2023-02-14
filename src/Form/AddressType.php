<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de l\'adresse',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner un nom d\'adresse'
                    ]),
                    new Length([
                        'min' => 1,
                        'minMessage' => 'Le nom de l\'adresse doit contenir au moins {{ limit }} caractères',
                        'max' => 150,
                        'maxMessage' => 'Le nom de l\'adresse doit contenir au maximum {{ limit }} caractères'
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
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner un Nom'
                    ]),
                    new Length([
                        'min' => 1,
                        'minMessage' => 'Le Nom doit contenir au moins {{ limit }} caractères',
                        'max' => 150,
                        'maxMessage' => 'Le Nom doit contenir au maximum {{ limit }} caractères'
                    ]),
                ]
            ])
            ->add('company', TextType::class, [
                'label' => 'Votre société (facultatif)',
                'required' => false
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'attr' => [
                    'placeholder' => '8 rue des Lilas...'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner une adresse'
                    ]),
                    new Length([
                        'min' => 1,
                        'minMessage' => 'L\' adresse doit contenir au moins {{ limit }} caractères',
                        'max' => 150,
                        'maxMessage' => 'L\' adresse doit contenir au maximum {{ limit }} caractères'
                    ]),
                ]
            ])
            ->add('postal', TextType::class, [
                'label' => 'Code postal',
                'attr' => [
                    'placeholder' => '75019'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner un code postal'
                    ]),
                    new Length([
                        'min' => 1,
                        'minMessage' => 'Le code postal doit contenir au moins {{ limit }} caractères',
                        'max' => 150,
                        'maxMessage' => 'Le code postal doit contenir au maximum {{ limit }} caractères'
                    ]),
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'attr' => [
                    'placeholder' => 'Paris'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner une ville'
                    ]),
                    new Length([
                        'min' => 1,
                        'minMessage' => 'La ville doit contenir au moins {{ limit }} caractères',
                        'max' => 150,
                        'maxMessage' => 'La ville doit contenir au maximum {{ limit }} caractères'
                    ]),
                ]
            ])
            ->add('country', CountryType::class, [
                'label' => 'Pays'
            ])
            ->add('phone', TelType::class, [
                'label' => 'Téléphone',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner un numéro de téléphone'
                    ]),
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Le numéro de téléphone doit contenir au moins {{ limit }} caractères',
                        'max' => 150,
                        'maxMessage' => 'Le numéro de téléphone doit contenir au maximum {{ limit }} caractères'
                    ]),
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'attr' => [
                    'class' => 'd-block btn-info btn-add-address'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
