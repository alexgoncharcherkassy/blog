<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Form\UserType;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;


class RegistrationController extends Controller
{
    /**
     * @Route("/register", name="user_registration")
     * @Template("@App/registration/register.html.twig")
     */
    public function registerAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setRoles('ROLE_USER');

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return ['form' =>$form->createView()];
    }

    /**
 * @Route("/register/check_username", name="register_check_username")
 */
    public function checkUserName(Request $request)
    {
        $userName = $request->request->get('username');

        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('AppBundle:User')
            ->findOneBy(array('username' => $userName));

        if ($user) {
            return new Response('No', 200);
        }

        return new Response('Yes', 200);
    }

    /**
     * @Route("/register/check_useremail", name="register_check_email")
     */
    public function checkUserEmail(Request $request)
    {
        $email = $request->request->get('email');

        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('AppBundle:User')
            ->findOneBy(array('email' => $email));

        if ($user) {
            return new Response('No', 200);
        }

        return new Response('Yes', 200);
    }
}
