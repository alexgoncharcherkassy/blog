<?php

namespace AppBundle\Entity;

/**
 * PostRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PostRepository extends \Doctrine\ORM\EntityRepository
{
    public function showAllPost()
    {
        return $this->createQueryBuilder('p')
            ->select('p, c, t')
            ->leftJoin('p.category', 'c')
            ->leftJoin('p.tags', 't')
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();

    }

    public function showPostWithoutCategory()
    {
        return $this->createQueryBuilder('p')
            ->select('p, c, t')
            ->leftJoin('p.category', 'c')
            ->leftJoin('p.tags', 't')
            ->where('p.category is NULL ')
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();

    }


    public function showPost($slug)
    {
        return $this->createQueryBuilder('p')
            ->select('p, c, t, cm')
            ->leftJoin('p.category', 'c')
            ->leftJoin('p.tags', 't')
            ->leftJoin('p.comments', 'cm')
            ->where('p.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getResult();

    }

    public function showCategoryPost($slug)
    {
        return $this->createQueryBuilder('p')
            ->select('p, c, t')
            ->leftJoin('p.category', 'c')
            ->leftJoin('p.tags', 't')
            ->where('c.slug = :slug')
            ->setParameter('slug', $slug)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();

    }

    public function showTagsPost($slug)
    {
        return $this->createQueryBuilder('p')
            ->select('p, c, t')
            ->leftJoin('p.category', 'c')
            ->leftJoin('p.tags', 't')
            ->where('t.slug = :slug')
            ->setParameter('slug', $slug)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();

    }

    public function showMostPopularPost()
    {
        return $this->createQueryBuilder('p')
            ->select('p')
            ->orderBy('p.rating', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

    public function show($start, $limit)
    {
        return $this->createQueryBuilder('p')
            ->select('p')
            ->orderBy('p.createdAt', 'DESC')
            ->setFirstResult($start)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function search($data)
    {
        return $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.titlePost LIKE :data')
            ->orWhere('p.textPost LIKE :data')
            ->setParameter('data', '%'.$data.'%')
            ->orderBy('p.rating')
            ->getQuery()
            ->getResult();
    }

}
