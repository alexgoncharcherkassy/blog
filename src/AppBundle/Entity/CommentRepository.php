<?php

namespace AppBundle\Entity;

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
        return $this->createQueryBuilder('c')
            ->select('c, p')
            ->leftJoin('c.post', 'p')
            ->orderBy('c.createdAt', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }
}
