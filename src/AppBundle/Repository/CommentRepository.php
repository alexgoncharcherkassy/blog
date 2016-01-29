<?php

namespace AppBundle\Repository;

/**
 * CommentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CommentRepository extends \Doctrine\ORM\EntityRepository
{
    public function showLastFiveComment()
    {
        /*return $this->createQueryBuilder('c')
            ->select('c, p')
            ->join('c.post', 'p')
            ->orderBy('c.createdAt', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();*/
        return $this->getEntityManager()
            ->createQuery(
                "SELECT c FROM AppBundle:Comment c
                LEFT JOIN AppBundle:Comment c1
                WITH c.id < c1.id AND c.post = c1.post
                WHERE c1.id IS NULL
                ORDER BY c.id DESC"
            )
            ->setMaxResults(5)
            ->getResult();
    }
}