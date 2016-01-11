<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController
 * @package AppBundle\Controller
 */
class DefaultController extends BaseController
{
    /**
     * @Route("/", name="homepage")
     * @Template("@App/default/index.html.twig")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $page = $request->request->get('page', 1);
        $limit = 5;
        $start = $page * $limit - $limit;
        if ($page > 1) {
            $template = $this->forward('AppBundle:Default:showAjax',
                array('start' => $start, 'limit' => $limit))
                ->getContent();

            $response = new Response($template, 200);

            return $response;
        }
        $posts = $em->getRepository('AppBundle:Post')
            ->show($start, $limit);
        $sidebar1 = $this->showMostPopular();
        $sidebar2 = $this->showLastComment();
        $sidebar3 = $this->tagsCloud();

        return ['posts' => $posts,
                'sidebar1' => $sidebar1,
                'sidebar2' => $sidebar2,
                'sidebar3' => $sidebar3
        ];
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
            ->show($start, $limit);

        return ['posts' => $posts];
    }
}
