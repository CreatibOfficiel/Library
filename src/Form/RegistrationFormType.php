<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert; // we need this for the validation

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => [
                    'attr' => [
                        'autocomplete' => 'new-password',
                    ],
                    'label' => 'Mot de passe',
                    'required' => true,
                    'constraints' => [
                        new Assert\NotBlank([
                            'message' => 'Veuillez entrer un mot de passe',
                        ]),
                        new Assert\Length([
                            'min' => 6,
                            'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                            'max' => 4096,
                        ]),
                    ],
                ],
                'second_options' => [
                    'attr' => [
                        'autocomplete' => 'new-password',
                    ],
                    'label' => 'Confirmer le mot de passe',
                    'required' => true,
                    'constraints' => [
                        new Assert\NotBlank([
                            'message' => 'Veuillez confirmer votre mot de passe',
                        ]),
                        new Assert\Length([
                            'min' => 6,
                            'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                            'max' => 4096,
                        ]),
                    ],
                ],
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez entrer un prénom',
                    ]),
                    new Assert\Length([
                        'min' => 3,
                        'minMessage' => 'Votre prénom doit contenir au moins {{ limit }} caractères',
                        'max' => 255,
                    ]),
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez entrer un nom',
                    ]),
                    new Assert\Length([
                        'min' => 3,
                        'minMessage' => 'Votre nom doit contenir au moins {{ limit }} caractères',
                        'max' => 255,
                    ]),
                ],
            ])
            ->add('country', TextType::class, [
                'label' => 'Pays',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez entrer un pays',
                    ]),
                    new Assert\Length([
                        'min' => 3,
                        'minMessage' => 'Votre pays doit contenir au moins {{ limit }} caractères',
                        'max' => 255,
                    ]),
                ],
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
