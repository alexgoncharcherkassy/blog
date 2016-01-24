<?php

namespace AppBundle\Twig\Extension;

use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class AppExtension
 * @package AppBundle\Twig\Extension
 */
class AppExtension extends \Twig_Extension
{
    /**
     * @var RegistryInterface
     */
    protected $doctrine;

    /**
     * @param RegistryInterface $doctrine
     */
    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('showSideBar',
                array($this, 'getSideBar'),
                array(
                    'needs_environment' => true,
                    'is_safe' => array('html'))
            )
        );
    }

    /**
     * @param \Twig_Environment $twig
     * @return string
     */
    public function getSideBar(\Twig_Environment $twig)
    {
        $em = $this->doctrine->getManager();

        $posts = $em->getRepository('AppBundle:Post')
            ->showMostPopularPost();
        $comments = $em->getRepository('AppBundle:Comment')
            ->showLastFiveComment();
        $tags = $em->getRepository('AppBundle:Tag')
            ->showNotNullTags();

        return $twig->render(
            '@App/sidebar.html.twig',
            array(
                'posts' => $posts,
                'comments' => $comments,
                'tags' => $tags
            )
        );
    }


    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_extension';
    }
}
