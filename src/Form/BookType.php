<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'row_attr' => [
                    'class' => 'input-group mb-3'
                ],
                'attr' => [
                    'class' => 'une-autre-classe'
                ]
            ])
            ->add('author', TextType::class, [
                'row_attr' => [
                    'class' => 'input-group mb-3'
                ]
            ])
            ->add('nbPage', IntegerType::class, [
                'row_attr' => [
                    'class' => 'input-group mb-3'
                ]
            ])
            ->add('publishedDate', DateType::class, [
                'widget' => 'single_text',
                'row_attr' => [
                    'class' => 'input-group mb-3'
                ]
            ])
            ->add('retreatedDate', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'row_attr' => [
                    'class' => 'input-group mb-3'
                ]
            ])
            ->add('status', ChoiceType::class, [
                'placeholder' => '-- Veuillez choisir un statut --',
                'choices' => [
                    'CREATED' => 'CREATED',
                    'PUBLISHED' => 'PUBLISHED',
                    'RETIRED' => 'RETIRED'
                ],
                'row_attr' => [
                    'class' => 'input-group mb-3'
                ]
            ])
            ->add('category', EntityType::class, [
                'required' => false,
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => '--Veuillez choisir une catégorie--',
                'row_attr' => [
                    'class' => 'input-group mb-3'
                ]
            ])
            ->add('picture_file', FileType::class, [
                'label' => 'Image (.jpeg, .jpg, .png)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                        ],
                        'maxSizeMessage' => 'Ce fichier est trop lourd',
                        'mimeTypesMessage' => 'Le format est pas ok: (.jpeg, .jpg, .png)',
                    ]),
                ],
                'row_attr' => [
                    'class' => 'input-group mb-3'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Save'
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
