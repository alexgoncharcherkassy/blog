<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProfileController
 * @package AppBundle\Controller
 */
class ProfileController extends Controller
{
    /**
     * @Route("/{_locale}/profile", name="user_profile", requirements={"_locale" : "en|ru"}, defaults={"_locale" : "en"})
     * @Template("@App/profile/showprofile.html.twig")
     */
    public function showProfileAction()
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Unable to access this page!');

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

        $maxRating = $maxRatingPost ? $maxRatingPost[0]->getRating() : null;


        return ['profile' => $profile,
                'countPosts' => $postsCount,
                'countComments' => $commentsCount,
                'allComment' => $allComment,
                'maxRating' => $maxRating
        ];
    }

    /**
     * @Route("/{_locale}/profile/edit", name="user_profile_edit", requirements={"_locale" : "en|ru"}, defaults={"_locale" : "en"})
     * @Template("@App/profile/showprofile.html.twig")
     */
    public function editProfileAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Unable to access this page!');

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

        $maxRating = $maxRatingPost ? $maxRatingPost[0]->getRating() : null;

        $form = $this->createForm(UserType::class, $profile);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $password = $this->get('security.password_encoder')
                ->encodePassword($profile, $profile->getPlainPassword());
            $profile->setPassword($password);

            $em->flush();

            return $this->redirectToRoute('user_profile');
        }

        return ['profile' => $profile,
                'countPosts' => $postsCount,
                'countComments' => $commentsCount,
                'allComment' => $allComment,
                'maxRating' => $maxRating,
                'form' => $form->createView(),
        ];
    }
}
