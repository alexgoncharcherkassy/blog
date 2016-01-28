<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 24.01.16
 * Time: 11:49
 */

namespace AppBundle\EventListener;


use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class ErrorRedirectEvent
{
    protected $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if ($exception instanceof NotFoundHttpException) {

            $route = 'page404';

            if ($route === $event->getRequest()->get('_route')) {
                return;
            }

            $url = $this->router->generate($route);
            $response = new RedirectResponse($url);
            $event->setResponse($response);

        }
    }
}
