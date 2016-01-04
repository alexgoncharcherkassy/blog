<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titlePost', TextType::class,[
                'attr' => [
                    'placeholder' => 'Add title',
                    'class' => 'form-control'
                ]
            ])
            ->add('textPost', TextareaType::class,[
                'attr' => [
                    'placeholder' => 'Add text post',
                    'class' => 'form-control'
                ]
            ])
            ->add('newTags', TextType::class, [
                'required' => false
            ])
            ->add('tags', EntityType::class, [
                'class' => 'AppBundle\Entity\Tags',
                'choice_label' => 'tagName',
                'attr' => [
                    'class' => 'form-control',
                    'required' => false
                ]
            ])
            ->add('newCategory', TextType::class, [
                'required' => false
            ])
            ->add('category', EntityType::class, [
                'class' => 'AppBundle\Entity\Category',
                'choice_label' => 'categoryName',
                'attr' => [
                    'class' => 'form-control',
                    'required' => false
                ]
            ])
            ->add('images', CollectionType::class, [
                'entry_type' => ImageType::class
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
