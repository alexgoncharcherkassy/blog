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

        return ['profile' => $profile];
    }
}
