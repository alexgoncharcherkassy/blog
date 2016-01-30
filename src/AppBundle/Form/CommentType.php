<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CommentType
 * @package AppBundle\Form
 */
class CommentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            /* ->add('rating', ChoiceType::class, [
                 'choices' => [
                     '1' => 1,
                     '2' => 2,
                     '3' => 3,
                     '4' => 4,
                     '5' => 5
                 ],
                 'choices_as_values' => true,
                 'expanded' => true

             ])*/
            ->add('rating', HiddenType::class)
            ->add('textComment', PurifiedTextareaType::class, [
                'required' => false,
                'label' => 'Add comment',
                'attr' => [
                    'class' => 'tinymce',
                ]
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Comment'
        ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'app_bundle_comment_type';
    }
}
