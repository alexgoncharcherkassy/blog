<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 28.01.16
 * Time: 18:54
 */

namespace AppBundle\Service;


use Doctrine\Bundle\DoctrineBundle\Registry;

class RatingServices
{
    private $doctrine;

    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }
    /**
     * @param $slug
     */
    public function changeRating($slug)
    {
        $em = $this->doctrine->getManager();

        $post = $em->getRepository('AppBundle:Post')
            ->findOneBy(array('slug' => $slug));
        $comments = $em->getRepository('AppBundle:Comment')
            ->findBy(array('post' => $post));

        $count = 0;
        $summ = 0;

        foreach ($comments as $comment) {
            if ($comment->getRating() !== null) {
                $summ += $comment->getRating();
                $count++;
            }
        }
        if ($summ == 0 || $count == 0) {
            $post->setRating(0);
            $em->flush();
            return;
        }
        $rating = $summ / $count;

        $post->setRating($rating);
        $em->flush();

        return;
    }
}