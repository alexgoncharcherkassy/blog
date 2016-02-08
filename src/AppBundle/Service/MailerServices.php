<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 08.02.16
 * Time: 18:41
 */

namespace AppBundle\Service;


use Symfony\Bundle\TwigBundle\TwigEngine;

class MailerServices
{
    private $mailer;
    private $templating;

    public function __construct(\Swift_Mailer $mailer, TwigEngine $template)
    {
        $this->mailer = $mailer;
        $this->templating = $template;
    }

    public function sendMail($mailTo)
    {
        $hash = md5(uniqid());
        $message = \Swift_Message::newInstance()
            ->setSubject('Registration')
            ->setFrom('cs210785gaa@gmail.com')
            ->setTo($mailTo)
            ->setBody(
                $this->templating->render(
                    '@App/Emails/registration.html.twig',
                    array('hash' => $hash)
                ),
                'text/html'
            );

        $this->mailer->send($message);
    }
}