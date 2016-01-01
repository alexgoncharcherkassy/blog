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
     * @Template("default/sidebar/sidebar1.html.twig")
     */
    public function showMostPopularAction()
    {
        $em = $this->getDoctrine()->getManager();

        $posts = $em->getRepository('AppBundle:Post')
            ->showMostPopularPost();

        return ['posts' => $posts];
    }
}