<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 03.02.16
 * Time: 19:40
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

/**
 * Class UserRepository
 * @package AppBundle\Repository
 */
class UserRepository extends EntityRepository
{
    /**
     * @return \Doctrine\ORM\Query
     */
    public function showUsers()
    {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT u FROM AppBundle:User u
               ORDER BY u.createdAt DESC"
            );
    }

    /**
     * @return \Doctrine\ORM\Query
     */
    public function searchUsers($users)
    {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT u FROM AppBundle:User u
                WHERE u.username = :users
                ORDER BY u.createdAt DESC"
            )
            ->setParameter('users', $users);
    }
}