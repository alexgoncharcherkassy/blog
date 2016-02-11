<?php

namespace AppBundle\Repository;

/**
 * PostRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
/**
 * Class PostRepository
 * @package AppBundle\Repository
 */
class PostRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @return \Doctrine\ORM\Query
     */
    public function showAllPost($id, $data)
    {
        /*return $this->createQueryBuilder('p')
            ->select('p, c, t')
            ->leftJoin('p.category', 'c')
            ->leftJoin('p.tags', 't')
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery();*/
        return $this->getEntityManager()
            ->createQuery(
                "SELECT p, c1, t1, a1 FROM AppBundle:Post p
               LEFT JOIN p.category c1
               LEFT JOIN p.tags t1
               LEFT JOIN p.author a1
               WHERE a1.id = :id
               AND p.titlePost LIKE :srch
               ORDER BY p.createdAt DESC"
            )
            ->setParameter('id', $id)
            ->setParameter('srch', '%'.$data.'%');
    }

    public function showAllPostAdmin($data)
    {
        /*return $this->createQueryBuilder('p')
            ->select('p, c, t')
            ->leftJoin('p.category', 'c')
            ->leftJoin('p.tags', 't')
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery();*/
        return $this->getEntityManager()
            ->createQuery(
                "SELECT p, c1, t1, a1 FROM AppBundle:Post p
               LEFT JOIN p.category c1
               LEFT JOIN p.tags t1
               LEFT JOIN p.author a1
               WHERE p.titlePost LIKE :srch
               ORDER BY p.createdAt DESC"
            )
            ->setParameter('srch', '%'.$data.'%');
    }

    /**
     * @return array
     */
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


    /**
     * @param $slug
     * @return array
     */
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

    /**
     * @param $slug
     * @return array
     */
    public function showCategoryPost($slug)
    {
        return $this->createQueryBuilder('p')
            ->select('p, c, t')
            ->join('p.category', 'c')
            ->join('p.tags', 't')
            ->where('c.slug = :slug')
            ->setParameter('slug', $slug)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();

    }

    /**
     * @param $slug
     * @return array
     */
    public function showTagsPost($slug)
    {
        return $this->createQueryBuilder('p')
            ->select('p, c, t')
            ->leftJoin('p.category', 'c')
            ->join('p.tags', 't')
            ->where('t.slug = :slug')
            ->setParameter('slug', $slug)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();

    }

    /**
     * @param $slug
     * @return array
     */
    public function showUsers($slug)
    {
        return $this->createQueryBuilder('p')
            ->select('p, c, t, a')
            ->leftJoin('p.category', 'c')
            ->join('p.tags', 't')
            ->join('p.author', 'a')
            ->where('a.slug = :slug')
            ->setParameter('slug', $slug)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();

    }

    /**
     * @return array
     */
    public function showMostPopularPost()
    {
        return $this->createQueryBuilder('p')
            ->select('p')
            ->orderBy('p.rating', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $start
     * @param $limit
     * @return array
     */
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

    /**
     * @param $data
     * @return array
     */
    public function search($data)
    {
        return $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.titlePost LIKE :data')
            //   ->orWhere('p.textPost LIKE :data')
            ->setParameter('data', '%' . $data . '%')
            ->orderBy('p.rating', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function searchPost($data)
    {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT p FROM AppBundle:Post p
               WHERE p.titlePost LIKE :srch
               ORDER BY p.createdAt DESC"
            )
            ->setParameter('srch', '%'.$data.'%');
    }

    public function getMaxRating($user)
    {
        return $this->createQueryBuilder('p')
            ->select('p')
            ->join('p.author', 'a')
            ->where('a.id = :user')
            ->setParameter('user', $user)
            ->orderBy('p.rating', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();

    }

}
