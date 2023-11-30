<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class,
                [
                    'label' => 'Title',
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'Please enter the title of your Category',
                            ]
                        ),
                        new Length(
                            [
                                'min' => 3,
                                'max' => 255,
                                'minMessage' => 'The title of your Category must be at least {{ limit }} characters long',
                                'maxMessage' => 'The title of your Category cannot be longer than {{ limit }} characters',
                            ]
                        ),
                    ],
                ]
            )
            ->add('description', TextareaType::class,
                [
                    'label' => 'Description',
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'Please enter the description of your Category',
                            ]
                        ),
                        new Length(
                            [
                                'min' => 3,
                                'max' => 255,
                                'minMessage' => 'The description of your Category must be at least {{ limit }} characters long',
                                'maxMessage' => 'The description of your Category cannot be longer than {{ limit }} characters',
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
            'data_class' => Category::class,
        ]);
    }
}
