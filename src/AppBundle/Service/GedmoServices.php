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

}
