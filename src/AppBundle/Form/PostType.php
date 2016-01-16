<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titlePost', TextType::class, [
                'attr' => [
                    'placeholder' => 'Add title post',
                    'class' => 'form-control'
                ]
            ])
            ->add('textPost', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Add text post',
                    'class' => 'form-control',
                    'rows' => 10
                ]
            ])
            ->add('newTags', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Add tags through a comma or/and select from tags'
                ],
                'required' => false
            ])
            ->add('tags', EntityType::class, [
                'class' => 'AppBundle\Entity\Tag',
                'choice_label' => 'tagName',
                'multiple' => true,
                'expanded' => true,
                'required' => false
            ])
            ->add('newCategory', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Add only one category or select from categories'
                ],
                'required' => false
            ])
            ->add('category', EntityType::class, [
                'class' => 'AppBundle\Entity\Category',
                'choice_label' => 'categoryName',
                'attr' => [
                    'class' => 'form-control'
                ],
                'placeholder' => 'Select category',
                'required' => false
            ])
            ->add('blog_image', FileType::class, [
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Post'
        ]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_post_type';
    }
}
