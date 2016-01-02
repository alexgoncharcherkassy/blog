<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 01.01.16
 * Time: 13:16
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


/**
 * Class BlogController
 * @package AppBundle\Controller
 */
class BlogController extends Controller
{
    /**
     * @Route("/show/mostpopular", name="most_popular")
     * @Template("@App/default/sidebar/sidebar1.html.twig")
     */
    public function showMostPopularAction()
    {
        $em = $this->getDoctrine()->getManager();

        $posts = $em->getRepository('AppBundle:Post')
            ->showMostPopularPost();

        return ['posts' => $posts];
    }

    /**
     * @Route("/show/lastcomment", name="last_comment")
     * @Template("@App/default/sidebar/sidebar2.html.twig")
     */
    public function showLastCommentAction()
    {
        $em = $this->getDoctrine()->getManager();

        $comments = $em->getRepository('AppBundle:Comment')
            ->showLastFiveComment();

        return ['comments' => $comments];
    }

    /**
     * @Route("/show/{slug}", name="show_post")
     * @Template("@App/blog/showPost.html.twig")
     */
    public function showPostAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $posts = $em->getRepository('AppBundle:Post')
            ->showPost($slug);

        return ['posts' => $posts];
    }

    /**
     * @Route("/show/category/{slug}", name="show_category")
     * @Template("@App/blog/showCategory.html.twig")
     */
    public function showCategoryAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $posts = $em->getRepository('AppBundle:Post')
            ->showCategoryPost($slug);

        return ['posts' => $posts];
    }

    /**
     * @Route("/show/tags/{slug}", name="show_tags")
     * @Template("@App/blog/showTags.html.twig")
     */
    public function showTagsAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $posts = $em->getRepository('AppBundle:Post')
            ->showTagsPost($slug);

        return ['posts' => $posts];
    }
}