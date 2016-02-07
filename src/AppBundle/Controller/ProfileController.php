<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class ProfileController
 * @package AppBundle\Controller
 */
class ProfileController extends Controller
{
    /**
     * @Route("/profile", name="user_profile")
     * @Template("@App/profile/showprofile.html.twig")
     */
    public function showProfileAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $profile = $em->getRepository('AppBundle:User')
            ->find($user);

        $posts = $em->getRepository('AppBundle:Post')
            ->findBy(array('author' => $user));
        $postsCount = count($posts);

        $comments = $em->getRepository('AppBundle:Comment')
            ->findBy(array('author' => $user));
        $commentsCount = count($comments);

        $allComment = 0;
        foreach ($posts as $post) {
            $allComment += count($post->getComments());
        }

        $maxRatingPost = $em->getRepository('AppBundle:Post')
            ->getMaxRating($user->getId());

        $maxRating = $maxRatingPost[0]->getRating();


        return ['profile' => $profile,
                'countPosts' => $postsCount,
                'countComments' => $commentsCount,
                'allComment' => $allComment,
                'maxRating' => $maxRating
        ];
    }
}
