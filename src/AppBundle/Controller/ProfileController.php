<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

        return ['profile' => $profile];
    }
}
