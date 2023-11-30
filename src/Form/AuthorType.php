<?php

namespace App\Form;

use App\Entity\Author;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AuthorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class,
                [
                    'label' => 'Firstname',
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'Please enter the firstname of your Author',
                            ]
                        ),
                        new Length(
                            [
                                'min' => 3,
                                'max' => 255,
                                'minMessage' => 'The firstname of your Author must be at least {{ limit }} characters long',
                                'maxMessage' => 'The firstname of your Author cannot be longer than {{ limit }} characters',
                            ]
                        ),
                    ],
                ]
            )
            ->add('lastname', TextType::class,
                [
                    'label' => 'Lastname',
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'Please enter the lastname of your Author',
                            ]
                        ),
                        new Length(
                            [
                                'min' => 3,
                                'max' => 255,
                                'minMessage' => 'The lastname of your Author must be at least {{ limit }} characters long',
                                'maxMessage' => 'The lastname of your Author cannot be longer than {{ limit }} characters',
                            ]
                        ),
                    ],
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Author::class,
        ]);
    }
}
