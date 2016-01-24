<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
                    'placeholder' => 'Add title article',
                    'class' => 'form-control'
                ]
            ])
            ->add('textPost', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Add text article',
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
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->orderBy('t.tagName', 'ASC');
                },
                'choice_label' => 'tagName',
                'multiple' => true,
                'expanded' => true,
                'attr' => ['class' => 'list-group-item'],
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
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.categoryName', 'ASC');
                },
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
