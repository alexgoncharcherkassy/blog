<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 03.02.16
 * Time: 19:40
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function showUsers()
    {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT u FROM AppBundle:User u
               ORDER BY u.createdAt DESC"
            );
    }
}