<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 28.01.16
 * Time: 19:07
 */

namespace AppBundle\Service;


use Doctrine\Bundle\DoctrineBundle\Registry;

class TagsCloudServices
{
    private $doctrine;

    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function updateTagsCloud()
    {
        $em = $this->doctrine->getManager();

        $tags = $em->getRepository('AppBundle:Tag')
            ->countTags();

        /**
         * @var \AppBundle\Entity\Tag $tag
         */
        foreach ($tags as $tag) {
            $countPosts = 0;
            foreach ($tag->getPosts() as $item) {
                $countPosts++;
            }
            $tag->setWeightTag($countPosts);
        }
        $em->flush();

        return;
    }
}