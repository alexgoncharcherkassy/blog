<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 28.01.16
 * Time: 19:47
 */

namespace AppBundle\EventListener;


use AppBundle\Entity\Post;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;

class ImageManagerSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return array(
            'prePersist',
            'preUpdate',
            'postRemove'
        );
    }

    /**
     * @return string
     */
    protected function getUploadRootDir()
    {
        return __DIR__ . '/../../../web/' . $this->getUploadDir();
    }

    /**
     * @return string
     */
    protected function getUploadDir()
    {
        return 'img/blog';
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->index($args);
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $this->index($args);
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        $post = $args->getEntity();

        if ($post instanceof Post && $post->getPathImage() !== null) {
            unlink($post->getPathImage());
        }

    }

    public function index(LifecycleEventArgs $args)
    {
        $post = $args->getEntity();

        if ($post instanceof Post) {
            if (null === $post->getBlogImage()) {
                return;
            }
            $randPrefix = mt_rand(1, 9999);
            $post->getBlogImage()->move(
                $this->getUploadRootDir(),
                $randPrefix . '-' . $post->getBlogImage()->getClientOriginalName()
            );
            $post->setPathImage($this->getUploadDir() . '/' . $randPrefix . '-' . $post->getBlogImage()->getClientOriginalName());
            $post->setNameImage($randPrefix . '-' . $post->getBlogImage()->getClientOriginalName());
            $post->setBlogImage(null);

            return;
        }
    }
}