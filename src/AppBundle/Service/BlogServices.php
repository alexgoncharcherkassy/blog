<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 01.02.16
 * Time: 18:30
 */

namespace AppBundle\Service;


use AppBundle\Entity\Category;
use AppBundle\Entity\Post;
use AppBundle\Entity\Tag;
use Symfony\Bridge\Doctrine\RegistryInterface;

class BlogServices
{
    private $doctrine;


    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function tagsInsertPost(Post $post, $newTags)
    {
        $em = $this->doctrine->getManager();

        foreach ($newTags as $item) {
            if ($findTag = $em->getRepository('AppBundle:Tag')->find(trim($item))) {
                $post->addTag($findTag);
            } else {
                $tag = new Tag();
                $tag->setTagName(trim($item));
                $tag->setWeightTag(1);
                $em->persist($tag);
                $post->addTag($tag);
            }
        }
    }

    public function tagsEditPost(Post $post, $newTags)
    {
        $em = $this->doctrine->getManager();

        $post->getTags()->clear();
        foreach ($newTags as $item) {
            $findTag = $em->getRepository('AppBundle:Tag')->find(trim($item));
            if ($post->getTags()->contains($findTag)) {
                continue;
            } elseif ($findTag) {
                $post->addTag($findTag);
            } else {
                $tag = new Tag();
                $tag->setTagName(trim($item));
                $tag->setWeightTag(1);
                $em->persist($tag);
                $post->addTag($tag);
            }
        }
    }

    public function categoryPost(Post $post, $newCategory)
    {
        $em = $this->doctrine->getManager();

        $item = $newCategory[0];
        if ($findCategory = $em->getRepository('AppBundle:Category')->find(trim($item))) {
            $post->setCategory($findCategory);
        } else {
            $category = new Category();
            $category->setCategoryName(trim($item));
            $em->persist($category);
            $post->setCategory($category);
        }
    }

}
