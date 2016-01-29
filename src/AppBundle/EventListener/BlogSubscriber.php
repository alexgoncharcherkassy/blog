<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 29.01.16
 * Time: 17:12
 */

namespace AppBundle\EventListener;


use AppBundle\Entity\Post;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BlogSubscriber implements EventSubscriber
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getSubscribedEvents()
    {
        return array(
            'prePersist',
            'preUpdate',
            'postRemove'
        );
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $post = $args->getEntity();

        if ($post instanceof Post) {
            if (null === $post->getBlogImage()) {
                return;
            }
            if (file_exists($post->getPathImage())) {
                unlink($post->getPathImage());
            }

            $this->container->get('app.custom.gedmo')->gedmoUpdatePost($post);
            $this->container->get('app.image.manager')->upload($post);
        }
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $post = $args->getEntity();

        if ($post instanceof Post) {
            if (null !== $post->getBlogImage()) {
                $this->container->get('app.image.manager')->upload($post);
            }

            $this->container->get('app.custom.gedmo')->gedmoPersistPost($post);
        }
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        $post = $args->getEntity();

        if ($post instanceof Post && $post->getPathImage() !== null && file_exists($post->getPathImage())) {
            unlink($post->getPathImage());
        }

    }


}