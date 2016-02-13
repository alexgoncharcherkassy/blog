<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Form\UserType;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class RegistrationController
 * @package AppBundle\Controller
 */
class RegistrationController extends Controller
{
    /**
     * @Route("/{_locale}/register", name="user_registration", requirements={"_locale" : "en|ru"}, defaults={"_locale" : "en"})
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

         //   $this->get('app.custom.mailer')->sendMail($user->getEmail());

            $this->addFlash('notice', 'You successfully were registered. Transfer to the form of login');

            return $this->redirectToRoute('homepage');
        }

        return ['form' =>$form->createView()];
    }

    /**
 * @Route("/{_locale}/register/check_username", name="register_check_username", requirements={"_locale" : "en|ru"}, defaults={"_locale" : "en"})
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
     * @Route("/{_locale}/register/check_useremail", name="register_check_email", requirements={"_locale" : "en|ru"}, defaults={"_locale" : "en"})
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

    /**
     * @Route("/{_locale}/register/check_hash/{hash}", name="register_check_hash", requirements={"_locale" : "en|ru"}, defaults={"_locale" : "en"})
     */
    public function checkUserHash($hash)
    {
        $hashData = $hash;

       /* $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('AppBundle:User')
            ->findOneBy(array('email' => $email));

        if ($user) {

            return new Response('No', 200);
        }

        return new Response('Yes', 200);*/
    }
}
