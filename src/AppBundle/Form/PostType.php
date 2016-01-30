<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PostType
 * @package AppBundle\Form
 */
class PostType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titlePost', TextType::class, [
                'attr' => [
                    'placeholder' => 'Add title article',
                    'class' => 'form-control'
                ]
            ])
            ->add('textPost', PurifiedTextareaType::class, [
                'attr' => [
                    'placeholder' => 'Add text article',
                    'class' => 'tinymce',
                    'rows' => 15
                ]
            ])
            ->add('newTags', TextType::class, [
                'required' => false
            ])
            ->add('newCategory', TextType::class, [
                'required' => false
            ])
            ->add('blog_image', FileType::class, [
                'required' => false
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Post'
        ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'app_bundle_post_type';
    }
}
