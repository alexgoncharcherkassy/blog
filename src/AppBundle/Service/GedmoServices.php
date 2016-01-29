<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 29.01.16
 * Time: 17:29
 */

namespace AppBundle\Service;


use AppBundle\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Registry;

class GedmoServices
{
    private $doctrine;

    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function gedmoPersist(Post $post)
    {
        $post->setCreatedAt(new \DateTime());
        $post->setUpdateAt(new \DateTime());
        $post->setSlug($this->slugify($post));

    }

    public function gedmoUpdate(Post $post)
    {
        $post->setUpdateAt(new \DateTime());
    }

    private function slugify(Post $post)
    {
        $em = $this->doctrine->getManager();
        $titles = $em->getRepository('AppBundle:Post')
            ->findAll();

        $string = preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower(trim(strip_tags($post->getTitlePost()))));

        foreach ($titles as $title) {
            if ($title->getTitlePost() == $post->getTitlePost()) {
                $string .= '-'.$title->getId();
            }
        }

        return $string;
    }

}