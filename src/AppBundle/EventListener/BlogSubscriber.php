<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 29.01.16
 * Time: 17:12
 */

namespace AppBundle\EventListener;


use AppBundle\Entity\Post;
use AppBundle\Service\ImageManagerServices;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class BlogSubscriber
 * @package AppBundle\EventListener
 */
class BlogSubscriber implements EventSubscriber
{
    /**
     * @var ImageManagerServices
     */
    protected $service;

    /**
     * @param ImageManagerServices $container
     */
    public function __construct(ImageManagerServices $service)
    {
        $this->service = $service;
    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            'prePersist',
            'preUpdate',
            'postRemove'
        );
    }

    /**
     * @param LifecycleEventArgs $args
     */
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

            $this->service->upload($post);
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $post = $args->getEntity();

        if ($post instanceof Post) {
            if (null !== $post->getBlogImage()) {
                $this->service->upload($post);
            }


        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postRemove(LifecycleEventArgs $args)
    {
        $post = $args->getEntity();

        if ($post instanceof Post && $post->getPathImage() !== null && file_exists($post->getPathImage())) {
            unlink($post->getPathImage());
        }

    }


}
