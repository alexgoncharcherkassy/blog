<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class BaseController
 * @package AppBundle\Controller
 */
class BaseController extends Controller
{
    protected function showMostPopularAction()
    {
        $em = $this->getDoctrine()->getManager();

        $posts = $em->getRepository('AppBundle:Post')
            ->showMostPopularPost();

        return ['posts' => $posts];
    }

    protected function showLastCommentAction()
    {
        $em = $this->getDoctrine()->getManager();

        $comments = $em->getRepository('AppBundle:Comment')
            ->showLastFiveComment();

        return ['comments' => $comments];
    }

    protected function TagsCloudAction()
    {
        $em = $this->getDoctrine()->getManager();

        $tags = $em->getRepository('AppBundle:Tags')
            ->showNotNullTags();

        return ['tags' => $tags];
    }

}
