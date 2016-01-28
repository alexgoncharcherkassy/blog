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

    public function getFilters()
    {
        return array(
            'limitWords' => new \Twig_Filter_Method($this, 'limitWords')
        );
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
     *
     * @param string $string
     */
    public function limitWords($string, $limit = 20)
    {
        $str = explode(' ', $string);
        $countWords = count($str);
        if ($countWords <= $limit) {
            $lim = $countWords;
        } else {
            $lim = $limit;
        }
        $strResult = '';
        for ($i = 0; $i < $lim; $i++) {
            $strResult .= $str[$i].' ';
        }

        return $strResult.' ...';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app.twig.extension';
    }
}
