<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 29.01.16
 * Time: 17:29
 */

namespace AppBundle\Service;


use AppBundle\Entity\Category;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Post;
use AppBundle\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Registry;

class GedmoServices
{
    private $doctrine;

    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function gedmoPersistPost(Post $post)
    {
        $post->setCreatedAt(new \DateTime());
        $post->setUpdateAt(new \DateTime());
        $post->setSlug($this->slugifyPost($post));

    }

    public function gedmoUpdatePost(Post $post)
    {
        $post->setUpdateAt(new \DateTime());
    }

    public function gedmoPersistTag(Tag $tag)
    {
        $tag->setSlug($this->slugifyTag($tag));

    }

    public function gedmoPersistCategory(Category $category)
    {
        $category->setSlug($this->slugifyCategory($category));

    }

    public function gedmoPersistComment(Comment $comment)
    {
        $comment->setCreatedAt(new \DateTime());
        $comment->setUpdateAt(new \DateTime());

    }

    public function gedmoUpdateComment(Comment $comment)
    {
        $comment->setUpdateAt(new \DateTime());
    }

    private function slugifyPost(Post $post)
    {
        $em = $this->doctrine->getManager();
        $titles = $em->getRepository('AppBundle:Post')
            ->findAll();

        $string = preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower(trim(strip_tags($post->getTitlePost()))));

        foreach ($titles as $title) {
            if ($title->getSlug() == $string) {
                $string .= '-'.$title->getId();
            }
        }

        return $string;
    }

    private function slugifyTag(Tag $tag)
    {
        $em = $this->doctrine->getManager();
        $titles = $em->getRepository('AppBundle:Tag')
            ->findAll();

        $string = preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower(trim(strip_tags($tag->getTagName()))));

        foreach ($titles as $title) {
            if ($title->getSlug() == $string) {
                $string .= '-'.$title->getId();
            }
        }

        return $string;
    }

    private function slugifyCategory(Category $category)
    {
        $em = $this->doctrine->getManager();
        $titles = $em->getRepository('AppBundle:Category')
            ->findAll();

        $string = preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower(trim(strip_tags($category->getCategoryName()))));

        foreach ($titles as $title) {
            if ($title->getSlug() == $string) {
                $string .= '-'.$title->getId();
            }
        }

        return $string;
    }

}