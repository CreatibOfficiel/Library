<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Category;
use App\Repository\AuthorRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Book title',
                'required' => true,
                'attr' => [
                    'placeholder' => 'My awesome Book title'
                ]
            ])
            ->add('isbn', TextType::class, [
                'label' => 'Book isbn',
                'required' => true,
                'attr' => [
                    'placeholder' => 'ISBN',
                ]
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'title',
                'multiple' => true,
                'expanded' => false,
                'required' => true,
                'query_builder' => function (CategoryRepository $categoryRepository): QueryBuilder {
                    return $categoryRepository->createQueryBuilder('c')
                        ->orderBy('c.title', 'ASC');
                },
                'by_reference' => false,
            ])
            ->add('author', EntityType::class, [
                'class' => Author::class,
                'choice_label' => 'lastname',
                'multiple' => false,
                'expanded' => false,
                'required' => true,
                'query_builder' => function (AuthorRepository $authorRepository): QueryBuilder {
                    return $authorRepository->createQueryBuilder('a')
                        ->orderBy('a.lastname', 'ASC');
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
