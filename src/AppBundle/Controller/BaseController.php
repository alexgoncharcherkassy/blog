<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class BaseController
 * @package AppBundle\Controller
 */
class BaseController extends Controller
{
    /**
     * @return array
     */
    protected function showMostPopular()
    {
        $em = $this->getDoctrine()->getManager();

        $posts = $em->getRepository('AppBundle:Post')
            ->showMostPopularPost();

        return $posts;
    }

    /**
     * @return array
     */
    protected function showLastComment()
    {
        $em = $this->getDoctrine()->getManager();

        $comments = $em->getRepository('AppBundle:Comment')
            ->showLastFiveComment();

        return $comments;
    }

    /**
     * @return array
     */
    protected function tagsCloud()
    {
        $em = $this->getDoctrine()->getManager();

        $tags = $em->getRepository('AppBundle:Tags')
            ->showNotNullTags();

        return $tags;
    }

}
