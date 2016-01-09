<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController
 * @package AppBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template("@App/default/index.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $posts = $em->getRepository('AppBundle:Post')
            ->showAllPost();

        return ['posts' => $posts];
    }

    /**
     * @Route("/ajax/", name="ajax_homepage")
     */
    public function indexAjaxAction(Request $request)
    {
        $page = $request->request->get('page');
        $limit = 5;
        $start = $page * $limit - $limit;
        $template = $this->forward('AppBundle:Default:showAjax',
            array('start' => $start, 'limit' => $limit))
            ->getContent();

        $response = new Response($template, 200);

        return $response;
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/show_ajax", name="show_ajax")
     * @Template("@App/default/ajaxLoad.html.twig")
     */
    public function showAjaxAction($start, $limit)
    {
        $em = $this->getDoctrine()->getManager();

        $posts = $em->getRepository('AppBundle:Post')
            ->showAjax($start, $limit);

        return ['posts' => $posts];
    }
}
